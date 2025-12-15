<?php
 require '../../core/inc/config.php';
 require '../../core/inc/function.php';
 require '../../core/inc/pdo_fun_calss.php';

//  ini_set('display_errors','1');
// error_reporting(E_ALL);

  if ($_POST){
    $pdo=new PDO_fun;
    $_POST=json_decode(file_get_contents("php://input"), true);
    if ($_POST['type']=='save_life'){
        
        $x=0;
        foreach ($_POST['places'] as $place) {
           $case=$pdo->select("SELECT * FROM life_tb WHERE case_id=:case_id", ['case_id'=>$_POST['Tb_index']], 'one');
           $Tb_index='ll'.date('YmdHis').$x;
           $life_location='('.$place['location']['lat'].', '.$place['location']['lng'].')';
           $internationalPhoneNumber=str_replace(' ', '', $place['internationalPhoneNumber']);
           $internationalPhoneNumber = preg_replace('/^\+886/', '0', $internationalPhoneNumber);
           $param=[
              'Tb_index'=>$Tb_index,
              'case_id'=>$_POST['Tb_index'],
              'life_type'=>$_POST['life_type'],
              'case_location'=>$case['location'],
              'life_location'=>$life_location,
              'life_name'=>$place['displayName'],
              'life_photo'=>$_POST['places_img'][$x],
              'life_phone'=>$internationalPhoneNumber,
              'life_addr'=>$place['formattedAddress'],
              'life_eva'=>$place['rating'],
              'OrderBy'=>$x+1,
            ];
            $pdo->insert('life_location', $param);
            $x++;
        }

        echo json_encode(['success'=>true, 'message'=>'儲存成功']);
        
    }

    $pdo->close();
  }
?>