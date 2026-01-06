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

    /* 資產管理樣式 */
    .asset-item {
        padding: 15px;
        margin: 8px 0;
        border: 1px solid #ddd;
        border-radius: 4px;
        background: #fff;
        transition: all 0.3s ease;
    }
    
    .asset-item:hover {
        border-color: #3498db;
        box-shadow: 0 2px 8px rgba(52, 152, 219, 0.2);
    }
    
    .asset-category {
        margin: 20px 0;
        padding: 15px;
        border-left: 4px solid #3498db;
        background: #f8f9fa;
    }
    
    .asset-category h5 {
        margin-top: 0;
        margin-bottom: 12px;
        color: #2c3e50;
    }
    
    .asset-badge {
        display: inline-block;
        padding: 3px 8px;
        margin-left: 5px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: bold;
    }
    
    .asset-badge.js {
        background-color: #f1c40f;
        color: #fff;
    }
    
    .asset-badge.css {
        background-color: #3498db;
        color: #fff;
    }
    
    .asset-description {
        color: #7f8c8d;
        font-size: 12px;
        margin: 5px 0;
    }
    
    .asset-path {
        color: #27ae60;
        font-size: 11px;
        word-break: break-all;
        font-family: monospace;
    }
    
    .custom-asset-item {
        padding: 15px;
        margin: 8px 0;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        background: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .custom-asset-info h5 {
        margin: 0 0 5px 0;
    }
    
    .custom-asset-info p {
        margin: 0;
        font-size: 12px;
        color: #7f8c8d;
    }
    
    .asset-buttons {
        margin-top: 10px;
    }
    
    .asset-buttons .btn {
        margin-right: 5px;
    }
    
    .no-assets {
        text-align: center;
        padding: 30px;
        color: #95a5a6;
    }
    
    .asset-checkbox {
        margin-right: 8px;
    }
    
    .filter-section {
        margin-bottom: 15px;
        padding: 12px;
        background: #ecf0f1;
        border-radius: 4px;
    }
    
    .tabs-container {
        margin-top: 15px;
    }
    
    .tab-content {
        padding: 15px;
        background: #fff;
        border: 1px solid #ddd;
        border-top: none;
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
                        
                        <a href="#" id="btn-manage-assets" class="btn btn-warning">
                            <i class="fa fa-cubes"></i> 套件庫
                        </a>
                        
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

<!-- 資產管理 Modal -->
<div class="modal fade" id="modal-assets-manager" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-cubes"></i> 套件庫管理
                </h5>
            </div>
            <div class="modal-body">
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#tab-library-assets">
                            <i class="fa fa-cube"></i> 套件庫
                            <span class="badge" id="library-assets-count">0</span>
                        </a></li>
                        <li><a data-toggle="tab" href="#tab-custom-assets">
                            <i class="fa fa-code"></i> 自訂資源
                            <span class="badge" id="custom-assets-count">0</span>
                        </a></li>
                    </ul>
                    <div class="tab-content">
                        <!-- 套件庫 -->
                        <div id="tab-library-assets" class="tab-pane active">
                            <div class="filter-section">
                                <label>篩選:</label>
                                <select id="filter-category-assets" class="form-control" style="width: auto; display: inline-block;">
                                    <option value="">-- 所有分類 --</option>
                                </select>
                            </div>
                            <div id="assets-library-list"></div>
                        </div>
                        
                        <!-- 自訂資源 -->
                        <div id="tab-custom-assets" class="tab-pane">
                            <button class="btn btn-success" id="btn-add-custom-asset" style="margin-bottom: 15px;">
                                <i class="fa fa-plus"></i> 新增自訂資源
                            </button>
                            <div id="custom-assets-list"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-save-assets-config">
                    <i class="fa fa-save"></i> 儲存設定
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
            </div>
        </div>
    </div>
</div>

<!-- 自訂資源編輯 Modal -->
<div class="modal fade" id="modal-edit-custom-asset" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-custom-title">新增自訂資源</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-custom-asset-edit">
                <div class="modal-body">
                    <input type="hidden" id="custom-Tb_index">
                    
                    <div class="form-group">
                        <label>資源名稱 *</label>
                        <input type="text" class="form-control" id="custom-asset_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label>資源類型 *</label>
                        <select class="form-control" id="custom-asset_type" required>
                            <option value="js">JavaScript</option>
                            <option value="css">CSS</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>載入位置 *</label>
                        <select class="form-control" id="custom-load_position" required>
                            <option value="head">Head (頁面頭部)</option>
                            <option value="body_top">Body Top (頁面開始)</option>
                            <option value="body_bottom" selected>Body Bottom (頁面結尾)</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>載入順序</label>
                        <input type="number" class="form-control" id="custom-load_order" value="200" min="1">
                        <small class="form-text text-muted">數字越小越優先載入</small>
                    </div>
                    
                    <div class="form-group">
                        <label>程式碼內容 *</label>
                        <textarea class="form-control" id="custom-content" rows="10" required style="font-family: monospace;"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                    <button type="button" class="btn btn-primary" id="btn-save-custom-asset">儲存</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 預覽 Modal -->
<div class="modal fade" id="modal-preview-asset" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">預覽</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <pre id="preview-content-asset" style="background: #f4f4f4; padding: 15px; border-radius: 4px; max-height: 400px; overflow: auto;"></pre>
            </div>
        </div>
    </div>
</div>

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
                var an_name = this['fun_id']!=null && this['fun_id'].substr(0, 2) == 'an' ? '：' + anchor_name(this[
                    'fun_id']) : '';

                // -- 判斷上線 --
                var OnLineOrNot = this['OnLineOrNot'] == '0' ? '- ( 未啟用 )' : '';


                let fun_id=this['fun_id']==null ? '':this['fun_id'];
                var txt = '<li class="' + this['btn_type'] + '" id="' + this['Tb_index'] + '">' +
                    '<i class="fa ' + this['btn_icon'] + '"></i> ' + this['box_name'] +an_name + OnLineOrNot +
                    '<a href="#" title="' + this['box_name'] +
                    '" class="pull-right btn btn-xs btn-danger del_funbox">刪除</a>' +
                    '<a style="margin-right:5px;" href="' + this['aUrl'] +
                    '?MT_id=<?php echo $_GET['MT_id'];?>&Tb_index=' + this['case_id'] + '&fun_id=' +
                    fun_id + '&rel_id=' + this['Tb_index'] +
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

// ==================== 資產管理功能 ====================
const caseId = '<?php echo $_GET['Tb_index']; ?>';
let allLibraryAssets = [];
let customAssets = [];
let selectedAssets = [];

// 打開資產管理 Modal
$('#btn-manage-assets').click(function(e) {
    e.preventDefault();
    $('#modal-assets-manager').modal('show');
    loadLibraryAssets();
    loadCustomAssetsData();
    loadAssetCategories();
});

// 載入套件庫
function loadLibraryAssets() {
    $.ajax({
        url: '../assets_manager/ajax_handler.php',
        type: 'POST',
        data: { action: 'get_library_assets', case_id: caseId },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                allLibraryAssets = response.data;
                renderLibraryAssets(response.data, response.selected);
                selectedAssets = response.selected;
            }
        }
    });
}

