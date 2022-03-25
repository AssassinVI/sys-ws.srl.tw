<?php 
 if (empty($_COOKIE['admin_index'])) {

  //-- 一周分析報告 --
  if(preg_match("/an_code/i", $_SERVER['QUERY_STRING'])){
    setcookie("newHref",'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],time()+200, '/sys');
  }

   echo "<script>";
   echo "location.replace('../../login.php');"; //網頁跳轉
   echo "alert('請登入系統');";
   echo "</script>";
   exit();
 }
 elseif ($_COOKIE['admin_index']!=unlock_key($_COOKIE['sys_login_key'])) {
   echo "<script>";
   echo "location.replace('../../login.php');"; //網頁跳轉
   echo "alert('錯誤登入方式');";
   echo "</script>";
   exit();
 }
 //-- 保持登入 --
else{

      setcookie("admin_index",$_COOKIE['admin_index'],time()+7200, '/sys');
      setcookie("admin_per",$_COOKIE['admin_per'],time()+7200, '/sys');
      setcookie("sys_login_key",$_COOKIE['sys_login_key'],time()+7200, '/sys');

      if ($_COOKIE['admin_per']!='admin') {
        setcookie("admin_name",$_COOKIE['admin_name'],time()+7200, '/sys');
        setcookie("group",$_COOKIE['group'],time()+7200, '/sys');
        setcookie("group_com",$_COOKIE['group_com'],time()+7200, '/sys');
        setcookie("group_case",$_COOKIE['group_case'],time()+7200, '/sys');
      }
      else{
        setcookie("admin_name",$_COOKIE['admin_name'],time()+7200, '/sys');
      }
      
        if (empty($_SESSION['admin_index'])) {
           $_SESSION['admin_index'] = $_COOKIE['admin_index'];
           $_SESSION['admin_name']=$_COOKIE['admin_name'];
           $_SESSION['admin_per'] = $_COOKIE['admin_per'];
           if ($_COOKIE['admin_per']!='admin'){
             $_SESSION['group']= explode(',', $_COOKIE['group']);
             $_SESSION['group_com']= explode(',', $_COOKIE['group_com']);
             $_SESSION['group_case']= explode(',', $_COOKIE['group_case']);
           }
           $_SESSION['sys_login_key']= $_COOKIE['sys_login_key'];
        }


        //-- POST token --
        if($_GET){
          $_SESSION['token'] = randTXT(35);
        }
        
}
?>