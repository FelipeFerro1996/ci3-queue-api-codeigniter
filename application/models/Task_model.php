<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Task_model extends CI_Model 
{

    public function getAll($filters = []){

        if(isset($filters['status'])){
            $this->db->where('status', $filters['status']);
        }

        return $this->db
            ->order_by('id', 'DESC')
            ->get('tasks')
            ->result_array();
    }

    public function getById($id){
        return $this->db
            ->get_where('tasks', ['id' => $id])
            ->row_array();
    }

    public function create($data){
        $success = $this->db->insert('tasks', $data);

        if (!$success) {
            return false;
        }

        return $this->getById($this->db->insert_id());
    }

    public function update($id, $data){
        $data['updated_at'] = date('Y-m-d H:i:s');
        var_dump($id);
        return $this->db
            ->where('id', $id)
            ->update('tasks', $data);
    }

    public function delete($id){
        return $this->db
            ->where('id', $id)
            ->delete('tasks');
    }

}