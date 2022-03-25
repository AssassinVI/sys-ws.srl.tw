<?php
require 'core/inc/config.php';
require 'core/inc/function.php';
require 'core/inc/pdo_fun_calss.php';

//-- 外部連結，解密 --
if($_GET){
  if(!empty($_GET['code'])){
    $url= aes_decrypt_7($aes_key, $_GET['code']);
    setcookie("newHref", $url, time()+200, '/sys');
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: ".$url);
  }
  else{
     echo 'error:錯誤連結';
  }
}

?>