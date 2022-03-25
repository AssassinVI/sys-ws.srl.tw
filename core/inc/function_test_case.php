<?php  

  
 /* ---------------- PDO新增 ----------------- */
 function pdo_insert($tb_name, $array_data, $dbname='srltw_test_case' )
 {
   $key=array_keys($array_data); //陣列鍵名
   $data_name='';
   $data='';

   for ($i=0; $i < count($array_data) ; $i++) { 
   	if ($i==count($array_data)-1) {
   	  $data_name.=$key[$i];
   	  $data.=':'.$key[$i];
   	}else{
      $data_name.=$key[$i].',';
   	  $data.=':'.$key[$i].',';
   	}
   }

   $sql_query="INSERT INTO ".$tb_name." (".$data_name.") VALUES (".$data.")";

 	$pdo=pdo_conn($dbname);
 	$sql=$pdo->prepare($sql_query);
   for ($i=0; $i < count($array_data) ; $i++) { 
   		$sql->bindparam(':'.$key[$i], $array_data[$key[$i]]);
   	}	
 	$sql->execute();
 	$pdo=NULL;
 }



 /* ---------------- PDO修改 ----------------- */
 function pdo_update($tb_name, $array_data, $where, $dbname='srltw_test_case')
 {
   $key=array_keys($array_data);//陣列鍵名
   $where_key=array_keys($where);
   $data='';

   for ($i=0; $i < count($array_data) ; $i++) { 
   	if ($i==count($array_data)-1) {
   	  $data.=$key[$i].'=:'.$key[$i];
   	}else{
   	  $data.=$key[$i].'=:'.$key[$i].',';
   	}
   }

   $sql_query="UPDATE ".$tb_name." SET ".$data." WHERE ".$where_key[0]."=:".$where_key[0];

    $pdo=pdo_conn($dbname);
 	$sql=$pdo->prepare($sql_query);
   for ($i=0; $i < count($array_data) ; $i++) { 
   		$sql->bindparam(':'.$key[$i], $array_data[$key[$i]]);
   	}	
   	$sql->bindparam(':'.$where_key[0], $where[$where_key[0]]);
 	$sql->execute();
 	$pdo=NULL;
 }


 /* ---------------- PDO刪除 ----------------- */
 function pdo_delete($tb_name, $where, $dbname='srltw_test_case')
 {
 	$where_key=array_keys($where);//陣列鍵名
    
    $sql_query="DELETE FROM ".$tb_name." WHERE ".$where_key[0]."=:".$where_key[0];

    $pdo=pdo_conn($dbname);
 	$sql=$pdo->prepare($sql_query);	
   	$sql->bindparam(':'.$where_key[0], $where[$where_key[0]]);
 	$sql->execute();
 	$pdo=NULL;
 }


 /* ----------------------- PDO 查詢 --------------------------- */
 function pdo_select($sql_query, $where, $dbname='srltw_test_case')
 {
   $pdo=pdo_conn($dbname);
   $sql=$pdo->prepare($sql_query);

   if ($where!='no') {
      $where_key=array_keys($where);//陣列鍵名
      for ($i=0; $i <count($where) ; $i++) { 
         $sql->bindparam($where_key[$i], $where[$where_key[$i]]);
      }
   }
   $sql->execute();
   if ($sql->rowcount()>1) {

      $row=$sql->fetchAll(PDO::FETCH_ASSOC);
      return $row;

   }else{
      $row=$sql->fetch(PDO::FETCH_ASSOC);
      return $row;
   }
   
   $pdo=NULL;
 }




 
//-- 圖片檔案上傳 --
function fire_upload($file_id, $file_name,$case_id)
{
  if(test_img($file_name)){
    move_uploaded_file($_FILES[$file_id]['tmp_name'], '../../../product_html/'.$case_id.'/img/'.$file_name);
  }
  else{
    echo '這不是圖檔';
  }
}

//-- 音樂檔案上傳 --
function audio_upload($file_id, $file_name,$case_id)
{
  if(test_audio($file_name)){
    move_uploaded_file($_FILES[$file_id]['tmp_name'], '../../../product_html/'.$case_id.'/audio/'.$file_name);
  }
  else{
    echo '這不是音樂檔';
  }
    
}

  /* ----------------------- 影片檔案上傳 --------------------------- */
 function video_upload($file_id, $file_name,$case_id)
 {
  if(test_file($file_name)){
    move_uploaded_file($_FILES[$file_id]['tmp_name'], '../../../product_html/'.$case_id.'/video/'.$file_name);
  }
  else{
    echo '這不是影片檔';
  }
   
 }

 /* ----------------------- 其他檔案上傳 --------------------------- */
  function other_fire_upload($file_id, $file_name)
 {
  if(test_file($file_name)){
    move_uploaded_file($_FILES[$file_id]['tmp_name'], '../../other_file/'.$file_name);
  }
  else{
    echo '這不是正常的檔案';
  } 
 }

  /* ----------------------- 其他檔案上傳(多檔) --------------------------- */
  function more_other_upload($file_id,$i, $file_name)
 {
  if(test_file($file_name)){
    move_uploaded_file($_FILES[$file_id]['tmp_name'][$i], '../../other_file/'.$file_name);
  }
  else{
    echo '這不是正常的檔案';
  } 
    move_uploaded_file($_FILES[$file_id]['tmp_name'][$i], '../../other_file/'.$file_name);
 }

  /* ----------------------- 多檔案上傳 --------------------------- */
  function more_fire_upload($file_id, $i, $file_name, $case_id)
 {
  if(test_file($file_name)){
    move_uploaded_file($_FILES[$file_id]['tmp_name'][$i], '../../../product_html/'.$case_id.'/img/'.$file_name);
  }
  else{
    echo '這不是正常的檔案';
  } 
 }


