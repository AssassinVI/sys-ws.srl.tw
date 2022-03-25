<?php
header("Content-type:application/vnd.ms-word charset=utf-8"); //檔案格式
$file_name=date('Ymd').$_POST['case_name'].'QRcode總表.doc';
header('Content-Disposition: attachment; filename='.$file_name); //設定檔案名稱

 require '../../core/inc/config.php';
 require '../../core/inc/function.php';


if ($_POST) {
    
    echo '
    <!DOCTYPE html>
    <html lang="zh-tw">
    <head>
    	<meta charset="UTF-8">
    </head>
    <body>
    <table border="1" cellpadding="5" cellspacing="0" >
	<thead>
		<tr>
			<th>QR code 圖</th>
			<th>網址</th>
			<th>來源</th>
			<th>媒體</th>
			<th>活動</th>
		</tr>
	</thead>
	<tbody>';

	$pdo=pdo_conn();
	$sql=$pdo->prepare("SELECT * FROM QRcode_tb WHERE case_id=:case_id");
	$sql->execute(['case_id'=>$_POST['case_id']]);
	while ($row=$sql->fetch(PDO::FETCH_ASSOC)) {
		echo '
		<tr>
			<td><img src="'.$row['QRcode_pic'].'"></td>
			<td>'.$row['QRcode_url'].'</td>
			<td>'.$row['media'].'</td>
			<td>'.$row['source'].'</td>
			<td>'.$row['event_name'].'</td>
		</tr>';
	}

	echo '	
      </tbody>
    </table>
    </body>
    </html>';
}
    
?>