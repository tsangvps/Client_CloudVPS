<?php
class Session {
    // Hàm bắt đầu session
    public function start()
    {
        @session_start();
    }
 
    // Hàm lưu session 
    public function send($key, $data)
    {
        $_SESSION[$key] = $data;
    }
 
    // Hàm lấy dữ liệu session
    public function get($key) 
    {
        if (isset($_SESSION[''.$key.'']))
        {
            $user = $_SESSION[''.$key.''];
        }
        else
        {
            $user = '';
        }
        return $user;
    }
 
    // Hàm xoá session
    public function destroy() 
    {
        @session_destroy();
    }
}
$session = new Session();
$session->start();