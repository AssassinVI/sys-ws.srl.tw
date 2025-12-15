<?php
session_start();
require_once '../vendor/autoload.php';

ini_set('display_errors','1');
error_reporting(E_ALL);

use Facebook\Facebook;
use Dotenv\Dotenv;

// 讀取 .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();

$fb = new Facebook([
  'app_id' => $_ENV['FB_APP_ID'],
  'app_secret' => $_ENV['FB_APP_SECRET'],
  'default_graph_version' => 'v23.0',
]);

$helper = $fb->getRedirectLoginHelper();

// $permissions = [
//   	'pages_messaging_subscriptions', 
// ];

$permissions = [
  	'pages_show_list', 
    'leads_retrieval', 
    // 'pages_read_engagement', 
    // 'pages_read_user_content',
    'pages_messaging',
    // 'pages_messaging_subscriptions',
    'pages_manage_metadata',
    // 'pages_manage_engagement',
    'pages_manage_ads', // 抓表單名稱用
    'business_management', // 獲取企業粉專用
    'public_profile'
];


//-- 串聯工具->粉專權限的登入連結 --
$loginUrl = $helper->getLoginUrl(
  $_ENV['FACEBOOK_REDIRECT_URI'],
  $permissions,
);
$loginUrl .= (strpos($loginUrl, '?') === false ? '?' : '&') . 'auth_type=rerequest&display=popup';

echo json_encode(['success'=>true, 'loginUrl' => $loginUrl]);
