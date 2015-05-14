<?php
if (!defined('IN_PHPBB')) {
    die();
}

if (empty($lang) || !is_array($lang)) {
    $lang = array();
}

$lang = array_merge($lang, array(
    'MCJP_TITLE' => 'MinecraftJP',
    'MCJP_SETTINGS' => 'Settings',
    'MCJP_SETTING_SAVED' => 'Settings have been saved successfully!',
    'MCJP_APPLICATION_SETTINGS' => 'Application Settings',
    'MCJP_APPLICATION_TYPE' => 'Application Type',
    'MCJP_SERVICE_ACCOUNT' => 'Service Account',
    'MCJP_CLIENT_ID' => 'Client ID',
    'MCJP_CLIENT_SECRET' => 'Client Secret',
    'MCJP_CALLBACK_URI' => 'Callback URI',
    'MCJP_APPLICATION_CONSOLE_LINK' => '<a href="https://minecraft.jp/en/developer/apps" target="__blank">You need to create a App in minecraft.jp App Console. Click here</a>',
    'MCJP_USER_SETTINGS' => 'User Settings',
    'MCJP_USERNAME_SUFFIX' => 'Username Suffix',
    'MCJP_LOGIN_WITH_MINECRAFTJP' => 'Login with minecraft.jp',
    'MCJP_LINK_ACCOUNT' => 'Link account',
    'MCJP_UNLINK_ACCOUNT' => 'Unlink account',
    'MCJP_NOT_AUTHORIZED' => 'Not authorized',
    'MCJP_ERROR' => 'Error',
    'MCJP_UNKNOWN_ACTION' => 'Unknown Action',
    'MCJP_USER_DUPLICATE' => 'username or email is already taken.',
    'MCJP_NOT_LOGGED' => 'Not logged.',
    'MCJP_ALREADY_LINKED' => 'This account is already linked.',
    'MCJP_MINECRAFT_ACCOUNT' => 'Minecraft Account',
));
