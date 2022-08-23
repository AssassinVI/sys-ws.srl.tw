<?php
require '../../core/inc/config.php';
require '../../core/inc/function.php';
require '../../core/inc/pdo_fun_calss.php';
require '../../core/inc/view_class.php';
require '../../core/inc/PHPZip.php';

$pdo_job=new PDO_fun('job');

$job=$pdo_job->select("SELECT * FROM put_website ORDER BY rowid LIMIT 0, 1", 'no', 'one');


if(!empty($job['case_id'])){

    //-- 刪除此筆資料 --
    $pdo_job->delete('put_website', ['case_id'=>$job['case_id']]);

    // // -- 清除舊暫存 --
    $web_url='website_tmp/'.$job['case_id'];

    if(file_exists($web_url)){
        deleteDir($web_url);
        mkdir($web_url);
    }
    else{
        mkdir($web_url);
    }

    // -- 複製CSS,JS,img --
    //-- 外層共用 --
    copy_dir(WS_PATH.'assets', $web_url.'/assets');
    deleteDir($web_url.'/assets/php');
    copy_dir(WS_PATH.'img', $web_url.'/img');
    //-- 建案檔案 --
    copy_dir(WS_PATH.'product_html/'.$job['case_id'], $web_url);
    unlink($web_url.'/Default.php');
    unlink($web_url.'/error_log');

    //-- 抓index.html --
    $ch = curl_init();
    $case_num = substr($job['case_id'], 4);

    // 設定擷取的URL網址
    curl_setopt($ch, CURLOPT_URL, "https://ws.srl.tw/test/".$case_num."/");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
   
    //將curl_exec()獲取的訊息以文件流的形式返回，而不是直接輸出。
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
   
    //-- 執行 --
    $temp=curl_exec($ch);

    $temp=preg_replace('/\.\.\/\.\.\//', '', $temp);
    $temp=preg_replace('/https\:\/\/ws\.srl\.tw\/cs\/'.$case_num.'\/img\//', 'img/', $temp);
    $temp=preg_replace('/\.\.\/product_html\/'.$job['case_id'].'\/img\//', 'img/', $temp);
    $temp=preg_replace('/https\:\/\/ws\.srl\.img\//', 'img/', $temp);
    $temp=preg_replace('/googleMapTool/', 'https://ws.srl.tw/googleMapTool', $temp);

    $web_url_index=$web_url.'/index.html';
    $fp=fopen($web_url_index, "w");
    fwrite($fp, $temp);
    fclose($fp);
    
   
    // 關閉CURL連線
    curl_close($ch);



     //-- 壓縮檔案 --
     // Get real path for our folder
     $rootPath = realpath('website_tmp/'.$job['case_id']);

     // Initialize archive object
     $zip = new ZipArchive();
     $zip->open('website_tmp/'.$job['case_id'].'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

     // Create recursive directory iterator
     /** @var SplFileInfo[] $files */
     $files = new RecursiveIteratorIterator(
         new RecursiveDirectoryIterator($rootPath),
         RecursiveIteratorIterator::LEAVES_ONLY
     );

     foreach ($files as $name => $file)
     {
         // Skip directories (they would be added automatically)
         if (!$file->isDir())
         {
            // Get real and relative path for current file
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);

            // Add current file to archive
            $zip->addFile($filePath, $relativePath);
         }
     }

     // Zip archive will be created only after closing object
     $zip->close();

     
     deleteDir($web_url);
}



$pdo_job->close();
?>