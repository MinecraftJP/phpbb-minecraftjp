<?php
namespace minecraftjp\phpbb\ucp;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class user_module {
    public function main($id, $mode) {
        global $config, $template, $user, $phpbb_container;

        $userMinecraftjp = $phpbb_container->get('minecraftjp.phpbb.model.user_minecraftjp');
        $mcjpUser = $userMinecraftjp->read($user->data['user_id']);
        $helper = $phpbb_container->get('controller.helper');

        $template->assign_vars(array(
            'MINECRAFTJP_IS_LINKED' => $mcjpUser !== false,
            'MINECRAFTJP_USERNAME' => $mcjpUser['username'],
            'MINECRAFTJP_LINK_URL' => $helper->route('minecraftjp_phpbb_controller', array('action' => 'login', 'type' => 'link'), true, false, UrlGeneratorInterface::ABSOLUTE_URL),
            'MINECRAFTJP_UNLINK_URL' => $helper->route('minecraftjp_phpbb_controller', array('action' => 'unlink'), true, false, UrlGeneratorInterface::ABSOLUTE_URL),
        ));
        $this->tpl_name = 'minecraftjp_ucp_link';
        $this->page_title = 'MCJP_TITLE';
    }
}