<?php
namespace minecraftjp\phpbb\event;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface {
    protected $container;
    protected $helper;
    protected $template;
    protected $request;
    protected $config;

    public function __construct(ContainerInterface $container, \phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\request\request $request,
        \phpbb\config\config $config) {
        $this->container = $container;
        $this->helper = $helper;
        $this->template = $template;
        $this->request = $request;
        $this->config = $config;
    }

    public static function getSubscribedEvents() {
        return array(
            'core.user_setup' => 'onUserSetup',
            'core.page_header_after' => 'onPageHeaderAfter',
            'core.delete_user_after' => 'onDeleteUserAfter',
        );
    }

    public function onUserSetup($event) {
        $lang_set_ext = $event['lang_set_ext'];
        $lang_set_ext[] = array(
            'ext_name' => 'minecraftjp/phpbb',
            'lang_set' => 'common',
        );
        $event['lang_set_ext'] = $lang_set_ext;
    }

    public function onPageHeaderAfter($event) {
        $mode = $this->request->variable('mode', '');
        if (!empty($this->config['minecraftjp_client_id']) && !empty($this->config['minecraftjp_client_secret'])) {
            $this->template->assign_vars(array(
                'MINECRAFTJP_ENABLED' => 1,
                'MINECRAFTJP_LOGIN_LINK' => $this->helper->route('minecraftjp_phpbb_controller', array('action' => 'login')),
            ));
        }
    }

    public function onDeleteUserAfter($event) {
        $userMinecraftjp = $this->container->get('minecraftjp.phpbb.model.user_minecraftjp');
        $userMinecraftjp->deleteByUserId($event['user_ids']);
    }
}