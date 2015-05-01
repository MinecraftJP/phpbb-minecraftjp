<?php
namespace minecraftjp\phpbb\ucp;

class user_info {
    function module() {
        return array(
            'filename' => '\minecraftjp\phpbb\ucp\user_module',
            'title' => 'MCJP_TITLE',
            'version' => '1.0.0',
            'modes' => array(
                'settings' => array(
                    'title' => 'MCJP_TITLE',
                    'auth' => 'acl_u_chgprofileinfo',
                    'cat' => array('UCP_PROFILE')
                ),
            ),
        );
    }
}
