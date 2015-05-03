<?php
namespace minecraftjp\phpbb;

class security {
    private $config;

    public function __construct(\phpbb\config\config $config) {
        $this->config = $config;
    }

    public function generateToken($ts = null) {
        global $user;
        if (empty($ts)) {
            $ts = time();
        }
        return $ts . '.' . hash_hmac('sha256', join('.', array($ts, $user->session_id)), $this->config['minecraftjp_client_secret']);
    }

    public function validateToken($token) {
        list($ts,) = explode('.', $token, 2);
        return $token == self::generateToken($ts) && $ts > time() - 7200;
    }
}