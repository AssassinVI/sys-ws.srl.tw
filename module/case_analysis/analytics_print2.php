<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<link rel="stylesheet" href="../../css/an_style.css">
<style>
  .sidebar-collapse, 
  .navbar-static-top, 
  #ph_tabs_btn,
  .tabs-container .nav-tabs.slideUp li.active>.del_tag,
  .footer{display: none;}
  #page-wrapper{ margin:0;}
    #date_use{width:100%;}
    #top_div{margin:0;}
    .user_d_div{padding-top:5px; padding-bottom:5px;}
    h2.text-primary{margin:0;}
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 

if ($_GET) {
 	$where=['Tb_index'=>$_GET['Tb_index']];
 	$row=pdo_select('SELECT * FROM google_analytics WHERE Tb_index=:Tb_index', $where);	

  $row_name=pdo_select("SELECT aTitle FROM build_case WHERE Tb_index=:Tb_index", $where);
  
  $row_week=pdo_select('SELECT SUM(one_user) as total FROM an_user WHERE case_id=:Tb_index AND date>=DATE_ADD( CURDATE( ) , INTERVAL -7 DAY ) GROUP BY case_id', $where);
  $row_month=pdo_select('SELECT SUM(one_user) as total FROM an_user WHERE case_id=:Tb_index AND date>=DATE_ADD( CURDATE( ) , INTERVAL -1 MONTH ) GROUP BY case_id', $where);
  $row_all=pdo_select('SELECT SUM(one_user) as total FROM an_user WHERE case_id=:Tb_index GROUP BY case_id', $where);
}
?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
    <div class="col-lg-12">
      <h2 class="text-primary">GOOGLE分析 列表 - <?php echo $row_name['aTitle'];?></h2>
      <p>本頁面列出各建案常用分析圖表</p>
       <div class="new_div">
         
         <!-- <a id="html_pic_btn" href="javascript:;" class="btn btn-default"><i class="fa fa-print"></i> 快照</a> -->
          
      </div>
    </div>

   <?php 
     if ($_SESSION['admin_per']=='admin' || $_SESSION['admin_per']=='group2020040610522078') {
   ?>

   
   <div class="col-lg-12 ">
   <div class="tabs-container">
       <ul id="tabs_list" class="nav nav-tabs slideUp">
         <li class="active"><a data-toggle="tab" href="#all">全時段分析</a></li>
          <?php
            $row_tag=$new_pdo->select("SELECT Tb_index, tab_name, an_StartDate, an_EndDate, com_StartDate, com_EndDate 
                                   FROM an_tab 
                                   WHERE case_id=:case_id AND OnLineOrNot=1
                                   ORDER BY Tb_index",
                                  ['case_id'=>$_GET['Tb_index']]);
            $x=1;
            foreach ($row_tag as $tag) {

              // $active=$x==1 ? 'active':'';
                echo '<li class=""><a data-toggle="tab" href="#'.$tag['Tb_index'].'"> '.$tag['tab_name'].'</a> <button tag_id="'.$tag['Tb_index'].'" tag_name="'.$tag['tab_name'].'" class="del_tag btn btn-danger no-print"  type="button">x</button></li>';

              $x++;
            }
          ?>

           <li class=""><a class="new_list_btn" data-fancybox-type="iframe" href="new_tag/index.html?<?php echo $_GET['Tb_index'];?>">＋新增</a></li>
           <li><a class="print_btn" href="javascript:;"><i class="fa fa-print"></i> 列印報表</a></li>
       </ul>
       
       <div class="tab-content">

       <div id="all"" class="tab-pane active">
                       <div class="panel-body">
                          <div class="an_list_div">
                           <h2>全時段分析 </h2>
                           <input type="hidden" name="an_StartDate" value="">
                           <input type="hidden" name="an_EndDate" value="">
                           <input type="hidden" name="com_StartDate" value="">
                           <input type="hidden" name="com_EndDate" value="">
                          </div>
                        </div>
        </div>

       <?php
       
            $x=1;
            foreach ($row_tag as $tag) {

              if($tag['com_StartDate']!='0000-00-00'){
                $com_txt='<div class="col-md-4 time_2_line">
                           <div>
                           <i class="color_b_2 fa fa-calendar"></i>  <span class="time_title"> 時間區間2<span>：</span></span>
                           <span class="time_span"><span>'.$tag['com_StartDate'].'</span><span> ~ </span><span>'.$tag['com_EndDate'].'</span></span>
                           <input type="hidden" name="com_StartDate" value="'.$tag['com_StartDate'].'">
                           <input type="hidden" name="com_EndDate" value="'.$tag['com_EndDate'].'">
                           </div>
                          </div>';
              }
              else{
                $com_txt='';
              }
              
              //$active=$x==1 ? 'active':'';
                echo '<div id="'.$tag['Tb_index'].'" class="tab-pane">
                       <div class="panel-body">
                        <div class="an_list_div">
                        <h2>'.$tag['tab_name'].' <button tag_id="'.$tag['Tb_index'].'" tag_name="'.$tag['tab_name'].'" type="button" class="del_tag_btn btn btn-danger">刪除</button></h2>
                         <form id="tool_an_bar" class="form-horizontal">
                           <div class="form-group">
                             <div class="col-md-1">
                               <h3 style="margin:0;">篩選依據：</h3>
                             </div>
                             <div class="col-md-4 time_1_line">
                               <div>
                                <i class="color_b_1 fa fa-calendar"></i><span class="time_title"> 時間區間1<span>：</span></span>
                                <span class="time_span"><span>'.$tag['an_StartDate'].'</span><span> ~ </span><span>'.$tag['an_EndDate'].'</span></span>
                                <input type="hidden" name="an_StartDate" value="'.$tag['an_StartDate'].'">
                                <input type="hidden" name="an_EndDate" value="'.$tag['an_EndDate'].'">
                               </div>
                             </div>
                             '.$com_txt.'

                           </div>
                          </form> 
                          </div> 
                          </div>
                       </div>';
              
              $x++;
            }
       
       ?>
          

       </div>
   </div>
   </div>

<?php }else{ ?>


 <div  class="col-lg-12">
  <div id="search_date_div">
    <p><span>篩選時間：</span><input type="text" id="an_StartDate"> ~ <input type="text" id="an_EndDate"> <button id="search_date_btn" class="btn btn-success" type="button">查詢</button> </p>
  </div>
 </div>


<?php }?>
    
    <div id="top_div" class="col-lg-12" >
     <div class="panel panel-default">
      <div class="panel-heading">
          使用者
      </div>
      <div class="panel-body">
        <div class="row">
              <div class="col-lg-12">
                <div class="ibox float-e-margins">
                                  <div class="ibox-title">
                                      <h5>每日使用人數
                                      </h5>
                                  </div>
                                  <div class="ibox-content">
                                      <div id="date_use">
                                          
                                      </div>
                                  </div>
                              </div>

              </div>
              <div id="user_data_div" class="col-lg-12 row">
                <div class="col-lg-4 col-xs-4">
                <div class="ibox float-e-margins one_week_h5">
                                  <div class="ibox-title">
                                      <h5>
                                       <i class="fa fa-user-o"></i>
                                       一周<span class="ph_none">瀏覽</span>人數
                                      </h5>
                                  </div>
                                  <div id="max_user" class="ibox-content user_num">
                                      <?php echo $row_week['total'].'人';?>
                                  </div>
                              </div>

              </div>
              <div class="col-lg-4 col-xs-4">
                <div class="ibox float-e-margins one_month_h5">
                                  <div class="ibox-title ">
                                      <h5 >
                                         <i class="fa fa-user-o"></i>
                                          一個月<span class="ph_none">瀏覽</span>人數
                                      </h5>
                                  </div>
                                  <div id="min_user" class="ibox-content user_num">
                                      <?php echo $row_month['total'].'人';?>
                                  </div>
                              </div>

              </div>
              <div class="col-lg-4 col-xs-4">
                <div class="ibox float-e-margins all_user_h5">
                                  <div class="ibox-title ">
                                      <h5>
                                         <i class="fa fa-user-o"></i>
                                         總瀏覽人數
                                      </h5>
                                  </div>
                                  <div id="all_user" class="ibox-content user_num">
                                      <?php echo $row_all['total'].'人';?>
                                  </div>
                              </div>

              </div>
            </div>
              


              <!-- 時間區間2 人數 -->
              <div id="user_data_c_div" class="col-lg-12 row" style="display:none;">
                <div class="col-lg-4 col-xs-4">
                <div class="ibox float-e-margins one_week_h5">
                                  <div class="ibox-title">
                                      <h5>
                                       <i class="fa fa-user-o"></i>
                                       
                                      </h5>
                                  </div>
                                  <div id="max_user" class="ibox-content user_num">
                                  </div>
                              </div>

              </div>
              <div class="col-lg-4 col-xs-4">
                <div class="ibox float-e-margins one_month_h5">
                                  <div class="ibox-title ">
                                      <h5 >
                                         <i class="fa fa-user-o"></i>
                                          
                                      </h5>
                                  </div>
                                  <div id="min_user" class="ibox-content user_num">
                                      
                                  </div>
                              </div>

              </div>
              <div class="col-lg-4 col-xs-4">
                <div class="ibox float-e-margins all_user_h5">
                                  <div class="ibox-title ">
                                      <h5>
                                         <i class="fa fa-user-o"></i>
                                         
                                      </h5>
                                  </div>
                                  <div id="all_user" class="ibox-content user_num">
                                      
                                  </div>
                              </div>

              </div>
              </div>
             
             <?php 
              if ($_SESSION['admin_per']=='admin' || $_SESSION['admin_per']=='group2020040610522078'){
             ?>

            
 
              <!-- 比較-最大瀏覽人數 -->
              <div id="user_max_d_div" class="col-lg-12  user_d_div">
               <div class="row">
                 <div class="col-xs-4">
                  <i class="fa fa-user"></i>
                  <p><span>最大</span>瀏覽人數</p>
                </div>
                <div class="col-xs-4">
                  <p>58,974</p>
                  <span>2020-02-12</span>
                </div>
                <div class="col-xs-4">
                  <p>58,974</p>
                  <span>2020-02-12</span>
                </div>
               </div>
               
              </div>

              <!-- 比較-最小瀏覽人數 -->
              <div id="user_min_d_div" class="col-lg-12  user_d_div">
               <div class="row">
                  <div class="col-xs-4">
                   <i class="fa fa-user"></i>
                   <p><span>最小</span>瀏覽人數</p>
                  </div>
                  <div class="col-xs-4">
                   <p>58,974</p>
                   <span>2020-02-12</span>
                  </div>
                  <div class="col-xs-4">
                   <p>58,974</p>
                   <span>2020-02-12</span>
                  </div>
               </div>
              </div>

              <!-- 比較-總瀏覽人數 -->
              <div id="user_all_d_div" class="col-lg-12 user_d_div">
               <div class="row">
                  <div class="col-xs-4">
                   <i class="fa fa-user"></i>
                   <p><span>全部</span>瀏覽人數</p>
                  </div>
                  <div class="col-xs-4">
                   <p>58,974</p>
                  </div>
                  <div class="col-xs-4">
                   <p>58,974</p>
                  </div>
               </div>
              </div>

             <?php
              }
             ?>


              <!-- 段行 -->
              <div style='page-break-after:always'></div>
              
              

              <div class="col-lg-6">
                <div class="ibox float-e-margins">
                                  <div class="ibox-title">
                                      <h5>使用者性別(人數)
                                      </h5>
                                  </div>
                                  <div class="ibox-content">
                                      <div id="sex"></div>
                                      <div class="c3_legend"></div>
                                  </div>
                              </div>

              </div>

              <div class="col-lg-6">
                <div class="ibox float-e-margins">
                                  <div class="ibox-title">
                                      <h5>使用者年齡(人數)
                                      </h5>
                                  </div>
                                  <div class="ibox-content">
                                     <div id="old"></div>
                                  </div>
                              </div>

              </div>


              <!-- 段行 -->
              <div style='page-break-after:always'></div>


                <div class="col-lg-6">
                  <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h5>地區使用人數
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


                <div class="col-lg-6">
                <div class="ibox float-e-margins">
                                  <div class="ibox-title">
                                      <h5>各年齡層停留網站時間(分鐘)
                                      </h5>
                                  </div>
                                  <div class="ibox-content">
                                      <div id="timeOnSite">
                                          
                                      </div>
                                  </div>
                              </div>

              </div>
        </div>
      </div>
     </div>
    </div>



   <!-- 段行 -->
   <div style='page-break-after:always'></div>


    
    <div class="col-lg-12">
    <div class="panel panel-default">
      <div class="panel-heading">
          媒體
      </div>
      <div class="panel-body">
         <div class="row">
              <div class="col-lg-6">
                <div class="ibox float-e-margins">
                                   <div class="ibox-title">
                                       <h5>使用的媒體
                                       </h5>
                                   </div>
                                   <div class="ibox-content">
                                       <div id="media"></div>
                                   </div>
                               </div>

              </div>


        
   <!-- 段行 -->
   <div style='page-break-after:always'></div>


              <div class="col-lg-6">
                <div class="ibox float-e-margins">
                                   <div class="ibox-title">
                                       <h5>使用的功能鈕
                                       </h5>
                                   </div>
                                   <div class="ibox-content">
                                       <div id="tool_btn">
                                           
                                       </div>
                                   </div>
                               </div>

              </div>
         </div>
      </div>
    </div>
    </div>


    <!-- 段行 -->
   <div style='page-break-after:always'></div>

    
    <div class="col-lg-12">
      <div class="panel panel-default">
        <div class="panel-heading">
           流量
        </div>
        <div class="panel-body">
          <div class="row">
                <div id="month_src_div" class="col-lg-6" >
                  <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h5><?php echo date('n');?>月流量來源
                                        </h5>
                                        
                                    </div>
                                    <div class="ibox-content">
                                        <div id="month_src_num">
                                            
                                        </div>
                                        <!-- 詳細表單 -->
                                         <div class="text-right">
                                           <!-- <button id="month_src_detail" class="btn btn-info">展開詳細資料</button> -->

                                             <div class="month_src_tb text-left" >
                                               <table id="month_src_tb" class="table table-hover ">
                                                 <thead id="title_tb">
                                                 <tr>
                                                     <th>流量來源</th>
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

                </div>

                <div id="all_src_div" class="col-lg-6">
                  <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h5>總流量來源
                                        </h5>
                                        
                                    </div>
                                    <div class="ibox-content">
                                        <div id="src_num">
                                            
                                        </div>
                                        <!-- 詳細表單 -->
                                         <div class="text-right">
                                           <!-- <button id="src_detail" class="btn btn-info">展開詳細資料</button> -->

                                             <div class="src_tb text-left" >
                                               <table id="all_src_tb" class="table table-hover ">
                                                 <thead id="title_tb">
                                                 <tr>
                                                     <th>流量來源</th>
                                                     <th class="num_name1">人數</th>
                                                 </tr>
                                                 </thead>
                                                 <tbody id="com_tb">
                                                 </tbody>
                                             </table>
                                           </div>
                                         </div>

                                    </div>
                                </div>

                </div>
          </div>
        </div>
      </div>
    </div>
		

	</div>

  <!-- 手機標籤按鈕 -->
  <?php 
    if ($_SESSION['admin_per']=='admin' || $_SESSION['admin_per']=='group2020040610522078'){
       echo '<a id="ph_tabs_btn" class="" href="javascript:;"><i class="fa fa-align-justify"></i></a>';
    }
  ?>
  