//----------------------------- 建立專案資料夾 ---------------------------------
   function create_dir($case_id)
  {
     mkdir($case_id);
     mkdir($case_id.'/img');
     mkdir($case_id.'/audio');
     mkdir($case_id.'/video');
     mkdir($case_id.'/zip');
  }

/* ==================================== 複製資料夾 ======================================== */
      function copy_dir($from_dir,$to_dir){  
        if(!is_dir($from_dir)){   //判斷有無要複製的資料夾
            return false ;  
        } 
        
      //  echo "<br>From:",$from_dir,' --- To:',$to_dir;  
        $from_files = scandir($from_dir);   //讀取資料夾裡有的檔案，array顯示

        if(!file_exists($to_dir)){  //判斷有無要貼上的資料夾
            mkdir($to_dir);  //產生資夾    
        }  
        if( ! empty($from_files)){  

            foreach ($from_files as $file){   
                if($file == '.' || $file == '..' ){  //判別虛擬資料夾
                    continue;  
                }  
                  
                if(is_dir($from_dir.'/'.$file)){
                    copy_dir($from_dir.'/'.$file,$to_dir.'/'.$file);  
                }else{
                    copy($from_dir.'/'.$file,$to_dir.'/'.$file);  
                }  
            }  
        }
        return true ;
    }

/* ==================================== 刪除資料夾 ======================================== */
 function deleteDir($dirPath)
{
    if (! is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            deleteDir($file); // recursion
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}


/* --------------------------- 判斷檔案是否存在 ---------------------------- */
function is_post_file($tb_name, $Tb_index, $file_id, $session_name)
{
   $where=array('Tb_index'=>$Tb_index);
   $row=pdo_select("SELECT ".$file_id." FROM ".$tb_name." WHERE Tb_index=:Tb_index", $where);
   if (isset($_SESSION[$session_name])) {

      return $_SESSION[$session_name];
   }
   elseif (isset($row[$file_id])){
      return $row[$file_id];
   }
   else{
      return '';
   }
}


//----------------------------- 每日流量 ---------------------------
function OneDayChart()
{
  if (empty($_SESSION['on_web'])) {
  $where=array('ChartDate'=>date('Y-m-d'));
  $row=pdo_select("SELECT * FROM OneDayChart WHERE ChartDate=:ChartDate", $where);

  if (empty($row['ChartDate'])) {
    $param=array('ChartDate'=>date('Y-m-d'), 'ChartNum'=>'1');
    pdo_insert('OneDayChart', $param);
  }
  else{
    $pdo=pdo_conn();
    $sql=$pdo->prepare("UPDATE OneDayChart SET ChartNum=ChartNum+1 WHERE ChartDate=:ChartDate");
    $sql->execute(array(':ChartDate'=>$row['ChartDate']));
  }
}
  $_SESSION['on_web']='online';
}


/* ------------------------------- 網頁跳轉 ------------------------------------ */

function location_up($location_path,$alert_txt)
{
   echo "<script>";

   if ($location_path=='back') {
     echo "history.back();"; //返回上一頁
   }else{
     echo "location.replace('".$location_path."');"; //網頁跳轉
   }
   
   if (!empty($alert_txt)) {
    echo "alert('" . $alert_txt . "');";
   }
   echo "</script>";
}



//--------------------------------- 資料AES加密 --------------------------------
function aes_encrypt($key, $data)
{
$hash = hash('SHA384', $key, true);
$app_cc_aes_key = substr($hash, 0, 32);
$app_cc_aes_iv = substr($hash, 32, 16);

$padding = 16 - (strlen($data) % 16); 
$data .= str_repeat(chr($padding), $padding); 
$encrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $app_cc_aes_key, $data, MCRYPT_MODE_CBC, $app_cc_aes_iv); 
$encrypt_text = base64_encode($encrypt);
return $encrypt_text;
}


//-------------------------------- 資料AES解密 --------------------------------------
function aes_decrypt($key, $unlock_data)
{ 
  $hash = hash('SHA384', $key, true);
  $app_cc_aes_key = substr($hash, 0, 32);
  $app_cc_aes_iv = substr($hash, 32, 16);

  $encrypt=base64_decode($unlock_data);
  $data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $app_cc_aes_key, $encrypt, MCRYPT_MODE_CBC, $app_cc_aes_iv);
  $padding = ord($data[strlen($data) - 1]);
  $decrypt_text = substr($data, 0, -$padding);
  return $decrypt_text;
}


