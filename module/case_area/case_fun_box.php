<?php include("../../core/page/header01.php");//載入頁面heaer01?>
<style type="text/css">
    .wrapper-content {
        padding: 20px 10px 0px;
    }

    #sel_fun {
        padding: 5px 15px;
        margin-right: 5px;
        font-size: 15px;
    }

    .ibox-tools a {
        color: #fff;
    }

    .loading {
        display: block;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        height: 31px;
        margin: auto;
    }

    .md-skin .ibox-content {
        position: relative;
    }

    .sk-spinner-three-bounce div {
        background-color: #838383;
    }

    .iframe_div {
        position: relative;
        width: 100%;
        height: 820px;
        overflow-x: auto;
    }

    .iframe_div iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 1920px;
        height: 975px;
        transform: scale(0.63);
        transform-origin: 0 0;
    }

    .ibox-title h3 {
        display: inline-block;
        margin-right: 10px;
    }

    .ibox-content {
        height: 66vh;
        overflow: auto;
    }

    #sort_btn {
        display: none;
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

   $case_id=empty($_GET['Tb_index']) ? '':$_GET['Tb_index'];
   $case_num=substr($case_id, 4);

   $sql=$pdo->prepare("SELECT * FROM Related_tb WHERE case_id = :com_id ORDER BY OrderBy DESC");
   $sql->execute( ['com_id'=>$_GET['Tb_index']] );

   //-- 專案名稱 --
   $row_name=pdo_select("SELECT aTitle FROM build_case WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['Tb_index']]);
}

?>


<div class="wrapper wrapper-content animated fadeInRight">
    <div class="col-lg-12">
        <h2 class="text-primary">功能區塊列表 - <?php echo $row_name['aTitle'];?></h2>
        <p class="text-danger">選擇一個功能區塊，開始編輯</p>
        <div class="new_div">

        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="ibox-tools">
                        <select id="sel_fun">
                            <option value="">-- 請選擇 --</option>
                            <?php
                                   $sql_fun=$pdo->prepare("SELECT * FROM FunBox WHERE OnLineOrNot='1' ORDER BY Tb_index ASC");
                                   $sql_fun->execute();
                                   while ($row_fun=$sql_fun->fetch(PDO::FETCH_ASSOC)) {
                                   	echo '<option value="'.$row_fun['Tb_index'].'">'.$row_fun['box_name'].'</option>';
                                   }
			 		        	?>

                        </select>
                        <a id="insert_fun" href="#" class="btn btn-primary"><i class="fa fa-plus"></i> 新增</a>

                        <a href="iframe_color.php?Tb_index=<?php echo $_GET['Tb_index']?>"
                            class="iframe_box btn btn-success">更改顏色</a>
                        <button id="sort_btn" type="button" class="btn btn-success"><i
                                class="fa fa-sort-amount-desc"></i> 更新排序</button>
                        <br>
                        <a href="iframe_css.php?Tb_index=<?php echo $_GET['Tb_index']?>"
                            class="iframe_box btn btn-success">自訂CSS</a>
                        <a href="iframe_js.php?Tb_index=<?php echo $_GET['Tb_index']?>"
                            class="iframe_box btn btn-success">自訂JS</a>
                        
                        <!-- 功能區塊排序 -->
                        <input type="hidden" id="fun_sort">

                    </div>
                </div>
                <div class="ibox-content">

                    <!-- ================功能區塊欄======================= -->

                    <ul class="sortable-list connectList agile-list ui-sortable" id="FunBox_ul">

                        <!-- <li class="warning-element" id="task9">
                                    <i class="fa fa-film"></i> 圖片輪播
                                    
                                    <a href="#" class="pull-right btn btn-xs btn-danger">刪除</a>
                                    <a style="margin-right:5px;" href="#" class="pull-right btn btn-xs btn-primary">編輯</a>
                                    <a style="margin-right:5px;" href="#" class="pull-right btn btn-xs btn-white">檢視</a>
                                </li> -->

                    </ul>

                    <!-- =================== Loading ====================== -->
                    <div class="loading">
                        <div class="sk-spinner sk-spinner-three-bounce">
                            <div class="sk-bounce1"></div>
                            <div class="sk-bounce2"></div>
                            <div class="sk-bounce3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h3>畫面預覽</h3>
                    <button type="button" id="if_reload" class="btn btn-info">重新整理</button>
                    <!-- <div class="ibox-tools" style="float: right;">
                        <button type="button" id="put_website" class="btn btn-success">匯出網頁</button>
                    </div> -->
                </div>
                <div class="ibox-content iframe_div ">
                    <?php 
        if(empty($case_num)){
        echo "<h2>無畫面...</h2>";
        }
        else{
          echo '<iframe src="https://ws.srl.tw/test/'.$case_num.'/" id="case_iframe" name="case_iframe" ></iframe>';
        }
      ?>
                </div>
            </div>
        </div>
    </div>