</div><!-- /#page-content -->

<?php  include("../../core/page/footer01.php");//載入頁面footer02.php?>
<script type="text/javascript" src="../../js/plugins/jsPDF/jspdf.min.js"></script>
<script type="text/javascript">
  
  //-- 建案ID --
  var search=location.search.split('&');
  var Tb_index=search[1].split('=');
      Tb_index=Tb_index[1];

    var tag_id=search[2].split('=');
    tag_id=tag_id[1];
    var an_StartDate=search[3].split('=');
    an_StartDate=an_StartDate[1];
    var an_EndDate=search[4].split('=');
    an_EndDate=an_EndDate[1];
    var com_StartDate=search[5].split('=');
    com_StartDate=com_StartDate[1];
    var com_EndDate=search[6].split('=');
    com_EndDate=com_EndDate[1];


	$(document).ready(function() {

        //-- 列印 --
        setTimeout ("window.print()" , 2000);
        setTimeout(() => {
          window.opener=null;
          window.close();
        }, 2500);


        $('#tabs_list li').removeClass('active');
       $('#tabs_list li [tag_id="'+tag_id+'"]').parent().addClass('active');
       $('.tab-content #'+tag_id).addClass('active');


      if(com_StartDate==''){
         $('#user_data_c_div').css('display','none');
       }
       else{
         $('#user_data_c_div').css('display','block');
       }
       
       
       //-- 手機 --
       if($(window).width()<=768){
         $('.tabs-container .nav-tabs').addClass('slideUp');
         //-- 選擇比較分析 --
         console.log(com_StartDate);
         if(com_StartDate!='' && com_StartDate!=undefined){
           $('.user_d_div').css('display','block');
           $('#user_data_div').css('display','none');
           $('#user_data_c_div').css('display','none');
           $('#tool_an_bar .time_1_line').removeClass('not_d_time');
         }
         else{
           $('.user_d_div').css('display','none');
           $('#user_data_div').css('display','block');
           $('#tool_an_bar .time_1_line').addClass('not_d_time');
         }
       }

    





    //------------------ 篩選日期 -----------------
		    var from= $('#an_StartDate' ).datepicker({
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

		   var to= $('#an_EndDate').datepicker({
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

		    if ($('#an_StartDate' ).val()!='') {
		      to.datepicker( "option", "minDate", $('#an_StartDate' ).val());
		      $('#an_StartDate' ).val();
		    }

		    if ($('#an_EndDate' ).val()!='') {
		      from.datepicker( "option", "maxDate", $('#an_EndDate' ).val());
		      $('#an_EndDate' ).val();
		    }

  
    
        
    //=========================================== 圖表樣式 ====================================

         //使用者性別
         var data_sex= c3.generate({
                     bindto: '#sex',
                     data:{
                         x:'x',
                         columns: [
                         ],
                         colors:{
                              男性: '#2196f3',
                              女性: '#ff6258',
                          },
                         type : 'pie',
                         labels: true
                     },
                     pie: {
                     label: {
                               format: function (value, ratio, id) {
                               return value+"人";
                              }
                            }
                       },
                      axis:{
                         x:{
                           type:'category'
                         },
                         y:{
                           show: false
                         }
                      },
                      padding: {
                         bottom: 20 
                      }
                 });
         ajax_sex(data_sex, an_StartDate, an_EndDate, com_StartDate, com_EndDate);

    
        //使用者年齡
        var data_old= c3.generate({
                      bindto: '#old',
                      data:{
                         x:'x',
                          columns: [
                              ['x'],['使用人數']
                          ],
                          
                          type: 'bar',
                          labels: true
                      },
                      axis:{

                         x:{
                           type:'category'
                         },
                         y:{
                           show: false
                         }
                      },
                      padding: {
                         bottom: 20 
                      }
                  });
        ajax_old(data_old, an_StartDate, an_EndDate, com_StartDate, com_EndDate);


        //使用媒體
       var data_media= c3.generate({
                bindto: '#media',
                data:{
                   x:'x',
                    columns: [
                        
                    ],
                    
                    type: 'bar',
                    labels: true
                },
                axis:{
                   x:{
                     type:'category'
                   },
                   y:{
                           show: false
                   }
                },
                size:{
                    height:500
                },
                padding: {
                         bottom: 20 
                      }
            });
        ajax_media(data_media, an_StartDate, an_EndDate, com_StartDate, com_EndDate);


      //使用功能鈕
      var data_tool_btn= c3.generate({
                bindto: '#tool_btn',
                data:{
                    x:'x',
                    columns: [

                    ],
                    type : 'pie',
                    labels: true
                },
                pie: {
                label: {
                          format: function (value, ratio, id) {
                          return value+"人";
                         }
                       }
                },
                axis:{
                         x:{
                           type:'category'
                         },
                         y:{
                           show: false
                         }
                },
                size:{
                    height:500
                },
                padding: {
                        
                         bottom: 20 
                      }
            });
      ajax_tool_btn(data_tool_btn, an_StartDate, an_EndDate, com_StartDate, com_EndDate);


      //流量來源
      var data_src_num= c3.generate({
                bindto: '#src_num',
                data:{
                    x:'x',
                    columns: [


                    ],
                    type : 'pie',
                    labels: true
                },
                pie: {
                label: {
                          format: function (value, ratio, id) {
                          return value+"人";
                         }
                       }
                },
                axis:{
                         x:{
                           type:'category'
                         },
                         y:{
                           show: false
                         }
                },
                size:{
                    height:450
                },
                padding: {
                         bottom: 20 
                      }
      });
      ajax_src_num(data_src_num, an_StartDate, an_EndDate, com_StartDate, com_EndDate);
      //-- 詳細資料 --
      ajax_src_num_d(an_StartDate, an_EndDate, com_StartDate, com_EndDate);


      //月流量來源
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
                },
                padding: {
                         bottom: 20 
                      }
      });
      ajax_month_src_num(data_month_src_num);
      //-- 月詳細資料 --
      ajax_month_src_num_d();

      //地區使用人數
      ajax_city(an_StartDate, an_EndDate, com_StartDate, com_EndDate);

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
                          },
                          y:{
                           show: false
                         }
                      },
                      padding: {
                         bottom: 20 
                      }
                  });
       ajax_timeOnSite(data_timeOnSite, an_StartDate, an_EndDate, com_StartDate, com_EndDate);


      //每日使用人數
      var data_use= c3.generate({
                    bindto: '#date_use',
                    padding: {
                        right: 10,
                    },
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
                              
                              count:4,
                              format: '%m-%d'
                          }
                       }
                    },
                    padding: {
                         right:10,
                         bottom: 20 
                      }
                });
         ajax_data_use(data_use, an_StartDate, an_EndDate, com_StartDate, com_EndDate);


        ajax_max_week_user (data_use,an_StartDate, an_EndDate, com_StartDate, com_EndDate);
        ajax_min_month_user (data_use,an_StartDate, an_EndDate, com_StartDate, com_EndDate);
        ajax_all_user (an_StartDate, an_EndDate, com_StartDate, com_EndDate);


        




    //------------- 日期區間 來源流量 ---------------
    $('.update_src_num').click(function(event) {
      
      $.ajax({
        url: '../../system/google_an/google_an_update.php',
        type: 'POST',
        data: {
          type: 'date_src_num',
          Tb_index: Tb_index,
          StartDate: $('[name="src_num_s_date"]').val(),
          EndDate: $('[name="src_num_e_date"]').val()
        },
        success:function (data) {
          ajax_src_num(data_src_num);
        }
      });

    });





    //--  --



 


    }); //JQUERY END


  //=========================================== 撈取資料 ====================================

  //使用者性別
	function ajax_sex(an, an_s_date, an_e_date, com_s_date, com_e_date) {
		$.ajax({
			url: 'analytics_ajax.php',
			type: 'POST',
			data: {
				type: 'sex',
				Tb_index: Tb_index,
        an_StartDate: an_s_date,
        an_EndDate: an_e_date,
        com_StartDate: com_s_date,
        com_EndDate: com_e_date
			},
		    success: function (data) {

		    	var an_data=data.split('|');
		    	var data_name=an_data[0].split(',');
		    	var data_num=an_data[1].split(',');
          
          
          //-- 時間區間2 --
          if(an_data[2]!=undefined){

            data_name=['x', '女性', '男性'];
          if(an_s_date==''){
            data_num.splice(0,0,'使用人數');
          }
          else{
            data_num.splice(0,0,'時間區間1');
          }

            var data_c_name=an_data[2].split(',');
		      	var data_c_num=an_data[3].split(',');

            data_c_name.splice(0,0,'x');
		       	data_c_num.splice(0,0,'時間區間2');
          }



          if(an_data[1]==''){
            an.load({
                  unload:true
		      	});
          }
          else{
            
            an.unload();
 
            //-- 時間區間2 --
             if(an_data[2]!=undefined){

               var columns_arr=[data_name, data_num];
               columns_arr.push(data_c_num);
               
               setTimeout(() => {
                 an.load({
                  columns: columns_arr,
                  type: 'bar',
                  colors:{
                              '時間區間1': '#2196f3',
                              '時間區間2': '#ff6258',
                          }
		         	   });

               }, 500);
             }
             else{

                var columns_arr=[
                   ['女性', data_num[0]],
                   ['男性', data_num[1]],
                   ['x', '女性', '男性']
                  ];

                setTimeout(() => {
                  an.load({
                   columns: columns_arr,
                   type: 'pie'
		            	});
                }, 500);
                
             }


          }

		    	
		    }
		});
	}
    
  
  //使用者年齡
	function ajax_old(an, an_s_date, an_e_date, com_s_date, com_e_date) {
		$.ajax({
			url: 'analytics_ajax.php',
			type: 'POST',
			data: {
				type: 'old',
				Tb_index: Tb_index,
        an_StartDate: an_s_date,
        an_EndDate: an_e_date,
        com_StartDate: com_s_date,
        com_EndDate: com_e_date
			},
		    success: function (data) {
		    	var an_data=data.split('|');
		    	var data_name=an_data[0].split(',');
		    	var data_num=an_data[1].split(',');

          data_name.splice(0,0,'x');
          if(an_s_date==''){
            data_num.splice(0,0,'使用人數');
          }
          else{
            data_num.splice(0,0,'時間區間1');
          }
		    	

          //-- 時間區間2 --
          if(an_data[2]!=undefined){
            var data_c_name=an_data[2].split(',');
		      	var data_c_num=an_data[3].split(',');

            data_c_name.splice(0,0,'x');
		       	data_c_num.splice(0,0,'時間區間2');
          }

          
          if(an_data[1]==''){
            an.load({
                  unload:true
		      	});
          }
          else{
          
          var columns_arr=[data_name, data_num];

          //-- 時間區間2 --
          if(an_data[2]!=undefined){
            columns_arr.push(data_c_num);
          }
          
          an.unload();

          setTimeout(() => {
            an.load({
              columns : columns_arr,
              colors:{
                              '時間區間1': '#2196f3',
                              '時間區間2': '#ff6258',
                          }
		        });
          }, 500);
          
         }

		   }
		});
	}

	 //使用媒體
	function ajax_media(an, an_s_date, an_e_date, com_s_date, com_e_date) {
		$.ajax({
			url: 'analytics_ajax.php',
			type: 'POST',
			data: {
				type: 'media',
				Tb_index: Tb_index,
        an_StartDate: an_s_date,
        an_EndDate: an_e_date,
        com_StartDate: com_s_date,
        com_EndDate: com_e_date
			},
		    success: function (data) {
          
		    	var an_data=data.split('|');
		    	//var data_name=an_data[0].split(',');
		    	var data_num=an_data[1].split(',');
          //data_name.splice(0,0,'x');
          if(an_s_date==''){
            data_num.splice(0,0,'使用人數');
          }
          else{
            data_num.splice(0,0,'時間區間1');
          }
		    	

          //-- 時間區間2 --
          if(an_data[2]!=undefined){
            
            var data_c_name=an_data[2].split(',');
		      	var data_c_num=an_data[3].split(',');

            data_c_name.splice(0,0,'x');
		       	data_c_num.splice(0,0,'時間區間2');
          }

          if(an_data[1]==''){
            an.load({
                  unload:true
		      	});
          }
          else{

            var columns_arr=[['x', '桌機', '手機','平板'], data_num];

            //-- 時間區間2 --
            if(an_data[2]!=undefined){
              columns_arr.push(data_c_num);
            }

            an.unload();
            setTimeout(() => {
              an.load({
                  columns: columns_arr,
                  colors:{
                              '時間區間1': '#2196f3',
                              '時間區間2': '#ff6258',
                          }
		    	    });
            }, 500);
            
          }
		    }
		});
	}

	 //使用功能鈕
	function ajax_tool_btn(an, an_s_date, an_e_date, com_s_date, com_e_date) {
		$.ajax({
			url: 'analytics_ajax.php',
			type: 'POST',
			data: {
				type: 'tool_btn',
				Tb_index: Tb_index,
        an_StartDate: an_s_date,
        an_EndDate: an_e_date,
        com_StartDate: com_s_date,
        com_EndDate: com_e_date
			},
		    success: function (data) {

          
          an.unload();

		    	var an_data=data.split('|');
		    	var data_name=an_data[0].split(',');
		    	var data_num=an_data[1].split(',');

          //-- 時間區間2 --
          if(an_data[2]!=undefined){

             data_name.splice(0,0,'x');
             if(an_s_date==''){
               data_num.splice(0,0,'使用人數');
             }
             else{
               data_num.splice(0,0,'時間區間1');
             }

            var data_c_name=an_data[2].split(',');
		      	var data_c_num=an_data[3].split(',');

            data_c_name.splice(0,0,'x');
		       	data_c_num.splice(0,0,'時間區間2');
          }
          

          if(an_data[1]==''){
            an.load({
              unload:true
		      	});
          }
          else{
             var data_all=[];
             var data_x=['x'];
             var data_num_new=data_name.length > 5 ? 5:data_name.length;
		    	   for (var i = 0; i < data_num_new; i++) {
		    	     data_all.push([data_name[i], data_num[i]]);
               data_x.push(data_name[i]);
		         }
             data_all.push(data_x);


             //-- 時間區間2 --
             if(an_data[2]!=undefined){

               var columns_arr=[data_name, data_num];
               columns_arr.push(data_c_num);
               
               //-- 限制5個 --
               var data_arr_length=6;
               for (let i = 0; i < 3; i++) {
                 var arr_length=columns_arr[i].length;
                 var arr_cut_5=parseInt(arr_length)-data_arr_length;
                 if(arr_cut_5>0){
                   columns_arr[i].splice(data_arr_length,arr_cut_5);
                 }
               }

                // for (let i = 0; i < data_c_name.length; i++) {
                //   var data_index=data_name.indexOf(data_c_name[i]);
                //   if(data_index!=-1){
                //      data_all[data_index][2]=data_c_num[i];
                //   }
                //   else{
                //     data_all.push([data_c_name[i], 0, data_c_num[i]]);
                //   }
                // }

                // var data_x=['x','時間區間1', '時間區間2'];
                // data_all.push(data_x);

                //console.log(columns_arr);

                
                setTimeout(() => {
                  an.load({
                   columns: columns_arr,
                   colors:{
                              '時間區間1': '#2196f3',
                              '時間區間2': '#ff6258',
                          },
                   type: 'bar'
		              });
                }, 500);
             }
             else{
                
                setTimeout(() => {
                  an.load({
                   columns: data_all,
                   type: 'pie'
		              });
                }, 500);
             }

             //console.log(data_all);
          }
		    	
		    }
		});
	}



	//流量來源
	function ajax_src_num(an, an_s_date, an_e_date, com_s_date, com_e_date) {

    if(an_s_date!=''){
      //-- 隱藏月流量 --
      $('#all_src_div').removeClass('col-lg-6');
      $('#all_src_div').addClass('col-lg-12');
      $('#month_src_div').css('display','none');
    }
    else{
      //-- 顯示月流量 --
      $('#all_src_div').addClass('col-lg-6');
      $('#all_src_div').removeClass('col-lg-12');
      $('#month_src_div').css('display','block');
    }

		$.ajax({
			url: 'analytics_ajax.php',
			type: 'POST',
			data: {
				type: 'src_num',
				Tb_index: Tb_index,
        an_StartDate: an_s_date,
        an_EndDate: an_e_date,
        com_StartDate: com_s_date,
        com_EndDate: com_e_date
			},
		    success: function (data) {

		    	var an_data=data.split('|');
		    	var data_name=an_data[0].split(',');
		    	var data_num=an_data[1].split(',');

          //-- 時間區間2 --
          if(an_data[2]!=undefined){
            

            var data_c_name=an_data[2].split(',');
		      	var data_c_num=an_data[3].split(',');

          }

          // console.log(data_name);
          // console.log(data_num);
          // console.log(data_c_name);
          // console.log(data_c_num);
		    	  
            an.unload();
            
            //-- 無資料 --
            if(an_data[1]==''){
              an.load({
                unload:true
		        	});
            }
            //-- 有資料 --
            else{

              var show=1;
		    	    var data_all=[];
              var data_x=['x'];
              var total=0;
            
            
            data_number=data_name.length>5 ? 5:data_name.length;
		    		for (var i = 0; i < data_number; i++) {
              
              var new_data = src_ch (data_name[i], data_num[i]);
               
               if(new_data[0]!==undefined){
                 data_all.push(new_data);
                 data_x.push(new_data[0]);
               }
		    		}

            data_all.push(data_x);



              
              //-- 時間區間2 --
              if(an_data[2]!=undefined){
                
                var data_all=[];
                var data_x=[];
                
                //-- 時間區間1 --
                var data_name_new=[];
                var data_number=data_name.length>5 ? 5:data_name.length;

		    		    for (var i = 0; i < data_number; i++) {
                  data_name_new.push(data_name[i]);
                  var new_data = src_ch (data_name[i], data_num[i]);
                   
                   if(new_data[0]!==undefined){
                     data_all.push(new_data[1]);
                     data_x.push(new_data[0]);
                   }
		    	    	}
                
                
                var data_c_all=[];

                //-- 時間區間1無而時間區間2有的來源 --
                var data_c_sp=[];
                var data_c_sp_name=[];
                var data_c_sp_num=[];

                for (let i = 0; i < data_c_name.length; i++) {
                     var data_index=data_name_new.indexOf(data_c_name[i]);
                     if(data_index!=-1){
                       data_c_all[data_index]=data_c_num[i];
                     }
                     //-- 時間區間1無而時間區間2有的來源 --
                     else{
                       var new_c_data = src_ch (data_c_name[i], data_c_num[i]);
                       if(new_c_data[0]!==undefined){
                          data_c_sp.push(new_c_data[1]);
                          data_c_sp_name.push(new_c_data[0]);
                          data_c_sp_num.push(i);
                       }
                     }
                }
                
                //-- 時間區間2(空值補0) --
                for (let i = 0; i < data_c_all.length; i++) {
                  data_c_all[i]=data_c_all[i]==undefined ? 0:data_c_all[i];
                }
                
                // console.log(data_c_sp_name);
                // console.log(data_c_sp_num);

                //-- 時間區間1無而時間區間2有的來源 (套入) --
                for (let j = 0; j < data_c_sp.length; j++) {

                  if(data_name.indexOf(data_c_sp_name[j])!=-1){
                    var data_inedx=data_name.indexOf(data_c_sp_name[j]);
                    data_all.splice(data_c_sp_num[j],0,data_num[data_inedx]);
                  }
                  else{
                    data_all.splice(data_c_sp_num[j],0,0);
                  }
                  
                  data_c_all.splice(data_c_sp_num[j],0,data_c_sp[j]);
                  data_x.splice(data_c_sp_num[j],0,data_c_sp_name[j]);
                }
                
                data_x.splice(0,0,'x');
                data_all.splice(0,0,'時間區間1');
                data_c_all.splice(0,0,'時間區間2');

                //-- 調整圖表顯示來源種類 --
                data_x=data_x.slice(0,6);
                // console.log(data_all);
                // console.log(data_c_all);

                
                var data_all_all=[];
                data_all_all.push(data_x);
                data_all_all.push(data_all);
                data_all_all.push(data_c_all);


                setTimeout(() => {
                an.load({
                 columns: data_all_all,
                 colors:{
                              '時間區間1': '#2196f3',
                              '時間區間2': '#ff6258',
                 },
                 type: 'bar'
		           	}); 
                }, 500);
              }
              else{
                setTimeout(() => {
                an.load({
                 columns: data_all,
                 type: 'pie'
		           	}); 
                }, 500);
              }
              
            }
            
            // console.log(data);

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
        Tb_index: Tb_index
      },
        success: function (data) {
          var an_data=data.split('|');
          var data_name=an_data[0].split(',');
          var data_num=an_data[1].split(',');
          var show=1;
          var data_all=[];
                var total=0;
                //var other_total=0;

          // for (var i = 0; i < data_num.length; i++) {
          //   total+=parseInt(data_num[i]);
          // }
          //   total=Math.round(total/data_num.length);
            //total=20;
            var new_data_num=data_name.length>5 ? 5:data_name.length;
            for (var i = 0; i < new_data_num; i++) {
                        
              if (data_name[i].search(/none/)>-1) {
                var find_name='直接連結';
                show=1;
              }
              else if(data_name[i].search(/organic/)>-1){
                var new_name=data_name[i].split('/');
                var find_name=new_name[0]+'搜尋';
                show=1;
              }
              else if(data_name[i].search(/referral/)>-1){
                var new_name=data_name[i].split('/');
                
                if(data_name[i].search(/m.facebook.com/)>-1){
                  var find_name='手機板FB推薦連結';
                  show=1;
                }
                else if(data_name[i].search(/l.facebook.com/)>-1){
                  show=0;
                }
                else if(data_name[i].search(/facebook.com/)>-1){
                  var find_name='電腦版FB推薦連結';
                  show=1;
                }
                else{
                  var find_name=new_name[0]+'推薦連結';
                  show=1;
                }
                
              }
              else if(data_name[i].search(/Campaigns/)>-1){
                var new_name=data_name[i].split('/');
                var find_name=new_name[0]+'google廣告';
                show=1;
              }
              else{
                var find_name=data_name[i];
                show=1;
              }
               
               if(show==1){
                 data_all.push([find_name, data_num[i]]);
               }
               
            
                      // if(other_total>0){
                      //   data_all.push(['其他', other_total]);
                      // }
            }
          
                an.load({
                     columns: data_all
          }); 

          // console.log(data_all);
        }
    });
  }



  //流量來源 詳細資料
  function ajax_src_num_d(an_s_date, an_e_date, com_s_date, com_e_date) {
    $.ajax({
      url: 'analytics_ajax.php',
      type: 'POST',
      data: {
        type: 'src_num',
        Tb_index: Tb_index,
        an_StartDate: an_s_date,
        an_EndDate: an_e_date,
        com_StartDate: com_s_date,
        com_EndDate: com_e_date
      },
        success: function (data) {
          
          $('.src_tb #com_tb').html('');
          
          var an_data=data.split('|');
          var data_name=an_data[0].split(',');
          var data_num=an_data[1].split(',');
          var show=1;
          

          //-- 時間區間2 --
          if(an_data[2]!=undefined){
            var data_c_name=an_data[2].split(',');
		      	var data_c_num=an_data[3].split(',');
          }
          else{
            var data_c_name=[];
          }

          
          if(data_name.length<=data_c_name.length){
             $('#all_src_tb .num_name1').html('時間區間1');
             if($('#all_src_tb .num_name2').length<1){
               $('#all_src_tb thead tr').append('<th class="num_name2">時間區間2</th>');
             }
             
             var data_total=data_c_name.length;
          }
          else{
             $('#all_src_tb .num_name1').html('人數');
             if($('#all_src_tb .num_name2').length>0){
                $('#all_src_tb .num_name2').remove();
             }
             var data_total=data_name.length;
          }


          for (var i = 0; i < data_total; i++) {


               //-- 比較 --
               if(an_data[2]!=undefined){
                 var new_c_data = src_ch (data_c_name[i], data_c_num[i]);
               }

        
                  //-- 比較 --
                  if(an_data[2]!=undefined){

                    var data_index= data_name.length<=data_c_name.length ? data_name.indexOf(data_c_name[i]) : data_c_name.indexOf(data_name[i]);
                    var data_all_name=data_name.length<=data_c_name.length ? data_c_name[i] : data_name[i];
                    var data_all_num=data_name.length<=data_c_name.length ? data_num[data_index] : data_num[i];
                    var data_all_c_num=data_name.length<=data_c_name.length ? data_c_num[i] : data_c_num[data_index];

                    if( data_all_num>10 || data_all_c_num>10 ){
                      if(data_index!=-1){
                         var new_data = src_ch (data_all_name, data_all_num);

                         if(new_data[0]!=undefined){
                          var txt='<tr><td>'+new_data[0]+'</td><td>'+new_data[1]+'</td><td>'+data_all_c_num+'</td></tr>';
                          $('.src_tb #com_tb').append(txt);
                         }
                     }
                     else{
                       var new_data = src_ch (data_all_name, data_all_num);

                       if(new_data[0]!=undefined){
                         var new_data_num = new_data[1]==undefined ? '0':new_data[1];
                         var txt='<tr><td>'+new_data[0]+'</td><td>'+new_data_num+'</td><td>'+data_all_c_num+'</td></tr>';
                         $('.src_tb #com_tb').append(txt);
                       }
                     }
                    }
                  }
                  else{
                    
                    var new_data = src_ch (data_name[i], data_num[i]);
                    if(data_num[i]>10 && new_data[0]!=undefined){
                      var txt='<tr><td>'+new_data[0]+'</td><td>'+new_data[1]+'</td></tr>';
                      $('.src_tb #com_tb').append(txt);
                    }
                  }
               
          }
        }
    });
  }


  //月流量來源 詳細資料
  function ajax_month_src_num_d() {
    $.ajax({
      url: 'analytics_ajax.php',
      type: 'POST',
      data: {
        type: 'month_src_num',
        Tb_index: Tb_index
      },
        success: function (data) {
          var an_data=data.split('|');
          var data_name=an_data[0].split(',');
          var data_num=an_data[1].split(',');
          var show=1;
          
          for (var i = 0; i < data_name.length; i++) {
            
            if (data_num[i]>10) {

              if (data_name[i].search(/none/)>-1) {
                var find_name='直接連結';
                show=1;
              }
              else if(data_name[i].search(/organic/)>-1){
                var new_name=data_name[i].split('/');
                var find_name=new_name[0]+'搜尋';
                show=1;
              }
              else if(data_name[i].search(/referral/)>-1){
                var new_name=data_name[i].split('/');

                if(data_name[i].search(/m.facebook.com/)>-1){
                  var find_name='手機板FB推薦連結';
                  show=1;
                }
                else if(data_name[i].search(/l.facebook.com/)>-1){
                  show=0;
                }
                else if(data_name[i].search(/facebook.com/)>-1){
                  var find_name='電腦版FB推薦連結';
                  show=1;
                }
                else{
                  var find_name=new_name[0]+'推薦連結';
                  show=1;
                }
                
              }
              else if(data_name[i].search(/Campaigns/)>-1){
                var new_name=data_name[i].split('/');
                var find_name=new_name[0]+'google廣告';
                show=1;
              }
              else{
                var find_name=data_name[i];
                show=1;
              }

              if(show==1){
                 var txt='<tr><td>'+find_name+'</td><td>'+data_num[i]+'</td></tr>';
                 $('.month_src_tb #com_tb').append(txt);
              }
              
            }
          }
        }
    });
  }


	//地區使用人數
	function ajax_city(an_s_date, an_e_date, com_s_date, com_e_date) {
		$.ajax({
			url: 'analytics_ajax.php',
			type: 'POST',
			data: {
				type: 'city',
				Tb_index: Tb_index,
        an_StartDate: an_s_date,
        an_EndDate: an_e_date,
        com_StartDate: com_s_date,
        com_EndDate: com_e_date
			},
		    success: function (data) {

          // console.log(data);
          $('#city #com_tb').html('');
		    	var an_data=data.split('|');
		    	var data_name=an_data[0].split(',');
		    	var data_num=an_data[1].split(',');

          //-- 時間區間2 --
          if(an_data[2]!=undefined){
            var data_c_name=an_data[2].split(',');
		      	var data_c_num=an_data[3].split(',');
            
            $('#city #title_tb').html('<tr><th>地名</th><th>時間區間1</th><th>時間區間2</th></tr>');
          }
          else{
            var data_c_name=[];
          }


		    	for (var i = 0; i < data_name.length; i++) {
            
            //-- 時間區間2 --
            if(an_data[2]!=undefined && data_name.indexOf(data_c_name[i])!=-1){
              var txt='<tr><td>'+data_name[i].substr(0,3)+'</td><td>'+data_num[i]+'</td><td>'+data_c_num[i]+'</td></tr>';
            }
            else{
              var txt='<tr><td>'+data_name[i].substr(0,3)+'</td><td>'+data_num[i]+'</td></tr>';
            }
		    		
		    	   $('#city #com_tb').append(txt);
		    	}
		    }
		});
	}


	//齡層平均停留網站時間
	function ajax_timeOnSite(an, an_s_date, an_e_date, com_s_date, com_e_date) {
		$.ajax({
			url: 'analytics_ajax.php',
			type: 'POST',
			data: {
				type: 'timeOnSite',
				Tb_index: Tb_index,
        an_StartDate: an_s_date,
        an_EndDate: an_e_date,
        com_StartDate: com_s_date,
        com_EndDate: com_e_date
			},
		    success: function (data) {
          //console.log(data);
		    	var an_data=data.split('|');
		    	var data_name=an_data[0].split(',');
		    	var data_num=an_data[1].split(',');

          //-- 時間區間2 --
          if(an_data[2]!=undefined){
		      	var data_c_name=an_data[2].split(',');
		      	var data_c_num=an_data[3].split(',');
          }

		    	data_name.splice(0,0,'x');
          if(an_s_date==''){
            data_num.splice(0,0,'停留時間(分鐘)');
          }else{
            data_num.splice(0,0,'時間區間1');
          }
		    	

          //-- 時間區間2 --
          if(an_data[2]!=undefined){

              data_c_name.splice(0,0,'x');
              data_c_num.splice(0,0,'時間區間2');
          }

          if(an_data[1]==''){
            an.load({
               unload:true
		      	});
          }
          else{
            
            var columns_arr=[ data_name, data_num ];
            an.unload();

               //-- 時間區間2 --
               if(an_data[2]!=undefined){
                 columns_arr.push(data_c_num);

                 setTimeout(function () {
                  an.load({
                    columns:columns_arr,
                    colors:{
                              '時間區間1': '#2196f3',
                              '時間區間2': '#ff6258',
                          }
                  });
                 },500);
               }
               else{
                 setTimeout(function () {
                  an.load({
                    columns:columns_arr
                  });
                 },500);
               }
          }

		    	
		    }
		});
	}


	 //每日使用人數
	function ajax_data_use(an, an_s_date, an_e_date, com_s_date, com_e_date) {
		$.ajax({
			url: 'analytics_ajax.php',
			type: 'POST',
			data: {
				type: 'data_use',
				Tb_index:Tb_index,
        an_StartDate: an_s_date,
        an_EndDate: an_e_date,
        com_StartDate: com_s_date,
        com_EndDate: com_e_date
			},
		    success: function (data) {

		    	var an_data=data.split('|');
		    	var data_name=an_data[0].split(',');
		    	var data_num=an_data[1].split(',');
          
          //-- 時間區間2 --
          if(an_data[2]!=undefined){
		      	var data_c_name=an_data[2].split(',');
		      	var data_c_num=an_data[3].split(',');
          }
          
          
          if (data_name.length>30 && (an_s_date==undefined || an_s_date=='')) {
                      var d30_s_num=parseInt(data_name.length)-30;
                      var d30_e_num=parseInt(data_name.length);
                      // console.log('s:'+d30_s_num+',e:'+d30_e_num);
                      var d30_data_name=[];
                      var d30_data_num=[];
                      for (var i = d30_s_num; i < d30_e_num; i++) {
                        d30_data_name.push(data_name[i]);
                        d30_data_num.push(data_num[i]);
                      }
                      d30_data_name.splice(0,0,'x');
                      d30_data_num.splice(0,0,'使用人數');
                      
                      an.unload();
                      setTimeout(function () {
                        an.load({
                              columns:[d30_data_name, d30_data_num]
                        });
                      },500);
                      

          }
          else{
            data_name.splice(0,0,'x');
            data_num.splice(0,0,'時間區間1-使用人數');
            
            //-- 時間區間2 --
            if(an_data[2]!=undefined){
              data_c_name.splice(0,0,'x');
              data_c_num.splice(0,0,'時間區間2-使用人數');
            }

            if(an_data[1]==''){
               an.load({
                  unload:true
		      	   });
            }
            else{

               var columns_arr=[ data_name, data_num ];
               an.unload();
               //-- 時間區間2 --
               if(an_data[2]!=undefined){

                 columns_arr.push(data_c_num);
                 
                 setTimeout(function () {
                  
                  an.load({
                    columns:columns_arr,
                    colors:{
                              '時間區間1-使用人數': '#2196f3',
                              '時間區間2-使用人數': '#ff6258',
                          }
                  });
                 },500);
               }
               else{
                 setTimeout(function () {

                  an.load({
                    columns:columns_arr
                  });
                 },500);
               }

            }
            
          }
		    }
		});
	}


