<?php include("../../core/page/header01.php");//載入頁面heaer01?>
<style type="text/css">
	#sel_com{ padding: 5px; margin-right: 5px; }

	#case_tb tr td:nth-child(3){font-size:1.5em;}
	.an_num{font-size: 17px; margin-left: 10px; padding-top: 14px; position: relative; width: 60px;}
	.an_num.yet::before{content: '昨日：'; position: absolute; top: 0; left: 0; font-size: 12px; width: 59px;  color: #1ab394;}
	.an_num.adv::before{content: '月平均：'; position: absolute; top: 0; left: 0; font-size: 12px; width: 59px; color: #b7b7b7;}
	.an_td{display: flex; align-items: center;}
	@media (max-width:500px){
	  .wrapper-content{padding: 0;}
	  tbody tr td::before{ width: 6em;}
	  #case_tb tr{padding:10px; border-bottom:1px solid #e8e8e8;}
	  #case_tb tr td{border:none; padding: 5px; text-align: left; font-size: 15px;}
	  #case_tb tr td:nth-child(3){ font-size: 15px;}
	  #case_tb tr td:nth-child(7){ text-align: left;}
	  .case_tb_div table thead{display: none;}
      .case_tb_div tbody tr{ display: block; padding: 10px 5px;}
      .case_tb_div tbody tr td{display: block; padding-top: 1px;  padding-bottom: 1px;}
      .case_tb_div tbody tr td::before{ content: attr(data-th) " : "; font-weight: bold; width: 5em; display: inline-block; color: #0e4e7b;}
	}
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 
$pdo=pdo_conn();//資料庫初始化

if ($_POST) {
   // -- 更新排序 --
  for ($i=0; $i <count($_POST['OrderBy']) ; $i++) { 
    $data=["OrderBy"=>$_POST['OrderBy'][$i]];
    $where=["Tb_index"=>$_POST['Tb_index'][$i]];
    pdo_update('build_case', $data, $where);
  }
}

if ($_GET) {

   if (!empty($_GET['Tb_index'])) {//刪除
    
    $param=['OnLineOrNot'=>'0'];
    $where=['Tb_index'=>$_GET['Tb_index']];

   	 pdo_update('build_case', $param, $where);
   }

   $com_id=empty($_GET['com_id']) ? '':$_GET['com_id'];

   $sql=$pdo->prepare("SELECT Tb_index, aTitle, OrderBy, OnLineOrNot, version FROM build_case WHERE com_id LIKE :com_id AND OnLineOrNot!=-1 ORDER BY OnLineOrNot DESC, OrderBy DESC, Tb_index DESC");
   $sql->execute( ['com_id'=>'%'.$com_id.'%'] );

   
}

?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary"><?php echo $page_name['MT_Name']?> 列表</h2>
		<p>本頁面條列出所有的文章清單，如需檢看或進行管理，請由每篇文章右側 管理區進行，感恩</p>
	   <div class="new_div">

       
	  </div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
			 <div class="ibox-title">
			 	<h5><?php echo $page_name['MT_Name']?>列表 </h5>
			 	<div class="ibox-tools">

				 <?php 
				 //-- DEMO 不顯示 --
				 if($_COOKIE['admin_per']!='group2020040610522078'){?>
			 		        <select id="sel_com">
			 		        	<option value="all">全部</option>
			 		        	<?php
                                   $sql_com=$pdo->prepare("SELECT * FROM company ORDER BY Tb_index ASC");
                                   $sql_com->execute();
                                   while ($row_com=$sql_com->fetch(PDO::FETCH_ASSOC)) {
                                   	if ($_SESSION['admin_per']=='admin' || in_array($row_com['Tb_index'], $_SESSION['group_com'])) {
                                   		echo '<option value="'.$row_com['Tb_index'].'">'.$row_com['com_name'].'</option>';
                                   	}
                                   }
			 		        	?>
			 		        </select>
				 <?php }?>
			 		        
			 	</div>
			 </div>
			<div class="ibox-content">
				<div class="table-responsive case_tb_div">
					<table class="table no-margin table-hover">
						<thead>
							<tr>
								<th class="none_420">#</th>
								<th class="none_420">ID</th>
								<th>專案名稱</th>
								<th>分析</th>
								<th class="none_420">啟用/停用</th>
								<th class="text-right">管理</th>

							</tr>
						</thead>
						<tbody id="case_tb">

						</tbody>
					</table>

    
				</div>
			</div>
		</div>
	</div>
</div>




</div><!-- /#page-content -->
<?php  include("../../core/page/footer01.php");//載入頁面footer01.php?>
<!-- Peity -->
<script src="../../js/plugins/peity/jquery.peity.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {

		//-- 抓全部 --
		get_an_list();

		 $(".iframe_box").fancybox({
		 	'padding'               :'0',
            'type'                  : 'iframe'
		 });
      
      //-------------- 排序 ---------------
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
         location.replace('admin.php?MT_id=<?php echo $_GET['MT_id'];?>');
		});

     
     //----------------- 選公司 ------------------
		$('#sel_com').change(function(event) {
			get_an_list($(this).val());
		});
	});


	function get_an_list (com_id='all') {
		$.ajax({
			url: 'admin_ajax.php',
			type: 'POST',
			dataType: 'json',
			data: {
				com_id: com_id,
				type:'company'
			},
			success:function (data) {

				console.log(data);
				$('#case_tb').html('');
				var x=1;
				var txt='';
				$.each(data, function() {

				var OnLineOrNot=this['OnLineOrNot']=='1' ? '<span class="label">啟用</span>' : '<span class="label label-danger">停用</span>';

					//-- 網址get --
					let url_arr=url_get(); 

					//-- 分析 --
					let an_txt=this['an'].join(',');
					let an_num=this['an'].length;
					let an_yet_num=an_num==0 ? 0:this['an'][an_num-1];
					let an_adv_num=an_num==0 ? 0: Math.round(this['an'].reduce((a,b)=>parseInt(a)+parseInt(b))/an_num);
					
					txt+=`<tr>
							<td class="none_420" >${x}</td>
							<td class="none_420">${this['Tb_index']}</td>
							<td data-th="名稱" >${this['aTitle']}</td>
							<td data-th="分析" class=" an_td"><span class="line">${an_txt}</span> <span class="an_num yet">${an_yet_num}人</span> <span class="an_num adv">${an_adv_num}人</span></td>
							<td class="none_420">${OnLineOrNot}</td>
							<td data-th="管理" class="text-right">
							<a class="btn btn-success btn-sm" href="analytics_new2.php?MT_id=${url_arr['MT_id']}&Tb_index=${this['Tb_index']}"><i class="fa fa-line-chart"></i> 進入分析</a>
							</td>
							</tr>`;
					x++;
				});

			$('#case_tb').append(txt);

				$(".line").peity("line",{
					fill: '#1ab394',
					stroke:'#169c81',
					width: 100,
					height: 32
				});
			}
		});
	}
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
