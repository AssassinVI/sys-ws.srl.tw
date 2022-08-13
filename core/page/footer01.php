<div class="footer">

            <div>
               <strong><?php echo $company['name'] ?> Admin </strong> - Copyright ©<?php echo $company['remark'] ?>
            </div>
        </div>

    </div>
</div>

<!-- Mainly scripts -->
<script src="../../js/jquery-2.1.1.js"></script>
<script src="../../js/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="../../js/bootstrap.min.js"></script>
<script src="../../js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="../../js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="../../js/inspinia.js"></script>
<script src="../../js/plugins/pace/pace.min.js"></script>

<!-- CKeditor -->
<script src="../../js/plugins/ckeditor/ckeditor.js"></script>
<!-- <script src="//cdn.ckeditor.com/4.5.11/full/ckeditor.js"></script> -->

<!-- twzipcode -->
<script src="../../js/plugins/twzipcode/twzipcode.js"></script>

<!-- AJAX File -->
<!-- <script src="../../js/ajaxfileupload.js"></script> -->

<!-- C3 Chart -->
<script src="../../js/plugins/c3/d3.min.js"></script>
<script src="../../js/plugins/c3/c3.min.js"></script>


<!-- dataTables -->
<script type="text/javascript" charset="utf8" src="../../js/plugins/datatables/datatables.min.js"></script>
<script src="../../js/plugins/datatables/main_op.js"></script>

<!-- FancyBox -->
<script type="text/javascript" src="../../js/plugins/fancyBox/jquery.fancybox.js"></script>

<!-- 圖片剪裁功能 -->
<script src="../../js/plugins/cropperjs/cropper.min.js"></script>

<!-- 漂亮拉bar -->
<script type="text/javascript" src="../../js//plugins/mCustomScrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
<!-- 超強動畫庫 -->
<script  src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.19.0/TweenMax.min.js"></script>

<!-- 自訂JS -->
<script src="../../js/main.js?15"></script>


<script type="text/javascript">

//-- 過時自動登出 ---
$(document).ready(function() {

  
  //  $(document).mousemove(function(event) {
  //   session_replay();
  // });

  // $(document).mousedown(function(event) {
  //   session_replay();
  // });
});

// var t;
// function session_replay() {
//   clearTimeout(t);
//     t=setTimeout(function () {
//     alert('您以登出，請重新登入');
//     location.replace('../../login.php');
//   },1440*1000);
// }


 //-- 大於今天日期 --
  if ($('.datepicker_today').length>0) {
    $('.datepicker_today').datepicker({
        dateFormat: "yy-mm-dd",
        minDate: 0,
        changeMonth: true,
        changeYear: true,
        dayNamesMin :["日","一","二","三","四","五","六"],
        dayNames :["日","一","二","三","四","五","六"],
        monthNamesShort  :["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"]
    });
  }


  //-- 期間日期 --
  if ($('.datepicker_range').length>0) {
    
    var from= $('.datepicker_range.from' ).datepicker({
              dateFormat: "yy-mm-dd",
              yearRange: "-10:+10",
              changeMonth: true,
              changeYear: true,
              dayNamesMin :["日","一","二","三","四","五","六"],
              dayNames :["日","一","二","三","四","五","六"],
              monthNamesShort  :["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"]
            })
            .on( "change", function() {
              to.datepicker( "option", "minDate", $(this).val());
            });

   var to= $('.datepicker_range.to').datepicker({
            dateFormat: "yy-mm-dd",
            yearRange: "-10:+10",
            changeMonth: true,
            changeYear: true,
            dayNamesMin :["日","一","二","三","四","五","六"],
            dayNames :["日","一","二","三","四","五","六"],
            monthNamesShort  :["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"]
          })
          .on( "change", function() {
            from.datepicker( "option", "maxDate", $(this).val());
          });

    if ($('.datepicker_range.form' ).val()!='') {
      to.datepicker( "option", "minDate", $('.datepicker_range.from' ).val());
      $('.datepicker_range.from' ).val();
    }

    if ($('.datepicker_range.to' ).val()!='') {
      from.datepicker( "option", "maxDate", $('.datepicker_range.to' ).val());
      $('.datepicker_range.to' ).val();
    }

  }


if ($('#ckeditor').length>0) {
  	CKEDITOR.replace('ckeditor',{filebrowserUploadUrl:'../../js/plugins/ckeditor/php/upload.php?case_id=<?php echo $_GET['Tb_index']?>',filebrowserImageUploadUrl : '../../js/plugins/ckeditor/php/upload_img.php?case_id=<?php echo $_GET['Tb_index']?>', height:300});
  }

if ($('#ckeditor1').length>0) {
    CKEDITOR.replace('ckeditor1',{filebrowserUploadUrl:'../../js/plugins/ckeditor/php/upload.php?case_id=<?php echo $_GET['Tb_index']?>',filebrowserImageUploadUrl : '../../js/plugins/ckeditor/php/upload_img.php?case_id=<?php echo $_GET['Tb_index']?>', height:300});
  }



	/* ===================== AJAX檔案上傳 (可能有問題) ======================== */
	// function ajax_file(url, file_id, show_id) {

	// 	$.ajaxFileUpload({
   //            url: url,
   //            secureuri: false, //是否需要安全協議
   //            fileElementId: file_id, //上傳input元件ID
   //            dataType: 'json',
   //            success: function (data, status) {  //服务器成功响应处理函数

   //                alert('檔案儲存');
   //            }
	// 	});
	// }


  

/* ===================== 燈箱 =================== */
$(".fancybox").fancybox({
   'padding':'0'
});






</script>