//-- 一週||最大使用人數 --
function ajax_max_week_user (an,an_s_date, an_e_date, com_s_date, com_e_date) { 

  an.xgrids.remove({class:'big_num'});

  $.ajax({
			url: 'analytics_ajax.php',
			type: 'POST',
			data: {
				type: 'max_user',
				Tb_index:Tb_index,
        an_StartDate: an_s_date,
        an_EndDate: an_e_date,
        com_StartDate: com_s_date,
        com_EndDate: com_e_date
	 },
   success:function (data) {

      if(an_s_date==''){
        $('#max_user').prev().find('h5').html('<i class="fa fa-user-o"></i> 一周人數');
        $('#max_user').html(data+'人');
      }
      else{

        var user_data=data.split(',');
        
        //-- 電腦 --
        if($(window).width()>768){
          $('#max_user').prev().find('h5').html('<i class="fa fa-user-o"></i> 時間區間1-最大瀏覽人數 <small>'+user_data[1]+'</small>');
          $('#max_user').html(user_data[0]+'人');
        }
        //-- 手機 --
        //-- 時間區間1 --
        else{
          $('#max_user').prev().find('h5').html('<i class="fa fa-user-o"></i>最大瀏覽人數');
          $('#max_user').html('<small>'+user_data[1]+'</small>'+user_data[0]+'人');
          $('#user_max_d_div div:nth-child(2) p').html(toCurrency(user_data[0]));
          $('#user_max_d_div div:nth-child(2) span').html(user_data[1]);
        }
        
        //-- 時間區間1 --
        if(com_s_date==undefined || com_s_date==''){

          var big_date=user_data[1].split('-');
          setTimeout(function () {
            an.xgrids.add([{value: big_date[0]+big_date[1]+big_date[2], text:'最大瀏覽人數', position: 'start', class:'big_num'}]);
          }, 1000);
        }

        //-- 時間區間2 --
        else{
        
          //-- 電腦 --
          if($(window).width()>768){
          $('#user_data_c_div #max_user').prev().find('h5').html('<i class="fa fa-user-o"></i> 最大瀏覽人數 <small>'+user_data[3]+'</small>');
          $('#user_data_c_div #max_user').html(user_data[2]+'人');
          }
          //-- 手機 --
          else{
            $('#user_data_c_div #max_user').prev().find('h5').html('<i class="fa fa-user-o"></i> 最大瀏覽人數');
            $('#user_data_c_div #max_user').html('<small>'+user_data[3]+'</small>'+user_data[2]+'人');
            $('#user_max_d_div div:nth-child(3) p').html(toCurrency(user_data[2]));
            $('#user_max_d_div div:nth-child(3) span').html(user_data[3]);
          }  
        }
        
        
        
        
      }
   }
});
}



