<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<link rel="stylesheet" href="../../css/an_style.css?12">
<style>
 #timeOnSite_div{display: none;}

 /*----------------- 新分析 ----------------------*/
 * {padding: 0; margin: 0; box-sizing: border-box;  }
 .date_none{display: none !important;}
 .lineChart{height: 400px !important; }
 .pieChart{height:400px !important;}
 .barChart{height:500px !important;}
 .mixChart{height:300px !important;}
 .pieChart.sm_chart{height:300px !important;}

  .legend_div{ width: 80%; padding: 0; margin:20px auto; }
  .legend_div li{display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e2e2e2; padding: 3px 0;}
  .legend_div li span{ margin-right: 10px;  width: 10px; height: 10px; display: inline-block; border-radius: 30px; }
  .legend_div li i{font-style: normal; font-size: 16px; font-weight: 300; color: #333;}
  .legend_div li b { color: #333; font-size: 16px;}

  .ibox-title h5 span{font-weight:600;}
  .ibox-title h5 i{margin-right:5px;}

  .img_box{margin-top:15px; height: 240px; overflow: hidden; border-radius: 10px 10px 0px 0px;}
  .txt_box{ padding: 10px 15px; border: 1px solid #e5e5e5; border-radius: 0px 0px 10px 10px; border-top: 0;}
  .txt_box h3{margin:10px 0 15px 0; font-size: 20px; font-weight: 500;}
  .txt_box p{font-weight: 300; font-size: 15px; color: #333; margin:5px 0;}
  .txt_box .dt{display: flex; justify-content: space-between;  align-items: flex-end;}
  .txt_box .dt span{font-weight: 300; font-size: 16px; color: #333; letter-spacing: 1px; line-height: 1;}
  .txt_box .dt .cut_num{  font-weight: 700; font-size: 18px; color: #2196f3; line-height: 1;}

  .re_visit{margin: 35px 45px;}
  .re_visit .ibox-title{background-color: #20b722; color: #fff; border-radius: 10px 10px 0 0;}

  .ch_tb tr th, .ch_tb tr td{font-size:16px;}
  .ch_tb .high_line{}
 
 @media (max-width:500px) {
   .lineChart{height: 350px !important; }
   .line_chart_div{width: 1200px !important;}
 }

 /*----------------- 新分析 END ----------------------*/

 @media print {
   .print_none{display:none !important;}
   .navbar-default{display:none !important;}
   #page-wrapper{ margin: 0}
   #an_mail_div{display:block !important;}
   #date_use{width: 100%;}
   /* .col-print-6{width:50% !important;} */
 }
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


<div class="loding_div">
  <div>
    <h2>讀取中....</h2>
    <div class="sk-spinner sk-spinner-wave">
                                    <div class="sk-rect1"></div>
                                    <div class="sk-rect2"></div>
                                    <div class="sk-rect3"></div>
                                    <div class="sk-rect4"></div>
                                    <div class="sk-rect5"></div>
                                </div>
  </div>
  

</div>

<div class="wrapper wrapper-content ">
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
     if ($_SESSION['admin_per']=='admin' || $_SESSION['admin_per']=='group2020040610522078' || $_COOKIE['is_an_all']=='1') {
   ?>

   
   <div class="col-lg-12 ">
   <div class="tabs-container print_none">
       <div id="tabs_list" class="slideUp">
       <ul  class="nav nav-tabs ">
         <li class="active"><a data-toggle="tab" href="#all">全時段分析</a></li>
          <?php
            $row_tag=$new_pdo->select("SELECT Tb_index, tab_name, an_StartDate, an_EndDate, com_StartDate, com_EndDate 
                                   FROM an_tab 
                                   WHERE case_id=:case_id AND OnLineOrNot=1
                                   ORDER BY Tb_index",
                                  ['case_id'=>$_GET['Tb_index']]);
            $x=1;
            foreach ($row_tag as $tag) {

              if(wp_is_mobile()){
               $ph_btn='<div class="ph_btn_div">
                         <a class="btn btn-success" data-toggle="tab" href="#'.$tag['Tb_index'].'">查詢</a>
                         <a class="del_tag btn btn-danger" tag_id="'.$tag['Tb_index'].'" tag_name="'.$tag['tab_name'].'" href="javascript:;">刪除</a>
                        </div>';
              }
              else{
                $ph_btn='<button tag_id="'.$tag['Tb_index'].'" tag_name="'.$tag['tab_name'].'" class="del_tag btn btn-danger no-print"  type="button">x</button>';
              }

                echo '<li class="">
                          <a data-toggle="tab" href="#'.$tag['Tb_index'].'"> '.$tag['tab_name'].'</a> 
                          '.$ph_btn.'
                      </li>';

              $x++;
            }
          ?>
          

           <li class=""><a class="new_list_btn" data-fancybox-type="iframe" href="new_tag/index.html?<?php echo $_GET['Tb_index'];?>">＋新增</a></li>
           <li><a class="print_btn" href="javascript:;"><i class="fa fa-print"></i> 列印報表</a></li>
           <li><a class="fancybox default_btn" data-fancybox-type="iframe" href="../case_url/catch_web.php?Tb_index=<?php echo $_GET['Tb_index'];?>"><i class="fa fa-globe"></i>網址</a></li>
       </ul>
       <a class="tabs_list_close" href="javascript:;">Ｘ</a>
       </div>
       
       <div class="tab-content">

       <div id="all" class="tab-pane active">
                       <div class="panel-body">
                          <div class="an_list_div">
                           <h2>全時段分析 </h2>
                           <div  class="col-lg-12">
                            <div id="search_date_div" class="search_c" >
                              <p>
                                <span>篩選時間：</span>
                                 <a class="sel_time_btn" href="javascript:;" id="search_week_btn" >前一週</a>
                                 <a class="sel_time_btn" href="javascript:;" id="search_week2_btn" >前兩週</a>
                                 <a class="sel_time_btn" href="javascript:;" id="search_month_btn" >前一個月</a>
                                 <a class="sel_time_btn" href="javascript:;" id="search_month2_btn" >前兩個月</a>
                                <input type="text" id="an_StartDate" readonly> ~ <input type="text" id="an_EndDate" readonly> <button id="search_date_btn" class="btn btn-success" type="button">查詢</button>
                              </p>
                            </div>
                           </div>
                           <?php
                            //################################# DEMO #################################
                            if($_COOKIE['admin_index']=='admin2020040610512274'){
                               echo'  <input type="hidden" name="an_StartDate" value="">
                                      <input type="hidden" name="an_EndDate" value="">
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
    <p><span>篩選時間：</span><input type="text" id="an_StartDate" readonly> ~ <input type="text" id="an_EndDate" readonly> <button id="search_date_btn" class="btn btn-success" type="button">查詢</button> </p>
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
                <div class=" float-e-margins">
                                  <div class="ibox-title">
                                      <h5>每日使用人數
                                      </h5>
                                  </div>
                                  <div class="ibox-content" style="overflow-x: auto;">
                                      <div class="line_chart_div">
                                          <canvas id="user_chart" class="lineChart"></canvas>
                                      </div>
                                  </div>
                                  <div class="ph_time_txt">
                                    可往右拖曳顯示全部 <i class="fa fa-arrow-right"></i>
                                  </div>
                              </div>

              </div>
              <div id="user_data_div" class="col-lg-12 row">
                <div class="col-lg-4 col-xs-4">
                <div id="week_users" class="ibox float-e-margins one_week_h5 top_num ">
                                  <div class="ibox-title">
                                      <h5>
                                       <i class="fa fa-user-o"></i> <span class="title_txt">一周<span class="ph_none">瀏覽</span>人數</span> 
                                      </h5>
                                  </div>
                                  <div id="max_user" class="ibox-content user_num">
                                      讀取中...
                                  </div>
                              </div>

              </div>
              <div class="col-lg-4 col-xs-4">
                <div id="month_users" class="ibox float-e-margins one_month_h5 top_num ">
                                  <div class="ibox-title ">
                                      <h5 >
                                         <i class="fa fa-user-o"></i> <span class="title_txt">一個月<span class="ph_none">瀏覽</span>人數</span>
                                      </h5>
                                  </div>
                                  <div id="min_user" class="ibox-content user_num">
                                      讀取中...
                                  </div>
                              </div>

              </div>
              <div class="col-lg-4 col-xs-4">
                <div id="all_users" class="ibox float-e-margins all_user_h5 top_num ">
                                  <div class="ibox-title ">
                                      <h5>
                                         <i class="fa fa-user-o"></i> 總瀏覽人數
                                      </h5>
                                  </div>
                                  <div id="all_user" class="ibox-content user_num">
                                      讀取中...
                                  </div>
                              </div>

              </div>
            </div>

            <!-- 每日來信+來電 -->
            <div class="col-lg-12">
                <div class=" float-e-margins">
                    <div class="ibox-title">
                        <h5>每日來信來電</h5>
                    </div>
                    <div class="ibox-content" style="overflow-x: auto;">
                        <div class="line_chart_div">
                            <canvas id="mail_date_chart" class="lineChart"></canvas>
                        </div>
                    </div>
                    <div class="ph_time_txt">
                      可往右拖曳顯示全部 <i class="fa fa-arrow-right"></i>
                    </div>
                </div>
            </div>

            <div id="user_data_div" class="col-lg-12 row">
                <div class="col-lg-4 col-xs-4">
                <div id="adv_mails" class="ibox float-e-margins one_week_h5 top_num ">
                    <div class="ibox-title" style="background-color: #dd7d06;">
                        <h5>
                          <i class="fa fa-envelope"></i> <span class="title_txt">來信數</span> 
                        </h5>
                    </div>
                    <div id="adv_mail" class="ibox-content user_num">
                        讀取中...
                    </div>
                </div>

              </div>
              <div class="col-lg-4 col-xs-4">
                <div id="adv_phones" class="ibox float-e-margins one_month_h5 top_num ">
                    <div class="ibox-title " style="background-color:#06c2d5;">
                        <h5 >
                            <i class="fa fa-phone"></i> <span class="title_txt">來電數</span>
                        </h5>
                    </div>
                    <div id="adv_phone" class="ibox-content user_num">
                        讀取中...
                    </div>
                </div>

              </div>
              <div class="col-lg-4 col-xs-4">
                <div id="adv_calls" class="ibox float-e-margins all_user_h5 top_num ">
                    <div class="ibox-title" style="background-color:#1c84c6;">
                        <h5>
                            <i class="fa fa-pie-chart"></i> <span class="title_txt">聯絡比率</span>
                        </h5>
                    </div>
                    <div id="adv_call" class="ibox-content user_num">
                        讀取中...
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
              if ($_SESSION['admin_per']=='admin' || $_SESSION['admin_per']=='group2020040610522078' || $_COOKIE['is_an_all']=='1'){
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
                        <div class="sex_chart_div">
                          <canvas id="sex_chart" class="pieChart"></canvas>
                        </div>
                        <ul id="sex_legend" class="legend_div"></ul>
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
                        <div class="years_chart_div">
                          <canvas id="years_chart" class="barChart"></canvas>
                        </div>
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
                                        <div id="city_chart_div">
                                          <canvas id="city_chart" class="barChart"></canvas>
                                        </div>
                                    </div>
                                </div>

                </div>

                <div class="col-lg-6">
                   <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>新舊訪客/回訪率
                            </h5>
                        </div>
                        <div class="ibox-content">
                            <div class="visit_chart_div">
                              <canvas id="visit_chart" class="pieChart sm_chart"></canvas>
                            </div>
                            <ul id="visit_legend" class="legend_div"></ul>
                            <div class="re_visit">
                               <div class="ibox float-e-margins one_week_h5 top_num ">
                                    <div class="ibox-title">
                                      <h5>
                                        <span class="title_txt"><i class="fa fa-pie-chart"></i>回訪率</span> 
                                      </h5>
                                    </div>
                                    <div class="ibox-content user_num">
                                        讀取中...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div id="timeOnSite_div" class="col-lg-6">
                <div class="ibox float-e-margins">
                                  <div class="ibox-title">
                                      <h5>各年齡層停留網站時間(比率)
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
              <div class="col-lg-6 ">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>使用的媒體
                        </h5>
                    </div>
                    <div class="ibox-content">
                        <div id="media_chart_div">
                          <canvas id="media_chart" class="barChart"></canvas>
                        </div>
                    </div>
                </div>

              </div>

              <div class="col-lg-6 ">
                <div class="ibox float-e-margins">
                      <div class="ibox-title">
                          <h5>使用的功能鈕
                          </h5>
                      </div>
                      <div class="ibox-content">
                          <div id="event_chart_div">
                            <canvas id="event_chart" class="pieChart"></canvas>
                          </div>
                          <ul id="event_legend" class="legend_div"></ul>
                      </div>
                  </div>
              </div>

              <div class="col-lg-12 ">
                <div class=" float-e-margins">
                      <div class="ibox-title">
                          <h5>互動比率 <small>使用者與網頁互動(點擊功能鈕、瀏覽畫面一半以上)的比率</small></h5>
                      </div>
                      <div class="ibox-content">
                          <div id="BounceRate_div">
                            <canvas id="BounceRate_chart" class="lineChart"></canvas>
                          </div>
                         
                      </div>
                  </div>
              </div>

              <div id="user_data_div" class="col-lg-12 row">
                <div class="col-lg-6 col-xs-6">
                <div id="max_BounceRates" class="ibox float-e-margins one_week_h5 top_num ">
                    <div class="ibox-title" style="background-color: #1c84c6;">
                        <h5><i class="fa fa-pie-chart"></i> <span class="title_txt">最大互動比率</span> </h5>
                    </div>
                    <div id="max_BounceRate" class="ibox-content user_num">
                        讀取中...
                    </div>
                </div>

              </div>
              <div class="col-lg-6 col-xs-6">
                <div id="avg_BounceRates" class="ibox float-e-margins one_month_h5 top_num ">
                    <div class="ibox-title " style="background-color:#1c84c6;">
                        <h5><i class="fa fa-pie-chart"></i> <span class="title_txt">平均互動比率</span></h5>
                    </div>
                    <div id="avg_BounceRate" class="ibox-content user_num">
                        讀取中...
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
           流量
        </div>
        <div class="panel-body">
          <div class="row">
                <div id="month_src_div" class="col-lg-6 " >
                  <div class="ibox float-e-margins">
                      <div class="ibox-title">
                          <h5><?php echo date('n');?>月流量來源</h5>
                      </div>
                      <div class="ibox-content">
                          <div id="month_src_chart_div">
                            <canvas id="month_src_chart" class="pieChart"></canvas>
                          </div>
                          <ul id="month_src_legend" class="legend_div"></ul>
                          
                      </div>
                  </div>

                </div>

                <div id="all_src_div" class="col-lg-6 ">
                  <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>總流量來源</h5>
                        </div>
                        <div class="ibox-content">
                            <div id="src_chart_div">
                              <canvas id="src_chart" class="pieChart"></canvas>
                            </div>
                            <ul id="src_legend" class="legend_div"></ul>
                        </div>
                    </div>

                </div>

                <div id="all_src_div" class="col-lg-6 ">
                  <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>來源使用時間</h5>
                        </div>
                        <div class="ibox-content">
                            <div id="src_time_chart_div">
                              
                            </div>
                        </div>
                    </div>

                </div>

              
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
              



              <div id="" class="col-lg-12">
                  <div class="ibox float-e-margins">
                        <div class="ibox-title" style="display: flex; justify-content: space-between;">
                            <h5>預約賞屋來信</h5>
                            <button type="button" class="btn btn-success slide_mail_btn">展開</button>
                        </div>
                        <div class="ibox-content">
                            
                            <div id="an_mail_div" class="row" style="display:none;">   
                              <div class="text-right">
                                <div id="ch_status">
                                  篩選狀態：
                                  <a href="javascript:;" class="label status_btn" status="">全部：<b>10</b></a>
                                  <a href="javascript:;" class="label label-danger status_btn" status="0">未處理：<b>10</b></a>
                                  <a href="javascript:;" class="label label-warning status_btn" status="2">處理中：<b>10</b></a>
                                  <a href="javascript:;" class="label label-primary status_btn" status="1">已處理：<b>10</b></a>
                                </div>
                              </div>                           
                              <div class="table-responsive">
                                <table class="table no-margin">
                                <thead>
                                  <tr>
                                    <th>#</th>
                                    <th>時間</th>
                                    <th>姓名</th>
                                    <th>電話</th>
                                    <th class="print_none">E-mail</th>
                                    <th>來源</th>
                                    <th class="print_none">狀態</th>
                                    <th class="print_none">管理</th>
                                    
                                  </tr>
                                </thead>
                                <tbody>
                                  
                                  
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
    if ($_SESSION['admin_per']=='admin' || $_SESSION['admin_per']=='group2020040610522078' || $_COOKIE['is_an_all']=='1'){

      $an_manage='<li><a class="tool_btn fancybox" data-fancybox-type="iframe" href="new_tag/index.html?'.$_GET['Tb_index'].'">新增比較</a></li>
                  <li><a id="manage1_btn" class="tool_btn" href="javascript:;">管理比較</a></li>';
    }
    else{
      $an_manage='';
    }
       echo '<div id="ph_tool">
              <ul>
               '.$an_manage.'
               <li><a class="tool_btn fancybox" data-fancybox-type="iframe" href="../case_url/catch_web_add.php?Tb_index='.$_GET['Tb_index'].'">新增網址</a></li>
               <li><a class="tool_btn fancybox" data-fancybox-type="iframe" href="../case_url/catch_web_manage.php?Tb_index='.$_GET['Tb_index'].'">管理網址</a></li>
               <li><a class="tool_btn print_btn" href="javascript:;">列印</a></li>
              </ul>
              <a id="ph_tabs_btn" class="" href="javascript:;"><i class="fa fa-plus"></i></a>
              <a id="ph_all_time_btn" data-toggle="tab"  class="" href="#all"><i class="fa fa-share"></i></a>
            </div>';
    
  ?>

  <input type="hidden" value="<?php echo $_GET['Tb_index'];?>" id="Tb_index">
  <input type="hidden" value="<?php echo $_COOKIE['admin_per'];?>" id="admin_per">
  

</div><!-- /#page-content -->

<?php  include("../../core/page/footer01.php");//載入頁面footer02.php?>
<script type="text/javascript" src="../../js/plugins/jsPDF/jspdf.min.js"></script>
<script src="../../js/Chart.min.js?1"></script>
<script src="../../js/plugins/chartjs/chartjs-plugin-annotation.min.js"></script>
<script src="../../js/plugins/chartjs/chartjs-plugin-datalabels.min.js"></script>
<script src="../../js/an_Class/Chart_class.js?3"></script>
<script src="../../js/an_Class/an_Class.js?46"></script>



<script type="text/javascript">
  
  //-- 建案ID --
  var Tb_index =location.search.split('&');
      Tb_index=Tb_index[1].split('=');
      Tb_index=Tb_index[1];

  /*-- 分析表 --*/
  var _user__chart, _mail_date__chart, _sex__chart, _years__chart, _city__chart, _visit__chart, _media__chart, _event__chart, _month_src__chart, _src__chart, _src_time_chart, _BounceRate__chart;

  //----------------- 新分析 --------------------
  var AnAll=new CaseAn;

    var an_StartDate='';
    var an_EndDate='';
    var com_StartDate='';
    var com_EndDate='';


	$(document).ready(function() {

    //-- 網址get --
    let url_arr=url_get(); 


    //-- 一週分析報告 --
    var search=location.search;
    if (search.search(/an_code/) > -1) {
      var search_arr=search.split('&');
      var date_txt=search_arr[2].split('=');
          date_txt=date_txt[1];

      $('.loding_div').css('display', 'flex'); 

      $.ajax({
        type: "POST",
        url: "analytics_ajax.php",
        data: {
           type: 'week_report',
           an_code: date_txt
        },
        // dataType: "dataType",
        success: function (data) {
          var data_arr=data.split(',');
          var an_StartDate=data_arr[0];
          var an_EndDate=data_arr[1];
          var com_StartDate='';
          var com_EndDate='';
          $('#an_StartDate').val(an_StartDate);
          $('#an_EndDate').val(an_EndDate);
          
          setTimeout(() => {
            //all_an_ajax(an_StartDate, an_EndDate, com_StartDate, com_EndDate);
            get_an({
              destroy:false
            });
            $('.loding_div').css('opacity', '0'); 
            $('.loding_div').css('z-index', '-1'); 
          }, 1000);
          
        }
      });
    }
    else{

        get_an({
          destroy:false
        });
    }

    
    

    //-- 來信展開 --
    $('.slide_mail_btn').click(function (e) {
      
      $('#an_mail_div').slideToggle('500', function () {
        if($('#an_mail_div').css('display')=='none'){
          $('.slide_mail_btn').html('展開');
        }else{
          $('.slide_mail_btn').html('收合');
        }
      });
    });

    //-- 新增標籤 --
    $(".new_list_btn").fancybox({
       maxWidth	: 420,
    });


    //-- 刪除標籤 --
    $('.del_tag, .del_tag_btn').click(function (e) { 
      
      var Tb_index=$(this).attr('tag_id');
      var tag_name=$(this).attr('tag_name');

      if(confirm('是否要刪除【'+tag_name+'】??')){
         $.ajax({
          type: "POST",
          url: "new_tag/tag_ajax.php",
          data: {
            type: 'delete',
            Tb_index: Tb_index
          },
          success: function (data) {
            alert('已刪除標籤');
            location.reload();
          }
        });
      }
    });



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

  
    
        //------------------- 手機標籤按鈕 ---------------------
    $('#ph_tabs_btn').click(function (e) { 
      $('#ph_tabs_btn i').toggleClass('btn_hide');
      $('#ph_tool ul').slideToggle('500');
    });

    //-- 管理比較 --
    $('#manage1_btn').click(function (e) { 
      $('#tabs_list').removeClass('slideUp');
      $('#tabs_list').css('z-index', '101');
    });


    //------------------- 手機標籤關閉 -----------------------
    $('.tabs_list_close').click(function (e) { 
      $('#tabs_list').addClass('slideUp');
      $('#tabs_list').css('z-index', '99');
    });

    $('[data-toggle="tab"]').click(function (e) { 
      $('#tabs_list').css('z-index', '99');
    });


    $('.tool_btn').click(function (e) { 
      $('#ph_tabs_btn i').removeClass('btn_hide');
      $('#ph_tool ul').slideUp('500');
    });


    $('.status_btn').click(function (e) { 
      an_status_mail(an_StartDate, an_EndDate, $(this).attr('status'));
    });
    
   

   //-- 列印 --
   $('.print_btn').click(function (e) { 

      let Tb_index=$('#Tb_index').val();
      let an_StartDate=$('#an_StartDate').val();
      let an_EndDate=$('#an_EndDate').val();
      window.open(`an_chart_print.php?Tb_index=${Tb_index}&s_date=${an_StartDate}&e_date=${an_EndDate}`, '列印', config='height=800,width=1100');
   });





    //------------- 日期區間 來源流量 ---------------
    // $('.update_src_num').click(function(event) {
      
    //   $.ajax({
    //     url: '../../system/google_an/google_an_update.php',
    //     type: 'POST',
    //     data: {
    //       type: 'date_src_num',
    //       Tb_index: Tb_index,
    //       StartDate: $('[name="src_num_s_date"]').val(),
    //       EndDate: $('[name="src_num_e_date"]').val()
    //     },
    //     success:function (data) {
    //       ajax_src_num(data_src_num);
    //     }
    //   });

    // });



    //------ 切換標籤 (未完成) ------------
     $('.tabs-container li a, #ph_all_time_btn').click(function (e) { 
      
      var tag_id=$(this).attr('href');

      //-- 全時段按鈕(手機) --
      if($(this).attr('id')=='ph_all_time_btn'){
        $('#an_StartDate').val('');
        $('#an_EndDate').val('');
        $('.sel_time_btn').removeClass('active');
        $('.nav.nav-tabs li').removeClass('active');
        $('.nav.nav-tabs li:nth-child(1)').addClass('active');
        $('#ph_all_time_btn').css('display', 'none');
      }
      else{
       
       $('#ph_all_time_btn').css('display', 'inline-flex');
      }

       
       an_StartDate=$(tag_id+' [name="an_StartDate"]').val();
       an_EndDate=$(tag_id+' [name="an_EndDate"]').val();
       com_StartDate=$(tag_id+' [name="com_StartDate"]').val();
       com_EndDate=$(tag_id+' [name="com_EndDate"]').val();

       if(tag_id=='#all' || com_StartDate==undefined){
         $('#user_data_c_div').css('display','none');
       }
       else{
         $('#user_data_c_div').css('display','block');
       }
       
       
       //-- 手機 --
       if($(window).width()<=768){
         $('#tabs_list').addClass('slideUp');
         //-- 選擇比較分析 --
         //console.log(com_StartDate);
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
       
      all_an_ajax(an_StartDate, an_EndDate, com_StartDate, com_EndDate);
    });



    //==================================== 篩選時間 查詢 ====================================
    $('#search_date_btn').click(function (e) { 
      
      var an_StartDate=$('#an_StartDate').val();
      var an_EndDate=$('#an_EndDate').val();

      get_an({
        destroy:true
      });

    });


    /*======================================= 一週 =======================================*/
    $('#search_week_btn').click(function (e) { 
      var an_StartDate=new Date();
          an_StartDate=DateFormat(an_StartDate.setDate(an_StartDate.getDate() - 7));
      var an_EndDate=new Date();
          an_EndDate=DateFormat(an_EndDate.setDate(an_EndDate.getDate() - 1));
      var com_StartDate='';
      var com_EndDate='';

      
      $('.sel_time_btn').removeClass('active');
      $(this).addClass('active');
      $('#an_StartDate').val(an_StartDate);
      $('#an_EndDate').val(an_EndDate);

      get_an({
        destroy:true
      });
      //all_an_ajax(an_StartDate, an_EndDate, com_StartDate, com_EndDate);
    });


    /*========================================= 二週 =========================================*/
    $('#search_week2_btn').click(function (e) { 
      var an_StartDate=new Date();
          an_StartDate=DateFormat(an_StartDate.setDate(an_StartDate.getDate() - 14));
      var an_EndDate=new Date();
          an_EndDate=DateFormat(an_EndDate.setDate(an_EndDate.getDate() - 1));
      var com_StartDate='';
      var com_EndDate='';


      $('.sel_time_btn').removeClass('active');
      $(this).addClass('active');
      $('#an_StartDate').val(an_StartDate);
      $('#an_EndDate').val(an_EndDate);
      
      get_an({
        destroy:true
      });
      //all_an_ajax(an_StartDate, an_EndDate, com_StartDate, com_EndDate);
    });



    /*========================================== 一個月 ==========================================*/
    $('#search_month_btn').click(function (e) { 
      var an_StartDate=new Date();
          an_StartDate=DateFormat(an_StartDate.setMonth(an_StartDate.getMonth() -1));
      var an_EndDate=new Date();
          an_EndDate=DateFormat(an_EndDate.setDate(an_EndDate.getDate() - 1));
      var com_StartDate='';
      var com_EndDate='';


      $('.sel_time_btn').removeClass('active');
      $(this).addClass('active');
      $('#an_StartDate').val(an_StartDate);
      $('#an_EndDate').val(an_EndDate);
      
      get_an({
        destroy:true
      });
      //all_an_ajax(an_StartDate, an_EndDate, com_StartDate, com_EndDate);
    });


    /*========================================== 二個月 ==========================================*/
    $('#search_month2_btn').click(function (e) { 
      var an_StartDate=new Date();
          an_StartDate=DateFormat(an_StartDate.setMonth(an_StartDate.getMonth() -2));
      var an_EndDate=new Date();
          an_EndDate=DateFormat(an_EndDate.setDate(an_EndDate.getDate() - 1));
      var com_StartDate='';
      var com_EndDate='';


      $('.sel_time_btn').removeClass('active');
      $(this).addClass('active');
      $('#an_StartDate').val(an_StartDate);
      $('#an_EndDate').val(an_EndDate);
      
      get_an({
        destroy:true
      });
      //all_an_ajax(an_StartDate, an_EndDate, com_StartDate, com_EndDate);
    });



    /*--------- 來信狀態修改 ----------- */
    $('#an_mail_div').on('change', '#is_process', function () {
      var _this=$(this);
      $.ajax({
        type: "POST",
        url: "analytics_ajax.php",
        data: {
          type:'ch_is_process',
          Tb_index: _this.attr('case_id'),
          is_process: _this.val()
        },
        success: function (data) {

          if(_this.val()=='0'){
              var is_process ='<span class="label label-danger">未處理</span>';
          }
          else if (_this.val() == '1'){
              var is_process = '<span class="label label-primary">已處理</span>';
          }
           else{
              var is_process = '<span class="label label-warning">處理中</span>';
          }

          an_num_mail(an_StartDate, an_EndDate, '0', '#ch_status [status="0"] b');
          an_num_mail(an_StartDate, an_EndDate, '1', '#ch_status [status="1"] b');
          an_num_mail(an_StartDate, an_EndDate, '2', '#ch_status [status="2"] b');

          _this.parent().parent().find('.ph_show').html(is_process);
          _this.parent().prev().html(is_process);
   
        }
      });
    });



    //--  --
    $(window).bind('scroll resize', function() {
      
      var beforeTop=$(this).scrollTop();

      $(window).bind('scroll resize', function() {
        
       var top=$(this).scrollTop();
      //console.log(top);
      if (top>300){
         $('.tabs-container').addClass('p-fixed');
         $('#top_div').addClass('p-fixed');
         $('#search_date_div').addClass('p-fixed');
      }
      else{
         $('.tabs-container').removeClass('p-fixed');
         $('#top_div').removeClass('p-fixed');
         $('#search_date_div').removeClass('p-fixed');
      }

      // if(beforeTop>top){
      //    beforeTop = top;
      //    var ph_tabs_btn_class=$('#ph_tabs_btn').attr('class');
      //    if(ph_tabs_btn_class.indexOf('btn_hide')!=-1){
      //       $('#ph_tabs_btn').removeClass('btn_hide');
      //    }
      // }
      // else if(beforeTop<top){
      //    beforeTop = top;
      //    var ph_tabs_btn_class=$('#ph_tabs_btn').attr('class');
      //    if(ph_tabs_btn_class.indexOf('btn_hide')==-1){
      //      $('#ph_tabs_btn').addClass('btn_hide');
      //    }
      // }
      });
      
    });


}); //JQUERY END

function get_an ({destroy=false}) {
  $.ajax({
      type: "POST",
      url: "an_chart_ajax.php",
      data: {
          type: 'an_get',
          case_id: $('#Tb_index').val(),
          s_date: $('#an_StartDate').val(),
          e_date: $('#an_EndDate').val(),
      },
      dataType: "json",
      success: function (data) {
          console.log(data);

          //-- 清除圖表 --
          if (destroy) {
              _user__chart.destroy();
              _mail_date__chart.destroy();
              _sex__chart.destroy();
              _years__chart.destroy();
              _city__chart.destroy();
              _media__chart.destroy();
              _event__chart.destroy();
              _month_src__chart.destroy();
              _src__chart.destroy();
          }

          //-- 最大瀏覽人數 --
          let max_user_index = data.data.user.indexOf(Math.max(...data.data.user));
          //-- 最小瀏覽人數 --
          let min_user_index = data.data.user.indexOf(Math.min(...data.data.user));

          //-- 一周人數 --
          $('#week_users .user_num').html(fm_Thousands(data.data.week_user) + '人');
          //-- 一月人數 --
          $('#month_users .user_num').html(fm_Thousands(data.data.month_user) + '人');
          //-- 總人數 --
          $('#all_users .user_num').html(fm_Thousands(data.data.total_user) + '人');

          if ($('#an_StartDate').val() != '') {
              $('#month_src_div').addClass('date_none');
              $('.src_box h2').html('區間流量來源');

              $('#week_users .user_num').html(`${fm_Thousands(data.data.user[max_user_index])}人`);
              $('#month_users .user_num').html(`${fm_Thousands(data.data.user[min_user_index])}人`);
              $('#week_users .title_txt').html(` 最大<span class="ph_none">瀏覽</span>人數 <small>${data.data.date[max_user_index]}</small>`);
              $('#month_users .title_txt').html(` 最小<span class="ph_none">瀏覽</span>人數 <small>${data.data.date[min_user_index]}</small>`);
          }

          //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 每日使用人數 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
          _user__chart= AnAll.user_chart({
            chart_id:'user_chart', 
            user_data: data.data.user, 
            date: data.data.date, 
            max_user_date: data.data.date[max_user_index], 
            min_user_date: data.data.date[min_user_index]
          });
          

          //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 每日來信來電 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
          //-- 最大來信數 --
          let max_mail_index = data.data.user_mail.indexOf(Math.max(...data.data.user_mail));
          //-- 最大來電數 --
          let max_phone_index = data.data.user_phone.indexOf(Math.max(...data.data.user_phone));
          _mail_date__chart= AnAll.mail_date_chart({
            chart_id:'mail_date_chart', 
            mail_data: data.data.user_mail, 
            phone_data: data.data.user_phone, 
            date: data.data.date, 
            max_mail_date: data.data.date[max_mail_index], 
            max_phone_date: data.data.date[max_phone_index]
          });
          //-- 來信數 --
          let total_user_mail=data.data.user_mail.reduce( (a,b)=>{ return a + b;});
          //-- 來電數 --
          let total_user_phone=data.data.user_phone.reduce( (a,b)=> { return a + b; });
          //-- 聯絡比率 --
          let adv_call=Math.round(((total_user_mail+total_user_phone)/data.data.total_user)*10000)/100;
          $('#adv_mails .user_num').html(total_user_mail + '封');
          $('#adv_phones .user_num').html(total_user_phone + '通');
          $('#adv_calls .user_num').html(adv_call + '%');

          

          //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 每日互動率 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
          //-- 跳出率轉互動率 --
          let br_arr=[];
          data.data.BounceRate.forEach(br => {
            let new_br=(100-br)==100 ? 0:Math.round((100-br)*10)/10;
            br_arr.push(new_br);
          });
          //-- 最大互動率 --
          let max_br_index = br_arr.indexOf(Math.max(...br_arr));
          _BounceRate__chart= AnAll.BounceRate_chart({
            chart_id:'BounceRate_chart', 
            user_data: br_arr, 
            date: data.data.date, 
            max_br_date: data.data.date[max_br_index]
          });
          //-- 平均互動率 --
          let total_BounceRate=br_arr.reduce( (a,b)=>{ return a + b;});
          let close_0_br_arr=br_arr.filter(function (item) {
            return item > 0;
          });
          let avg_BounceRate=Math.round((total_BounceRate/close_0_br_arr.length)*10)/10;

          $('#max_BounceRates .user_num').html(br_arr[max_br_index]+'%');
          $('#avg_BounceRates .user_num').html(avg_BounceRate+'%');
          

          
          //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 性別 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
          _sex__chart= AnAll.sex_chart({
            chart_id: 'sex_chart', 
            user_data: data.data.sex, 
            legend_id: 'sex_legend'
          });


          //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 年齡 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
          _years__chart= AnAll.years_chart({
            chart_id: 'years_chart', 
            user_data: data.data.years
          });


          //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 地區 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
          _city__chart= AnAll.city_chart({
            chart_id: 'city_chart', 
            user_data: data.data.city
          });


          //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 新舊訪客/回訪率 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
          _visit__chart=AnAll.visit_chart({
            chart_id: 'visit_chart', 
            user_data: data.data.return_visit, 
            legend_id: 'visit_legend'
          });

          //-- 回訪率 --
          let visit_total=0;
          data.data.return_visit.forEach(one => {
              visit_total+=parseInt( one.total);
          });
          let avg_re_visit=Math.round( (data.data.return_visit[1].total/visit_total)*1000)/10;
          $('.re_visit .user_num').html(`${avg_re_visit}%`);


          //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 使用的媒體 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
          _media__chart=AnAll.media_chart({
            chart_id: 'media_chart',
            user_data: data.data.media
          });


          //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 使用功能 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
          _event__chart= AnAll.event_chart({
            chart_id: 'event_chart', 
            user_data: data.data.event, 
            legend_id: 'event_legend'
          });


          //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 當月流量 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
          _month_src__chart= AnAll.src_chart({
            chart_id: 'month_src_chart', 
            user_data: data.data.month_src, 
            legend_id: 'month_src_legend'
          });


          //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 總流量 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
          _src__chart= AnAll.src_chart({
            chart_id: 'src_chart', 
            user_data: data.data.src, 
            legend_id: 'src_legend'
          });


          //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 來源使用時間 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
          _src_time_chart= AnAll.src_time_chart({
            chart_id: 'src_time_chart_div', 
            user_data: data.data.src_time
          });


          //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 網頁瀏覽程度 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
          AnAll.completion_chart({
            DIV_id: 'an_completion', 
            chart_id: 'mixChart', 
            div_class: 'col-md-4',
            completion_data: data.data.completion, 
            date: data.data.date
          });


          //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 預約賞屋來信 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
          AnAll.mail_list({
            DIV_id:'#an_mail_div table tbody',
            show_is_process:true,
            user_data: data.data.mail
          });
      }
  });
}
  
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>