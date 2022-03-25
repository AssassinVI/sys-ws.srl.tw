<?php 
 require '../../core/inc/config.php';
 require '../../core/inc/function.php';
 if ($_POST) {
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
          $where="WHERE ".mb_substr($where, 0,-2, 'utf-8');
        }
        
        $sql_query="SELECT Tb_index, aTitle, version, OnLineOrNot, OrderBy FROM build_case ".$where." ORDER BY OrderBy DESC, Tb_index DESC";

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

       $sql=$pdo->prepare("SELECT Tb_index, aTitle, version, OnLineOrNot, OrderBy FROM build_case WHERE com_id=:com_id ".$where." ORDER BY OrderBy DESC, Tb_index DESC");
       $sql->execute(['com_id'=>$_POST['com_id']]);
     }
   	 
       $row=$sql->fetchAll(PDO::FETCH_ASSOC);
   	
      echo json_encode($row);
   }
  
 }
?>