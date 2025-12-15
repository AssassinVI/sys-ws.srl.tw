/* ==================== 基本AJAX 新增，修改，刪除 ======================= */
function ajax_in(url, data, alert_txt, replace) {
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function () {
            if (alert_txt != 'no') { alert(alert_txt); }
            if (replace != 'no') { location.replace(replace); }
        }
    });
}


/* ========================== 預覽影片方法 ============================= */
function video_load(controller, html_id) {

    var file = controller.files[0];
    if (file == null) {
        $(html_id).html('');
    }
    else {
        var fileReader = new FileReader();
        fileReader.readAsDataURL(file);
        fileReader.onload = function (event) {
            // $(html_id).attr('src', this.result);
            var file_name = controller.value.split('\\');
            var type = file_name[2].split('.');
            var re = /(\.mp4)$/i;

            //-- 驗證影片 --
            if (re.exec(file_name[2])) {
                $(html_id).html(' <video controls src="' + this.result + '"></video>');
            }
            else {
                alert('請上傳mp4檔');
                controller.value = '';
            }
        }
    };
}


/* ========================== 預覽音樂方法 ============================= */
function audio_load(controller, html_id) {

    var file = controller.files[0];
    if (file == null) {
        $(html_id).html('');
    }
    else {
        var fileReader = new FileReader();
        fileReader.readAsDataURL(file);
        fileReader.onload = function (event) {
            // $(html_id).attr('src', this.result);
            var file_name = controller.value.split('\\');
            var type = file_name[2].split('.');
            var re = /(\.mp3)$/i;

            //-- 驗證音樂 --
            if (re.exec(file_name[2])) {
                $(html_id).html(' <audio controls src="' + this.result + '"></audio>');
            }
            else {
                alert('請上傳mp3檔');
                controller.value = '';
            }

        }
    };
}


/* ========================== 預覽圖片方法 ============================= */
function file_viewer_load_new(controller, html_id) {

    if (html_id != '') {
        $(html_id).html('');
    }

    var file = controller.files;
    for (var i = 0; i < file.length; i++) {

        if (file[i] != null) {
            var fileReader = new FileReader();
            fileReader.readAsDataURL(file[i]);
            fileReader.onload = function (event) {

                var file_name = controller.value.split('\\');
                var type = file_name[2].split('.');
                var re = /(\.jpg|\.jpeg|\.svg|\.bmp|\.gif|\.png|\.webp)$/i;

                //-- 驗證圖檔 --
                if (re.exec(file_name[2])) {
                    if (html_id != '') {
                        var result = this.result;
                        var html_txt = '<img id="one_img" src="' + result + '" alt="請上傳代表圖檔">';
                        $(html_id).html(html_txt);
                    }
                } else {
                    alert('請上傳圖檔');
                    controller.value = '';
                }
            }
        }
    }
}



/* ========================== 彈出圖片剪裁 ============================= */
function cropper_img_box (html_id, width, height) {

    $.fancybox.open({
       'href':`/core/api/cropper/iframe.html?html_id=${html_id}&width=${width}&height=${height}`,
       'padding' :'0',
       'width':'1280',
       'type' : 'iframe'
    });
}




/* ======================== 重設表單 ========================== */
function clean_all() {
    if (confirm("是否要重設表單??")) {
        window.location.reload();
    }
}