</div><!-- /#page-content -->

<input type="hidden" name="case_id" value="<?php echo $_GET['Tb_index'];?>">

<?php  include("../../core/page/footer01.php");//載入頁面footer01.php?>
<script type="text/javascript">
$(document).ready(function() {


    // $("#put_website").click(function(e) {
    //     var url = location.href;
    //     var case_id = url.split('Tb_index=');
    //     case_id = case_id[1];

    //     // -- 複製資料 --
    //     $.ajax({
    //         type: "POST",
    //         url: "case_fun_box_ajax.php",
    //         data: {
    //             type: 'put_website',
    //             case_id: case_id
    //         },
    //         dataType: "json",
    //         beforeSend: function() {

    //             $('body').append(
    //                 '<div id="put_website_div" style="position: fixed; top: 0; left: 0; z-index: 100; width: 100%; height: 100%; background-color: rgb(255 255 255 / 0.8); display: flex;  justify-content: center; align-items: center;"><p>匯出中...</p></div>'
    //             );
    //         },
    //         complete: function() {

                
    //         },
    //         success: function(data) {

    //             if(data['success']){
    //                 $('#put_website_div').remove();
    //                 alert(data['msg']);
    //             }
    //             else{
    //                 $('#put_website_div').remove();
    //                 alert(data['msg']);
    //             }
                

    //             //-- 複製index.html --
    //             var case_num = case_id.substr(4);
    //             $.ajax({
    //                 type: "POST",
    //                 url: "https://ws.srl.tw/cs/" + case_num + "/",
    //                 success: function(data) {

    //                     var html_txt=data.replace(/\.\.\/\.\.\//g, '');
    //                         html_txt=html_txt.replace('https://ws.srl.tw/cs/'+case_num+'/img/', 'img/');
    //                         html_txt=html_txt.replace(new RegExp('../product_html/'+case_id+'/img/', 'g'), 'img/');
    //                         html_txt=html_txt.replace(/https\:\/\/ws.srl.img\//g, 'img/');
    //                         html_txt=html_txt.replace(/googleMapTool/g, 'https://ws.srl.tw/googleMapTool');

    //                     $.ajax({
    //                         type: "POST",
    //                         url: "case_fun_box_ajax.php",
    //                         data: {
    //                             type: 'index_html',
    //                             case_id: case_id,
    //                             html:html_txt
    //                         },
    //                         success: function (data) {

    //                             $('#put_website_div').remove();

    //                             alert('匯出');
                                
    //                             location.replace('case_zip_down.php?file='+data);
    //                         }
    //                     });
    //                 }
    //             });

    //         }
    //     });
    // });



    //-- 撈取功能區塊 --
    funbox_all();


    //-- 新增功能區塊 --
    $("#insert_fun").click(function(event) {

        if ($('#sel_fun').val() == '') {
            alert('請選擇一個功能區塊');
        } else {

            $.ajax({
                url: 'case_fun_box_ajax.php',
                type: 'POST',
                data: {
                    type: 'insert',
                    funbox_id: $('#sel_fun').val(),
                    case_id: '<?php echo $_GET['Tb_index']?>'

                },
                success: function(data) {

                    funbox_all();
                }
            });
        }



    });


    //-- 功能區快-拖曳功能 --  
    $("#FunBox_ul").sortable({
        connectWith: ".connectList",
        revert: true,
        update: function(event, ui) {

            var FunBox_ul = $("#FunBox_ul").sortable("toArray");
            //更新功能區塊排序
            $('#fun_sort').val(FunBox_ul);
            $('#sort_btn').css('display', 'inline-block');

        }
    }).disableSelection();

    //更新功能區塊排序
    $('#sort_btn').click(function(event) {

        $.ajax({
            url: 'case_fun_box_ajax.php',
            type: 'POST',
            data: {
                type: 'update',
                related_id_array: $('#fun_sort').val()
            },
            success: function() {
                alert('以更新排序');
                $('#sort_btn').css('display', 'none');
            }
        });
    });



    //------- 刪除功能區塊 ---------
    $('.sortable-list').on('click', '.del_funbox', function(event) {
        event.preventDefault();

        if (confirm("是否刪除 [ " + $(this).attr('title') + " ] ??")) {
            $.ajax({
                url: 'case_fun_box_ajax.php',
                type: 'POST',
                data: {
                    type: 'delete',
                    related_id: $(this).parent().attr('id')
                },
                success: function(data) {

                    //-- 撈取功能區塊 --
                    funbox_all();
                }
            });

        } else {

        }

    });


    $(".iframe_box").fancybox({
        'padding': '0',
        'type': 'iframe',
        'width': '1280',
        afterClose: function() {
            funbox_all();
            return;
        }
    });


    //-- iframe 重新整理 ---
    $('#if_reload').click(function(event) {
        $('#case_iframe').attr('src', $('#case_iframe').attr('src'));
    });

});


