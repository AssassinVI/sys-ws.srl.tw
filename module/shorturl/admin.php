<?php
/*短網址管理列表(PHP)*/
include_once '../../core/page/header01.php'; //載入頁面heaer01
?>
<style>
	.page_div.shorturl{ float: right; }
	/* #table_shorturl_filter{ display: none; } */
    #table_shorturl_paginate{ display: none; }
</style>
<?php
include_once '../../core/page/header02.php'; //載入頁面heaer02
?>
<?php
if ($_GET)
{
	$MT_id = $_GET['MT_id'];
}
?>
<div class="wrapper wrapper-content animated fadeInRight">
	<form id="put_form" method="POST" enctype='multipart/form-data' class="form-horizontal">
	    <div class="col-lg-12">
		    <h2 class="text-primary"><?php echo $page_name['MT_Name']?> 列表</h2>
		    <p>本頁面條列出所有的文章清單，如需檢看或進行管理，請由每篇文章右側 管理區進行，感恩</p>
	        <div class="new_div">
				<button style="display: none;" id="sort_btn" type="button" class="btn btn-success">
                    <i class="fa fa-sort-amount-desc"></i> 更新排序
                </button>
				<button id="add_btn" type="button" class="btn btn-success">
                    <i class="fa fa-plus"></i> 新增短網址
                </button>
		    </div>
	    </div>
		
		<div class="row">
		    <div class="col-lg-12">
			    <div class="panel panel-default">
			        <div class="panel-body">
				        <div class="table-responsive">
							<div class="page_div shorturl">
                                <a href="javascript:void(0);" class="prev_btn">上一頁</a>
                                <a href="javascript:void(0);" class="next_btn">下一頁</a>
                                到
                                <select class="page_sel"></select>
                            </div>
					        <table id="table_shorturl" class="table no-margin">
						        <thead>
							        <tr>
									    <th style="width: 10px;">#</th>
								        <th style="width: 100px;">短網址</th>
										<th style="width: 20px;"></th>
									    <th >對應網址</th>
										<th style="width: 150px;">標題</th>
									    <th style="width: 100px;">目前狀態</th>
										<th style="width: 60px;">排序</th>
										<th style="width: 60px;">狀態</th>
								        <th class="text-right">管理</th>
								    </tr>
							    </thead>
						    </table>
							<div class="page_div shorturl">
                                <a href="javascript:void(0);" class="prev_btn">上一頁</a>
                                <a href="javascript:void(0);" class="next_btn">下一頁</a>
                                到
                                <select class="page_sel"></select>
                            </div>
					    </div>
				    </div>
			    </div>
		    </div>
	    </div>
		<input type="hidden" id="MT_id" name="MT_id" value="<?php echo $MT_id; ?>" />
		<input type="text" id="short_url" name="short_url" style="display: none;"/>
	</form>
	
	
	
</div>
<?php
include_once '../../core/page/footer01.php'; //載入頁面footer01
?>
<script type="text/javascript" src="admin.js?3"></script>
<?php
include_once '../../core/page/footer02.php'; //載入頁面footer02
?>