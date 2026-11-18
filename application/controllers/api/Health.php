<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Health extends CI_Controller
{
    public function index(){

        $databaseStatus = $this->checkDatabase();
        $redisStatus = $this->checkRedis();

        $success = $databaseStatus && $redisStatus;

        $response = [
                'success' => $success,
                'message' => 'API is running',
                'services' => [
                    'postgresql' => $databaseStatus ? 'connected' : 'disconnected',
                    'redis' => $redisStatus ? 'connected' : 'disconnected'
                ]
            ];
        
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header($success ? 200 : 503)
            ->set_output(json_encode($response));
        
    }

    private function checkDatabase()
    {
        try{
            $this->load->database();

            $query = $this->db->query('SELECT 1');

            return $query !== false;
        }catch(Throwable $e){
            log_message('error', 'PostgreSql: '.$e->getMessage());

            return false;
        }
    }

    private function checkredis()
    {
        $redis = null;

        try {
            $redis = new Redis();

            $connected = $redis->connect(
                get_env('REDIS_HOST') ?: 'redis',
                (int) (get_env('REDIS_PORT') ?: 6379)
            );

            return $connected && $redis->ping() === '+pong';

        } catch (Throwable $e) {
            log_message('error', 'Redis: ' . $e->getMessage());

            return false;

        } finally {
            if ($redis instanceof Redis && $redis->isConnected()) {
                $redis->close();
            }
        }   
    }
}