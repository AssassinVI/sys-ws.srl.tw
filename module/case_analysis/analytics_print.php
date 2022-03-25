<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<style type="text/css">
	.fb_fans{ display: none; }
	.user_num{ font-size: 35px; text-align: center; }
	.c3 svg{ font: 15px Microsoft JhengHei; }
    .c3-legend-item{ font-size: 16px; }
    #com_tb, #title_tb{ font-size: 18px; }
    body.fixed-sidebar .navbar-static-side, body.canvas-menu .navbar-static-side, .border-bottom{ display: none; }
    #page-wrapper{ margin:0 !important;   }
  #wrapper{ width: 800px; }



    @page { margin: 1cm;} /* 列印邊距 */


</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 

if ($_GET) {
 	$where=['Tb_index'=>$_GET['Tb_index']];
 	$row=pdo_select('SELECT * FROM google_analytics WHERE Tb_index=:Tb_index', $where);	

  $row_name=pdo_select("SELECT aTitle FROM build_case WHERE Tb_index=:Tb_index", $where);
}
?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
    <div class="col-lg-12 col-xs-12">
      <h2 class="text-primary">GOOGLE分析 列表 - <?php echo $row_name['aTitle'];?></h2>
      <p>本頁面列出各建案常用分析圖表</p>
       <div class="new_div">
          
      </div>
    </div>
		<div class="col-lg-4 col-xs-4">
			<div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>一周瀏覽人數
                                <small>Number of visitors per week</small>
                            </h5>
                        </div>
                        <div class="ibox-content user_num">
                            <?php echo $row['week_user'].'人';?>
                        </div>
                    </div>

		</div>
		<div class="col-lg-4 col-xs-4">
			<div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>一個月瀏覽人數
                                <small>Number of visitors in a month</small>
                            </h5>
                        </div>
                        <div class="ibox-content user_num">
                            <?php echo $row['month_user'].'人';?>
                        </div>
                    </div>

		</div>
		<div class="col-lg-4 col-xs-4">
			<div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>總瀏覽人數
                                <small>The total number of visitors</small>
                            </h5>
                        </div>
                        <div class="ibox-content user_num">
                            <?php echo $row['total_user'].'人';?>
                        </div>
                    </div>

		</div>
		<div class="col-lg-12 col-xs-12">
			<div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>使用者性別
                                <small>User gender</small>
                            </h5>
                        </div>
                        <div class="ibox-content">
                            <div id="sex"></div>
                        </div>
                    </div>

		</div>

		<div class="col-lg-12 col-xs-12">
			<div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>使用者年齡
                                <small>User year</small>
                            </h5>
                        </div>
                        <div class="ibox-content">
                           <div id="old"></div>
                        </div>
                    </div>

		</div>
    
    <!-- -- 分頁 -- -->
    <p style='page-break-after:always'> </p>

		<div class="col-lg-12 col-xs-12">
			<div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>使用的媒體
                                <small>Used media</small>
                            </h5>
                        </div>
                        <div class="ibox-content">
                            <div id="media"></div>
                        </div>
                    </div>

		</div>

		<div class="col-lg-12 col-xs-12">
			<div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>使用的功能鈕
                                <small>The function button used</small>
                            </h5>
                        </div>
                        <div class="ibox-content">
                            <div id="tool_btn">
                                
                            </div>
                        </div>
                    </div>

		</div>

    <!-- -- 分頁 -- -->
    <p style='page-break-after:always'> </p>

    <div class="col-lg-12 col-xs-12">
      <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5><?php echo date('n')?>月流量來源
                                <small>Traffic Sources</small>
                            </h5>
                        </div>
                        <div class="ibox-content">
                            <div id="month_src_num">
                                
                            </div>
                        </div>
                    </div>

    </div>

		<div class="col-lg-12 col-xs-12">
			<div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>總流量來源
                                <small>Traffic Sources</small>
                            </h5>
                        </div>
                        <div class="ibox-content">
                            <div id="src_num">
                                
                            </div>
                        </div>
                    </div>

		</div>

    <!-- -- 分頁 -- -->
    <p style='page-break-after:always'> </p>

		<div class="col-lg-12 col-xs-12">
			<div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>地區使用人數
                                <small>Number of users in each region</small>
                            </h5>
                        </div>
                        <div class="ibox-content">
                            <div id="city">
                                <table class="table table-hover ">
                                <thead id="title_tb">
                                <tr>
                                    <th>地名</th>
                                    <th>人數</th>
                                </tr>
                                </thead>
                                <tbody id="com_tb">

                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>

		</div>
    

		<div class="col-lg-12 col-xs-12">
			<div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>各年齡層平均停留網站時間
                                <small>Each age average residence time of the site</small>
                            </h5>
                        </div>
                        <div class="ibox-content">
                            <div id="timeOnSite">
                                
                            </div>
                        </div>
                    </div>

		</div>


    <!-- -- 分頁 -- -->
    <p style='page-break-after:always'> </p>

		<div class="col-lg-12 col-xs-12">
			<div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>每日使用人數
                                <small>The number of daily user</small>
                            </h5>
                        </div>
                        <div class="ibox-content">
                            <div id="date_use">
                                
                            </div>
                        </div>
                    </div>

		</div>

	</div>