// 載入資產分類
function loadAssetCategories() {
    $.ajax({
        url: '../assets_manager/library_ajax.php',
        type: 'POST',
        data: { action: 'get_categories' },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                let html = '<option value="">-- 所有分類 --</option>';
                response.data.forEach(cat => {
                    html += '<option value="' + cat + '">' + cat + '</option>';
                });
                $('#filter-category-assets').html(html);
            }
        }
    });
}

// 渲染套件庫
function renderLibraryAssets(data, selected = []) {
    let html = '';
    const categories = {};
    
    data.forEach(item => {
        const cat = item.asset_category || '其他';
        if (!categories[cat]) categories[cat] = [];
        categories[cat].push(item);
    });
    
    if (Object.keys(categories).length === 0) {
        html = '<div class="no-assets"><p>沒有可用的套件</p></div>';
        $('#assets-library-list').html(html);
        return;
    }
    
    for (let cat in categories) {
        html += '<div class="asset-category"><h5>' + cat + '</h5>';
        
        categories[cat].forEach(asset => {
            const checked = selected.includes(asset.Tb_index) ? 'checked' : '';
            const icon = asset.asset_type === 'js' ? 'fa-file-code-o' : 'fa-css3';
            const badgeClass = asset.asset_type === 'js' ? 'js' : 'css';
            const badgeText = asset.asset_type.toUpperCase();
            
            html += `
                <div class="asset-item" data-asset-id="${asset.Tb_index}">
                    <label style="margin: 0; cursor: pointer;">
                        <input type="checkbox" class="asset-checkbox" 
                               value="${asset.Tb_index}" ${checked}>
                        <i class="fa ${icon}"></i>
                        <strong>${asset.asset_name}</strong>
                        <span class="asset-badge ${badgeClass}">${badgeText}</span>
                    </label>
                    <div class="asset-description">${asset.description || '(無描述)'}</div>
                    <div class="asset-path">
                        <i class="fa fa-link"></i> ${asset.file_path}
                    </div>
                    ${asset.version ? '<small class="text-muted">版本: ' + asset.version + '</small>' : ''}
                </div>
            `;
        });
        
        html += '</div>';
    }
    
    $('#assets-library-list').html(html);
    
    const checkedCount = $('.asset-checkbox:checked').length;
    $('#library-assets-count').text(checkedCount);
}

