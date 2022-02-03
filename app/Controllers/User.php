<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RoleModel;
use CodeIgniter\RESTful\ResourceController;

class User extends ResourceController {

    protected $modelName = 'App\Models\UserModel';
    protected $format    = 'json';

    public function index() {
        return $this->genericResponse($this->model->findAll(), "", 200);
    }

    public function show($id = null) {
        if($id == null){
            return $this->genericResponse(null, "ID es requerido", 400);
        }
        $user = $this->model->find($id);
        if(!$user){
            return $this->genericResponse(null, "User no existe", 404);
        }
        return $this->genericResponse($user, "", 200);
    }

    public function create(){
        $user = new UserModel();
        $role = new RoleModel();

        if($this->validate('user_create')){
            if(!$this->request->getPost('id_rol')){
                return $this->genericResponse(null, array('id_rol' => 'Role es requerido'), 400);
            }
            if(!$role->get($this->request->getPost('id_rol'))){
                return $this->genericResponse(null, array('id_rol' => 'Role no existe'), 404);
            }
            $id = $user->insert([
                'username' => $this->request->getPost('username'),
                'password' => MD5($this->request->getPost('password')),
                'email' => $this->request->getPost('email'),
                'id_rol' => $this->request->getPost('id_rol'),
            ]);
            return $this->genericResponse($this->model->find($id),null,200);
        }
        $validation = \Config\Services::validation();
        return $this->genericResponse(null, $validation->getErrors(), 400);
    }

    public function update($id = null){
        $user = new UserModel();
        $role = new RoleModel();

        if(!$this->model->find($id)){
            return $this->genericResponse(null, "User no existe", 404);
        }

        $data = $this->request->getRawInput();

        if($this->validate('user_update')){
            if(!$data['id_rol']){
                return $this->genericResponse(null, array('id_rol' => 'Role es requerido'), 400);
            }
            if(!$role->get($data['id_rol'])){
                return $this->genericResponse(null, array('id_rol' => 'Role no existe'), 404);
            }
            var_dump($data['username']);
            $user->update($id, [
                'username' => $data['username'],
                'email' => $data['email'],
                'id_rol' => $data['id_rol']
            ]);
            return $this->genericResponse($this->model->find($id),null,200);
        }
        $validation = \Config\Services::validation();
        return $this->genericResponse(null, $validation->getErrors(), 400);
    }

    public function delete($id = null) {
        $this->model->delete($id);
        return $this->genericResponse("Registro $id eliminado correctamente", null, 200);
    }
    
    private function genericResponse($data, $msg, $code){
        if($code == 200){
            return $this->respond(array(
                "data" => $data,
                "code" => $code
            ));
        }else{
            return $this->respond(array(
                "msg" => $msg,
                "code" => $code
            ), $code);
        }
    }
}