</div><!-- /#page-content -->

<?php  include("../../core/page/footer01.php");//載入頁面footer02.php?>
<script type="text/javascript">
	$(document).ready(function() {
        
    //=========================================== 圖表樣式 ====================================

         //使用者性別
         var data_sex= c3.generate({
                     bindto: '#sex',
                     data:{
                         columns: [
                             ['男性'],['女性']
                         ],

                         type : 'pie'
                     },
                     pie: {
                     label: {
                               format: function (value, ratio, id) {
                               return value+"人";
                              }
                            }
                       },
                     size:{
                      height:300
                    }
                 });
         ajax_sex(data_sex);


        //使用者年齡
        var data_old= c3.generate({
                      bindto: '#old',
                      data:{
                         x:'x',
                          columns: [
                              ['x'],['使用人數']
                          ],
                          colors:{
                              使用人數: '#1ab394',
                          },
                          type: 'bar',
                          labels: true
                      },
                 axis:{
                         x:{
                           type:'category'
                         }
                      }
                  });
        ajax_old(data_old);


        //使用媒體
       var data_media= c3.generate({
                bindto: '#media',
                data:{
                   x:'x',
                    columns: [
                        
                    ],
                    colors:{
                        使用人數: '#1ab394',
                    },
                    type: 'bar',
                    labels: true
                },
                axis:{
                   x:{
                     type:'category'
                   }
                }
            });
        ajax_media(data_media);


      //使用功能鈕
      var data_tool_btn= c3.generate({
                bindto: '#tool_btn',
                data:{
                    columns: [


                    ],
                    type : 'pie'
                },
                pie: {
                label: {
                          format: function (value, ratio, id) {
                          return value+"人";
                         }
                       }
                  },
                size:{
                    height:500
                  }
            });
      ajax_tool_btn(data_tool_btn);


      //流量來源
      var data_src_num= c3.generate({
                bindto: '#src_num',
                data:{
                    columns: [


                    ],
                    type : 'pie'
                },
                pie: {
                label: {
                          format: function (value, ratio, id) {
                          return value+"人";
                         }
                       }
                  },
                size:{
                    height:450
                  }
            });
      ajax_src_num(data_src_num);


      //流量來源
      var data_month_src_num= c3.generate({
                bindto: '#month_src_num',
                data:{
                    columns: [


                    ],
                    type : 'pie'
                },
                pie: {
                label: {
                          format: function (value, ratio, id) {
                          return value+"人";
                         }
                       }
                  },
                size:{
                    height:450
                  }
            });
      ajax_month_src_num(data_month_src_num);

      //地區使用人數
      ajax_city();

      //齡層平均停留網站時間
      var data_timeOnSite= c3.generate({
                      bindto: '#timeOnSite',
                      data:{
                         x:'x',
                          columns: [
                              ['x'],['停留時間(分鐘)']
                          ],
                          colors:{
                              使用人數: '#1ab394',
                          },
                          type: 'bar',
                          labels: true
                      },
                 axis:{
                         x:{
                           type:'category'
                         }
                      }
                  });
       ajax_timeOnSite(data_timeOnSite);


      //每日使用人數
      var data_use= c3.generate({
                    bindto: '#date_use',
                    data:{
                       x:'x',
                       xFormat: '%Y%m%d',
                        columns: [

                        ['x'],['使用人數']
                           
                        ],
                        colors:{
                            data1: '#1ab394',
                            
                        },
                        type: 'line',
                        
                    },
                    axis:{
                       x:{
                         type:'timeseries',
                          tick:{
                              
                              count:10,
                              format: '%m-%d'
                          }
                       }
                    }
                });
         ajax_data_use(data_use);

      
    }); //JQUERY END
   

   //--- 載入完成後列印 ---
   $(window).load(function() {
     setTimeout ("window.print()" , 1000);
     setTimeout ("CloseWin()" , 2000);
     
   });
 

// -- 列印後關閉 --

