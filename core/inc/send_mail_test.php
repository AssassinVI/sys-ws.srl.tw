<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require dirname(__FILE__).'/PHPMailer_new/src/Exception.php';
require dirname(__FILE__).'/PHPMailer_new/src/PHPMailer.php';
require dirname(__FILE__).'/PHPMailer_new/src/SMTP.php';


$mail = new PHPMailer(true);                        // 建立新物件        

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_CLIENT;                      // // SMTP::DEBUG_OFF 關閉  SMTP::DEBUG_CLIENT 開啟
    $mail->isSMTP();                                            // 設定使用SMTP方式寄信  
    $mail->SMTPAuth   = true;                                   // 設定SMTP需要驗證

    $mail->SMTPSecure = "ssl"; // Gmail的SMTP主機需要使用SSL連線   
    $mail->Host = "smtp.gmail.com"; //Gamil的SMTP主機        
    $mail->Port = 465;  //Gamil的SMTP主機的SMTP埠位為465埠。
	$mail->CharSet = "UTF-8"; //設定郵件編碼
	$mail->Username = "d974252037@gmail.com"; //設定驗證帳號        
    $mail->Password = "xm30926056565"; //設定驗證密碼



    //$mail->WordWrap = 50;                           // 每50個字元自動斷行

    //Recipients
    $mail->setFrom('d974252037@gmail.com', 'test');

    $mail->addAddress('d974252037@gmail.com', '呂');    // 收件人

    // for ($i=0; $i <count($BBC_arr) ; $i++) { 
    //   $mail->addBCC($BBC_arr[$i]);  // 密件副本
    // }

    // $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
    // $mail->addAddress('ellen@example.com');               // Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');

    // Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'test';
    $mail->Body    = '測試';
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    //echo 'Message has been sent';
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error:'.$mail->ErrorInfo;
}
?>