//-- 一月||最小使用人數 --
function ajax_min_month_user (an, an_s_date, an_e_date, com_s_date, com_e_date) { 

  an.xgrids.remove({class:'small_num'});

  $.ajax({
			url: 'analytics_ajax.php',
			type: 'POST',
			data: {
				type: 'min_user',
				Tb_index:Tb_index,
        an_StartDate: an_s_date,
        an_EndDate: an_e_date,
        com_StartDate: com_s_date,
        com_EndDate: com_e_date
	 },
   success:function (data) {

    //  console.log(data);
      if(an_s_date==''){
        $('#min_user').prev().find('h5').html('<i class="fa fa-user-o"></i> 一個月人數');
        $('#min_user').html(data+'人');
      }
      else{

        var user_data=data.split(',');
      
        //-- 電腦 --
        if($(window).width()>768){
          $('#min_user').prev().find('h5').html('<i class="fa fa-user-o"></i> 時間區間1-最小瀏覽人數 <small>'+user_data[1]+'</small>');
          $('#min_user').html(user_data[0]+'人');
        }
        //-- 手機 --
        //-- 時間區間1 --
        else{
          $('#min_user').prev().find('h5').html('<i class="fa fa-user-o"></i>最小瀏覽人數');
          $('#min_user').html('<small>'+user_data[1]+'</small>'+user_data[0]+'人');
          $('#user_min_d_div div:nth-child(2) p').html(toCurrency(user_data[0]));
          $('#user_min_d_div div:nth-child(2) span').html(user_data[1]);
        }
       
        //-- 時間區間1 --
        if(com_s_date==undefined || com_s_date==''){
          var big_date=user_data[1].split('-');
          setTimeout(function () {
            an.xgrids.add([{value: big_date[0]+big_date[1]+big_date[2], text:'最小瀏覽人數', class:'small_num'}]);
          }, 1000);
        }

        //-- 時間區間2 --
        else{
          
          //-- 電腦 --
          if($(window).width()>768){
          $('#user_data_c_div #min_user').prev().find('h5').html('<i class="fa fa-user-o"></i> 最小瀏覽人數 <small>'+user_data[3]+'</small>');
          $('#user_data_c_div #min_user').html(user_data[2]+'人');
          }
          //-- 手機 --
          else{
            $('#user_data_c_div #min_user').prev().find('h5').html('<i class="fa fa-user-o"></i> 最小瀏覽人數');
            $('#user_data_c_div #min_user').html('<small>'+user_data[3]+'</small>'+user_data[2]+'人');
            $('#user_min_d_div div:nth-child(3) p').html(toCurrency(user_data[2]));
            $('#user_min_d_div div:nth-child(3) span').html(user_data[3]);
          }
        }
        
      }
   }
});
}



