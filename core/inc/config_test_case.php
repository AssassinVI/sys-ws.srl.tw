<?php session_start();
date_default_timezone_set("Asia/Taipei");//台灣時區
require_once 'phpmailer/class.phpmailer.php';

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ PDO連線 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
function pdo_conn($dataBase_name='srltw_test_case') {
	$dbanme = $dataBase_name; //資料庫名稱
	$user_id = 'srltw_website'; //使用者ID
	$user_pwd = '1qazXSW@3'; //使用者密碼

	$dsn = "mysql:host=localhost;dbname=" . $dbanme;
	$db = new PDO($dsn, $user_id, $user_pwd);
	$db->exec("SET NAMES UTF8");
	return $db;
}


/*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ PHPMail @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/
function send_Mail($set_name, $set_mail, $Subject, $body_data, $name_data, $adds_data)
{

$mail = new PHPMailer();                        // 建立新物件        

    $mail->IsSMTP();                                // 設定使用SMTP方式寄信        
    $mail->SMTPAuth = true;                         // 設定SMTP需要驗證

    $mail->SMTPSecure = "ssl";                      // Gmail的SMTP主機需要使用SSL連線   
    $mail->Host = "srl.tw";                 // Gmail的SMTP主機        
    $mail->Port = 465;                              // Gmail的SMTP主機的port為465      
    $mail->CharSet = "utf-8";                       // 設定郵件編碼   
    $mail->Encoding = "base64";
    $mail->WordWrap = 50;                           // 每50個字元自動斷行
    $mail->Username = "server@srl.tw";     // 設定驗證帳號        
    $mail->Password = "1qazXSW@3";              // 設定驗證密碼        
    $mail->From = $set_mail;                 // 設定寄件者信箱        
    $mail->FromName = $set_name;                 // 設定寄件者姓名        
    $mail->Subject =$Subject ;                   // 設定郵件標題        
    $mail->IsHTML(true);                            // 設定郵件內容為HTML  

   for ($i=0; $i <count($name_data) ; $i++) { 
     $mail->AddAddress($adds_data[$i],$name_data[$i]);    // 收件人
   }
    $mail->Body = $body_data;
    $mail->Send();
}


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 語系 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
if (!empty($_GET['lang'])) {
	$_SESSION['sys_weblang'] = $_GET['lang'];
}
$weblang = empty($_SESSION['sys_weblang']) ? 'tw' : $_SESSION['sys_weblang'];

/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 加密金鑰 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
  $aes_key='srl'; //加密鑰匙;

?>