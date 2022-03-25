//短網址管理新增修改(Javascript)
$(document).ready(function(){
	
	var MT_id = $('#MT_id').val();
	var type = $('#type').val();
	var header = $('#header').val();
	var Tb_index = $('#Tb_index').val();
	var aUrl = $('#aUrl');
	var aTitle = $('#aTitle');
	var div_aTitle = $('#div_aTitle');
	var div_url_id = $('#div_url_id');
	var div_short_url = $('#div_short_url');
	var lbl_short_url = $('#lbl_short_url');
	var short_url = $('[name=short_url]');
	var url_id = $('#url_id');
	var div_get_shorturl = $('#div_get_shorturl');
	var submit_btn = $('#submit_btn');
	var cancel_btn = $('#cancel_btn');
	var div_submit_cancel = $('#div_submit_cancel');
	var short_url_first = $('#short_url_first');
	var link_short_url = $('.link_short_url');
	var copyurl_btn = $('.copyurl_btn');
	
	switch (type){
		case 'add':
			div_aTitle.hide();
			div_url_id.hide();
			div_short_url.hide();
			lbl_short_url.hide();
			copyurl_btn.hide();
			div_submit_cancel.hide();
			submit_btn.text('新增');
			cancel_btn.text('取消');
			break;
		case 'mod':
			div_aTitle.show();
			div_url_id.show();
			div_short_url.hide();
			lbl_short_url.show();
			copyurl_btn.show();
			div_get_shorturl.hide();
			url_id.hide();
			//aUrl.attr('readonly','readonly');
			div_submit_cancel.show();
			submit_btn.text('更新');
			cancel_btn.text('取消');
			$(`[name="OnLineOrNot"][value="${$('[name="OnLineOrNot_v"]').val()}"]`).prop('checked',true);
			break;
		default:
			div_aTitle.hide();
			div_url_id.hide();
			div_short_url.hide();
			lbl_short_url.hide();
			div_submit_cancel.hide();
			submit_btn.text('新增');
			cancel_btn.text('取消');
			break;
	}
	
	//-- 表單送出 --
	submit_btn.click(function(e){
		e.prototype;
		var aTitle_val = aTitle.val();
		var aUrl_val = aUrl.val();
		var short_url_first_val = short_url_first.val();
		var url_id_val = url_id.val();
		var err_message = '';
		var col_focus = '';
		if (aUrl_val == '') {
			err_message = '請輸入輸入網址!!';
			col_focus = aUrl;
		} else if (aUrl_val != '' && aUrl_val.indexOf("http") != 0){
			err_message = '輸入網址是不合法網址!!';
			col_focus = aUrl;
		} else if (!div_aTitle.is(":hidden") && aTitle_val == ''){
			err_message = '請輸入標題名稱!!';
			col_focus = aTitle;
		} else if (!div_url_id.is(":hidden") && (url_id_val == '' || url_id_val.length != 5)){
			err_message = '短網址輸入不完整!!';
			col_focus = url_id;
		}
		
		if (err_message != ''){
			alert(err_message);
			if (col_focus != ''){
				col_focus.focus();
			}
		} else {
			$.ajax({
				url: 'manager_ajax.php',
            	type: 'POST',
				data: {
              		type: 'check_url_id',
              		url_id: url_id_val,
					Tb_index: Tb_index
            	},
				dataType: 'json',
				success:function (data) {
					var errCode = data.errCode;
					if (errCode == "0"){
						$.ajax({
							url: 'manager_ajax.php',
            				type: 'POST',
							data: {
              					type: type,
								aTitle: aTitle_val,
								aUrl: aUrl_val,
              					url_id: url_id_val,
								Tb_index: Tb_index,
								OnlineOrNot: $('[name="OnLineOrNot"]').val()
            				},
							dataType: 'json',
							success:function (data) {
								alert('成功'+header);
								switch (type){
									case 'add':
										lbl_short_url.text(short_url_first_val+url_id_val);
										div_short_url.hide();
										lbl_short_url.show();
										copyurl_btn.show();
										submit_btn.hide();
										cancel_btn.text('回列表');
										break;
									case 'mod': 
										location.href = 'admin.php?MT_id='+MT_id;
										break;
									default:
										location.href = 'admin.php?MT_id='+MT_id;
										break;
								}
								
							}
						});
					} else {
						alert('輸入的短網址重複');
						url_id.focus();
					}
				}
			});
		}
	});
	
	//-- 表單取消 --
	cancel_btn.click(function(e){
		e.prototype;
		switch (type){
			case 'add':
				if (cancel_btn.text() == '回列表'){
					location.href = 'admin.php?MT_id='+MT_id;
				} else {
					if (confirm('是否確定要取消新增 \r\n ['+short_url.val()+'] 此筆短網址資料? \r\n 按[確定]取消新增 \r\n 按[取消]繼續輸入')) {
						$.ajax({
							url: 'manager_ajax.php',
            				type: 'POST',
							data: {
              					type: 'del',
								Tb_index: Tb_index
            				},
							dataType: 'json',
							success:function (data) {
								alert('已取消新增['+short_url.val()+']短網址資料');
								location.href = 'admin.php?MT_id='+MT_id;
							}
						});
				
					}
				}
				break;
			case 'mod':
				location.href = 'admin.php?MT_id='+MT_id;
				break;
			default:
				break;
		}
	});
	
	//-- 產生短網址 --
	$("[name=get_shorturl]").click(function(e) {
		e.prototype;
		var aUrl_val = aUrl.val();
		var err_message = '';
		var col_focus = '';
		if (aUrl_val == '') {
			err_message = '請輸入輸入網址!!';
			col_focus = aUrl;
		} else if (aUrl_val != '' && aUrl_val.indexOf('http') != 0){
			err_message = '輸入網址是不合法網址!!';
			col_focus = aUrl;
		}
		
		if (err_message != ''){
			alert(err_message);
			if (col_focus != ''){
				col_focus.focus();
			}
		} else {
			$.ajax({
				url: 'manager_ajax.php',
            	type: 'POST',
				data: {
              		type: 'get_shorturl',
              		link_url: aUrl_val
            	},
				dataType: 'json',
				success:function (data) {
					var resData = data.resData;
					var errCode = data.errCode;
					if (errCode == "0"){
						
						aTitle.val(resData[0]);
						short_url.val(resData[1]);
						url_id.val(resData[2]);
						if (div_aTitle.is(":hidden")){
							div_aTitle.show();
						}
						if (div_url_id.is(":hidden")){
							div_url_id.show();
						}
						if (div_short_url.is(":hidden")){
							div_short_url.show();
						}
						if (!div_get_shorturl.is(":hidden")){
							div_get_shorturl.hide();
						}
						if (div_submit_cancel.is(":hidden")){
							div_submit_cancel.show();
						}
					} else {
						alert("無法解析"+aUrl_val+"\r\n該網頁可能不存在，請重新輸入網址");
						aUrl.focus();
					}
				}
			});
			
		}
	});
	
	//複製短網址
	copyurl_btn.click(function(e){
		e.prototype;
		short_url.show();
		short_url.select();
		document.execCommand("Copy");
		alert(short_url.val()+'短網址已複製!');
		short_url.hide();
	});

});