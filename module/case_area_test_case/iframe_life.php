<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<style type="text/css">
	.md-skin .navbar-static-side, .border-bottom, body.fixed-sidebar .navbar-static-side, body.canvas-menu .navbar-static-side{display: none;}
	#page-wrapper{ margin:0px;  }

	.ibox-tools a{ color: #626262; }
  .color_bar{ padding: 15px 25px; display: inline-block; }

  #txt_fadein_type{ display: none; }
  #img_fadein_type{ display: none; }

  .one_traffic, .one_fun{ float: left; position: relative; width: 33%;}
  .one_traffic label, .one_fun label{ display: block; }
  .top_traffic, .top_fun{ margin-top: 10px; }
	
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 

$test_case_db='srltw_test_case'; //-- 測試用修改資料庫 --

if($_POST){

  
  //----------------- 新增 ----------------------------------------------

  if(empty($_GET['fun_id'])){

    $Tb_index='li'.date('YmdHis').rand(0,99);

    $OnLineOrNot=empty($_POST['OnLineOrNot'])? 0 : 1;

    //---- 更新關聯資料表 -----
    pdo_update('Related_tb', ['fun_id'=>$Tb_index, 'OnLineOrNot'=>$OnLineOrNot], ['Tb_index'=>$_GET['rel_id']], $test_case_db);
    
    $range=implode('|', $_POST['range']);
    $keyword=implode('|', $_POST['keyword']);
    $life_zoom=implode('|', $_POST['life_zoom']);
    $traffic_loc=empty($_POST['traffic_loc']) ? '' : implode('|', $_POST['traffic_loc']);
    $traffic_name=empty($_POST['traffic_name']) ? '' : implode('|', $_POST['traffic_name']);
    $fun_loc=empty($_POST['fun_loc']) ? '' : implode('|', $_POST['fun_loc']);
    $fun_name=empty($_POST['fun_name']) ? '' : implode('|', $_POST['fun_name']);

    $param=[
       'Tb_index'=>$Tb_index,
       'case_id'=>$_GET['Tb_index'],
       'location'=>$_POST['location'],
       'life_range'=>$range,
       'life_keyword'=>$keyword,
       'life_zoom'=>$life_zoom,
       'traffic_loc'=>$traffic_loc,
       'traffic_name'=>$traffic_name,
       'traffic_zoom'=>$_POST['traffic_zoom'],
       'fun_loc'=>$fun_loc,
       'fun_name'=>$fun_name,
       'color_type'=>$_POST['color_type'],
       'type'=>$_POST['type'],
       'OnLineOrNot'=>$OnLineOrNot
    ];
    pdo_insert('life_tb', $param, $test_case_db);
    location_up('iframe_life.php?Tb_index='.$_GET['Tb_index'].'&fun_id='.$Tb_index, '功能已成功新增');
  }

  //----------------- 修改 ----------------------------------------------

  else{

    $Tb_index=$_GET['fun_id'];

    $range=implode('|', $_POST['range']);
    $keyword=implode('|', $_POST['keyword']);
    $life_zoom=implode('|', $_POST['life_zoom']);
    $traffic_loc=empty($_POST['traffic_loc']) ? '' : implode('|', $_POST['traffic_loc']);
    $traffic_name=empty($_POST['traffic_name']) ? '' : implode('|', $_POST['traffic_name']);
    $fun_loc=empty($_POST['fun_loc']) ? '' : implode('|', $_POST['fun_loc']);
    $fun_name=empty($_POST['fun_name']) ? '' : implode('|', $_POST['fun_name']);
    

      $OnLineOrNot=empty($_POST['OnLineOrNot'])? 0 : 1;

      $param=[
       'location'=>$_POST['location'],
       'life_range'=>$range,
       'life_keyword'=>$keyword,
       'life_zoom'=>$life_zoom,
       'traffic_loc'=>$traffic_loc,
       'traffic_name'=>$traffic_name,
       'traffic_zoom'=>$_POST['traffic_zoom'],
       'fun_loc'=>$fun_loc,
       'fun_name'=>$fun_name,
       'color_type'=>$_POST['color_type'],
       'type'=>$_POST['type'],
       'OnLineOrNot'=>$OnLineOrNot
    ];
    pdo_update('life_tb', $param, ['Tb_index'=>$Tb_index], $test_case_db);

    //---- 更新關聯資料表 -----
    pdo_update('Related_tb', ['OnLineOrNot'=>$OnLineOrNot], ['fun_id'=>$Tb_index], $test_case_db);
    location_up('iframe_life.php?Tb_index='.$_GET['Tb_index'].'&fun_id='.$Tb_index, '功能已更新');
  }
  
}//-- POST END --


  $row_case=pdo_select("SELECT aTitle FROM build_case WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['Tb_index']], $test_case_db);

  $Tb_id=substr($_GET['Tb_index'], 4);

  $row=pdo_select("SELECT * FROM life_tb WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['fun_id']], $test_case_db);

  $range=explode('|', $row['life_range']);
  $keyword=explode('|', $row['life_keyword']);
  $life_zoom=explode('|', $row['life_zoom']);

?>

<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary"><?php echo $row_case['aTitle'];?>-食醫住行 <span class="text-danger">(此為測試修改區)</span></h2>
      
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
			 <div class="ibox-title">
			 	
			 	<div class="ibox-tools">
			 		<button id="save_btn" type="button" class="btn btn-primary">儲存</button>      
			 	</div>
			 </div>
			<div class="ibox-content">
				<form id="fun_form" action="#" method="POST" class="form-horizontal" enctype='multipart/form-data'>
            
           <div class="form-group">
             <label class="col-sm-2 control-label" ></label>
             <div class="col-sm-10">
            <?php 
             if (empty($row['Tb_index'])) {
            ?>
              <h3>未產生功能，請按下儲存</h3>
            <?php
             }else{
            ?>
              <h3>已產生功能</h3>
            <?php
             }
            ?>
            </div>
           </div> 

            <div class="form-group">
              <label class="col-sm-2 control-label" >食醫住行按鈕類型</label>
              <div class="col-sm-10">
                <select class="form-control" name="type">
                  <option value="0" <?php echo $check=$row['type']=='0' ?  'selected':'';?> >Menu彈出</option>
                  <option value="1" <?php echo $check=$row['type']=='1' ?  'selected':'';?>>滑到指定位置</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label" >按鈕配色</label>
              <div class="col-sm-10">
                <select class="form-control" name="color_type">
                  <option value="0" <?php echo $check=$row['color_type']=='0' ?  'selected':'';?> >預設</option>
                  <option value="1" <?php echo $check=$row['color_type']=='1' ?  'selected':'';?>>高檔1</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label" >位置座標:</label>
              <div class="col-sm-10">
                <input class="form-control" type="text" name="location" placeholder="請輸入地圖座標" value="<?php echo  $row['location'];?>">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label" ></label>
              <div class="col-sm-10">
                <p class="text-danger">食醫住行範圍預設: 1000，地圖縮放比預設: 14</p>
              </div>
            </div>
            <?php
              $life_name=['食','醫','住','育','樂','公園','公車站','咖啡店','銀行','商店','加油站','藥局'];
              $life_num=count($life_name);

              for ($i=0; $i <$life_num ; $i++) { 
                
                echo '
                <div class="form-group">
                 <label class="col-sm-2 control-label" >"'.$life_name[$i].'"範圍:</label>
                 <div class="col-sm-2">
                   <input class="form-control" type="text" name="range[]" placeholder="請輸入地圖範圍" value="'.$range[$i].'">
                 </div>

                 <label class="col-sm-1 control-label" >"'.$life_name[$i].'"關鍵字:</label>
                 <div class="col-sm-3">
                   <input class="form-control" type="text" name="keyword[]" placeholder="請輸入關鍵字" value="'.$keyword[$i].'">
                 </div>

                 <label class="col-sm-1 control-label" >"'.$life_name[$i].'"地圖縮放比:</label>
                 <div class="col-sm-3">
                   <input class="form-control" type="text" name="life_zoom[]" placeholder="請輸入比率" value="'.$life_zoom[$i].'">
                   <span class="text-danger">縮放比數字越大地圖越近，反之越小越遠</span>
                 </div>
                </div>';
              }
            ?>

             




            <hr>

            <h2>"行"座標，名稱自訂</h2>
            <div class="form-group">
              <div class="col-sm-10">
                <button type="button" id="traffic_btn" class="btn btn-info"><i class="fa fa-plus"></i> 新增座標</button>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-1 control-label" >"行"地圖縮放比:</label>
              <div class="col-sm-10">
                <input class="form-control" type="text" name="traffic_zoom" placeholder="請輸入地圖座標" value="<?php echo  $row['traffic_zoom'];?>">
                <span class="text-danger">縮放比數字越小地圖越近，反之越大越遠 ，地圖縮放比預設: 14</span>
              </div>
            </div>

            <div class="form-group">
               <div class="col-sm-12">
                <ul class="sortable-list connectList agile-list ui-sortable traffic_div" >

                 <?php 
                  if (!empty($row['traffic_loc'])) {
                    
                    $traffic_loc=explode('|', $row['traffic_loc']);
                    $traffic_name=explode('|', $row['traffic_name']);
                    $traffic_num=count($traffic_loc);

                    for ($i=0; $i <$traffic_num ; $i++) { 
                       
                       echo '<li class="one_traffic">
                              <span class="mark_num">'.($i+1).'</span>
                              <button type="button"  class="btn btn-danger one_del_div">x</button>
                              <label class="top_traffic">座標位置: <input type="text" name="traffic_loc[]" value="'.$traffic_loc[$i].'" class="form-control"></label>
                              <label>座標名稱: <input type="text" name="traffic_name[]" value="'.$traffic_name[$i].'" class="form-control"></label>
                            </li>';
                    }
                  }
                 ?>


                                                
                </ul>
              </div>
            </div>

            <hr>

             <h2>"樂"額外座標，名稱自訂</h2>
            <div class="form-group">
              <div class="col-sm-10">
                <button type="button" id="fun_btn" class="btn btn-info"><i class="fa fa-plus"></i> 新增座標</button>
              </div>
            </div>

            <div class="form-group">
               <div class="col-sm-12">
                <ul class="sortable-list connectList agile-list ui-sortable fun_div" >

                 <?php 
                  if (!empty($row['fun_loc'])) {
                    
                    $fun_loc=explode('|', $row['fun_loc']);
                    $fun_name=explode('|', $row['fun_name']);
                    $fun_num=count($fun_loc);

                    for ($i=0; $i <$fun_num ; $i++) { 
                       
                       echo '<li class="one_fun">
                              <span class="mark_num">'.($i+1).'</span>
                              <button type="button"  class="btn btn-danger one_del_div">x</button>
                              <label class="top_fun">座標位置: <input type="text" name="fun_loc[]" value="'.$fun_loc[$i].'" class="form-control"></label>
                              <label>座標名稱: <input type="text" name="fun_name[]" value="'.$fun_name[$i].'" class="form-control"></label>
                            </li>';
                    }
                  }
                 ?>
                                                
                </ul>
              </div>
            </div>

            <hr>
            

            <div class="form-group">
              <label class="col-sm-1 control-label" for="OnLineOrNot">是否上線</label>
              <div class="col-sm-11">
                <input style="width: 20px; height: 20px;" id="OnLineOrNot" name="OnLineOrNot" type="checkbox" value="1" <?php echo $check=!isset($row['OnLineOrNot']) || $row['OnLineOrNot']==1 ? 'checked' : ''; ?>  />
              </div>
            </div>

            
         
				</form>
			</div>
		</div>
	</div>
</div>



</div><!-- /#page-content -->
<?php  include("../../core/page/footer01.php");//載入頁面footer01.php?>
<script type="text/javascript">
	$(document).ready(function() {


      $('#save_btn').click(function(event) {
         $('#fun_form').submit();
      });


      //-- 行 新增座標 --
      var traffic_num=$('[name="traffic_loc[]"]').length;
      $('#traffic_btn').click(function(event) {

        var txt='<li class="one_traffic">'+
                    '<span class="mark_num">'+(traffic_num+1)+'</span>'+
                    '<button type="button"  class="btn btn-danger one_del_div">x</button>'+
                    '<label class="top_traffic">座標位置: <input type="text" name="traffic_loc[]"  class="form-control"></label>'+
                    '<label>座標名稱: <input type="text" name="traffic_name[]"  class="form-control"></label>'+
                '</li>';

         $('.traffic_div').append(txt);
        traffic_num++;
      });
      
      $('.traffic_div').on('click', '.one_del_div', function(event) {
        event.preventDefault();
         if (confirm('是否要刪除此座標??')) {
          $(this).parent().remove();
        }
      });

      // 拖曳多圖檔
       $(".traffic_div").sortable({
         connectWith: ".traffic_div",
         update: function( event, ui ) {

              var OtherFile_arr = $( ".traffic_div" ).sortable( "toArray" );
         }
      }).disableSelection();

       

      //-- 樂 新增座標 --
      var fun_num=$('[name="fun_loc[]"]').length;
      $('#fun_btn').click(function(event) {

        var txt='<li class="one_fun">'+
                    '<span class="mark_num">'+(fun_num+1)+'</span>'+
                    '<button type="button"  class="btn btn-danger one_del_div">x</button>'+
                    '<label class="top_fun">座標位置: <input type="text" name="fun_loc[]"  class="form-control"></label>'+
                    '<label>座標名稱: <input type="text" name="fun_name[]"  class="form-control"></label>'+
                '</li>';

         $('.fun_div').append(txt);
        fun_num++;
      });
      
      $('.fun_div').on('click', '.one_del_div', function(event) {
        event.preventDefault();
         if (confirm('是否要刪除此座標??')) {
          $(this).parent().remove();
        }
      });

      // 拖曳多圖檔
       $(".fun_div").sortable({
         connectWith: ".fun_div",
         update: function( event, ui ) {

              var OtherFile_arr = $( ".fun_div" ).sortable( "toArray" );
         }
      }).disableSelection();

      
	 });

</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
