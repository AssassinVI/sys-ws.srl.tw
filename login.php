<?php session_start();
require 'core/inc/config.php';
require 'core/inc/function.php';
require 'core/inc/pdo_fun_calss.php';

//------------------- 判斷HTTPS ------------------
/*if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS']!='on'){
  header("Location: https://srl.tw/newsite/sys/login.php");
  exit();
}*/
?>
<?php
$pdo_new=new PDO_fun;

$company_txt = pdo_select("SELECT * FROM company_base WHERE webLang='tw'", 'no');


if ($_GET) {
	if ($_GET['login'] == 'out') {
    session_destroy();

    if ($_COOKIE['admin_per']!='admin') {
      setcookie("group",'',time()-7200);
      setcookie("group_com",'',time()-7200);
      setcookie("group_case",'',time()-7200);
    }

    setcookie("admin_name",'',time()-7200);
    setcookie("admin_index",'',time()-7200);
    setcookie("admin_per",'',time()-7200);
    setcookie("sys_login_key",'',time()-7200);
    setcookie("is_an_all",'',time()-7200);

  }
}

if ($_POST) {

	//-- GOOGLE recaptcha 驗證程式 --
 	GOOGLE_recaptcha('6Le-hSUTAAAAAKpUuKnGOoHpKhgq60V1irZPA_4E', $_POST['g-recaptcha-response'], 'login.php');
    
     
       //-- 帳密登入 --
      if(!empty($_POST['admin_id']) && !empty($_POST['admin_pwd'])){
         
         $where = array("admin_id" => $_POST['admin_id'], "admin_pwd" => aes_encrypt_7($aes_key, $_POST['admin_pwd']));
         $admin = pdo_select("SELECT Tb_index, admin_per, name, is_an_all, StartDate, EndDate FROM sysAdmin WHERE admin_id=:admin_id AND admin_pwd=:admin_pwd AND is_use='1'", $where);

       if (empty($admin)) {
           location_up('login.php', '帳號或密碼錯誤!!');
           exit();
       } 

        if($admin['StartDate']=='0000-00-00' && $admin['EndDate']=='0000-00-00'){
          $is_use_date=true;
        }
        elseif(strtotime($admin['StartDate'])<= strtotime('today') && $admin['EndDate']=='0000-00-00'){
          $is_use_date=true;
        }
        elseif(strtotime($admin['StartDate'])<= strtotime('today') && strtotime($admin['EndDate']) >= strtotime('today')){
          $is_use_date=true;
        }
        else{
          $is_use_date=false;
        }

       if(!$is_use_date){
          location_up('login.php', '非權限使用期間，請與管理員聯絡!!');
          exit();
       }

    }
    //-- 記住我 --
    elseif(!empty($_COOKIE['Tb_index'])){

      $where=["Tb_index"=>$_COOKIE['Tb_index']];
      $admin=pdo_select("SELECT Tb_index, admin_per, name, is_an_all FROM sysAdmin WHERE Tb_index=:Tb_index", $where);
      
      if (empty($admin['Tb_index'])) {
         location_up('login.php', 'COOKIE錯誤，請重新登入!!');
         exit();
      }
    }


    //------------------------- 記住我 -------------------------------

        if(!empty($_POST['remember'])){
           setcookie('Tb_index', $admin['Tb_index'], time()+3600000);
        }else{
           setcookie('Tb_index', '', time()-3600000);
        }
          
          //-------------------------- 權限判斷 ----------------------------

        if ($admin['admin_per'] == 'admin') {
            
            //登入密鑰
            login_key($admin['Tb_index']);
            $_SESSION['admin_index'] = $admin['Tb_index'];
            $_SESSION['admin_per'] = $admin['admin_per'];
            setcookie("admin_index",$admin['Tb_index'],time()+7200);
            setcookie("admin_name",$admin['name'],time()+7200);
            setcookie("admin_per",$admin['admin_per'],time()+7200);

               if (empty($_COOKIE['newHref'])) {
                 location_up('module/case_analysis/admin.php?MT_id=site2018040210520640', '歡迎管理者登入');
               }
               else{
                 location_up($_COOKIE['newHref'], '歡迎管理者登入');
               }
        } else {
           
            
            $group_where=array("Tb_index"=>$admin['admin_per']);
            $group=pdo_select("SELECT * FROM sysAdminGroup WHERE Tb_index=:Tb_index", $group_where);
            $group_array=explode(',', $group['Permissions']);

            //登入密鑰
            login_key($admin['Tb_index']);
            $_SESSION['admin_index'] = $admin['Tb_index'];
            $_SESSION['admin_name'] = $admin['name'];
            $_SESSION['admin_per'] = $admin['admin_per'];
            $_SESSION['group']=$group_array;
            $_SESSION['group_com']=explode(',', $group['company_id']);
            $_SESSION['group_case']=explode(',', $group['case_id']);

               setcookie("admin_index",$admin['Tb_index'],time()+7200);
               setcookie("admin_name",$admin['name'],time()+7200);
               setcookie("admin_per",$admin['admin_per'],time()+7200);
               setcookie("group",$group['Permissions'],time()+7200);
               setcookie("group_com",$group['company_id'],time()+7200);
               setcookie("group_case",$group['case_id'],time()+7200);
               setcookie("is_an_all",$admin['is_an_all'],time()+7200);

              if (empty($_COOKIE['newHref'])){
                location_up('module/case_analysis/admin.php?MT_id=site2018040210520640', '歡迎' . $admin['name'] . '登入');
              }
              else{
                location_up($_COOKIE['newHref'], '歡迎' . $admin['name'] . '登入');
              }
        }

        //-- 新增歷史紀錄 --
        $pdo_new->hs_tb_name='sysAdmin';
        $pdo_new->hs_h_location='管理者登入';
        $pdo_new->hs_h_action_type='login';
        $pdo_new->hs_h_title='管理者登入-'.$admin['name'];
        $pdo_new->hs_admin_id=$admin['Tb_index'];
        $pdo_new->hs_admin_name=$admin['name'];
          //-- 舊資料 --
        $pdo_new->old_data();

        $pdo_new->add_history();
}

