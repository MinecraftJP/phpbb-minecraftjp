<?php
namespace minecraftjp\phpbb\acp;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class main_module {
    var $u_action;

    function main($id, $mode) {
        global $db, $user, $auth, $template, $cache, $request;
        global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx, $phpbb_container;

        $helper = $phpbb_container->get('controller.helper');

        $user->add_lang('acp/common');
        $this->tpl_name = 'settings';
        $this->page_title = $user->lang('MCJP_TITLE');
        add_form_key('minecraftjp/phpbb');

        if ($request->is_set_post('submit')) {
            if (!check_form_key('minecraftjp/phpbb')) {
                trigger_error('FORM_INVALID');
            }

            $config->set('minecraftjp_client_id', $request->variable('minecraftjp_client_id', ''));
            $config->set('minecraftjp_client_secret', $request->variable('minecraftjp_client_secret', ''));
            $config->set('minecraftjp_username_suffix', $request->variable('minecraftjp_username_suffix', ''));

            trigger_error($user->lang('MCJP_SETTING_SAVED') . adm_back_link($this->u_action));
        }

        $url = $helper->route('minecraftjp_phpbb_controller', array('action' => 'doLogin'), true, false, UrlGeneratorInterface::ABSOLUTE_URL);
        $url = preg_replace('/\?sid=[a-f0-9]+/', '', $url);

        $template->assign_vars(array(
            'U_ACTION' => $this->u_action,
            'MINECRAFTJP_CLIENT_ID' => $config['minecraftjp_client_id'],
            'MINECRAFTJP_CLIENT_SECRET' => $config['minecraftjp_client_secret'],
            'MINECRAFTJP_REDIRECT_URI' => $url,
            'MINECRAFTJP_USERNAME_SUFFIX' => $config['minecraftjp_username_suffix'],
        ));
    }
}