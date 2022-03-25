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

    #an_completion .an_com_div .img{ height:135px;}
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
        
         <input name="admin_per" type="hidden" value="<?php echo $_SESSION['admin_per'];?>">
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
           <li><a class="fancybox default_btn" data-fancybox-type="iframe" href="../case_url/catch_web.php?Tb_index=<?php echo $_GET['Tb_index'];?>"><i class="fa fa-globe"></i>網址</a></li>
       </ul>
       
       <div class="tab-content">

       <div id="all" class="tab-pane active">
                       <div class="panel-body">
                          <div class="an_list_div">
                           <h2>全時段分析 </h2>
                           <div  class="col-lg-12">
                            <div id="search_date_div" class="search_c" >
                              <p>
                                <span>篩選時間：</span>
                                <input type="text" id="an_StartDate" value=<?php echo $_GET['an_StartDate'];?>> ~ <input type="text" id="an_EndDate" value="<?php echo $_GET['an_EndDate'];?>">
                              </p>
                            </div>
                           </div>
                           <?php
                            //################################# DEMO #################################
                            if($_COOKIE['admin_index']=='admin2020040610512274'){
                               echo'  <input type="hidden" name="an_StartDate" value="2019-03-01">
                                      <input type="hidden" name="an_EndDate" value="2019-03-31">
                                      <input type="hidden" name="com_StartDate" value="">
                                      <input type="hidden" name="com_EndDate" value="">';
                            }
                            //################################# 正常 #################################
                            else{
                              echo'  <input type="hidden" name="an_StartDate" value="">
                                      <input type="hidden" name="an_EndDate" value="">
                                      <input type="hidden" name="com_StartDate" value="">
                                      <input type="hidden" name="com_EndDate" value="">';
                            }
                           ?>
                          
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
                                  <div class="ibox-content" style="overflow-x: auto;">
                                      <div id="date_use">
                                          
                                      </div>
                                  </div>
                                  <div class="date_use_legend c3_legend no_num"></div>
                              </div>

              </div>
              <div id="user_data_div" class="col-lg-12 row">
                <div class="col-lg-4 col-xs-4">
                <div class="ibox float-e-margins one_week_h5 top_num">
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
                <div class="ibox float-e-margins one_month_h5 top_num">
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
                <div class="ibox float-e-margins all_user_h5 top_num">
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
                <div class="ibox float-e-margins one_week_h5 top_num">
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
                <div class="ibox float-e-margins one_month_h5 top_num">
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
                <div class="ibox float-e-margins all_user_h5 top_num">
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
              
              
            <div class="col-lg-12">
            <div class="col-lg-6">
                <div class="ibox float-e-margins">
                                  <div class="ibox-title">
                                      <h5>使用者性別(人數)
                                      </h5>
                                  </div>
                                  <div class="ibox-content">
                                      <div id="sex"></div>
                                      <div class="sex_legend c3_legend"></div>
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
            </div>
              
            
            <div class="col-lg-12">
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
    </div>
    
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

              <div class="col-lg-6">
                <div class="ibox float-e-margins">
                                   <div class="ibox-title">
                                       <h5>使用的功能鈕
                                       </h5>
                                   </div>
                                   <div class="ibox-content">
                                       <div id="tool_btn">
                                       </div>
                                       <div class="tool_btn_legend c3_legend"></div>
                                   </div>
                               </div>

              </div>
         </div>
      </div>
    </div>
    </div>

    
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
                                        <div class="month_src_num_legend c3_legend"></div>
                                        <!-- 詳細表單 -->
                                         <div class="text-right">
                                           <!-- <button id="month_src_detail" class="btn btn-info">展開詳細資料</button> -->

                                             <div class="month_src_tb text-left" >
                                               <table id="month_src_tb" class="table table-hover ">
                                                 <thead id="title_tb">
                                                 <tr>
                                                     <th>流量來源</th>
                                                     <th class="text-right">人數</th>
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
                                        <div class="src_num_legend c3_legend"></div>
                                        <!-- 詳細表單 -->
                                         <div class="text-right">
                                           <!-- <button id="src_detail" class="btn btn-info">展開詳細資料</button> -->

                                             <div class="src_tb text-left" >
                                               <table id="all_src_tb" class="table table-hover ">
                                                 <thead id="title_tb">
                                                 <tr>
                                                     <th>流量來源</th>
                                                     <th class="num_name1 text-right">人數</th>
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

              
              <?php if ($_SESSION['admin_per']=='admin' || $_SESSION['admin_per']=='group2020040610522078') {?>
               
                <div id="an_completion_div" class="col-lg-12">
                  <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h5>網頁瀏覽程度</h5>
                                    </div>
                                    <div class="ibox-content">
                                        <div id="an_completion" class="row">
                                         
                                          
                                        </div>
                                    </div>
                  </div>
                </div>
              
              <?php }?>


              <!-- <div id="" class="col-lg-12">
                  <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h5>預約賞屋來信 <button type="button" class="btn btn-success slide_mail_btn">展開</button></h5>
                                    </div>
                                    <div class="ibox-content">
                                        <div id="an_mail_div" class="row" style="display:none;">                              
                                          <div class="table-responsive">
					                                 <table class="table no-margin">
					                                 	<thead>
					                                 		<tr>
				                                 				<th>#</th>
					                                 			<th>時間</th>
				                                 				<th>姓名</th>
					                                 			<th>電話</th>
								                                <th>E-mail</th>
							                                </tr>
						                                </thead>
                                            <tbody>
                                              
                                              
                                            </tbody>
					                                </table>
				                                </div> 
                                     </div>
                                 </div>
                   </div>
                </div> -->





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

