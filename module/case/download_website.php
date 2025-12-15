<?php
 require '../../core/inc/config.php';
 require '../../core/inc/function.php';

$file=urldecode(aes_decrypt_7($aes_key, $_GET['file']));
// echo aes_decrypt_7($aes_key, $_GET['file']);
header("Cache-Control: public"); 
header("Content-Description: File Transfer"); 
header('Content-disposition: attachment; filename='.basename($file)); //檔名   
header("Content-Type: application/zip"); //zip格式的   
header("Content-Transfer-Encoding: binary"); //告訴瀏覽器，這是二進位制檔案    
header('Content-Length: '. filesize($file)); //告訴瀏覽器，檔案大小   
@readfile($file);
?>