// 資產分類篩選
$('#filter-category-assets').change(function() {
    const category = $(this).val();
    
    if (category === '') {
        renderLibraryAssets(allLibraryAssets, selectedAssets);
    } else {
        const filtered = allLibraryAssets.filter(item => 
            item.asset_category === category
        );
        renderLibraryAssets(filtered, selectedAssets);
    }
});

// 載入自訂資源
function loadCustomAssetsData() {
    $.ajax({
        url: '../assets_manager/ajax_handler.php',
        type: 'POST',
        data: { action: 'get_custom_assets', case_id: caseId },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                customAssets = response.data;
                renderCustomAssetsData(response.data);
            }
        }
    });
}

// 渲染自訂資源
function renderCustomAssetsData(data) {
    let html = '';
    
    if (data.length === 0) {
        html = '<div class="no-assets"><p>還沒有自訂資源</p></div>';
    } else {
        data.forEach(asset => {
            const statusClass = asset.is_enabled==1 ? 'success' : 'warning';
            const statusText = asset.is_enabled==1 ? '已啟用' : '已停用';
            const icon = asset.asset_type === 'js' ? 'fa-file-code-o' : 'fa-css3';
            
            html += `
                <div class="custom-asset-item">
                    <div class="custom-asset-info">
                        <h5>
                            <i class="fa ${icon}"></i>
                            ${asset.asset_name}
                            <span class="label label-${statusClass}">${statusText}</span>
                        </h5>
                        <p>
                            <strong>類型:</strong> ${asset.asset_type.toUpperCase()} | 
                            <strong>位置:</strong> ${asset.load_position} |
                            <strong>順序:</strong> ${asset.load_order}
                        </p>
                    </div>
                    <div class="asset-buttons">
                        <button class="btn btn-xs btn-info btn-preview-asset" data-id="${asset.Tb_index}">
                            <i class="fa fa-eye"></i> 預覽
                        </button>
                        <button class="btn btn-xs btn-primary btn-edit-asset" data-id="${asset.Tb_index}">
                            <i class="fa fa-edit"></i> 編輯
                        </button>
                        <button class="btn btn-xs btn-warning btn-toggle-asset" data-id="${asset.Tb_index}">
                            <i class="fa fa-toggle-on"></i> ${asset.is_enabled ? '停用' : '啟用'}
                        </button>
                        <button class="btn btn-xs btn-danger btn-delete-asset" data-id="${asset.Tb_index}">
                            <i class="fa fa-trash"></i> 刪除
                        </button>
                    </div>
                </div>
            `;
        });
    }
    
    $('#custom-assets-list').html(html);
    
    // 綁定事件
    $('.btn-edit-asset').click(editCustomAssetModal);
    $('.btn-delete-asset').click(deleteCustomAssetModal);
    $('.btn-toggle-asset').click(toggleCustomAssetModal);
    $('.btn-preview-asset').click(previewCustomAssetModal);
    
    $('#custom-assets-count').text(data.length);
}

