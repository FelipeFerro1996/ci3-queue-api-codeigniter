<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'dto/Task_dto.php';

class Task_service
{
    private $taskModel;

    public function __construct()
    {
        $CI =& get_instance();

        $CI->load->model('Task_model');

        $this->taskModel = $CI->Task_model;
    }

    public function getAll($filters = []){
        return $this->taskModel->getAll($filters);
    }

    public function getById($id)
    {
        return $this->taskModel->getById($id);
    }

    public function create(Task_dto $dto)
    {
        return $this->taskModel->create($dto->toArray());
    }

    public function update($id, Task_dto $dto)
    {
        $task = $this->taskModel->getById($id);

        if (!$task) {
            return null;
        }

        $updated = $this->taskModel->update(
            $id,
            $dto->toArray()
        );

        if (!$updated) {
            return false;
        }

        return $this->taskModel->getById($id);
    }

    public function delete($id)
    {
        $task = $this->taskModel->getById($id);

        if (!$task) {
            return null;
        }

        if (!$this->taskModel->delete($id)) {
            return false;
        }

        return true;
    }
}