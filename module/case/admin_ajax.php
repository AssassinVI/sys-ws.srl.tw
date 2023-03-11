<?php 
 require '../../core/inc/config.php';
 require '../../core/inc/function.php';
 require '../../core/inc/pdo_fun_calss.php';


 
 if ($_POST) {

   $pdo_job= new PDO_fun('job');

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
        
        $sql_query="SELECT Tb_index, aTitle, version, OnLineOrNot, OrderBy, google_code FROM build_case WHERE OnLineOrNot!=-1 ".$where." ORDER BY OnLineOrNot DESC, OrderBy DESC, Tb_index DESC";

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

       $sql=$pdo->prepare("SELECT Tb_index, aTitle, version, OnLineOrNot, OrderBy, google_code FROM build_case WHERE com_id=:com_id AND OnLineOrNot!=-1 ".$where." ORDER BY OnLineOrNot DESC, OrderBy DESC, Tb_index DESC");
       $sql->execute(['com_id'=>$_POST['com_id']]);
     }
   	 
       $row=$sql->fetchAll(PDO::FETCH_ASSOC);
   	
      echo json_encode($row);
   }

   //-- 匯出網站(資料庫紀錄) --
   elseif($_POST['type']=='put_website'){
      $job=$pdo_job->select("SELECT COUNT(*) as total FROM put_website WHERE case_id=:case_id", ['case_id'=>$_POST['case_id']], 'one');
      if((int)$job['total']<1){
        $pdo_job->insert('put_website', ['case_id'=>$_POST['case_id']]);
        echo json_encode(['success'=>true, 'msg'=>'已編排匯出']);
      }
      else{
        echo json_encode(['success'=>false, 'msg'=>'匯出已編排，請勿重複!!']);
      }
   }

   //-- 下載網站 --
   elseif($_POST['type']=='download_website'){
      //-- 路徑加密 --
      echo urlencode(aes_encrypt_7($aes_key, '/home/srltw/sys-ws.srl.tw/system/cron_job/website_tmp/'.$_POST['case_id'].'.zip'));
   }


   //-- 刪除匯出檔案 --
   elseif($_POST['type']=='delete_website'){
     $path='/home/srltw/sys-ws.srl.tw/system/cron_job/website_tmp/'.$_POST['case_id'].'.zip';
     if(is_file($path)){
       unlink($path);
       echo json_encode(['success'=>true, 'msg'=>'已刪除檔案']);
     }
     else{
       echo json_encode(['success'=>false, 'msg'=>'此檔案不存在']);
     }
   }
  
   $pdo_job->close();
 }
?>