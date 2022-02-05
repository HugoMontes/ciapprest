<?php

namespace App\Controllers;

use App\Models\RoleModel;
use CodeIgniter\RESTful\ResourceController;

header('Access-Control-Allow-Origin: *');


class Role extends ResourceController {

    protected $modelName = 'App\Models\RoleModel';
    protected $format    = 'json';

    public function index() {
        $rows = $this->model->findAll();
        return $this->genericResponse($rows, ["total" => count($rows)], null, 200);
    }

    public function show($id = null) {
        $role = $this->model->find($id);
        if(!$role){
            return $this->genericResponse(null, "Registro inexistente", "No existe el registro $id", 404);
        }
        return $this->genericResponse($role, "Se ha encontrado el registro", null, 200);
    }

    public function create(){
        $role = new RoleModel();

        if($this->validate('role')){
            $id = $role->insert([
                'name' => $this->request->getPost('name'),
            ]);
            return $this->genericResponse($this->model->find($id), "Registro adicionado correctamente", null, 200);
        }
        $validation = \Config\Services::validation();
        return $this->genericResponse(null, "Error de validación", $validation->getErrors(), 400);
    }

    public function update($id = null){
        $role = new RoleModel();

        $data = $this->request->getRawInput();

        if($this->validate('role')){
            $role->update($id, [
                'name' => $data['name'],
            ]);
            return $this->genericResponse($this->model->find($id), "Registro $id editado correctamente", null,200);
        }
        $validation = \Config\Services::validation();
        return $this->genericResponse(null, "Error de validación", $validation->getErrors(), 400);
    }

    public function delete($id = null) {
        if(!$this->model->find($id)){
            return $this->genericResponse(null, "Registro inexistente", "Role no existe", 404);
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