/*--- checkout 功能  ---*/
// =============================== 檢查input ====================================
function check_input(id, txt) {
    if ($(id).length > 0) {
        //-- 核取方塊、選取方塊 --
        if ($(id).attr('type') == 'radio' || $(id).attr('type') == 'checkbox') {
            if ($(id + ':checked').val() == undefined) {
                $(id).css('borderColor', 'red');
                return txt;
            } else {
                $(id).css('borderColor', 'rgba(0,0,0,0.1)');
                return "";
            }
        } else {
            if ($(id).val() == '' || $(id).val().search(/^(?:[^\~|\!|\#|\$|\%|\^|\&|\*|\(|\)|\=|\+|\{|\}|\[|\]|\"|\'|\<|\>]+)$/) == -1) {
                $(id).css('borderColor', 'red');
                return txt;
            } else {
                $(id).css('borderColor', 'rgba(0,0,0,0.1)');
                return "";
            }
        }
    } else {
        return txt;
    }
}



/*============================= 日期格式化 ======================= */
function DateFormat(date) {
    var dateTime = new Date(date);
    var year = dateTime.getFullYear(),
        month = dateTime.getMonth() + 1,
        g_date = dateTime.getDate();

    month = month < 10 ? '0' + month : month;
    g_date = g_date < 10 ? '0' + g_date : g_date;

    var timeFormat = year + '-' + month + '-' + g_date;

    return timeFormat;
}


function GetDateStr(AddDayCount, format = 'Y/m/d') {
    var dd = new Date();
    dd.setDate(dd.getDate() + AddDayCount);//获取AddDayCount天后的日期
    var Y = dd.getFullYear();
    var m = dd.getMonth() + 1;//获取当前月份的日期
    var d = dd.getDate();
    var date_txt = format;
    date_txt = date_txt.replace('Y', Y);
    date_txt = date_txt.replace('m', m);
    date_txt = date_txt.replace('d', d);
    return date_txt;
}



//========================== 獲取url get =================================
function url_get() {
    let url_get = location.search;
        url_get = url_get.substring(1);
    let get_arr = url_get.indexOf('&') != -1 ? url_get.split('&') : [url_get];
    let get_key_arr = [];

    get_arr.forEach(get => {
        let one = get.split('=');
        get_key_arr[one[0]] = one[1];
    });

    return get_key_arr;
}



//========================== 單用千分位 ==========================
function fm_Thousands(num) {
    return num.toString().replace(/(\d{1,3})(?=(\d{3})+(?:$|\D))/g, "$1,");
}



const img_html=`<div id="img_div">
					    <p>目前圖檔</p>
						<button type="button" class="one_del_img"> X </button>
						<span class="img_check"><i class="fa fa-check"></i></span>
						<img id="one_img" src="../../img/{{img_name}}?{{update_num}}" alt="請上傳代表圖檔">
						<input type="hidden" name="old_img" value="{{img_name}}">
					</div>`;

const video_html=`<div id="img_div">
					    <p>目前影片</p>
						<button type="button" class="one_del_img"> X </button>
						<span class="img_check"><i class="fa fa-check"></i></span>
                        <video id="one_img" src="../../img/other_file/{{img_name}}?{{update_num}}" controls></video>
						<input type="hidden" name="old_img" value="{{img_name}}">
					</div>`;

const img_m_html=`<div id="img_div">
                <p>目前圖檔</p>
                <button type="button" class="one_del_m_img"> X </button>
                <span class="img_check"><i class="fa fa-check"></i></span>
                <img id="one_img" src="../../img/{{img_name}}?{{update_num}}" alt="請上傳代表圖檔">
                <input type="hidden" name="old_m_img_{{dom_name}}[]" value="{{img_name}}">
            </div>`;

//-- 區塊圖檔 --
const img_b_html=`<div id="img_div">
                    <p>目前圖檔</p>
                    <button type="button" class="one_del_b_img"> X </button>
                    <span class="img_check"><i class="fa fa-check"></i></span>
                    <img id="one_img" src="../../img/{{img_name}}?{{update_num}}" alt="請上傳代表圖檔">
                    <input type="hidden" name="old_b_img_{{dom_name}}[]" value="{{img_name}}">
                </div>`;

//-- 區塊檔案 --
const file_b_html=`<div id="img_div">
                    <p>目前檔案</p>
                    
                    <span class="img_check"><i class="fa fa-check"></i></span>
                    <a href="../../other_file/{{img_name}}?{{update_num}}" target="_blank">PDF檔案</a>
                    <input type="hidden" name="old_b_img_{{dom_name}}[]" value="{{img_name}}">
                </div>`;



//----- 撈資料 -------
/**
 * 
 * @param {String} url 
 * @param {Object} data 
 */
function select_one (url, data) {
    let jqXHR = $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    dataType: "json",
                    beforeSend: function(){
                    $('.ajax_loading').addClass('show_in');
                    },
                    complete: function(){
                    $('.ajax_loading').removeClass('show_in');
                    },
                    success: function (data) {
                        console.log(data);
                        if(data.success && data.data!=false){
                            $.each($('input, select'), function (index, valueOfElement) { 
                                if($(this).attr('name')!=undefined){
                                    let dom_name=$(this).attr('name').replaceAll('[]', '');
                                    let dom_type=$(this).attr('type');
                                    //console.log($(this).attr('name'), $(this).attr('type'));
                                    //-- 文字、數字、顏色、select、 --
                                    if(dom_type=='text' || dom_type=='date' || dom_type=='month' || dom_type=='number' || dom_type == 'color' || $(this).find('option').length>0){
                                        $(`[name="${dom_name}"]`).val(data.data[dom_name]);
                                    }
                                    //-- radio方塊 --
                                    else if (dom_type == 'radio') {
                                        $(`[name="${dom_name}"][value="${data.data[dom_name]}"]`).prop('checked', true);
                                    }
                                    //-- 核取方塊 --
                                    else if(dom_type=='checkbox'){
                                        let checkbox_ch=data.data[dom_name]=='1' ?  true:false;
                                        $(`[name="${dom_name}"]`).prop('checked', checkbox_ch);
                                    }
                                    //-- 圖 --
                                    else if(dom_type=='file'){

                                        if($(this).attr('name').indexOf('[]')!=-1 || $(this).attr('multiple')!=undefined){
                                            if(data.data[dom_name] != ''){
                                                let m_img_arr=data.data[dom_name].split(',');
                                                
                                                m_img_arr.forEach(item => {
                                                    let m_img_html=img_m_html;
                                                    m_img_html= m_img_html.replaceAll('{{img_name}}', item);
                                                    m_img_html = m_img_html.replaceAll('{{dom_name}}', dom_name);
                                                    m_img_html= m_img_html.replaceAll('{{update_num}}', data.data.update_num);

                                                    $(`[data-img="${dom_name}"]`).append(m_img_html);
                                                });
        
                                                $(`[data-img="${dom_name}"]`).next().append(`<span class="text-danger ">可拖曳排序</span>`);
                                                $( ".sort_div" ).sortable({revert: 300,});
                                                $( ".sort_div" ).disableSelection();
                                            }
                                        }
                                        else if(data.data[dom_name]!=''){
                                            let s_img_html=data.data[dom_name].indexOf('mp4')!=-1 ? video_html : img_html;
                                            s_img_html= s_img_html.replaceAll('{{img_name}}', data.data[dom_name]);
                                            s_img_html= s_img_html.replaceAll('{{update_num}}', data.data.update_num);

                                            $(`[data-img="${dom_name}"]`).html(s_img_html);
                                        }
                                    }
                                }
                            });

                            let ck_num=0;
                            $.each($('textarea'), function (index, valueOfElement) { 
                                let dom_name=$(this).attr('name');
                                let dom_id=$(this).attr('id');
                                
                                if(dom_id.indexOf('ckeditor')!=-1){
                                    CKEDITOR.instances[dom_id].destroy();
                                    
                                    if(dom_id.indexOf('ckeditor_sp')!=-1){
                                         ck_box[ck_num]= CKEDITOR.replace(dom_id, ck_sp_config);
                                    }
                                    else{
                                        ck_box[ck_num]= CKEDITOR.replace(dom_id, ck_config);
                                    }
                                    
                                    //-- 在 CKEDITOR 實例完全創建、完全初始化並準備好進行交互時觸發的事件 --
                                    ck_box[ck_num].on( 'instanceReady', function() {
                                        //console.log('CK準備好了', this);
                                        this.setData(data.data[dom_name] ); 
                                     });
                                     ck_num++;
                                }
                                else{
                                    $(`[name="${dom_name}"]`).val(data.data[dom_name]);
                                }
                            });
                        }
                        else{
                            alert(data.msg);
                        }
                    }
                });
    return jqXHR;
}