//-- 總使用人數 --
function ajax_all_user (an_s_date, an_e_date, com_s_date, com_e_date) { 

  $.ajax({
			url: 'analytics_ajax.php',
			type: 'POST',
			data: {
				type: 'all_user',
				Tb_index:Tb_index,
        an_StartDate: an_s_date,
        an_EndDate: an_e_date,
        com_StartDate: com_s_date,
        com_EndDate: com_e_date
	 },
   success:function (data) {
      if(com_s_date!=undefined){
        var data_arr=data.split(',');
        
        if($(window).width()>768){
          $('.all_user_h5 h5').html('<i class="fa fa-user-o"></i> 時間區間1-總瀏覽人數');
          $('#user_data_c_div .all_user_h5 h5').html('<i class="fa fa-user-o"></i> 時間區間2-總瀏覽人數');
          $('#all_user').html(data_arr[0]+'人');
          $('#user_data_c_div #all_user').html(data_arr[1]+'人');
        }
        else{
          //-- 時間區間1 --
          $('.all_user_h5 h5').html('<i class="fa fa-user-o"></i> 總瀏覽人數');
          //-- 時間區間2 --
          $('#user_data_c_div .all_user_h5 h5').html('<i class="fa fa-user-o"></i> 總瀏覽人數');
          $('#all_user').html(data_arr[0]+'人');
          $('#user_data_c_div #all_user').html(data_arr[1]+'人');
          $('#user_all_d_div div:nth-child(2) p').html(toCurrency(data_arr[0]));
          $('#user_all_d_div div:nth-child(3) p').html(toCurrency(data_arr[1]));
        }
      }
      else{
        $('.all_user_h5 h5').html('<i class="fa fa-user-o"></i> 總瀏覽人數');
        $('#all_user').html(data+'人');
      }
   }
});
}




