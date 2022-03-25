<?php 
 require '../../core/inc/config.php';
 require '../../core/inc/function.php';
 require '../../core/inc/pdo_fun_calss.php';
 if ($_POST) {
   
   $pdo_new=new PDO_fun;
    
   if ($_POST['type']=='company') {

     //---------------- 查詢專案 --------------
     $pdo=pdo_conn();

     if ($_POST['com_id']=='all') {

        $where='';
        
        if ($_SESSION['admin_per']!='admin') {
          
          $case_arr_num=count($_SESSION['group_case']);
          for ($i=0; $i <$case_arr_num ; $i++) { 
            $where.=" Tb_index='".$_SESSION['group_case'][$i]."' OR";
          }
          $where="AND (".mb_substr($where, 0,-2, 'utf-8').") ";
        }
        
        $sql_query="SELECT Tb_index, aTitle, version, OnLineOrNot, OrderBy, google_code FROM build_case WHERE OnLineOrNot=-1 ".$where." ORDER BY OnLineOrNot DESC, OrderBy DESC, Tb_index DESC";

        $sql=$pdo->prepare($sql_query);
        $sql->execute();
     }
     else{

       $where='';

       if ($_SESSION['admin_per']!='admin') {
          
          $case_arr_num=count($_SESSION['group_case']);
          for ($i=0; $i <$case_arr_num ; $i++) { 
            $where.=" Tb_index='".$_SESSION['group_case'][$i]."' OR";
          }
          $where="AND (".mb_substr($where, 0,-2, 'utf-8').")";
        }

       $sql=$pdo->prepare("SELECT Tb_index, aTitle, version, OnLineOrNot, OrderBy, google_code FROM build_case WHERE com_id=:com_id AND OnLineOrNot=-1 ".$where." ORDER BY OnLineOrNot DESC, OrderBy DESC, Tb_index DESC");
       $sql->execute(['com_id'=>$_POST['com_id']]);
     }
   	 
       $row=$sql->fetchAll(PDO::FETCH_ASSOC);
   	
      echo json_encode($row);
   }
   
   //-- 刪除建案 --
   elseif($_POST['type']=='del_case'){

      if($_POST['check_txt']=='033479656'){

        //-- 基本圖文 --
        $pdo_new->delete('base_word', ['case_id'=>$_POST['case_id']]);
        //-- 錨點 --
        $pdo_new->delete('anchor_tb', ['case_id'=>$_POST['case_id']]);
        //-- 聯絡我們 --
        $pdo_new->delete('call_us_tb', ['case_id'=>$_POST['case_id']]);
        $pdo_new->delete('call_record_tb', ['case_id'=>$_POST['case_id']]);
        //-- google map --
        $pdo_new->delete('googlemap_tb', ['case_id'=>$_POST['case_id']]);
        //-- 圖片牆 --
        $pdo_new->delete('img_wall_tb', ['case_id'=>$_POST['case_id']]);
        //-- 食衣住行 --
        $pdo_new->delete('life_location', ['case_id'=>$_POST['case_id']]);
        $pdo_new->delete('life_tb', ['case_id'=>$_POST['case_id']]);
        //-- 自訂義 --
        $pdo_new->delete('other_tb', ['case_id'=>$_POST['case_id']]);
        //-- 輪播 --
        $pdo_new->delete('slideshow_tb', ['case_id'=>$_POST['case_id']]);

        //-- 基本資料 --
        $pdo_new->delete('build_case', ['Tb_index'=>$_POST['case_id']]);
        //-- 新聞 --
        $pdo_new->delete('case_news', ['case_id'=>$_POST['case_id']]);
        //-- CSS --
        $pdo_new->delete('change_css', ['Tb_index'=>$_POST['case_id']]);
        //-- 顏色 --
        $pdo_new->delete('color', ['Tb_index'=>$_POST['case_id']]);
        //-- GA --
        $pdo_new->delete('google_analytics', ['Tb_index'=>$_POST['case_id']]);
        //-- 房貸試算 --
        $pdo_new->delete('mathHome_tb', ['case_id'=>$_POST['case_id']]);
        //-- 短網址QRcode --
        $pdo_new->delete('QRcode_tb', ['case_id'=>$_POST['case_id']]);
        //-- 建案功能關聯 --
        $pdo_new->delete('Related_tb', ['case_id'=>$_POST['case_id']]);

        //-- 刪除資料夾 --
        $del_return=deleteDir('../../../product_html/'.$_POST['case_id']);
        $del_msg=$del_return!=false ? '':'，無資料夾';

        echo json_encode(['success'=>true, 'message'=>'成功刪除'.$del_msg]);
      }
      else{
        echo json_encode(['success'=>false, 'message'=>'密碼錯誤']);
      }

   }
  
   $pdo_new->close();
 }
?>