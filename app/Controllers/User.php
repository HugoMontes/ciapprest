<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RoleModel;
use CodeIgniter\RESTful\ResourceController;

class User extends ResourceController {

    protected $modelName = 'App\Models\UserModel';
    protected $format    = 'json';

    public function index() {
        $rows = $this->model->findAll();
        return $this->genericResponse($rows, ["total" => count($rows)], null, 200);
    }

    public function show($id = null) {
        $user = $this->model->find($id);
        if(!$user){
            return $this->genericResponse(null, "Registro inexistente", "No existe el registro $id", 404);
        }
        return $this->genericResponse($user, "Se ha encontrado el registro", null, 200);
    }

    public function create(){
        $user = new UserModel();
        $role = new RoleModel();

        if($this->validate('user_create')){
            if(!$this->request->getPost('id_rol')){
                return $this->genericResponse(null, "Error de validación", array('id_rol' => 'Rol es requerido'), 400);
            }
            if(!$role->get($this->request->getPost('id_rol'))){
                return $this->genericResponse(null, "Registro inexistente", array('id_rol' => 'Rol no existe'), 404);
            }
            $id = $user->insert([
                'username' => $this->request->getPost('username'),
                'password' => MD5($this->request->getPost('password')),
                'email' => $this->request->getPost('email'),
                'id_rol' => $this->request->getPost('id_rol'),
            ]);
            return $this->genericResponse($this->model->find($id), "Registro adicionado correctamente", null, 200);
        }
        $validation = \Config\Services::validation();
        return $this->genericResponse(null, "Error de validación", $validation->getErrors(), 400);
    }

    public function update($id = null){
        $user = new UserModel();
        $role = new RoleModel();

        if(!$this->model->find($id)){
            return $this->genericResponse(null, "Registro inexistente", "User no existe", 404);
        }

        $data = $this->request->getRawInput();

        if($this->validate('user_update')){
            if(!isset($data['id_rol'])){
                return $this->genericResponse(null, "Error de validación", array('id_rol' => 'Rol es requerido'), 400);
            }
            if(!$role->get($data['id_rol'])){
                return $this->genericResponse(null, "Registro inexistente", array('id_rol' => 'Rol no existe'), 404);
            }
            var_dump($data['username']);
            $user->update($id, [
                'username' => $data['username'],
                'email' => $data['email'],
                'id_rol' => $data['id_rol']
            ]);
            return $this->genericResponse($this->model->find($id), "Registro $id editado correctamente", null,200);
        }
        $validation = \Config\Services::validation();
        return $this->genericResponse(null, "Error de validación", $validation->getErrors(), 400);
    }

    public function delete($id = null) {
        if(!$this->model->find($id)){
            return $this->genericResponse(null, "Registro inexistente", "User no existe", 404);
        }
        $this->model->delete($id);
        return $this->genericResponse(null, "Registro $id eliminado correctamente", null, 200);
    }
    
    private function genericResponse($data, $message, $error, $code){
        if($code == 200){
            return $this->respond(array(
                "ok" => true,
                "message" => $message,
                "data" => $data,
            ));
        } else {
            return $this->respond(array(
                "ok" => false,
                "message" => $message,
                "error" => $error,                
            ), $code);
        }
    }
}