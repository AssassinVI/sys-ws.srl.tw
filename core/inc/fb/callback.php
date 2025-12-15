<?php
session_start();
include "../config.php"; //載入基本設定
include "../pdo_fun_calss.php"; // PDO
include "../file_class.php"; // file class
include "../function.php"; //載入基本function
require_once '../vendor/autoload.php';

// ini_set('display_errors','1');
// error_reporting(E_ALL);

use Facebook\Facebook;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();

$fb = new Facebook([
  'app_id' => $_ENV['FB_APP_ID'],
  'app_secret' => $_ENV['FB_APP_SECRET'],
  'default_graph_version' => 'v23.0',
]);

$helper = $fb->getRedirectLoginHelper();

try {
  //-- 獲取使用者token --
  $accessToken = $helper->getAccessToken($_ENV['FACEBOOK_REDIRECT_URI']);
} catch(\Facebook\Exceptions\FacebookResponseException $e) {
  echo '獲取使用者token Graph returned an error: ' . $e->getMessage();
  exit;
} catch(\Facebook\Exceptions\FacebookSDKException $e) {
  echo '獲取使用者token Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

if (! isset($accessToken)) {
  exit('連結失敗，請重新再試一次 (Bad callback, no token)');
}

// 取得長效 User Token
$oAuth2Client = $fb->getOAuth2Client();
$longLivedToken = $oAuth2Client->getLongLivedAccessToken($accessToken);

// 呼叫 /me/accounts 拿粉專
try {
  
  $pdo = new PDO_fun();

  //-- 獲取所有粉專 --
  $FBpage = $pdo->select("SELECT * FROM appFBpage WHERE admin_id = :admin_id", ['admin_id' => $_SESSION['admin_id']]);

  //-- 先清除全部粉專 --
  $pdo->delete("appFBpage", ['admin_id' => $_SESSION['admin_id']]);


  //######### 自己擁有的粉專 ############
  $response = $fb->get('/me/accounts', $longLivedToken);
  $pages = $response->getGraphEdge();
  $x=0;
  foreach ($pages as $page) {
    $pageId = $page['id'];
    $pageName = $page['name'];
    $pageToken = $page['access_token'];

    echo "Page: $pageName ($pageId) <br>";

    // 檢查粉專是否已存在
    //$existingPage = $pdo->select("SELECT * FROM appFBpage WHERE pageId = :pageId", ['pageId' => $pageId], 'one');

    $line_bot_group='';
    $is_top=0;
    foreach ($FBpage as $F_page) {
      if($F_page['pageId']==$pageId){
        $line_bot_group=$F_page['line_bot_group'];
        $is_top=$F_page['is_top'];
      }
    }

    $pdo->insert("appFBpage", [
          'admin_id'=> $_SESSION['admin_id'],
          'pageName'=> $pageName,
          'pageId' => $pageId,
          'pageToken' => $pageToken,
          'line_bot_group'=> $line_bot_group,
          'is_top'=> $is_top,
          'StartDate'=>date('Y-m-d H:i:s'),
          'update_num'=>date('His'),
      ]);
    echo "更新粉絲專頁: $pageName ($pageId) <br>";


    // 訂閱粉專事件
    try {
      $response = $fb->post("/$pageId/subscribed_apps", [
        'subscribed_fields' => 'feed,leadgen,messages', // 訂閱 feed 和 leadgen 事件
      ], $pageToken);

      $result = $response->getGraphNode()->asArray();
      echo "Subscribed to page events: " . json_encode($result) . "<br>";
    } catch(\Facebook\Exceptions\FacebookResponseException $e) {
      echo '訂閱粉專事件 Graph error: ' . $e->getMessage() . "<br>";
    } catch(\Facebook\Exceptions\FacebookSDKException $e) {
      echo '訂閱粉專事件 SDK error: ' . $e->getMessage() . "<br>";
    }
    $x++;
  }


  //######### 商家合作夥伴授予的粉專 ############
  //-- 獲取商家資產管理組ID --
  $businesses_response = $fb->get('/me/businesses', $longLivedToken);
  $businesses = $businesses_response->getGraphEdge();
  //-- 獲取商家合作夥伴授予的粉專 --
  foreach ($businesses as $b_one) {
      $businesses_partner_pages_response = $fb->get("/{$b_one['id']}/client_pages?fields=id,name,access_token", $longLivedToken);
      $businesses_partner_pages = $businesses_partner_pages_response->getGraphEdge();
      $x=0;
      foreach ($businesses_partner_pages as $page) {
        $pageId = $page['id'];
        $pageName = $page['name'];
        $pageToken = $page['access_token'];

        echo "Page: $pageName ($pageId) <br>";

        // 檢查粉專是否已存在
        //$existingPage = $pdo->select("SELECT * FROM appFBpage WHERE pageId = :pageId", ['pageId' => $pageId], 'one');

        $line_bot_group='';
        foreach ($FBpage as $F_page) {
          if($F_page['pageId']==$pageId){
            $line_bot_group=$F_page['line_bot_group'];
          }
        }

        $pdo->insert("appFBpage", [
              'admin_id'=> $_SESSION['admin_id'],
              'pageName'=> $pageName,
              'pageId' => $pageId,
              'pageToken' => $pageToken,
              'line_bot_group'=> $line_bot_group,
              'StartDate'=>date('Y-m-d H:i:s'),
              'update_num'=>date('His'),
          ]);
        echo "更新粉絲專頁: $pageName ($pageId) <br>";


        // 訂閱粉專事件
        try {
          $response = $fb->post("/$pageId/subscribed_apps", [
            'subscribed_fields' => 'feed,leadgen,messages', // 訂閱 feed 和 leadgen 事件
          ], $pageToken);

          $result = $response->getGraphNode()->asArray();
          echo "Subscribed to page events: " . json_encode($result) . "<br>";
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
          echo '訂閱粉專事件 Graph error: ' . $e->getMessage() . "<br>";
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
          echo '訂閱粉專事件 SDK error: ' . $e->getMessage() . "<br>";
        }
        $x++;
      }
  }
  


  $pdo->close(); // 關閉 PDO 連線

  echo "粉絲專頁已新增，請關閉頁面....";

} catch(\Facebook\Exceptions\FacebookResponseException $e) {
  echo '拿粉專 Graph error: ' . $e->getMessage();
  exit;
} catch(\Facebook\Exceptions\FacebookSDKException $e) {
  echo '拿粉專 SDK error: ' . $e->getMessage();
  exit;
}
?>

<!DOCTYPE html>
<html lang="zh-tw">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>粉絲專頁已新增，請關閉頁面....</title>
</head>
<body>
   <script>
    //重新讀取父頁面
    if (window.opener && !window.opener.closed) { 
        window.opener.location.reload();
    }
   </script>
</body>
</html>