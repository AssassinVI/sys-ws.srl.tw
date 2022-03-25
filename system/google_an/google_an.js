var t //---- 延遲 ----

//-------------- ALL 全部分析Function ------------------
function google_an_all(view_id, case_id) {
	$('#'+case_id).css('opacity', '1');
	//---- 每周使用人數 ----
	queryReports_e(view_id, '7daysAgo', 'today', 'ga:sessions', 'week_user');
	//---- 每月使用人數 ----
	queryReports_e(view_id, '30daysAgo', 'today', 'ga:sessions', 'month_user') ;
	//---- 總使用人數 ----
	queryReports_e(view_id, '2016-04-01', 'today', 'ga:sessions', 'total_user') ;
	//---- 性別 ----
	queryReports(view_id, '2016-04-01', 'today', 'ga:sessions', 'ga:userGender', 'sex') ;
	//---- 年齡 ----
	queryReports(view_id, '2016-04-01', 'today', 'ga:sessions', 'ga:userAgeBracket', 'years') ;
	//---- 媒體 ----
	queryReports(view_id, '2016-04-01', 'today', 'ga:sessions', 'ga:deviceCategory', 'media');
	//---- 熱門事件點擊 ----
	queryReports(view_id, '2016-04-01', 'today', 'ga:uniqueEvents', 'ga:eventCategory', 'event') ;
	//---- 流量來源 ----
	queryReports(view_id, '2016-04-01', 'today', 'ga:sessions', 'ga:sourceMedium', 'src');
	//---- 月流量來源 ----
	queryReports(view_id, '30daysAgo', 'today', 'ga:sessions', 'ga:sourceMedium', 'month_src');
	//---- 地區 ----
	queryReports(view_id, '2016-04-01', 'today', 'ga:sessions', 'ga:region', 'city');
	//---- 網站停留時間-年齡層 ----
	queryReports(view_id, '2016-04-01', 'today', 'ga:avgTimeOnPage', 'ga:userAgeBracket', 'timeOnSite_years') ;
	//---- 每日瀏覽人數 ----
	queryReports(view_id, '30daysAgo', 'today', 'ga:sessions', 'ga:date', 'user_date');

   //---------------- 監測分析數值有無全部輸入 --------------
    var timeOut_num=0; //-- 時間記數 --
	t=setTimeout(function an_ajax() {
            
			var is_data_num=0;
				$.each($('#an_data').find('input'), function(index, val) {
					 
					 if ($(this).val()!='') {
					 	console.log($(this).attr('name')+'無空直');
					 	is_data_num++;
					 }
				});

			t=setTimeout(an_ajax, 1000);

			timeOut_num++;

			//---- 超過10秒重新整理 ----
			if (timeOut_num>5) {
				//window.location.reload();

				$.ajax({
		           	url: 'google_an_ajax.php',
		           	type: 'POST',
		           	data: {
		           		  Tb_index: case_id,
		           		 week_user: $('[name="week_user"]').val(),
		           		month_user: $('[name="month_user"]').val(),
		           		total_user: $('[name="total_user"]').val(),
		           		       sex: $('[name="sex"]').val(),
		           		     years: $('[name="years"]').val(),
		           		     media: $('[name="media"]').val(),
		           		     event: $('[name="event"]').val(),
		           		       src: $('[name="src"]').val(),
		           		 month_src: $('[name="month_src"]').val(),
		           		      city: $('[name="city"]').val(),
		             timeOnSite_years: $('[name="timeOnSite_years"]').val(),
		           		 user_date: $('[name="user_date"]').val()
		           	},
		           	success:function (data) {
		           		$('#an_data').find('input').val('');
		           		$('#'+case_id).css('opacity', '0');
		           		$('#timeOut_num').val(timeOut_num);

		           		var Today=new Date();

		           		$('#time_'+case_id).html(Today.getFullYear()+'-'+(Today.getMonth()+1)+'-'+Today.getDate()+' '+Today.getHours()+':'+Today.getMinutes()+':'+Today.getSeconds());
		           	}
		           });

		           clearTimeout(t);
			}
            
            //---------- 全都有值 --------
			if (is_data_num==$('#an_data').find('input').length) {
                   
                   $.ajax({
		           	url: 'google_an_ajax.php',
		           	type: 'POST',
		           	data: {
		           		  Tb_index: case_id,
		           		 week_user: $('[name="week_user"]').val(),
		           		month_user: $('[name="month_user"]').val(),
		           		total_user: $('[name="total_user"]').val(),
		           		       sex: $('[name="sex"]').val(),
		           		     years: $('[name="years"]').val(),
		           		     media: $('[name="media"]').val(),
		           		     event: $('[name="event"]').val(),
		           		       src: $('[name="src"]').val(),
		           		 month_src: $('[name="month_src"]').val(),
		           		      city: $('[name="city"]').val(),
		             timeOnSite_years: $('[name="timeOnSite_years"]').val(),
		           		 user_date: $('[name="user_date"]').val()
		           	},
		           	success:function (data) {
		           		$('#an_data').find('input').val('');
		           		$('#'+case_id).css('opacity', '0');
		           		$('#timeOut_num').val(timeOut_num);

		           		var Today=new Date();

		           		$('#time_'+case_id).html(Today.getFullYear()+'-'+(Today.getMonth()+1)+'-'+Today.getDate()+' '+Today.getHours()+':'+Today.getMinutes()+':'+Today.getSeconds());
		           	}
		           });

		           clearTimeout(t);
			}

	}, 1000);
	
}


	  // Replace with your view ID.
		//var VIEW_ID = '149822707';

		// Query the API and print the results to the page.
		//--------------------- 指標+維度 ---------------------
		function queryReports(view_id, startDate, endDate, expression, dimensions, type) {
		  gapi.client.request({
		    path: '/v4/reports:batchGet',
		    root: 'https://analyticsreporting.googleapis.com/',
		    method: 'POST',
		    body: {
		      reportRequests: [
		        {
		          viewId: view_id,
		          dateRanges: [
		            {
		              startDate: startDate,
		              endDate: endDate
		            }
		          ],
		          metrics: [
		            { expression: expression }
		          ],
		          dimensions:[
                    { name: dimensions }
		          ]
		        }
		      ]
		    }
		  }).then(function (response) {

		  	var an_row=response.result.reports[0].data.rows;
            var an_name='';
            var an_num='';
		  	for (var i = 0; i < an_row.length; i++) {

		  		an_name+=an_row[i].dimensions[0]+',';
		  		an_num+=an_row[i].metrics[0].values[0]+',';
		  	}
		  	an_name=an_name.slice(0,-1);
		  	an_num=an_num.slice(0,-1);

		  	$('[name="'+type+'"]').val(an_name+'|'+an_num);
		  });
		}

        //--------------------- 指標 ---------------------
		function queryReports_e(view_id, startDate, endDate, expression, type) {
		  gapi.client.request({
		    path: '/v4/reports:batchGet',
		    root: 'https://analyticsreporting.googleapis.com/',
		    method: 'POST',
		    body: {
		      reportRequests: [
		        {
		          viewId: view_id,
		          dateRanges: [
		            {
		              startDate: startDate,
		              endDate: endDate
		            }
		          ],
		          metrics: [
		            { expression: expression }
		          ]
		        }
		      ]
		    }
		  }).then(function (response) {

		  	 var an_row=response.result.reports[0].data.rows;
          
		  	 $('[name="'+type+'"]').val(an_row[0].metrics[0].values[0]);
		  });

		}