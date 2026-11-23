<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tasks extends CI_Controller
{
    
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Task_model');
    }

    public function index()
    {
        $method = $this->input->method(TRUE);

        if($method === 'GET'){
            return $this->getAll();
        }

        if($method === 'POST'){
           return $this->store();
        }

        return $this->response([
            'success' => false,
            'message' => 'MEthod not allowed'
        ], 405);
    }

    public function getAll()
    {
        $tasks = $this->Task_model->getAll();

        return $this->response([
            'success' => true,
            'data' => $tasks
        ], 200);
    }

    public function store()
    {
        $json = $this->input->raw_input_stream;

        $data = json_decode($json, true);

        if(json_last_error() != JSON_ERROR_NONE || !is_array($data)){
            return $this->response([
                'success' => false,
                'message' => 'Invalid JSON'
            ], 404);
        }

        $title = isset($data['title']) && is_string($data['title']) ? trim($data['title']) : '';

        $description = isset($data['description']) && is_string($data['description']) ? trim($data['description']) : null;

        $priority = isset($data['priority']) ? $data['priority'] : 1;

        if($title === ''){
            return $this->response([
                'success' => false,
                'message' => 'O título é obrigatório'
            ], 422); 
        }

        if(strlen($title) > 150){
            return $this->response([
                'success' => false,
                'message' => 'O título deve ter no máximo 150 caracteres'
            ], 422); 
        }

        if(!is_int($priority) || $priority < 1 || $priority > 5){
            return $this->response([
                'success' => false,
                'message' => 'A prioridadede deve ser um número inteiro entre 1 e 5'
            ], 422); 
        }

        $taskData = [
            'title' => $title,
            'description' => $description,
            'priority' => $priority
        ];

        $task = $this->Task_model->create($taskData);

        if(!$task){
            return $this->response([
                'success' => false,
                'message' => 'Falha ao criar tarefa'
            ], 500);
        }
    
        return $this->response([
            'success' => false,
            'message' => 'Tarefa criada com sucesso',
            'data' => $task
        ], 201);
    }

    public function show($id)
    {
        $task = $this->Task_model->getByid($id);

        if(!$task){
            return $this->response([
                'success' => false,
                'message' => 'Tarefa não encontrada'
            ], 404);
        }

        return $this->response([
            'success' => true,
            'data' => $task
        ], 200);
    }

    public function update($id)
    {
        $json = $this->input->raw_input_stream;
        $data = json_decode($json, true);

        if(json_last_error() != JSON_ERROR_NONE || !is_array($data)){
            return $this->response([
                'success' => false,
                'message' => 'Invalid JSON'
            ], 404);
        }

        $title = isset($data['title']) && is_string($data['title']) ? trim($data['title']) : '';

        $description = isset($data['description']) && is_string($data['description']) ? trim($data['description']) : null;

        $priority = isset($data['priority']) ? $data['priority'] : 1;

        if($title === ''){
            return $this->response([
                'success' => false,
                'message' => 'O título é obrigatório'
            ], 422); 
        }

        if(strlen($title) > 150){
            return $this->response([
                'success' => false,
                'message' => 'O título deve ter no máximo 150 caracteres'
            ], 422); 
        }

        if(!is_int($priority) || $priority < 1 || $priority > 5){
            return $this->response([
                'success' => false,
                'message' => 'A prioridadede deve ser um número inteiro entre 1 e 5'
            ], 422); 
        }

        $taskData = [
            'title' => $title,
            'description' => $description,
            'priority' => $priority
        ];

        $task = $this->Task_model->update($id, $taskData);

        if (!$task) {
            return $this->response([
                'success' => false,
                'message' => 'Tarefa não encontrada ou falha ao atualizar'
            ], 404);
        }

        return $this->response([
            'success' => true,
            'message' => 'Tarefa atualizada com sucesso',
            'data' => $task
        ], 200);
    }

    public function destroy($id){
        $retorno = $this->Task_model->delete($id);

        if(!$retorno){
            return $this->response([
                'success' => false,
                'message' => 'tarefa não encontrada ou erro ao deletar'
            ], 404);
        }

        return $this->response([
            'success' => true,
            'message' => 'Tarefa excluída com sucesso'
        ], 200);
    }

    private function response($data, $status)
    {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header($status)
            ->set_output(json_encode($data));
    }

}