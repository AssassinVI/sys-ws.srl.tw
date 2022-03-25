<?php include("../../core/page/header01.php");//載入頁面heaer01?>
 <link rel="stylesheet" type="text/css" href="../../css/jquery.treetable.css">
 <style type="text/css">
   tr.expanded:hover, tr.collapsed:hover { background-color: #ccfff6 !important;}
 </style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php
 if ($_POST) {
   // -- 更新排序 --
  for ($i=0; $i <count($_POST['OrderBy']) ; $i++) { 
    $data=array("OrderBy"=>$_POST['OrderBy'][$i]);
    $where=array("Tb_index"=>$_POST['Tb_index'][$i]);
    pdo_update('maintable', $data, $where);
  }
 }
 if ($_GET) {
    $Tb_index=$_GET['Tb_index'];

    //刪除檔案
    $where=array('Tb_index'=>$Tb_index);
    $row=pdo_select('SELECT aPic FROM maintable WHERE Tb_index=:Tb_index', $where);
    unlink('../../img/'.$row['aPic']);

    $where=array('Tb_index'=>$Tb_index);
    pdo_delete('maintable', $where);
 }
?>



<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary">網頁框架 列表</h2>
		
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
			<div class="panel-body">
				<div class="table-responsive"> 



<table id="product-table"  class="table table-hover ">
  <caption style=" text-align:right">
  <a href="#" id="sort_btn" class="btn btn-white btn-sm"><i class="fa fa-sort-amount-desc"></i> 更新排序</a>
  <a href="manager.php" class="btn btn-white btn-sm"><i class="fa fa-folder-open-o"></i> 建立大分類</a> <a href="manager_data.php" class="btn btn-white btn-sm"><i class="fa fa-file-text-o"></i> 建立第一層單元</a> |　 <a href="#" onclick="jQuery('#product-table').treetable('expandAll'); return false;">全部展開</a> | <a href="#" onclick="jQuery('#product-table').treetable('collapseAll'); return false;">全部收合</a>
  </caption>
  <thead>
    <tr>
      <th>名稱</th>
      <th>排序</th>
      <th>使用模組</th>
      <th>管理</th>
    </tr>
  </thead>
  <tbody>

  <?php 
  $pdo=pdo_conn();
  $sql=$pdo->prepare("SELECT * FROM maintable WHERE weblang=:weblang ORDER BY OrderBy DESC,Tb_index ASC");
  $sql->execute(array(':weblang'=>$weblang));

  while ($row=$sql->fetch(PDO::FETCH_ASSOC)) {
   
   /* -------------- 第一層 --------------- */
    if (empty($row['parent_id'])) {
       
       // -- 單元 --
  	   if ($row['is_data']=='1') { 
          $site_txt=unit_txt($row, $weblang);

       // -- 資料夾 --   
        }else{  
          $site_txt=folder_txt($row, $weblang);

          // -- 子樹狀 --
           $sql_tree=$pdo->prepare("SELECT * FROM maintable WHERE parent_id=:parent_id ORDER BY OrderBy DESC,Tb_index ASC");
           $sql_tree->execute(array(':parent_id'=>$row['Tb_index']));
           while ($row_tree=$sql_tree->fetch(PDO::FETCH_ASSOC)) {

             $site_txt.=tree_tb($row_tree, $weblang);
           }
        }
        echo $site_txt;
    }
 }
  $pdo=NUll;

/* ====================== 子樹狀階層 ====================== */
 function tree_tb($row, $weblang)
 {
  	   if ($row['is_data']=='1') { // -- 子單元 --
          $txt=unit_txt($row, $weblang);
        }else{  // -- 子資料夾 --
           $txt=folder_txt($row, $weblang);
            $pdo=pdo_conn();
            $sql=$pdo->prepare("SELECT * FROM maintable WHERE parent_id=:parent_id ORDER BY OrderBy DESC,Tb_index ASC");
            $sql->execute(array(':parent_id'=>$row['Tb_index']));
           while ($row_sql=$sql->fetch(PDO::FETCH_ASSOC)) {
           	 $txt.=tree_tb($row_sql, $weblang);
           }
           $pdo=NULL;
        }
    return $txt;
 }

/* ====================== 單元HTML ====================== */
  function unit_txt($row, $weblang)
  {
  	if (!empty($row['parent_id'])) {
       $txt='<tr data-tt-id="'.$row['Tb_index'].'" data-tt-parent-id="'.$row['parent_id'].'" style="background-color: #FAFAFA">';
       $txt.='<td class="parent_td">
                     <span style="color:#999"><strong><i class="fa fa-file-text "></i> '.$row['MT_Name'].' </strong></span>
                  </td>';
  	}
  	else{
       $txt='<tr data-tt-id="'.$row['Tb_index'].'" style="background-color: #fff">';
       $txt.='<td class="parent_td">
                     <span style="color:#179c81"><strong><i class="fa fa-file-text "></i> '.$row['MT_Name'].' </strong></span>
                  </td>';
  	}


           $txt.= '<td><input type="number" class="sort_in" Tb_index="'.$row['Tb_index'].'" value="'.$row['OrderBy'].'"> </td>';
         
         $where=array('Tb_index'=>$row['UseModuleID']);
         $mode=pdo_select("SELECT Mod_name FROM sysModule WHERE Tb_index=:Tb_index", $where);

           $txt.= '<td>'.$mode['Mod_name'].'</td>';
           $txt.= '<td align="right">
                     <a href="manager_data.php?Tb_index='.$row['Tb_index'].'&parent_id='.$row['parent_id'].'&weblang='.$weblang.'" class="btn btn-white btn-sm">
                        <i class="fa fa-pencil-square-o "></i> 修改</a> 

                     <a href="admin.php?Tb_index='.$row['Tb_index'].'" class="btn btn-white btn-sm" onclick="if (!confirm(\'確定要刪除 ['.$row['MT_Name'].'] ?\')) {return false;}">
                        <i class="fa fa-trash-o "></i> 刪除</a>
                  </td>
               </tr>';
     return $txt;
  }

/* ====================== 資料夾HTML ====================== */
  function folder_txt($row, $weblang)
  {
  	if (!empty($row['parent_id'])) {
       $txt='<tr data-tt-id="'.$row['Tb_index'].'" data-tt-parent-id="'.$row['parent_id'].'" style="background-color: #FAFAFA">';
       $txt.='<td class="parent_td">
                     <span style="color:#999"><strong><i class="fa fa-folder-open "></i> '.$row['MT_Name'].' </strong></span>
                  </td>';
  	}
  	else{
       $txt='<tr data-tt-id="'.$row['Tb_index'].'" style="background-color: #fff">';
       $txt.='<td class="parent_td">
                     <span style="color:#179c81"><strong><i class="fa fa-folder-open "></i> '.$row['MT_Name'].' </strong></span>
                  </td>';
  	}
           $txt.= '<td><input type="number" class="sort_in" Tb_index="'.$row['Tb_index'].'" value="'.$row['OrderBy'].'"> </td>';
           $txt.= '<td></td>';


           $txt.= '<td align="right">';
          
    if ($_SESSION['admin_per']=='admin') {

         $txt.='<a href="manager.php?parent_id='.$row['Tb_index'].'" class="btn btn-white btn-sm"><i class="fa fa-folder-open-o"></i> 建立子分類</a>';
       }   
          $txt.='    <a href="manager_data.php?parent_id='.$row['Tb_index'].'" class="btn btn-white btn-sm"><i class="fa fa-file-text-o "></i> 建立單元</a>

                     <a href="manager.php?Tb_index='.$row['Tb_index'].'&parent_id='.$row['parent_id'].'&weblang='.$weblang.'" class="btn btn-white btn-sm">
                        <i class="fa fa-pencil-square-o "></i> 修改</a> 

                     <a href="admin.php?Tb_index='.$row['Tb_index'].'" class="btn btn-white btn-sm" onclick="if (!confirm(\'確定要刪除 ['.$row['MT_Name'].'] ?\')) {return false;}">
                        <i class="fa fa-trash-o "></i> 刪除</a>
                  </td>
               </tr>';
     return $txt;
  }
  ?>
    
  </tbody>
</table>

 


				</div>
			</div>
		</div>
	</div>


</div>
</div><!-- /#page-content -->
<?php  include("../../core/page/footer01.php");//載入頁面footer01.php?>



<script src="../../js/jquery.treetable.js"></script>
    <script>
      $("#product-table").treetable({ expandable: true });
	  $('#product-table').treetable('expandAll');

      // Highlight selected row
      $("#product-table tbody").on("mousedown", "tr", function() {
        $(".selected").not(this).removeClass("selected");
        $(this).toggleClass("selected");
      });

      // Drag & Drop Example Code
      /*$("#product-table .file, #product-table .folder").draggable({
        helper: "clone",
        opacity: .75,
        refreshPositions: true, // Performance?
        revert: "invalid",
        revertDuration: 300,
        scroll: true
      });*/

      $("#product-table .folder").each(function() {
        $(this).parents("#product-table tr").droppable({
          accept: ".file, .folder",
          drop: function(e, ui) {
            var droppedEl = ui.draggable.parents("tr");
            $("#product-table").treetable("move", droppedEl.data("ttId"), $(this).data("ttId"));
          },
          hoverClass: "accept",
          over: function(e, ui) {
            var droppedEl = ui.draggable.parents("tr");
            if(this != droppedEl[0] && !$(this).is(".expanded")) {
              $("#product-table").treetable("expandNode", $(this).data("ttId"));
            }
          }
        });
      });

      $("form#reveal").submit(function() {
        var nodeId = $("#revealNodeId").val()

        try {
          $("#product-table").treetable("reveal", nodeId);
        }
        catch(error) {
          alert(error.message);
        }

        return false;
      });


      // -- 更新排序 --
      $("#sort_btn").click(function(event) {
        
        var arr_OrderBy=new Array();
        var arr_Tb_index=new Array();

          $(".sort_in").each(function(index, el) {
             
             arr_OrderBy.push($(this).val());
             arr_Tb_index.push($(this).attr('Tb_index'));
          });

          var data={ 
                        OrderBy: arr_OrderBy,
                       Tb_index: arr_Tb_index 
                      };
             ajax_in('admin.php', data, 'no', 'no');

          alert('更新排序');
          location.replace('admin.php');
      });
    </script> 
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>