$pdo_new->close();
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $company_txt['name'] ?> | Login</title>
    <link rel="shortcut icon" href="../favicon.ico" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <link rel="shortcut icon" href="/favicon.ico" />
  <style type="text/css">
    body{ font-family: Microsoft JhengHei }
    .logo-name{ font-size: 75px; letter-spacing: -5px; text-shadow: 2px 4px 10px #acacac;color:#fff;}
    #check_div{text-align: left; padding: 5px 15px; border: 1px solid #d5d5d5;}
    #check_div label{ padding: 5px; padding-right: 190px; }
    .member{ padding: 14px 0; border: 1px solid #ccc;}
  </style>


</head>

<body class="gray-bg">

<?php 
 //-- 轉換密碼編譯 --
//  $pdo=new PDO_fun;
//  $sysadmin=$pdo->select("SELECT * FROM sysAdmin WHERE is_use=1");
//  foreach ($sysadmin as $admin_one) {
//    $decrypt_txt=aes_decrypt($aes_key, $admin_one['admin_pwd']);
//    $new_pwd=aes_encrypt_7($aes_key, $decrypt_txt);
//    $pdo->update("sysAdmin", ['admin_pwd'=>$new_pwd], ['Tb_index'=>$admin_one['Tb_index']]);
//    echo $decrypt_txt.' --> '.$new_pwd.'<br>';
//  }
//  $pdo->close();
?>

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>

                <h3 class="logo-name"><?php echo $e_name = empty($company_txt['e_name']) ? $company_txt['name'] : $company_txt['e_name'] ?></h3>

            </div>
            <h3>Welcome to <?php echo $e_name?></h3>

            <form class="m-t" role="form" method="POST" action="login.php">
            
            <?php
              if(!empty($_COOKIE['Tb_index'])){
                $where=["Tb_index"=>$_COOKIE['Tb_index']];
                $admin_name=pdo_select("SELECT name FROM sysAdmin WHERE Tb_index=:Tb_index", $where);

                echo '
                <div id="mem_div" class="form-group">
                  <h3 class="member">'.$admin_name['name'].'</h3>
                </div>
                <button type="button" class="other_mem btn btn-default block full-width m-b">其他帳號</button>';
              }
              else{

                echo '
                <div class="form-group">
                    <input type="text" class="form-control" name="admin_id" placeholder="Username" required="" value="">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="admin_pwd" placeholder="Password" required="">
                </div>';
              }
            ?>
                
                <div id="check_div" class="form-group">
                    <input type="checkbox" name="remember" id="remember" value="1" <?php echo $check=empty($_COOKIE['Tb_index'])?'':'checked'; ?>> <label for="remember">記住帳號</label>
                </div>
                <!-- google 驗證碼 -->
                <div class="g-recaptcha" data-sitekey="6Le-hSUTAAAAABhfvrZeqewWS6hENhApDVtdAJfr"></div>
                <button type="submit" class="btn btn-primary block full-width m-b">登入系統</button>

            </form>
            <p class="m-t"> <small>Copyright ©<?php echo $company_txt['remark'] ?></small> </p>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-2.1.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- GOOGLE recaptcha 驗證程式 -->
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.other_mem').click(function(event) {
                if (confirm('是否要改用其他帳號登入??')) {
                  var mem_txt=
                  '<div class="form-group">'+
                      '<input type="text" class="form-control" name="admin_id" placeholder="Username" required="" value="">'+
                  '</div>'+
                  '<div class="form-group">'+
                      '<input type="password" class="form-control" name="admin_pwd" placeholder="Password" required="">'+
                  '</div>';
                  $('#mem_div').html(mem_txt);  
                }
            });
        });
    </script>





</body>

</html>