//-------------------------------- 登入密鑰 -----------------------------------------
function login_key($login_key)
{ 
  global $aes_key;
  //** 加入登入密鑰 **
        $_SESSION['sys_login_key']=aes_encrypt( $aes_key, $login_key);
}

//-------------------------------- 登入解密 -----------------------------------------
function unlock_key($login_key)
{ 
  global $aes_key;
  //** 加入解密 **
        $unlock_key=aes_decrypt( $aes_key, $login_key);
        return $unlock_key;
}


//----------------GOOGLE recaptcha 驗證程式 --------------------
function GOOGLE_recaptcha($secretKey, $recaptcha_response, $location)
{
    if (!empty($recaptcha_response)) {

    $ReCaptchaResponse = filter_input(INPUT_POST, 'g-recaptcha-response');

    // 建立CURL連線
    $ch = curl_init();
    // 設定擷取的URL網址
    curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response=' . trim($ReCaptchaResponse));
    curl_setopt($ch, CURLOPT_HEADER, false);
    //將curl_exec()獲取的訊息以文件流的形式返回，而不是直接輸出。
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // 執行
    $Response = curl_exec($ch);
    // 關閉CURL連線
    curl_close($ch);

    //$Response=file_get_contents();
    $re_code = json_decode($Response, true);

    if ($re_code['success'] != true) {

      location_up($location, '請確定您不是機器人');
      exit();
    }
  } else {

    location_up($location, '請確定您不是機器人');
    exit();
  }
}

  //----------------GOOGLE recaptcha 驗證程式 --------------------*


//--- 亂碼產生器 ----
function randTXT($num)
{
 
  //$random預設為10，更改此數值可以改變亂數的位數----(程式範例-PHP教學)
$random=empty($num) ? 10:$num ;
//FOR回圈以$random為判斷執行次數
for ($i=1;$i<=$random;$i=$i+1)
{
//亂數$c設定三種亂數資料格式大寫、小寫、數字，隨機產生
$c=rand(1,3);
//在$c==1的情況下，設定$a亂數取值為97-122之間，並用chr()將數值轉變為對應英文，儲存在$b
if($c==1){$a=rand(97,122);$b=chr($a);}
//在$c==2的情況下，設定$a亂數取值為65-90之間，並用chr()將數值轉變為對應英文，儲存在$b
if($c==2){$a=rand(65,90);$b=chr($a);}
//在$c==3的情況下，設定$b亂數取值為0-9之間的數字
if($c==3){$b=rand(0,9);}
//使用$randoma連接$b
$randoma=$randoma.$b;
}
//輸出$randoma每次更新網頁你會發現，亂數重新產生了
return $randoma;
}


//--------------------- 手機判斷 -------------------
function check_mobile(){
    $regex_match="/(nokia|iphone|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|";
    $regex_match.="htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|";
    $regex_match.="blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|";
    $regex_match.="symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|";   
    $regex_match.="jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320x320|240x320|176x220";
    $regex_match.=")/i";
    return preg_match($regex_match, strtolower($_SERVER['HTTP_USER_AGENT']));
}



//--------------------- 平板手機判斷 -------------------
function wp_is_mobile() {
  static $is_mobile = null;
 
  if ( isset( $is_mobile ) ) {
    return $is_mobile;
  }
 
  if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
    $is_mobile = false;
  } elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false // many mobile devices (all iPhone, iPad, etc.)
    || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
    || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
    || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
    || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
    || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
    || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {
      $is_mobile = true;
  } else {
    $is_mobile = false;
  }
 
  return $is_mobile;
}




//-------------------------------- 驗證 input 排除特殊符號 ---------------------------------------------
function test_input($GET)
{
  if(preg_match("/^(?:[^\~|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\_|\=|\+|\{|\}|\[|\]|\"|\'|\?|\<|\>]+)$/", $GET)){ //== 排除特殊符號 == 
    return $GET;
  }
  else{
    location_up('back','請勿輸入特殊字元!!');
    exit();
  }
}


//-------------------------------- 驗證 E-mail  ---------------------------------------------
function test_mail($mail)
{
  if (preg_match("/^\w+(?:(?:-\w+)|(?:\.\w+))*\@\w+(?:(?:\.|-)\w+)*\.[A-Za-z]+$/", $mail)) {
    return true;
  }else{
    return false;
  }
}

//----------------------------------- 驗證圖片 ---------------------------------
function test_audio($audio)
{
  if (preg_match('/^.+\.(mp3)$/i', $audio)){
    return true;
  }else{
    return false;
  }
}


//----------------------------------- 驗證圖片 ---------------------------------
function test_img($img)
{
  if (preg_match('/^.+\.(jpg|png|gif)$/i', $img)){
    return true;
  }else{
    return false;
  }
}


//----------------------------------- 驗證其他檔案 ---------------------------------
function test_file($file)
{
  if (preg_match('/^.+\.(jpg|png|gif|doc|docx|xls|xlsx|ppt|pptx|pdf|mp4)$/i', $file)){
    return true;
  }else{
    return false;
  }
}

?>