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