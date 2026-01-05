<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<link rel="stylesheet" type="text/css" href="../../css/codemirror.css">
<link rel="stylesheet" type="text/css" href="../../js/plugins/codemirror/theme/monokai.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/styles/monokai.min.css">
<style type="text/css">
	.md-skin .navbar-static-side, .border-bottom, body.fixed-sidebar .navbar-static-side, body.canvas-menu .navbar-static-side{display: none;}
	#page-wrapper{ margin:0px;  }

	.ibox-tools a{ color: #626262; }
  .color_bar{ padding: 15px 25px; display: inline-block; }
	
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 

if($_POST){

    // ======================== 刪除 ===========================
    if (!empty($_POST['type']) && $_POST['type']=='delete') { 
    
        //----------------------- 多檔刪除 -------------------------------
        $sel_where=['Tb_index'=>$_POST['fun_id']];
        $otr_file=pdo_select('SELECT show_img FROM slideshow_tb WHERE Tb_index=:Tb_index', $sel_where);
        $otr_file=explode(',', $otr_file['show_img']);
        for ($i=0; $i <count($otr_file)-1 ; $i++) { //比對 
           if ($otr_file[$i]!=$_POST['show_img']) {
            $new_file.=$otr_file[$i].',';
           }else{
             unlink('../../../product_html/'.$_POST['case_id'].'/img/'.$_POST['show_img']);
           }
        }
        $param=['show_img'=>$new_file];
            $where=['Tb_index'=>$_POST['fun_id']];
            pdo_update('slideshow_tb', $param, $where);
      
       exit();
    }
  
  //----------------- 新增 ----------------------------------------------

  if(empty($_GET['fun_id'])){

    $Tb_index='call'.date('YmdHis').rand(0,99);

    $OnLineOrNot=empty($_POST['OnLineOrNot'])? 0:1;
    $is_custom=empty($_POST['is_custom'])? 0:1;

    //---- 更新關聯資料表 -----
    pdo_update('Related_tb', ['fun_id'=>$Tb_index, 'OnLineOrNot'=>$OnLineOrNot], ['Tb_index'=>$_GET['rel_id']]);
    
    $param=[
       'Tb_index'=>$Tb_index,
       'case_id'=>$_GET['Tb_index'],
      //  'btn_name'=>$_POST['btn_name'],
       're_name'=>implode(',', $_POST['re_name']),
       're_mail'=>implode(',', $_POST['re_mail']),
       'send_type'=>$_POST['send_type'],
      //  'line_Client_ID'=>$_POST['line_Client_ID'],
       'is_custom'=>$is_custom,
       'cus_html'=>$_POST['cus_html'],
       'privacy_case_name'=>$_POST['privacy_case_name'],
       'thanks_head_code'=>$_POST['thanks_head_code'],
       'thanks_body_code'=>$_POST['thanks_body_code'],
       'thanks_back_url'=>$_POST['thanks_back_url'],
       'OnLineOrNot'=>$OnLineOrNot
    ];

    // if(!empty($_POST['line_Client_Secret'])){
    //   $param['line_Client_Secret']=$_POST['line_Client_Secret'];
    // }

    pdo_insert('call_us_tb', $param);
    location_up('iframe_call.php?Tb_index='.$_GET['Tb_index'].'&fun_id='.$Tb_index, '功能已成功新增');
  }

  //----------------- 修改 ----------------------------------------------

  else{

    $Tb_index=$_GET['fun_id'];
    

      $OnLineOrNot=empty($_POST['OnLineOrNot'])? 0:1;
      $is_custom=empty($_POST['is_custom'])? 0:1;
      $param=[
       //  'btn_name'=>$_POST['btn_name'],
       're_name'=>implode(',', $_POST['re_name']),
       're_mail'=>implode(',', $_POST['re_mail']),
       'send_type'=>$_POST['send_type'],
      //  'line_Client_ID'=>$_POST['line_Client_ID'],
       'is_custom'=>$is_custom,
       'cus_html'=>$_POST['cus_html'],
       'privacy_case_name'=>$_POST['privacy_case_name'],
       'thanks_head_code'=>$_POST['thanks_head_code'],
       'thanks_body_code'=>$_POST['thanks_body_code'],
       'thanks_back_url'=>$_POST['thanks_back_url'],
       'OnLineOrNot'=>$OnLineOrNot
    ];

    // if(!empty($_POST['line_Client_Secret'])){
    //   $param['line_Client_Secret']=$_POST['line_Client_Secret'];
    // }

    pdo_update('call_us_tb', $param, ['Tb_index'=>$Tb_index]);

    //---- 更新關聯資料表 -----
    pdo_update('Related_tb', ['OnLineOrNot'=>$OnLineOrNot], ['fun_id'=>$Tb_index]);
    location_up('iframe_call.php?Tb_index='.$_GET['Tb_index'].'&fun_id='.$Tb_index, '功能已更新');
  }
  
}//-- POST END --


  $row_case=pdo_select("SELECT aTitle FROM build_case WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['Tb_index']]);

  $Tb_id=substr($_GET['Tb_index'], 4);

  $row=pdo_select("SELECT * FROM call_us_tb WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['fun_id']]);

  if(empty($row['re_name'])){
    $re_name=[
      '呂',
      'jacky',
    ];
    $re_mail=[
      'd974252037@gmail.com',
      'tangi6520@yahoo.com.tw',
    ];
  }
  else{
    $re_name=explode(',', $row['re_name']);
    $re_mail=explode(',', $row['re_mail']);
  }
  
  
  if(!empty($row['line_notify_token'])){
    $btn_display='style="display:block;"';
    $box_display='style="display:none;"';
  }
  else{
    $btn_display='style="display:none;"';
    $box_display='style="display:block;"';
  }
?>

<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary"><?php echo $row_case['aTitle'];?>-聯絡我們</h2>
      
	</div>
	<div class="row" >
    <form id="fun_form" action="#" method="POST"  enctype='multipart/form-data'>
            <div class="col-lg-12">
              <div class="ibox float-e-margins">
              <div class="ibox-title">
                
                <div class="ibox-tools" >
                  <button id="save_btn" type="button" class="btn btn-primary">儲存</button>      
                </div>
              </div>
              <div class="ibox-content">
                <div class="form-horizontal" >

                  <!-- <div class="form-group">
                      <label class="col-sm-2 control-label">按鈕名稱</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" name="btn_name" value="<?php echo $row['btn_name'];?>">
                      </div>
                      
                    </div> -->
                  
                  <?php 
                  for ($i=0; $i <8 ; $i++) { 
                  ?>

                    <div class="form-group">
                      <label class="col-sm-2 control-label">收件人</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" name="re_name[]" value="<?php echo $re_name[$i];?>">
                      </div>

                      <label class="col-sm-2 control-label">收件人E-mail</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" name="re_mail[]" value="<?php echo $re_mail[$i];?>">
                      </div>
                    </div>

                  <?php
                  }
                  ?>
                  
                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="send_type">發信類型</label>
                      <div class="col-sm-10">
                        <!-- <label> <input type="radio" name="send_type" value="0"> 系統查看 </label>｜ -->
                        <label> <input type="radio" name="send_type" value="1" checked> 直接顯示資料 </label>
                        <input type="hidden" name="ch_send_type" value="<?php echo $row['send_type'];?>">
                      </div>
                    </div>

                    

                    
                    <!-- <div class="form-group" <?php echo $btn_display;?>>
                      <label class="col-sm-2 control-label" for="send_type">LINE notify 發送測試</label>
                      <div class="col-sm-4">
                        <textarea name="send_test" id="send_test" class="form-control" rows="5"></textarea>
                      </div>
                      <div class="col-sm-2"><a class="btn btn-success send_test_btn" href="javascript:;">發送測試</a></div>
                    </div> -->

                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="OnLineOrNot">是否上線</label>
                      <div class="col-sm-10">
                        <input style="width: 20px; height: 20px;" id="OnLineOrNot" name="OnLineOrNot" type="checkbox" value="1" <?php echo $check=!isset($row['OnLineOrNot']) || $row['OnLineOrNot']==1 ? 'checked' : ''; ?>  />
                      </div>
                    </div>
                </div>

                <input type="hidden" id="Tb_index" value="<?php echo $_GET['Tb_index'];?>">
                <input type="hidden" id="fun_id" value="<?php echo $_GET['fun_id'];?>">
              </div>
            </div>
          </div>
          <div class="col-lg-12">
                <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>自定義表單</h5>
                            <div class="ibox-tools">
                               <label ><input type="checkbox" name="is_custom" id="is_custom" value="1" <?php echo $check=$row['is_custom']==1 ? 'checked' : ''; ?>> 是否自定義表單</label>
                            </div>
                        </div>
                        <div class="ibox-content">
                          <div class="form-horizontal" >
                              <!-- <p>
                                HTML規則：<br>
                                必填項目input加上"required"的class，屬性加上err_name="欄位名稱"如：
                                <pre><code class="required_code language-html hljs"></code></pre><br>
                                <span class="text-danger">(必要項目)：</span><br>
                                個資告知事項聲明核選input 如： <pre><code class="user_code language-html hljs"></code></pre>
                                送出表單按鈕 如：<pre><code class="submit_code language-html hljs"></code></pre>
                                姓名欄位input加上id="ca_name"<br>
                                電話欄位input加上id="ca_phone"<br>
                                信箱欄位input加上id="ca_email"<br>
                                其他欄位input加上id="可自訂"，屬性加上input_name="欄位名稱"如：<br>
                                <pre><code class="input_code language-html hljs"></code></pre><br>
                              </p> -->
                              <div class="form-group">
                                <textarea id="cus_html" name="cus_html" class="form-control"  rows="30"><?php echo $row['cus_html'];?></textarea>
                              </div>
                                 <div class="form-group">
                                  <label class="col-sm-2 control-label">隱私權申明公司名稱</label>
                                  <div class="col-sm-10">
                                    <input type="text" class="form-control" id="privacy_case_name" name="privacy_case_name" value="<?php echo $row['privacy_case_name'];?>">
                                 </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-sm-2 control-label">感謝頁head代碼</label>
                                  <div class="col-sm-10">
                                    <textarea name="thanks_head_code" id="thanks_head_code" class="form-control" rows="5"><?php echo $row['thanks_head_code'];?></textarea>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-sm-2 control-label">感謝頁body代碼</label>
                                  <div class="col-sm-10">
                                    <textarea name="thanks_body_code" id="thanks_body_code" class="form-control" rows="5"><?php echo $row['thanks_body_code'];?></textarea>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-sm-2 control-label">感謝頁返回連結</label>
                                  <div class="col-sm-10">
                                    <input type="text" class="form-control" id="thanks_back_url" name="thanks_back_url" value="<?php echo $row['thanks_back_url'];?>">
                                  </div>
                                </div>
                          </div>
                        </div>
                    </div>
          </div>
    </form>
		
</div>


</div><!-- /#page-content -->
<?php  include("../../core/page/footer01.php");//載入頁面footer01.php?>
<script type="text/javascript" src="../../js/plugins/codemirror/codemirror.js"></script>
<script type="text/javascript" src="../../js/plugins/codemirror/mode/xml/xml.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/highlight.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {

      let html = hljs.highlight('<input type="text" class="form-control required" err_name="姓名" id="user_name" name="user_name">', {language: 'html'}).value;
      $('.required_code').html(html);
          html = hljs.highlight('<input type="checkbox" id="ca_privacy"> 本人知悉並同意『<a target="_blank" href="privacy.html">個資告知事項聲明</a>』內容', {language: 'html'}).value;
      $('.user_code').html(html);
          html = hljs.highlight('<button id="sub_btn" type="button" >發送訊息</button>', {language: 'html'}).value;
      $('.submit_code').html(html);
          html = hljs.highlight('<input type="text" id="ca_test" input_name="測試欄位名稱">', {language: 'html'}).value;
      $('.input_code').html(html);
        
      $(`[name="send_type"][value="${$('[name="ch_send_type"]').val()}"]`).prop('checked',true);

      $('#save_btn').click(function(event) {

         var err_txt='';
          err_txt = err_txt + check_input( '#fun_form [name="re_name[]"]:nth-child(1)', '姓名，' );
          err_txt = err_txt + check_input( '#fun_form [name="re_mail[]"]:nth-child(1)', '手機，' );

          if(err_txt!=''){
            alert("請輸入"+err_txt+"!!");
          }
          else{
             $('#fun_form').submit();
          }
      });

      // var myModeSpec = {
      //   name: "xml",
      //   tags: {
      //     style: [["type", /^text\/(x-)?scss$/, "text/x-scss"],
      //             [null, null, "css"]],
      //     custom: [[null, null, "customMode"]]
      //   }
      // };
      var myCodeMirror = CodeMirror.fromTextArea(document.getElementById('cus_html'), {
          mode: 'text/html',
          lineNumbers: true,
              matchBrackets: true,
              styleActiveLine: true,
              theme: 'monokai'
      });

      myCodeMirror.setSize(null, 600);



      //------------------------------ 刪圖檔 ---------------------------------
    //   $(".one_del_file").click(function(event) { 
    //   if (confirm('是否要刪除檔案?')) {
    //    var data={
    //                   case_id: $('#Tb_index').val(),
    //                   fun_id: $('#fun_id').val(),
    //                    show_img: $(this).next().next().val(),
    //                         type: 'delete'
    //             };  
    //            ajax_in('iframe_show.php', data, '成功刪除', 'no');
    //            $(this).parent().html('');
    //   }
    // });

    //----------------------------- 取消 LINE連結 -------------------------------
    // $(".del_line_btn").click(function(event){
    //   if (confirm('是否要取消LINE連結?')){
    //     $.ajax({
    //       type: "POST",
    //       url: "call_ajax.php",
    //       data: {
    //         type: 'del_line_notify',
    //         case_id: $('#Tb_index').val()
    //       },
    //       dataType: "json",
    //       success: function (data) {
    //         if(data.success){
    //           alert('已取消連結!!');
    //           $('[name="line_Client_ID"]').val('');
    //           $('[name="line_Client_Secret"]').val('');
    //           $('.del_line_btn').remove();
    //         }
    //         else{
    //           alert(`error：${data.msg}`);
    //         }
    //       }
    //     });
    //   }
    // });

    //----------------------- 發送 LINE notify TEST ------------------------------
    // $('.send_test_btn').click(function (e) { 
    //   if($('#send_test').val()!=''){
    //     $.ajax({
    //       type: "POST",
    //       url: "https://ws.srl.tw/line_notify/send_notify.php",
    //       data: {
    //         case_id: $('#Tb_index').val(),
    //         message: $('#send_test').val()
    //       },
    //       dataType: "json",
    //       success: function (data) {
    //         console.log(data);
    //         if(data.status==200){
    //           alert('成功發送訊息!');
    //           $('#send_test').val('');
    //         }
    //         else{
    //           alert('發送失敗\nerror log：'+data.message);
    //           $('#send_test').val('');
    //         }
    //       }
    //     });
    //   }
    // });


    //---------------------- 更新 LINE notify  ------------------------------
    // $('#update_notify_btn').click(function (e) { 
    //   $('.notify_box').slideToggle();
    // });
	});
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
