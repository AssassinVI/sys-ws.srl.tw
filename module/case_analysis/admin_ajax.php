<?php 
 require '../../core/inc/config.php';
 require '../../core/inc/function.php';
 require '../../core/inc/pdo_fun_calss.php';
 if ($_POST) {

   $pdo=new PDO_fun();

   if ($_POST['type']=='company') {

     //---------------- 查詢專案 --------------
      $where='';
          
      if ($_SESSION['admin_per']!='admin') {
        
        $case_arr_num=count($_SESSION['group_case']);
        for ($i=0; $i <$case_arr_num ; $i++) { 
          $where.=" Tb_index='".$_SESSION['group_case'][$i]."' OR";
        }
        $where="AND (".mb_substr($where, 0,-2, 'utf-8').")";
      }

      if ($_POST['com_id']=='all'){
        $sql_query="SELECT Tb_index, aTitle, version, OnLineOrNot, OrderBy FROM build_case WHERE OnLineOrNot!=-1 ".$where." ORDER BY OnLineOrNot DESC, OrderBy DESC, Tb_index DESC";
        $where_arr=[];
      }
      else{
        $sql_query="SELECT Tb_index, aTitle, version, OnLineOrNot, OrderBy FROM build_case WHERE com_id=:com_id AND OnLineOrNot!=-1 ".$where." ORDER BY OnLineOrNot DESC, OrderBy DESC, Tb_index DESC";
        $where_arr=['com_id'=>$_POST['com_id']];
      }

      $row=$pdo->select($sql_query, $where_arr);

      $x=0;
      foreach ($row as $one) {
        $an_arr=[];
        $row_an=$pdo->select("SELECT one_user FROM an_user WHERE case_id=:case_id AND date BETWEEN DATE_SUB(CURDATE(),INTERVAL 30 DAY) AND CURDATE() GROUP BY date ORDER BY date ASC",
                              ['case_id'=>$one['Tb_index']]);
        foreach ($row_an as $an_one) {
          array_push($an_arr, $an_one['one_user']);
        }
        $row[$x]['an']=$an_arr;
        $x++;
      }
   	
      echo json_encode($row);
   }
  
 }
?>