<!-- 分析JS -->
<script src="../../js/an.js?1"></script>
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


  /*-- 分析表 --*/
  var data_sex, data_old, data_media, data_tool_btn, data_src_num, data_month_src_num, data_timeOnSite, data_use;


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
          data_sex= c3.generate({
                     bindto: '#sex',
                     data:{
                         x:'x',
                         columns: [
                         ],
                         colors:{
                              男性: '#2196f3',
                              女性: '#ff6258',
                              未知: '#c3c3c3',
                          },
                         type : 'pie',
                         labels: true
                     },
                     legend: {
                       show: false
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
        data_old= c3.generate({
                      bindto: '#old',
                      data:{
                         x:'x',
                          columns: [
                              ['x'],['使用人數']
                          ],
                          colors:{
                              使用人數: '#2196f3'
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
                      size:{
                          height:400
                      },
                      padding: {
                         bottom: 20 
                      }
                  });
        ajax_old(data_old, an_StartDate, an_EndDate, com_StartDate, com_EndDate);


        //使用媒體
       data_media= c3.generate({
                bindto: '#media',
                data:{
                   x:'x',
                    columns: [
                        
                    ],
                    colors:{
                              使用人數: '#2196f3'
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
                size:{
                    height:500
                },
                padding: {
                         bottom: 20 
                      }
            });
        ajax_media(data_media, an_StartDate, an_EndDate, com_StartDate, com_EndDate);


      //使用功能鈕
      data_tool_btn= c3.generate({
                bindto: '#tool_btn',
                data:{
                    x:'x',
                    columns: [

                    ],
                    
                    type : 'donut',
                    labels: true
                },
                legend: {
                  show: false
                },
                donut: {
                  label: {
                          format: function (value, ratio, id) {
                          return value+"人";
                         }
                        },
                  title: '使用的功能鈕'
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
                    height:400
                },
                padding: {
                        
                         bottom: 20 
                      }
            });
      ajax_tool_btn(data_tool_btn, an_StartDate, an_EndDate, com_StartDate, com_EndDate);


      //流量來源
      data_src_num= c3.generate({
                bindto: '#src_num',
                data:{
                    x:'x',
                    columns: [


                    ],
                    type : 'pie',
                    labels: true
                },
                legend: {
                  show: false
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
                    height:400
                },
                padding: {
                         bottom: 20 
                      }
      });
      ajax_src_num(data_src_num, an_StartDate, an_EndDate, com_StartDate, com_EndDate);
      //-- 詳細資料 --
      ajax_src_num_d(an_StartDate, an_EndDate, com_StartDate, com_EndDate);


      //月流量來源
      data_month_src_num= c3.generate({
                bindto: '#month_src_num',
                data:{
                    columns: [


                    ],
                    type : 'pie'
                },
                legend: {
                  show: false
                },
                pie: {
                label: {
                          format: function (value, ratio, id) {
                          return value+"人";
                         }
                       }
                  },
                size:{
                    height:400
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
      data_timeOnSite= c3.generate({
                      bindto: '#timeOnSite',
                      data:{
                         x:'x',
                          columns: [
                              ['x'],['停留時間(分鐘)']
                          ],
                          colors:{
                              '停留時間(分鐘)': '#2196f3'
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
      data_use= c3.generate({
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
                         right:5,
                         bottom: 20 
                      }
                });
         ajax_data_use(data_use, an_StartDate, an_EndDate, com_StartDate, com_EndDate);

   

   //-- 網頁瀏覽程度 --
   an_completion(an_StartDate, an_EndDate, com_StartDate, com_EndDate);


   ajax_max_week_user (data_use,an_StartDate, an_EndDate, com_StartDate, com_EndDate);
   ajax_min_month_user (data_use,an_StartDate, an_EndDate, com_StartDate, com_EndDate);
   ajax_all_user (an_StartDate, an_EndDate, com_StartDate, com_EndDate);

   //-- 來信 --
   //an_mail(an_StartDate, an_EndDate);





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

    }); //JQUERY END

</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>