<?php
namespace minecraftjp\phpbb\acp;

class main_info {
    function module() {
        return array(
            'filename' => '\minecraftjp\phpbb\acp\main_module',
            'title' => 'MCJP_TITLE',
            'version' => '1.0.0',
            'modes' => array(
                'settings' => array(
                    'title' => 'MCJP_SETTINGS',
                    'auth' => 'acl_a_board',
                    'cat' => array()
                ),
            ),
        );
    }
}
