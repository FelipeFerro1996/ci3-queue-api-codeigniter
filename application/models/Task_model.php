<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Task_model extends CI_Model 
{

    public function getAll(){
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
        $this->db->insert('tasks', $data);

        $id = $this->db->insert_id();

        if (!$id){
            return false;
        }

        return $this->db->get_where('tasks', [
            'id' => $this->db->insert_id()
        ])->row_array();
    }

    public function update($id, $data){
        if(!$this->getById($id)){
            return false;
        }

        $data['updated_at'] = date('Y-m-d H:i:s');

        $this->db
            ->where('id', $id)
            ->update('tasks', $data);

        if($this->db->error()['code'] != 0){
            return false;
        }

        return $this->getById($id);
    }

    public function delete($id){
        if (!$this->getById($id)){
            return false;
        }

        $this->db
            ->where('id', $id)
            ->delete('tasks');

        return $this->db->affected_rows() > 0;
    }

}