//-- 流量來源辨識 --
function src_ch (data_name, data_num) { 
    
             if (data_name.search(/none/)>-1) {
		    				var find_name='直接連結';
                show=1;
		    			}
		    			else if(data_name.search(/organic/)>-1){
		    				var new_name=data_name.split('/');
		    				var find_name=new_name[0]+'搜尋';
                show=1;
		    			}
		    			else if(data_name.search(/referral/)>-1){

		    				var new_name=data_name.split('/');

                if(data_name.search(/m.facebook.com/)>-1){
                  var find_name='手機板FB推薦連結';
                  show=1;
                }
                else if(data_name.search(/l.facebook.com/)>-1){
                  show=0;
                }
                else if(data_name.search(/facebook.com/)>-1){
                  var find_name='電腦版FB推薦連結';
                  show=1;
                }
                else if(data_name.search(/tw.search.yahoo.com/)>-1){
                  show=0;
                }
                else{
                  var find_name=new_name[0]+'推薦連結';
                  show=1;
                }
		    				
		    			}
              else if(data_name.search(/(not set)/)>-1){
                  var find_name='無';
                  show=0;
              }
		    			else if(data_name.search(/Campaigns/)>-1){
		    				var new_name=data_name.split('/');
		    				var find_name=new_name[0]+'google廣告';
                show=1;
		    			}
		    			else{
		    				var find_name=data_name;
                show=1;
		    			}
		    		   
               if(show==1){
                 return [find_name, data_num];
               }
               else{
                 return [undefined, undefined];
               }
}


/*-- 千分位 -- */
function toCurrency(num){
    var parts = num.toString().split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    return parts.join('.');
}


</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>