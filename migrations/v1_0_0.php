<?php
namespace minecraftjp\phpbb\migrations;

class v1_0_0 extends \phpbb\db\migration\migration {
    public function update_data() {
        return array(
            // ACP
            array('module.add', array(
                'acp',
                'ACP_CAT_DOT_MODS',
                'MCJP_TITLE',
            )),
            array('module.add', array(
                'acp',
                'MCJP_TITLE',
                array(
                    'module_basename' => '\minecraftjp\phpbb\acp\main_module',
                    'modes' => array('settings'),
                ),
            )),

            //UCP
            array('module.add', array(
                'ucp',
                'UCP_PROFILE',
                'MCJP_TITLE',
            )),
            array('module.add', array(
                'ucp',
                'MCJP_TITLE',
                array(
                    'module_basename' => '\minecraftjp\phpbb\ucp\user_module',
                    'modes' => array('settings'),
                ),
            )),
        );
    }

    public function update_schema() {
        return array(
            'add_tables' => array(
                $this->table_prefix . 'user_minecraftjp' => array(
                    'COLUMNS' => array(
                        'user_id' => array('UINT', null),
                        'sub' => array('UINT:11', null),
                        'username' => array('VCHAR:32', ''),
                        'uuid' => array('VCHAR:32', ''),
                    ),
                    'PRIMARY_KEY' => 'user_id',
                    'KEYS' => array(
                        'sub' => array('UNIQUE', 'sub'),
                    ),
                ),
            ),
        );
    }

    public function revert_schema() {
        return array(
            'drop_tables' => array(
                $this->table_prefix . 'user_minecraftjp',
            ),
        );
    }
}