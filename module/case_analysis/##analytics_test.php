<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<link rel="stylesheet" href="../../css/an_style.css?2">
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
     if ($_SESSION['admin_per']=='admin' || $_SESSION['admin_per']=='group2020040610522078') {
   ?>

   
   <div class="col-lg-12 ">
   <div class="tabs-container">
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
                                <input type="text" id="an_StartDate"> ~ <input type="text" id="an_EndDate"> <button id="search_date_btn" class="btn btn-success" type="button">查詢</button>
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
                                  <div class="ph_time_txt">
                                    可往右拖曳顯示全部 <i class="fa fa-arrow-right"></i>
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


              <div class="col-lg-4 col-xs-4">
                <div class="ibox float-e-margins top_num">
                                  <div class="ibox-title ">
                                      <h5>
                                         <i class="fa fa-user-o"></i>
                                         回訪率
                                      </h5>
                                  </div>
                                  <div id="back_user" class="ibox-content user_num">
                                      
                                  </div>
                </div>
              </div>


              <div class="col-lg-4 col-xs-4">
                <div class="ibox float-e-margins top_num">
                                  <div class="ibox-title ">
                                      <h5>
                                         <i class="fa fa-user-o"></i>
                                         忠誠度
                                      </h5>
                                  </div>
                                  <div id="loyalty" class="ibox-content user_num">
                                      
                                  </div>
                                  <small>註：回訪者在一週內再度回訪的百分比</small>
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


             <div class="col-lg-6">
                <div class="ibox float-e-margins">
                                  <div class="ibox-title">
                                      <h5>新訪者/回訪者(人數)
                                      </h5>
                                  </div>
                                  <div class="ibox-content">
                                      <div id="userType"></div>
                                      <div class="userType_legend c3_legend"></div>
                                  </div>
                </div>
              </div>


              <div class="col-lg-6">
                <div class="ibox float-e-margins">
                                  <div class="ibox-title">
                                      <h5>回訪次數(人數)
                                      </h5>
                                  </div>
                                  <div class="ibox-content">
                                      <div id="userCount"></div>
                                      <div class="userCount_legend c3_legend"></div>
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
                                         <!-- <div class="text-right">
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
                                         </div> -->

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
                                         <!-- <div class="text-right">
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
                                         </div> -->

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
                                    <div class="ibox-title">
                                        <h5>預約賞屋來信 <button type="button" class="btn btn-success slide_mail_btn">展開</button></h5>
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
								                                <th>E-mail</th>
                                                <th>狀態</th>
                                                <th>管理</th>
                                                
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

       echo '<div id="ph_tool">
              <ul>
               <li><a class="tool_btn fancybox" data-fancybox-type="iframe" href="new_tag/index.html?'.$_GET['Tb_index'].'">新增比較</a></li>
               <li><a id="manage1_btn" class="tool_btn" href="javascript:;">管理比較</a></li>
               <li><a class="tool_btn fancybox" data-fancybox-type="iframe" href="../case_url/catch_web_add.php?Tb_index='.$_GET['Tb_index'].'">新增網址</a></li>
               <li><a class="tool_btn fancybox" data-fancybox-type="iframe" href="../case_url/catch_web_manage.php?Tb_index='.$_GET['Tb_index'].'">管理網址</a></li>
               <li><a class="tool_btn print_btn" href="javascript:;">列印</a></li>
              </ul>
              <a id="ph_tabs_btn" class="" href="javascript:;"><i class="fa fa-plus"></i></a>
              <a id="ph_all_time_btn" data-toggle="tab"  class="" href="#all"><i class="fa fa-share"></i></a>
            </div>';
    
  ?>


  <input type="hidden" value="<?php echo $_COOKIE['admin_per'];?>" id="admin_per">
  

</div><!-- /#page-content -->

<?php  include("../../core/page/footer01.php");//載入頁面footer02.php?>
<script type="text/javascript" src="../../js/plugins/jsPDF/jspdf.min.js"></script>
<script src="../../js/Chart.min.js"></script>

<!-- 分析JS -->
<script src="../../js/an_test.js?9"></script>
<script type="text/javascript">
  
  //-- 建案ID --
  var Tb_index =location.search.split('&');
      Tb_index=Tb_index[1].split('=');
      Tb_index=Tb_index[1];


  /*-- 分析表 --*/
  var data_sex, data_old, data_media, data_tool_btn, data_src_num, data_month_src_num, data_timeOnSite, data_use, data_userType, data_userCount;


    var an_StartDate='';
    var an_EndDate='';
    var com_StartDate='';
    var com_EndDate='';



	$(document).ready(function() {

    //-- 分析未完 --
    // $.ajax({
    //   type: "POST",
    //   url: "/sys/core/inc/an_function.php",
    //   data: {
    //     StartDate:'2021-10-01',
    //     EndDate:'2021-10-31'
    //   },
    //   dataType : 'json',
    //   success: function (data) {
    //     console.log(data);
    //   }
    // });
    
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
            all_an_ajax(an_StartDate, an_EndDate, com_StartDate, com_EndDate);
            $('.loding_div').css('opacity', '0'); 
            $('.loding_div').css('z-index', '-1'); 
          }, 1000);
          
        }
      });
    }

    //################################# DEMO #################################
    
    //-- 網頁瀏覽程度 --
     
    

    var location_search=location.search.split('Tb_index=');
    // console.log(location_search[1]);
    // if(location_search[1]=='case2018101812190565' || location_search[1]=='case2018071915501554'){
    //   $.ajax({
    //   type: "POST",
    //   url: "analytics_ajax.php",
    //   async:false,
    //   data: {
    //     type: 'mem_cookie'
    //   },
    //   success: function (data) {
    //     //console.log(data);
    //     if(data=='admin2020040610512274'){

    //      $('.loding_div').css('display', 'flex');
        
    //       var an_StartDate='2019-03-01';
    //       var an_EndDate='2019-03-31';
    //       var com_StartDate='';
    //       var com_EndDate='';
    //       setTimeout(() => {
    //         all_an_ajax(an_StartDate, an_EndDate, com_StartDate, com_EndDate);
    //         $('.loding_div').css('opacity', '0'); 
    //         $('.loding_div').css('z-index', '-1'); 
    //       }, 2000);
       
    //     }
    //   }
    // });
    // }
    //################################# DEMO END #################################
    
    




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
                          },
                         type : 'pie',
                         labels: true
                     },
                     legend: {
                       show: false
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



         //回訪者
          data_userType= c3.generate({
                     bindto: '#userType',
                     data:{
                         x:'x',
                         columns: [
                           
                         ],
                         colors:{
                              新訪者: '#2196f3',
                              回訪者: '#ff6258',
                          },
                         type : 'donut',
                         labels: true
                     },
                     legend: {
                       show: false
                     },
                     donut: {
                        title: '新訪者/回訪者'
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
          ajax_userType(data_userType, an_StartDate, an_EndDate, com_StartDate, com_EndDate);
          //-- 回訪率 --
          ajax_userType_per(an_StartDate, an_EndDate, com_StartDate, com_EndDate);
          //-- 忠誠度 --
          ajax_loyalty(an_StartDate, an_EndDate, com_StartDate, com_EndDate);



        //回訪次數(人數)
        data_userCount= c3.generate({
                      bindto: '#userCount',
                      data:{
                         x:'x',
                          columns: [
                              ['x','2次','3次','4次','5次','6次','7次','8次','9次','10次'],
                              ['使用人數','4001','1037','632','400','286','216','176','140','116']
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
        ajax_userCount(data_userCount, an_StartDate, an_EndDate, com_StartDate, com_EndDate);

    
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
      //ajax_src_num_d(an_StartDate, an_EndDate, com_StartDate, com_EndDate);


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
                
                size:{
                    height:400
                },
                padding: {
                         bottom: 20 
                      }
      });
      ajax_month_src_num(data_month_src_num);
      //-- 月詳細資料 --
      //ajax_month_src_num_d();

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
   an_completion_test(an_StartDate, an_EndDate, com_StartDate, com_EndDate);

   //-- 來信 --
   an_mail(an_StartDate, an_EndDate);
   


   //-- 列印 --
   $('.print_btn').click(function (e) { 
     
      if($('#an_StartDate').val()!=''){
        var tag_id='all';
        an_StartDate=$('#an_StartDate').val();
        an_EndDate=$('#an_EndDate').val();
      }
      else{
       var tag_id=$('.tabs-container li.active a').attr('href');
       an_StartDate=$(tag_id+' [name="an_StartDate"]').val()==undefined ? '' : $(tag_id+' [name="an_StartDate"]').val();
       an_EndDate=$(tag_id+' [name="an_EndDate"]').val()==undefined ? '' : $(tag_id+' [name="an_EndDate"]').val();
      }

      com_StartDate=$(tag_id+' [name="com_StartDate"]').val()==undefined ? '' : $(tag_id+' [name="com_StartDate"]').val();
      com_EndDate=$(tag_id+' [name="com_EndDate"]').val()==undefined ? '' : $(tag_id+' [name="com_EndDate"]').val();
       
      
       var get_data=location.search;
       get_data=get_data.split('&');
       var MT_id=get_data[0].split('=');
       MT_id=MT_id[1];
       var Tb_index=get_data[1].split('=');
       Tb_index=Tb_index[1];
       
      // window.open("analytics_print2.php?MT_id="+MT_id+"&Tb_index="+Tb_index+"&an_StartDate="+an_StartDate+"&an_EndDate="+an_EndDate+"&com_StartDate="+com_StartDate+"&com_EndDate="+com_EndDate, "_blank");
       window.open("analytics_print3.php?MT_id="+MT_id+"&Tb_index="+Tb_index+"&tag_id="+tag_id.substr(1)+"&an_StartDate="+an_StartDate+"&an_EndDate="+an_EndDate+"&com_StartDate="+com_StartDate+"&com_EndDate="+com_EndDate, '列印', config='height=900,width=750');
   });





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





    //------ 切換標籤 ------------
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



    //-- 篩選時間 查詢 --
    $('#search_date_btn').click(function (e) { 
      
      var an_StartDate=$('#an_StartDate').val();
      var an_EndDate=$('#an_EndDate').val();
      var com_StartDate='';
      var com_EndDate='';
      
      all_an_ajax(an_StartDate, an_EndDate, com_StartDate, com_EndDate);

    });





    /*-- 一週 --*/
    $('#search_week_btn').click(function (e) { 
      var an_StartDate=new Date();
          an_StartDate=DateFormat(an_StartDate.setDate(an_StartDate.getDate() - 7));
      var an_EndDate=new Date();
          an_EndDate=DateFormat(an_EndDate.setDate(an_EndDate.getDate() - 1));
      var com_StartDate='';
      var com_EndDate='';

      //-- DEMO --
      if ($('[name="admin_per"]').val()=="group2020040610522078") {
        // an_StartDate=String(an_StartDate).split('-');
        // an_EndDate=String(an_EndDate).split('-');
        // an_StartDate='2019-'+an_StartDate['1']+'-'+an_StartDate[2];
        // an_EndDate='2019-'+an_EndDate['1']+'-'+an_EndDate[2];
        an_StartDate='2019-03-11';
        an_EndDate='2019-03-17';
      }
      
      $('.sel_time_btn').removeClass('active');
      $(this).addClass('active');
      $('#an_StartDate').val(an_StartDate);
      $('#an_EndDate').val(an_EndDate);

      
      all_an_ajax(an_StartDate, an_EndDate, com_StartDate, com_EndDate);
    });


    /*-- 二週 --*/
    $('#search_week2_btn').click(function (e) { 
      var an_StartDate=new Date();
          an_StartDate=DateFormat(an_StartDate.setDate(an_StartDate.getDate() - 14));
      var an_EndDate=new Date();
          an_EndDate=DateFormat(an_EndDate.setDate(an_EndDate.getDate() - 1));
      var com_StartDate='';
      var com_EndDate='';

      //-- DEMO --
      if ($('[name="admin_per"]').val()=="group2020040610522078") {
        // an_StartDate=String(an_StartDate).split('-');
        // an_EndDate=String(an_EndDate).split('-');
        // an_StartDate='2019-'+an_StartDate['1']+'-'+an_StartDate[2];
        // an_EndDate='2019-'+an_EndDate['1']+'-'+an_EndDate[2];
        an_StartDate='2019-03-11';
        an_EndDate='2019-03-25';
      }

      $('.sel_time_btn').removeClass('active');
      $(this).addClass('active');
      $('#an_StartDate').val(an_StartDate);
      $('#an_EndDate').val(an_EndDate);
      
      all_an_ajax(an_StartDate, an_EndDate, com_StartDate, com_EndDate);
    });



    /*-- 一個月 --*/
    $('#search_month_btn').click(function (e) { 
      var an_StartDate=new Date();
          an_StartDate=DateFormat(an_StartDate.setMonth(an_StartDate.getMonth() -1));
      var an_EndDate=new Date();
          an_EndDate=DateFormat(an_EndDate.setDate(an_EndDate.getDate() - 1));
      var com_StartDate='';
      var com_EndDate='';

      //-- DEMO --
      if ($('[name="admin_per"]').val()=="group2020040610522078") {
        // an_StartDate=String(an_StartDate).split('-');
        // an_EndDate=String(an_EndDate).split('-');
        // an_StartDate='2019-'+an_StartDate['1']+'-'+an_StartDate[2];
        // an_EndDate='2019-'+an_EndDate['1']+'-'+an_EndDate[2];
        an_StartDate='2019-03-11';
        an_EndDate='2019-04-11';
      }

      $('.sel_time_btn').removeClass('active');
      $(this).addClass('active');
      $('#an_StartDate').val(an_StartDate);
      $('#an_EndDate').val(an_EndDate);
      
      all_an_ajax(an_StartDate, an_EndDate, com_StartDate, com_EndDate);
    });


    /*-- 二個月 --*/
    $('#search_month2_btn').click(function (e) { 
      var an_StartDate=new Date();
          an_StartDate=DateFormat(an_StartDate.setMonth(an_StartDate.getMonth() -2));
      var an_EndDate=new Date();
          an_EndDate=DateFormat(an_EndDate.setDate(an_EndDate.getDate() - 1));
      var com_StartDate='';
      var com_EndDate='';

      //-- DEMO --
      if ($('[name="admin_per"]').val()=="group2020040610522078") {
        // an_StartDate=String(an_StartDate).split('-');
        // an_EndDate=String(an_EndDate).split('-');
        // an_StartDate='2019-'+an_StartDate['1']+'-'+an_StartDate[2];
        // an_EndDate='2019-'+an_EndDate['1']+'-'+an_EndDate[2];
        an_StartDate='2019-03-11';
        an_EndDate='2019-05-11';
      }

      $('.sel_time_btn').removeClass('active');
      $(this).addClass('active');
      $('#an_StartDate').val(an_StartDate);
      $('#an_EndDate').val(an_EndDate);
      
      all_an_ajax(an_StartDate, an_EndDate, com_StartDate, com_EndDate);
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


  
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>