// 新增自訂資源
$('#btn-add-custom-asset').click(function() {
    $('#custom-Tb_index').val('');
    $('#form-custom-asset-edit')[0].reset();
    $('#custom-load_order').val(200);
    $('#modal-custom-title').text('新增自訂資源');
    $('#modal-edit-custom-asset').modal('show');
});

// 編輯自訂資源
function editCustomAssetModal() {
    const id = $(this).data('id');
    const asset = customAssets.find(a => a.Tb_index == id);
    
    if (asset) {
        $('#custom-Tb_index').val(asset.Tb_index);
        $('#custom-asset_name').val(asset.asset_name);
        $('#custom-asset_type').val(asset.asset_type);
        $('#custom-load_position').val(asset.load_position);
        $('#custom-load_order').val(asset.load_order);
        $('#custom-content').val(asset.content);
        $('#modal-custom-title').text('編輯自訂資源');
        $('#modal-edit-custom-asset').modal('show');
    }
}

// 預覽自訂資源
function previewCustomAssetModal() {
    const id = $(this).data('id');
    const asset = customAssets.find(a => a.Tb_index == id);
    
    if (asset) {
        $('#preview-content-asset').text(asset.content);
        $('#modal-preview-asset').modal('show');
    }
}

// 保存自訂資源
$('#btn-save-custom-asset').click(function() {
    const Tb_index = $('#custom-Tb_index').val();
    const data = {
        action: 'save_custom_asset',
        case_id: caseId,
        asset_name: $('#custom-asset_name').val(),
        asset_type: $('#custom-asset_type').val(),
        content: $('#custom-content').val(),
        load_position: $('#custom-load_position').val(),
        load_order: $('#custom-load_order').val()
    };
    
    if (Tb_index) {
        data.Tb_index = Tb_index;
    }
    
    $.ajax({
        url: '../assets_manager/ajax_handler.php',
        type: 'POST',
        data: data,
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                alert('已保存');
                $('#modal-edit-custom-asset').modal('hide');
                loadCustomAssetsData();
            }
        },
        error: function(xhr) {
            alert('保存失敗');
        }
    });
});

// 刪除自訂資源
function deleteCustomAssetModal() {
    const id = $(this).data('id');
    
    if (confirm('確定要刪除此資源嗎？')) {
        $.ajax({
            url: '../assets_manager/ajax_handler.php',
            type: 'POST',
            data: {
                action: 'delete_custom_asset',
                case_id: caseId,
                Tb_index: id
            },
            dataType: 'json',
            success: function(response) {
                alert('已刪除');
                loadCustomAssetsData();
            }
        });
    }
}

// 切換自訂資源狀態
function toggleCustomAssetModal() {
    const id = $(this).data('id');
    
    $.ajax({
        url: '../assets_manager/ajax_handler.php',
        type: 'POST',
        data: {
            action: 'toggle_custom_asset',
            case_id: caseId,
            Tb_index: id
        },
        dataType: 'json',
        success: function(response) {
            loadCustomAssetsData();
        }
    });
}

// 儲存套件設定
$('#btn-save-assets-config').click(function() {
    const selected = [];
    $('.asset-checkbox:checked').each(function() {
        selected.push($(this).val());
    });
    
    $.ajax({
        url: '../assets_manager/ajax_handler.php',
        type: 'POST',
        data: { 
            action: 'save_case_assets',
            case_id: caseId,
            assets: selected
        },
        dataType: 'json',
        success: function(response) {
            alert('已儲存');
        },
        error: function(xhr) {
            alert('保存失敗');
        }
    });
});

// 監聽複選框變化
$(document).on('change', '.asset-checkbox', function() {
    $('#library-assets-count').text($('.asset-checkbox:checked').length);
});

</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>