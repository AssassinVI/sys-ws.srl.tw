<?php
/*短網址管理新增修改(PHP)*/
include_once '../../core/page/header01.php'; //載入頁面heaer01
?>

<?php
include_once '../../core/page/header02.php'; //載入頁面heaer02
?>
<?php
if ($_GET)
{
	$pdo = new PDO_fun('short');
	
	$MT_id = $_GET['MT_id'];
	$url_group = "inSS2018070409244214";
	$type = $_GET['type'];
	$table_name = 'appShort';
	$short_url_first = "https://srl.tw/sh";
	
	switch ($type)
	{
		case 'add':
			$header = "新增短網址";
			$Tb_index = $_GET['Tb_index'];
			$query = "SELECT * FROM {$table_name} WHERE ifnull(url_id, '') = '' AND Tb_index =:Tb_index";
			$row = $pdo->select($query, ['Tb_index'=>$Tb_index], 'one');
			break;
		case 'mod':
			$header = "修改短網址";
			$Tb_index = $_GET['Tb_index'];
			$query = "SELECT * FROM {$table_name} WHERE ifnull(url_id, '') != '' AND Tb_index =:Tb_index";
			$row = $pdo->select($query, ['Tb_index'=>$Tb_index], 'one');
			break;
		default:
			$Tb_index = 'short'.date('YmdHis').str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);
			
			$query = "SELECT ifnull(max(OrderBy), 0) + 1 AS OrderBy
			         FROM {$table_name} WHERE ifnull(url_id, '') = ''";
			$row_OrderBy = $pdo->select($query, 'no', 'one');
			
			$OrderBy = $row_OrderBy['OrderBy'];
			$param = array(
				'Tb_index'=>$Tb_index,
				'mt_id'=>$MT_id,
				'url_group'=>$url_group,
				'url_id'=>'',
				'OnlineOrNot'=>'0',
				'OrderBy'=>$OrderBy,
				'CreateTime'=>date('Y-m-d H:i:s'),
				'CreateAdm_Pk'=>$_SESSION['admin_index']
			);
			$pdo->insert($table_name, $param);
			location_up('manager.php?MT_id='.$MT_id.'&type=add&Tb_index='.$Tb_index, '');
			break;
	}
}
?>
<div class="wrapper wrapper-content animated fadeInRight">
	<form id="put_form" method="POST" enctype='multipart/form-data' class="form-horizontal">
	    <div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<header><?php echo $header; ?></header>
					</div><!-- /.panel-heading -->
					<div class="panel-body">
						<div class="form-group">
							<label class="col-md-2 control-label" for="aUrl"><span class="text-danger">*</span>輸入網址</label>
							<div class="col-md-6">
								<input type="text" class="form-control title_w" id="aUrl" name="aUrl" autocomplete="off" value="<?php echo $row['aUrl']; ?>" />
							</div>
							<div class="col-md-2" id="div_get_shorturl">
								<a name="get_shorturl" class="btn btn-info">產生短網址</a>
							</div>
						</div>
						
						<div class="form-group" id="div_aTitle">
							<label class="col-md-2 control-label" for="aTitle"><span class="text-danger">*</span>標題名稱</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="aTitle" name="aTitle" autocomplete="off" value="<?php echo $row['aTitle']; ?>" />
							</div>
						</div>
						
						<div class="form-group" id="div_url_id">
							<label class="col-md-2 control-label" for="url_id"><span class="text-danger">*</span>短網址</label>
							<div class="col-md-8">
								<div id="div_short_url">
									<label class="control-label"><?=$short_url_first?></label>
									<input type="text" class="form-control" style="width: 200px; display: inline-block;" maxlength="5" id="url_id" name="url_id" autocomplete="off" value="<?php echo $row['url_id']; ?>" />
								</div>
								<label id="lbl_short_url"><?php echo $short_url_first.$row['url_id']; ?></label>
								<a class="link_short_url" href="<?php echo $short_url_first.$row['url_id']; ?>"></a>
								<a class="copyurl_btn" href="javascript:void(0);" title="複製"><img src="images/copy-content.png" width="20"  /></a>
							</div>
						</div>
						<div class="form-group" id="div_aTitle">
							<label class="col-md-2 control-label" for="aTitle">使用狀態</label>
							<div class="col-md-10">
								<label> <input type="radio" name="OnLineOrNot" value="1" > 使用中 </label>｜<label> <input type="radio" name="OnLineOrNot" value="0"> 停用 </label>
								<input type="hidden" name="OnLineOrNot_v" value="<?php echo $row['OnLineOrNot']; ?>">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row" id="div_submit_cancel">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<header>儲存您的資料</header>
					</div><!-- /.panel-heading -->
					<div class="panel-body text-center">
						<button type="button" id="submit_btn" class="btn btn-info btn-raised"></button>
          				<button type="button" id="cancel_btn" class="btn btn-danger btn-flat"></button>
					</div><!-- /.panel-body -->
				</div><!-- /.panel -->
			</div>
		</div>
		<input type="hidden" id="MT_id" name="MT_id" value="<?php echo $MT_id; ?>" />
		<input type="hidden" id="url_group" name="url_group" value="<?php echo $url_group; ?>" />
		<input type="hidden" id="type" name="type" value="<?php echo $type; ?>" />
		<input type="hidden" id="header" name="header" value="<?php echo $header; ?>" />
		<input type="hidden" id="Tb_index" name="Tb_index" value="<?php echo $Tb_index; ?>" />
		<input type="text" id="short_url" name="short_url" value="<?php echo $short_url_first.$row['url_id']; ?>" style="display: none;"/>
		<input type="hidden" id="short_url_first" name="short_url_first" value="<?php echo $short_url_first; ?>" />
	</form>
</div>
<?php
include_once '../../core/page/footer01.php'; //載入頁面footer01
?>
<script type="text/javascript" src="manager.js?3"></script>
<?php
include_once '../../core/page/footer02.php'; //載入頁面footer02
?>