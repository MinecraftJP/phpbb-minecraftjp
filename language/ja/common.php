<?php
if (!defined('IN_PHPBB')) {
    die();
}

if (empty($lang) || !is_array($lang)) {
    $lang = array();
}

$lang = array_merge($lang, array(
    'MCJP_TITLE' => 'MinecraftJP',
    'MCJP_SETTINGS' => '設定',
    'MCJP_SETTING_SAVED' => '設定を保存しました。',
    'MCJP_APPLICATION_SETTINGS' => 'アプリケーション設定',
    'MCJP_APPLICATION_TYPE' => 'アプリケーションタイプ',
    'MCJP_SERVICE_ACCOUNT' => 'サービスアカウント',
    'MCJP_CLIENT_ID' => 'Client ID',
    'MCJP_CLIENT_SECRET' => 'Client Secret',
    'MCJP_CALLBACK_URI' => 'コールバックURI',
    'MCJP_APPLICATION_CONSOLE_LINK' => '<a href="https://minecraft.jp/en/developer/apps" target="__blank">minecraft.jpのApp Consoleからアプリを登録する必要があります。こちらをクリックしてください。</a>',
    'MCJP_USER_SETTINGS' => 'ユーザ設定',
    'MCJP_USERNAME_SUFFIX' => 'ユーザ名のサフィックス',
    'MCJP_LOGIN_WITH_MINECRAFTJP' => 'minecraft.jpでログイン',
    'MCJP_LINK_ACCOUNT' => 'アカウントをリンク',
    'MCJP_UNLINK_ACCOUNT' => 'アカウントのリンクを解除',
    'MCJP_NOT_AUTHORIZED' => 'Not authorized',
    'MCJP_ERROR' => 'エラー',
    'MCJP_UNKNOWN_ACTION' => 'Unknown Action',
    'MCJP_USER_DUPLICATE' => 'ユーザ名またはメールアドレスが既に登録されています。',
    'MCJP_NOT_LOGGED' => 'ログインしていません。',
    'MCJP_ALREADY_LINKED' => 'このアカウントは既にリンクされています。',
));
