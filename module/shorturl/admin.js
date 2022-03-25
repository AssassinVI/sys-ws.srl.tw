// 短網址管理列表(JavaScript)
$(document).ready(function(){
		
	var MT_id = $('#MT_id').val();
	
	//-- 判斷目前頁數 --
    var shorturl_displayStart = (localStorage.getItem("shorturl_list_page") != null) ? parseInt(localStorage.getItem("shorturl_list_page")) : 0;
    var table_pagesize = 20;
    var table_shorturl = $('#table_shorturl').DataTable({
		"displayStart": shorturl_displayStart,
        "columnDefs": [
            { "orderable": false, "targets": 0 },
            { "orderable": false, "targets": 1 },
            { "orderable": false, "targets": 2 },
            { "orderable": false, "targets": 3 },
            { "orderable": false, "targets": 4 },
			{ "orderable": false, "targets": 5 },
			{ "orderable": false, "targets": 8 }
        ],
		"order": [6, 'asc'],
		"lengthMenu": [ table_pagesize ],
        "language":{
	    "sProcessing": "處理中...",
            "sLengthMenu": "顯示 _MENU_ 項結果",
            "sZeroRecords": "無任何資料",
            "sInfo": "顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
            "sInfoEmpty": "顯示第 0 至 0 項結果，共 0 項",
            "sInfoFiltered": "(由 _MAX_ 項結果過濾)",
            "sInfoPostFix": "",
            "sSearch": "搜索:",
            "sUrl": "",
            "sEmptyTable": "無任何資料",
            "sLoadingRecords": "載入中...",
            "sInfoThousands": ",",
            "oPaginate": {
                "sFirst": "首頁",
            	"sPrevious": "上一頁",
            	"sNext": "下一頁",
            	"sLast": "末頁"
            },
            "oAria": {
                "sSortAscending": ": 以升序排列此列",
            	"sSortDescending": ": 以降序排列此列"
            }
        },
		
        "paging": true,
        "lengthChange": false,
        "info": true,
        "ordering": true,
        //-- GET 數過多導致資訊錯誤 --
	    "ajax": {
            "url": 'admin_ajax.php',
            "type": 'POST',
            "data":{
                "mt_id": MT_id,
                "type": 'list'
            }
        },
		"processing": true,
        "ServerSide": true
	});
	
	//-- 上一頁 --
    $('.page_div .prev_btn').click(function (e) {
        e.prototype;
	var shorturl_pageinfo = table_shorturl.page.info();
      	localStorage.setItem("shorturl_list_page", parseInt(shorturl_pageinfo.start)-table_pagesize);
      	table_shorturl.page( 'previous' ).draw( 'page' );
    });
    
    //-- 下一頁 --
    $('.page_div .next_btn').click(function (e) {
        e.prototype;
        var shorturl_pageinfo = table_shorturl.page.info();
      	localStorage.setItem("shorturl_list_page", parseInt(shorturl_pageinfo.start)+table_pagesize);
      	table_shorturl.page( 'next' ).draw( 'page' );
    });
        
    //-- 選擇頁數 --
    $('.page_div .page_sel').change(function (e) {
        e.prototype;
        var shorturl_pageinfo = table_shorturl.page.info();
      	localStorage.setItem("shorturl_list_page", parseInt($(this).val())*table_pagesize);
      	table_shorturl.page( parseInt($(this).val()) ).draw( 'page' );
    });
	
	//-- 資料載入後 --
    table_shorturl.on('draw', function(){
        
		table_shorturl.column( 0 ).visible( false );
    
        var shorturl_pageinfo = table_shorturl.page.info();
        
        if(shorturl_pageinfo.page == 0){
            $('.page_div .prev_btn').css('display', 'none');
        } else {
            $('.page_div .prev_btn').css('display', 'inline-block');
        }
            
        if((shorturl_pageinfo.page+1) == shorturl_pageinfo.pages){
	        $('.page_div .next_btn').css('display', 'none');
        } else {
	        $('.page_div .next_btn').css('display', 'inline-block');
        }
			
        $('.page_div .page_sel').html('');
        for (let i = 0; i <shorturl_pageinfo.pages ; i++) {
	        $('.page_div .page_sel').append('<option '+((i == shorturl_pageinfo.page) ? 'selected' : '')+' value="'+i+'">第'+(i+1)+'頁</option>');
        }
        
    });
	
	//-- 新增資料 --
	$('#add_btn').click(function(e){
		e.prototype;
		location.href = 'manager.php?MT_id='+MT_id;
	});
	
	//-- 修改資料 --
    $('#table_shorturl tbody').on('click', '.mod_btn', function(e) {
        e.prototype;
        var _this = $(this);
        var Tb_index = $(this).attr('Tb_index');
	    location.href = 'manager.php?MT_id='+MT_id+'&type=mod&Tb_index='+Tb_index;
    });
	
	//-- 刪除資料 --
    $('#table_shorturl tbody').on('click', '.del_btn', function(e) {
        e.prototype;
        var _this = $(this);
        var Tb_index = $(this).attr('Tb_index');
		var short_url = $(this).parents('tr').find('td:nth-child(1)').html();
	    if (confirm('是否確定要刪除 \r\n ['+short_url+'] 此筆短網址資料? \r\n 按[確定]確定刪除 \r\n 按[取消]取消刪除')) {
            if (confirm('再次確定是否要刪除 \r\n ['+short_url+'] 此筆短網址資料? \r\n 按[確定]確定刪除 \r\n 按[取消]取消刪除')) {
				$.ajax({
					url: 'admin_ajax.php',
            		type: 'POST',
					data: {
              			type: 'del',
						Tb_index: Tb_index
            		},
					dataType: 'json',
					success:function (data) {
						alert('成功刪除');
						table_shorturl.ajax.reload();
					}
				});
			}
		}
    });
	
	$( "#table_shorturl tbody" ).sortable({
	    revert: 300,
	    update: function( event, ui ) {
	        $("#sort_btn").css('display', 'inline-block');
	    }
    });
	
	//-- 更新排序
    $("#sort_btn").click(function(e) {
        e.prototype;
        var Tb_index = '';
        $(".sort_in").each(function(index, el) {
            Tb_index += (Tb_index == '') ? $(this).val() : '_'+$(this).val();
        });
        $.ajax({
			url: 'admin_ajax.php',
            type: 'POST',
			data: {
              	type: 'sort',
				Tb_index: Tb_index
            },
			dataType: 'json',
			success:function (data) {
				alert('已完成排序');
				$("#sort_btn").css('display', 'none');
				table_shorturl.ajax.reload();
			}
		});
	});
	
	//-- 複製短網址 --
    $('#table_shorturl tbody').on('click', '.copyurl_btn', function(e) {
	    e.prototype;
		var short_url = $('[name=short_url]')
		short_url.val($(this).attr('short_url'));
		short_url.show();
		short_url.select();
		document.execCommand("Copy");
		alert(short_url.val()+'短網址已複製!');
		short_url.hide();
	});
		
});