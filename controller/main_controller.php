<?php
namespace minecraftjp\phpbb\controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use \MinecraftJP;

class main_controller {
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

    public function handle($action) {
        if (method_exists($this, $action)) {
            $check = new \ReflectionMethod($this, $action);
            if ($check->isPublic()) {
                return call_user_func(array(&$this, $action));
            }
        }

        return $this->showError($this->getString('UNKNOWN_ACTION'));
    }

    public function login() {
        global $_SID, $user;
        $minecraftjp = $this->getMinecraftJP();

        $_SESSION['auth_type'] = request_var('type', 'login');
        if ($_SESSION['auth_type'] == 'link') {
            $_SESSION['phpbb_session_id'] = $_SID;
            $_SESSION['phpbb_user_id'] = $user->data['user_id'];
        }

        $minecraftjp->logout();
        $url = $minecraftjp->getLoginUrl(array(
            'scope' => 'openid profile email',
        ));
        session_write_close();
        header('Location: ' . $url);
        exit;
    }

    public function doLogin() {
        global $user;
        $minecraftjp = $this->getMinecraftJP();
        $mcjpUser = $minecraftjp->getUser();

        $authType = !empty($_SESSION['auth_type']) ? $_SESSION['auth_type'] : 'login';

        $userMinecraftjp = $this->container->get('minecraftjp.phpbb.model.user_minecraftjp');

        if ($authType == 'link') {
            if (!empty($mcjpUser)) {
                $userId = $_SESSION['phpbb_user_id'];
                $sessionId = $_SESSION['phpbb_session_id'];
                session_destroy();
                if (empty($userId) || $userId == ANONYMOUS) {
                    return $this->showError('NOT_LOGGED');
                }
                $existsUserId = $userMinecraftjp->getUserIdBySub($mcjpUser['sub']);
                if (!empty($existsUserId) && $existsUserId != $userId) {
                    return $this->showError('ALREADY_LINKED');
                }

                $userMinecraftjp->updateUser($userId, $mcjpUser);

                $this->performRedirect('ucp', array('sid' => $sessionId));
                exit;
            } else {
                return $this->showError($this->getString('NOT_AUTHORIZED'));
            }
        } else {
            if (!empty($mcjpUser)) {
                // checking exists user
                $userId = $userMinecraftjp->getUserIdBySub($mcjpUser['sub']);
                if (!$userId) {
                    // first time
                    $this->usePHPBB('includes/functions_user');

                    $passwordsManager = $this->container->get('passwords.manager');

                    $password = gen_rand_string_friendly(max(8, mt_rand((int) $this->config['min_pass_chars'], (int) $this->config['max_pass_chars'])));;

                    $user_row = array(
                        'username' => $mcjpUser['preferred_username'] . (isset($this->config['minecraftjp_username_suffix']) ? $this->config['minecraftjp_username_suffix'] : ''),
                        'user_password' => $passwordsManager->hash($password),
                        'user_email' => $mcjpUser['email'],
                        'group_id' => 2, // REGISTERED
                        'user_timezone' => $this->config['board_timezone'],
                        'user_lang' => $user->lang_name,
                        'user_type' => USER_NORMAL,
                        'user_actkey' => '',
                        'user_ip' => $user->ip,
                        'user_regdate' => time(),
                        'user_inactive_reason' => 0,
                        'user_inactive_time' => 0,
                    );

                    if ($this->config['new_member_post_limit']) {
                        $user_row['user_new'] = 1;
                    }

                    $userId = user_add($user_row, false);
                    if (!$userId) {
                        return $this->showError($this->getString('USER_DUPLICATE'));
                    }

                    if ($this->config['email_enable']) {
                        $this->usePHPBB('includes/functions_messenger');

                        $messenger = new \messenger(false);
                        $messenger->template('@minecraftjp_phpbb/user_welcome_password', $user->lang_name);
                        $messenger->to($mcjpUser['email'], $mcjpUser['preferred_username']);
                        $messenger->anti_abuse_headers($this->config, $user);

                        $messenger->assign_vars(array(
                            'WELCOME_MSG'	=> htmlspecialchars_decode(sprintf($user->lang['WELCOME_SUBJECT'], $this->config['sitename'])),
                            'USERNAME'		=> htmlspecialchars_decode($user_row['username']),
                            'PASSWORD'		=> htmlspecialchars_decode($password),
                            'U_ACTIVATE'	=> '',
                        ));

                        $messenger->send(NOTIFY_EMAIL);
                    }
                }
                // update uuid and username
                $userMinecraftjp->updateUser($userId, $mcjpUser);

                $result = $user->session_create($userId, false, false, true);
                $this->performRedirect();
                exit;
            } else {
                return $this->showError($this->getString('NOT_AUTHORIZED'));
            }
        }
    }

    public function unlink() {
        global $user;
        $userId = $user->data['user_id'];
        if (!empty($userId) && $userId != ANONYMOUS) {
            $userMinecraftjp = $this->container->get('minecraftjp.phpbb.model.user_minecraftjp');
            $userMinecraftjp->deleteByUserId($userId);
        }
        $this->performRedirect('ucp');
        exit;
    }

    protected function getMinecraftJP() {
        if (empty($this->config['minecraftjp_client_id']) || empty($this->config['minecraftjp_client_secret'])) {
            echo 'Not configured.';
            exit;
        }
        $sessionId = session_id();
        if (empty($sessionId)) {
            $sessionId = (request_var(session_name(), '', false, true));
            if (!empty($sessionId)) {
                session_id($sessionId);
            }
            session_start();
        }

        $redirectUri = $this->helper->route('minecraftjp_phpbb_controller', array('action' => 'doLogin'), true, false, UrlGeneratorInterface::ABSOLUTE_URL);
        $redirectUri = preg_replace('/\?sid=[a-f0-9]+/', '', $redirectUri);

        return new \MinecraftJP(array(
            'clientId' => $this->config['minecraftjp_client_id'],
            'clientSecret' => $this->config['minecraftjp_client_secret'],
            'redirectUri' => $redirectUri,
        ));
    }

    protected function usePHPBB($file) {
        global $phpbb_root_path, $phpEx;
        require($phpbb_root_path . $file . '.' . $phpEx);
    }

    protected function performRedirect($file = 'index', $query = array()) {
        global $phpbb_root_path, $phpEx;


        $url = $phpbb_root_path . $file . '.' . $phpEx;
        if (empty($query['sid'])) {
            $url = append_sid($url);
        }
        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }
        redirect($url);
    }

    protected function showError($message) {
        $this->template->assign_var('MESSAGE', $message);
        return $this->helper->render('error.html');
    }

    protected function getString($key) {
        global $user;

        return isset($user->lang['MCJP_' . $key]) ? $user->lang['MCJP_' . $key] : $key;
    }
}