function CloseWin()
{
window.opener=null;
window.close();
}

    //=========================================== 撈取資料 ====================================

     //使用者性別
	function ajax_sex(an) {
		$.ajax({
			url: 'analytics_ajax.php',
			type: 'POST',
			data: {
				type: 'sex',
				Tb_index: '<?php echo $_GET['Tb_index'];?>'
			},
		    success: function (data) {
		    	var an_data=data.split('|');
		    	var data_name=an_data[0].split(',');
		    	var data_num=an_data[1].split(',');

		    	an.load({
                  columns:[
                   ['女性', data_num[0]],
                   ['男性', data_num[1]]
                  ]
		    	});
		    }
		});
	}
    
     //使用者年齡
	function ajax_old(an) {
		$.ajax({
			url: 'analytics_ajax.php',
			type: 'POST',
			data: {
				type: 'old',
				Tb_index: '<?php echo $_GET['Tb_index'];?>'
			},
		    success: function (data) {
		    	var an_data=data.split('|');
		    	var data_name=an_data[0].split(',');
		    	var data_num=an_data[1].split(',');
		    	data_name.splice(0,0,'x');
		    	data_num.splice(0,0,'使用人數');

		    	an.load({
                  columns:[data_name, data_num]
		    	});
		    }
		});
	}

	 //使用媒體
	function ajax_media(an) {
		$.ajax({
			url: 'analytics_ajax.php',
			type: 'POST',
			data: {
				type: 'media',
				Tb_index: '<?php echo $_GET['Tb_index'];?>'
			},
		    success: function (data) {
		    	var an_data=data.split('|');
		    	//var data_name=an_data[0].split(',');
		    	var data_num=an_data[1].split(',');
		    	//data_name.splice(0,0,'x');
		    	data_num.splice(0,0,'使用人數');

		    	an.load({
                  columns:[['x', '桌機', '手機','平板'], data_num]
		    	});
		    }
		});
	}

	 //使用功能紐
	function ajax_tool_btn(an) {
		$.ajax({
			url: 'analytics_ajax.php',
			type: 'POST',
			data: {
				type: 'tool_btn',
				Tb_index: '<?php echo $_GET['Tb_index'];?>'
			},
		    success: function (data) {
		    	var an_data=data.split('|');
		    	var data_name=an_data[0].split(',');
		    	var data_num=an_data[1].split(',');
		    	var data_all=[];
		    	for (var i = 0; i < data_name.length; i++) {
		    	   data_all.push([data_name[i], data_num[i]]);
		    	}

                an.load({
                     columns: data_all
		    	});	
		    }
		});
	}

  
     //月流量來源
    function ajax_month_src_num(an) {
      $.ajax({
        url: 'analytics_ajax.php',
        type: 'POST',
        data: {
          type: 'month_src_num',
          Tb_index: '<?php echo $_GET['Tb_index'];?>'
        },
          success: function (data) {
            var an_data=data.split('|');
            var data_name=an_data[0].split(',');
            var data_num=an_data[1].split(',');
            var data_all=[];
                  var total=0;
                  var other_total=0;

            for (var i = 0; i < data_num.length; i++) {
              total+=parseInt(data_num[i]);
            }
            total=Math.round(total/data_num.length);

            console.log(total);

              for (var i = 0; i < data_name.length; i++) {
                          
                          if(data_num[i]>total){

                if (data_name[i].search(/none/)>-1) {
                  var find_name='直接連結';
                }
                else if(data_name[i].search(/organic/)>-1){
                  var new_name=data_name[i].split('/');
                  var find_name=new_name[0]+'搜尋';
                }
                else if(data_name[i].search(/referral/)>-1){
                  var new_name=data_name[i].split('/');
                  var find_name=new_name[0]+'推薦連結';
                }
                else if(data_name[i].search(/Campaigns/)>-1){
                  var new_name=data_name[i].split('/');
                  var find_name=new_name[0]+'google廣告';
                }
                else{
                  var find_name=data_name[i];
                }
                 
                 data_all.push([find_name, data_num[i]]);
               }
               else{

                other_total+=parseInt(data_num[i]);
               }

                        if(other_total>0){
                          data_all.push(['其他', other_total]);
                        }
              }
            
            
                  an.load({
                       columns: data_all
            }); 
          }
      });
    }



	 //流量來源
	function ajax_src_num(an) {
		$.ajax({
			url: 'analytics_ajax.php',
			type: 'POST',
			data: {
				type: 'src_num',
				Tb_index: '<?php echo $_GET['Tb_index'];?>'
			},
		    success: function (data) {
		    	var an_data=data.split('|');
		    	var data_name=an_data[0].split(',');
		    	var data_num=an_data[1].split(',');
		    	var data_all=[];
                var total=0;
                var other_total=0;

		    	for (var i = 0; i < data_num.length; i++) {
		    		total+=parseInt(data_num[i]);
		    	}
		    	total=Math.round(total/data_num.length);

		    	console.log(total);

		    		for (var i = 0; i < data_name.length; i++) {
                        
                        if(data_num[i]>total){

		    			if (data_name[i].search(/none/)>-1) {
		    				var find_name='直接連結';
		    			}
		    			else if(data_name[i].search(/organic/)>-1){
		    				var new_name=data_name[i].split('/');
		    				var find_name=new_name[0]+'搜尋';
		    			}
		    			else if(data_name[i].search(/referral/)>-1){
		    				var new_name=data_name[i].split('/');
		    				var find_name=new_name[0]+'推薦連結';
		    			}
		    			else if(data_name[i].search(/Campaigns/)>-1){
		    				var new_name=data_name[i].split('/');
		    				var find_name=new_name[0]+'google廣告';
		    			}
		    			else{
		    				var find_name=data_name[i];
		    			}
		    		   
		    		   data_all.push([find_name, data_num[i]]);
		    	 	 }
		    	 	 else{

		    	 	 	other_total+=parseInt(data_num[i]);
		    	 	 }

                      if(other_total>0){
                        data_all.push(['其他', other_total]);
                      }
		    		}
		    	
		    	// else{

		    	// 	for (var i = 0; i < data_name.length; i++) {

		    	// 		if (data_name[i].search(/none/)>-1) {
		    	// 			var find_name='直接連結';
		    	// 		}
		    	// 		else if(data_name[i].search(/organic/)>-1){
		    	// 			var new_name=data_name[i].split('/');
		    	// 			var find_name=new_name[0]+'搜尋';
		    	// 		}
		    	// 		else if(data_name[i].search(/referral/)>-1){
		    	// 			var new_name=data_name[i].split('/');
		    	// 			var find_name=new_name[0]+'推薦連結';
		    	// 		}
		    	// 		else if(data_name[i].search(/Campaigns/)>-1){
		    	// 			var new_name=data_name[i].split('/');
		    	// 			var find_name=new_name[0]+'google廣告';
		    	// 		}
		    	// 		else{
		    	// 			var find_name=data_name[i];
		    	// 		}

		    	// 	   data_all.push([find_name, data_num[i]]);

		    		   
		    	// 	}
		    	// }
		    	
                an.load({
                     columns: data_all
		    	});	
		    }
		});
	}


	//地區使用人數
	function ajax_city() {
		$.ajax({
			url: 'analytics_ajax.php',
			type: 'POST',
			data: {
				type: 'city',
				Tb_index: '<?php echo $_GET['Tb_index'];?>'
			},
		    success: function (data) {
		    	var an_data=data.split('|');
		    	var data_name=an_data[0].split(',');
		    	var data_num=an_data[1].split(',');
		    	
		    	for (var i = 0; i < data_name.length; i++) {

		    		var txt='<tr><td>'+data_name[i]+'</td><td>'+data_num[i]+'</td></tr>';
		    	   $('#city #com_tb').append(txt);
		    	}
		    }
		});
	}


	//齡層平均停留網站時間
	function ajax_timeOnSite(an) {
		$.ajax({
			url: 'analytics_ajax.php',
			type: 'POST',
			data: {
				type: 'timeOnSite',
				Tb_index: '<?php echo $_GET['Tb_index'];?>'
			},
		    success: function (data) {
		    	var an_data=data.split('|');
		    	var data_name=an_data[0].split(',');
		    	var data_num=an_data[1].split(',');
		    	data_name.splice(0,0,'x');
		    	data_num.splice(0,0,'停留時間(分鐘)');

		    	an.load({
                  columns:[data_name, data_num]
		    	});
		    }
		});
	}


	 //每日使用人數
	function ajax_data_use(an) {
		$.ajax({
			url: 'analytics_ajax.php',
			type: 'POST',
			data: {
				type: 'data_use',
				Tb_index:'<?php echo $_GET['Tb_index'];?>'
			},
		    success: function (data) {
		    	var an_data=data.split('|');
		    	var data_name=an_data[0].split(',');
		    	var data_num=an_data[1].split(',');
		    	data_name.splice(0,0,'x');
		    	data_num.splice(0,0,'使用人數');
                
		    	an.load({
                  columns:[data_name, data_num]
		    	});
		    }
		});
	}
    


</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>

