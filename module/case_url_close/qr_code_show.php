<?php
include "../../core/inc/config.php"; //載入基本設定
include "../../core/inc/function.php"; //載入基本function
include "../../core/inc/pdo_fun_calss.php";
?>
<!DOCTYPE html>
<html lang="zh-tw"">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR code 網址</title>
    <style>
     .qr_div{padding: 30px;}
     .qr_div div{padding: 10px; border-bottom: 1px solid #ccc; text-align: center; font-family: Microsoft JhengHei;}
     .copy_btn{display: inline-block;  padding: 7px 10px;  text-decoration: none;  background-color: #1B7BB9;  color: #fff;  border-radius: 4px;}
     .close_btn{display: inline-block;  padding: 7px 43px;  text-decoration: none;  background-color: #d01b1b;  color: #fff;  border-radius: 4px;}
     #QRcode_div img{margin:auto;}
    </style>
</head>
<body>
 
<?php
if($_GET && isset($_GET['qr_id'])){

  $qr_id=urldecode(aes_decrypt_7($aes_key, $_GET['qr_id']));
  $pdo= new PDO_fun;
  $qr=$pdo->select("SELECT QRcode_pic, QRcode_url, source FROM QRcode_tb WHERE Tb_index=:Tb_index", ['Tb_index'=>$qr_id], 'one');
  if(empty($qr['source'])){
    echo '找無來源....';
  }
  else{

    echo '
     <div class="qr_div">
       <div id="QRcode_div">
        
       </div>
       <div>
         <span>網址：</span><a class="QRcode_url" href="'.$qr['QRcode_url'].'">'.$qr['QRcode_url'].'</a>
       </div>
       <div>
         <span>媒體：</span>'.$qr['source'].'
       </div>
       <div>
         <a class="copy_btn" href="javascript:;">複製本頁網址</a>
       </div>
       <div>
         <a class="close_btn" href="javascript:;">關閉</a>
       </div>
     </div>';
  }
}
else{
  echo 'error....';
}
?>

<script src="../../js/jquery-2.1.1.js"></script>

<!-- qrcode -->
<script src="../../js/qrcode.min.js"></script>

<script>
 $(document).ready(function () {


  
    var qrcode = new QRCode(document.getElementById('QRcode_div'), {
        width : 150,
        height : 150
      });

      qrcode.makeCode($('.QRcode_url').html());

   
   /*-- 複製btn --*/
   $('.copy_btn').click(function (e) { 
     var temp = $('<input>'); // 建立input物件
       $('body').append(temp); // 將input物件增加到body
       //var url = $('.QRcode_url').html(); // 取得要複製的連結
       var url = location.href; // 取得要複製的連結
       temp.val(url).select(); // 將連結加到input物件value
       document.execCommand('copy'); // 複製
       temp.remove(); // 移除input物件
	   alert('複製成功\n'+url);
   });


   
   $('.close_btn').click(function (e) { 
      window.opener=null;
      window.close();
     });



 });



</script>
    
</body>
</html>