<?php
include '../../core/inc/function.php';
include "../../core/inc/security.php"; //載入安全設定
?>
<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>換圖</title>
    <style> 
        body{width:300px; height:500px;}
        #one_img{ width: 200px;}
    </style>
</head>
<body>
   <div>
     <form id="ch_img_form" method="POST" action="an_ch_img_input.php" enctype="multipart/form-data">
        <input name="ch_img" type="file" onchange="file_viewer_load_new(this, '.new_img_div div')">
        <input name="case_id" type="hidden" value="">
        <input name="anchor_id" type="hidden" value="">
        <input type="hidden" name="tk" value="<?php echo $_SESSION['token'];?>">
        <button class="sub_btn" type="button">更改</button>
     </form>
   </div> 
   <div class="new_img_div">
      <p>目前圖檔</p> 
      <div>
        
      </div>
   </div>
 
  <script src="../../js/jquery-2.1.1.js"></script>
  <script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
  integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
  crossorigin="anonymous"></script>
  <script>

    $(document).ready(function () {

        

        $('.sub_btn').click(function (e) { 
           var search=location.search.split('&');
           var case_id=search[0].split('=');
               case_id=case_id[1];
           var anchor_id=search[1].split('=');
               anchor_id=anchor_id[1];
           $('.ch_img_a[anchor_id="'+anchor_id+'"] img', parent.document).attr('src',$('#one_img').attr('src'));
           $('[name="case_id"]').val(case_id);
           $('[name="anchor_id"]').val(anchor_id);

           $('#ch_img_form').submit();
        });
    });

      /* ========================== 預覽圖片方法 ============================= */
        function file_viewer_load_new(controller, html_id) {
            $(html_id).html('');
            var file = controller.files;
            for (var i = 0; i < file.length; i++) {

                if (file[i] == null) {

                    $(html_id).html('');
                }
                else {
                    var fileReader = new FileReader();
                    fileReader.readAsDataURL(file[i]);
                    fileReader.onload = function (event) {

                        //$(html_id).attr('src', this.result);
                        var file_name = controller.value.split('\\');
                        var type = file_name[2].split('.');
                        var re = /(\.jpg|\.jpeg|\.bmp|\.gif|\.png)$/i;

                        if (re.exec(file_name[2])) {
                            var result = this.result;
                        } else {
                            var result = '../../img/other_file_img/file.svg';
                        }

                        var html_txt = '<img id="one_img" src="' + result + '" alt="請上傳代表圖檔">';


                        $(html_id).append(html_txt);
                    }
                }
            }

        }
  </script>
</body>
</html>