//-- iframe完全載入後 ---
// $('#case_iframe').load(function() {

//    var if_body=$(this).contents();

// });



//------ 撈取功能區塊 --------
function funbox_all() {
    $.ajax({
        url: 'case_fun_box_ajax.php',
        type: 'POST',
        dataType: 'json',
        data: {
            type: 'select',
            case_id: $('[name="case_id"]').val(),
        },
        success: function(data) {
            $('.sortable-list').html('');
            $.each(data, function() {

                // -- 錨點名稱 --
                var an_name = this['fun_id'].substr(0, 2) == 'an' ? '：' + anchor_name(this[
                    'fun_id']) : '';

                // -- 判斷上線 --
                var OnLineOrNot = this['OnLineOrNot'] == '0' ? '- ( 未啟用 )' : '';



                var txt = '<li class="' + this['btn_type'] + '" id="' + this['Tb_index'] + '">' +
                    '<i class="fa ' + this['btn_icon'] + '"></i> ' + this['box_name'] +an_name + OnLineOrNot +
                    '<a href="#" title="' + this['box_name'] +
                    '" class="pull-right btn btn-xs btn-danger del_funbox">刪除</a>' +
                    '<a style="margin-right:5px;" href="' + this['aUrl'] +
                    '?MT_id=<?php echo $_GET['MT_id'];?>&Tb_index=' + this['case_id'] + '&fun_id=' +
                    this['fun_id'] + '&rel_id=' + this['Tb_index'] +
                    '" class="pull-right btn btn-xs btn-primary iframe_box">編輯</a>' +
                    
                    '</li>';
                $('.sortable-list').append(txt);

            });
        },
        beforeSend: function() {
            $('.loading').css('display', 'block');
        },
        complete: function() {
            $('.loading').css('display', 'none');
        }
    });
}



// ------------ 錨點名稱 ------------
function anchor_name(fun_id) {
    var name = '';
    $.ajax({
        url: 'case_fun_box_ajax.php',
        async: false,
        type: 'POST',
        data: {
            type: 'anchor_name',
            fun_id: fun_id
        },
        success: function(data) {
            name = data;

        }
    });

    return name;
}



//----- iframe 滑到指定位置 ------
function move_iframe(id) {

    var if_body = $(window.frames['case_iframe'].document);
    console.log(if_body.find('#' + id).offset());
    if_body.find('html,body').animate({
        scrollTop: if_body.find('#' + id).offset().top 
    }, 1000);

}
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>