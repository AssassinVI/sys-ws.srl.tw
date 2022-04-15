<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<link rel="stylesheet" href="../../css/an_style.css?20">
<style>
 #timeOnSite_div{display: none;}

 /*----------------- 新分析 ----------------------*/
 * {padding: 0; margin: 0; box-sizing: border-box;  }
 .date_none{display: none !important;}
 .lineChart{height: 400px !important; }
 .pieChart{height:300px !important;}
 .barChart{height:400px !important; }
 .mixChart{height:300px !important;}
 .pieChart.sm_chart{height:300px !important;}
 .tooltip-inner{background-color: #eee; color:#333;  font-size:15px; letter-spacing:1px; font-weight: 300;}
 .tooltip.right .tooltip-arrow{border-right-color: #eee;}
 .tooltip.bottom .tooltip-arrow{border-bottom-color: #eee;}

 #search_date_btn{ border-radius: 30px;}
 #search_date_div input{border: none; outline: none; margin: 0 10px; box-shadow: 2px 2px 5px rgb(0 0 0 / 10%) inset; border-radius: 30px;}
 #search_date_div.search_c{padding: 0px;background-color: #ffffff;}

 .an_tool a{ border-radius: 30px;}
 .an_title{font-weight: 700; color: #8d8d8d; letter-spacing: 2px; margin: 50px 0 10px 0;}

 /*-- top 功能bar --*/
 .tabs-container.p-fixed #tabs_list, .tabs-container.p-fixed .flex_box.ai-center{display: none;}
 .tabs-container.p-fixed{width: auto; background: none;}
 .tabs-container.p-fixed .panel-body{ border-radius: 0px 0px 20px 20px; box-shadow: 0px 0px 20px rgb(0 0 0 / 20%); padding: 15px;}
 .tabs-container .panel-body{border-radius: 20px; box-shadow: 0px 0px 20px rgb(0 0 0 / 10%);}
 .an_list_div h2{padding: 0px 10px 0 0; margin: 0;}
 .an_list_div .flex_box.ai-center{margin-bottom: 15px;} 
 .anchor_box{margin-left: 8px;}
 .anchor_box button{border-radius: 30px;}

 #top_div{margin-top:0;}

 .flex_box{display:flex;}
 .flex_box.ai-center{align-items: center;}
 .flex_box .flex_item{}
 .flex_box.column{flex-direction: column;}
 .flex_box.jc-space-between{justify-content: space-between;}

 .chart_box{display: flex; margin: 0 -15px;}
 .chart_box>div{padding:15px;}
 .chart_box .float-e-margins{ position: relative; border-radius: 20px; overflow: hidden; box-shadow: 0px 0px 20px rgb(0 0 0 / 10%); height: 100%;  background-color: #fff;}
 .chart_box .box_100{flex:1 1 100%; width: 100%;}
 .chart_box .box_50{flex:1 1 50%; width: 50%;}
 .chart_box .box_30{flex:1 1 33%; width: 33%;}

  .ibox-title{ height: auto; min-height: auto; padding: 20px;}
  .ibox-title h5{ float: none; margin: 0; font-size: 18px; font-weight: 400; justify-content: space-between;}
  .ibox-title h5:before{content:none;}
 .chart_item .flex_item{border-radius: 20px; overflow: hidden; box-shadow: 0px 0px 20px rgb(0 0 0 / 10%);  
                        position: relative; height: 145px;  background-color: #fff; padding: 16px 20px; display: flex; align-items: center;}
 .chart_item .flex_item .top_num .ibox-content{border:none; text-align: left; font-size: 35px; font-weight:700; padding: 0; background:none; letter-spacing: 1px;}
 .chart_item .flex_item .top_num i{position: absolute; right: -20px; bottom: -20px; font-size: 135px; color: rgb(255 255 255 / 20%);}
 .chart_item .new-title{font-size: 17px; font-weight: 300; margin-top:0; letter-spacing: 2px;}
 .chart_item .new-title small{color:#fff;}

  .inline_item.flex_item{ padding: 0px 20px;  height: 100px;}
  .inline_item.flex_item .top_num .ibox-content{line-height: 1;}


 /*-- 使用人數 --*/
 .user_box{flex-direction: row-reverse;}
 .user_box .line_ch_div{flex: 1 1 80%; width: 80%;}
 .user_box .chart_item{flex: 1 1 20%; width: 20%;}

 .user_box .chart_item .flex_item{
    color:#fff;
    background: #1c84c6;
    /* background: -moz-linear-gradient(45deg,  rgba(28,132,198,1) 0%, rgba(37,189,140,1) 100%); 
    background: -webkit-linear-gradient(45deg,  rgba(28,132,198,1) 0%,rgba(37,189,140,1) 100%); 
    background: linear-gradient(45deg,  rgba(28,132,198,1) 0%,rgba(37,189,140,1) 100%); */
 }



 /*-- 每日來信來電數 --*/
 .user_mail_box{    flex-direction: row-reverse;}
 .user_mail_box .line_ch_div{flex: 1 1 80%; width: 80%;}
 .user_mail_box .chart_item{flex: 1 1 20%; width: 20%;}

 .user_mail_box .chart_item .flex_item:nth-child(1){
    color:#fff;
    background:#00aabb;
    /* background: -moz-linear-gradient(45deg,  rgba(80,0,0,1) 0%, rgba(221,125,6,1) 100%); 
    background: -webkit-linear-gradient(45deg,  rgba(80,0,0,1) 0%,rgba(221,125,6,1) 100%); 
    background: linear-gradient(45deg,  rgba(80,0,0,1) 0%,rgba(221,125,6,1) 100%); */
 }
 .user_mail_box .chart_item .flex_item:nth-child(2){
    color:#fff;
    background: #cf7200;
    /* background: -moz-linear-gradient(45deg,  rgba(13,80,124,1) 0%, rgba(6,194,213,1) 100%); 
    background: -webkit-linear-gradient(45deg,  rgba(13,80,124,1) 0%,rgba(6,194,213,1) 100%); 
    background: linear-gradient(45deg,  rgba(13,80,124,1) 0%,rgba(6,194,213,1) 100%); */
 }
 .user_mail_box .chart_item .flex_item:nth-child(3){
    color:#fff;
    background: #bd3b33;
    /* background: -moz-linear-gradient(45deg,  rgba(227,119,0,1) 0%, rgba(6,194,213,1) 100%); 
    background: -webkit-linear-gradient(45deg,  rgba(227,119,0,1) 0%,rgba(6,194,213,1) 100%); 
    background: linear-gradient(45deg,  rgba(227,119,0,1) 0%,rgba(6,194,213,1) 100%); */
 }


 /*-- 互動比率 --*/
 .BounceRate_box{}
 .BounceRate_box .line_ch_div{flex: 1 1 80%; width: 80%;}
 .BounceRate_box .chart_item{flex: 1 1 20%; width: 20%;}

 .BounceRate_box .chart_item .flex_item{
    color:#fff;
    background: #1a79b5;
    /* background: -moz-linear-gradient(45deg,  rgba(217,39,164,1) 0%, rgba(0,167,231,1) 100%);
    background: -webkit-linear-gradient(45deg,  rgba(217,39,164,1) 0%,rgba(0,167,231,1) 100%);
    background: linear-gradient(45deg,  rgba(217,39,164,1) 0%,rgba(0,167,231,1) 100%); */
 }


  .re_visit{margin: 30px 20px;}
  .re_visit .flex_item{
    color:#fff;
    background: #199d20;
    /* background: -moz-linear-gradient(0deg,  rgb(15 122 28) 0%,rgba(32,183,34,1) 100%);
    background: -webkit-linear-gradient(0deg,  rgb(15 122 28) 0%,rgba(32,183,34,1) 100%); 
    background: linear-gradient(0deg,  rgb(15 122 28) 0%,rgba(32,183,34,1) 100%);  */
 }

 #month_src_ch{border-radius: 30px; padding: 0px 10px; border: 0; font-size: 16px; color: #333;}

 .media_item{margin: 15px 10px;}
 .media_item .flex_item{
    color:#fff;
    background: #2177ad;
    /* background: -moz-linear-gradient(0deg, rgb(20 69 100) 0%,rgb(28 132 198) 100%); 
    background: -webkit-linear-gradient(0deg, rgb(20 69 100) 0%,rgb(28 132 198) 100%); 
    background: linear-gradient(0deg, rgb(20 69 100) 0%,rgb(28 132 198) 100%); */
 }

 .interest_item{margin: 15px 10px;}
 .interest_item .flex_item{
    color:#fff;
    background: #d56217;
    /* background: -moz-linear-gradient(0deg, rgb(20 69 100) 0%,rgb(28 132 198) 100%); 
    background: -webkit-linear-gradient(0deg, rgb(20 69 100) 0%,rgb(28 132 198) 100%); 
    background: linear-gradient(0deg, rgb(20 69 100) 0%,rgb(28 132 198) 100%); */
 }

 .st_ch_box{ border-radius: 20px; overflow: hidden;  box-shadow: 1px 1px 5px rgb(13 53 78 / 30%); margin-bottom: 15px;}
 .st_ch_box h3{font-size: 16px; font-weight:500; padding: 15px; margin: 0;  color: #fff; letter-spacing: 1px;
  background: #1c84c6;
  /* background: -moz-linear-gradient(0deg, rgb(20 69 100) 0%,rgb(28 132 198) 100%);
  background: -webkit-linear-gradient( 0deg, rgb(20 69 100) 0%,rgb(28 132 198) 100%);
  background: linear-gradient( 47deg, rgb(27 88 126) 0%,rgb(23 144 221) 100%); */
 }

 .st_ch_box .st_dt_box{padding: 15px;}
 .st_ch_box .st_dt_box p{margin: 0;}
 .st_ch_box .st_dt_box .s_num{    color: #c9c9c9; font-weight: 300; font-size: 14px;}
 .st_ch_box .st_dt_box .s_sec{font-size: 17px;}

 .an_completion_box, .an_mail_box{flex:1 1 100%; width:100%;}
 .an_mail_box .ibox-title{ display: flex; justify-content: space-between; align-items: center; }
 .an_mail_box .tool a, .an_mail_box .tool button{margin:0;}
 .an_mail_box .remark_txt{ padding: 5px 10px; margin-left: 5px; }
 .an_mail_box .remark_td{display:flex; align-items: center;}

  /*----------------- 新分析 END ----------------------*/
 

  .legend_div{ width: 80%; padding: 0; margin:20px auto; }
  .legend_div li{display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e2e2e2; padding: 3px 0;}
  .legend_div li span{ margin-right: 10px;  width: 10px; height: 10px; display: inline-block; border-radius: 30px; }
  .legend_div li i{font-style: normal; font-size: 16px; font-weight: 300; color: #333;}
  .legend_div li b { color: #333; font-size: 16px;}

  .ibox-title h5 span{font-weight:500; margin-right: 7px;}
  .ibox-title h5 i{margin-right:5px;}
  .ibox-title h5 small{font-size: 13px; margin-top: 5px; font-weight: 300; letter-spacing: 1px;}

  .img_box{margin-top:15px; height: 240px; overflow: hidden; border-radius: 10px 10px 0px 0px;}
  .txt_box{ padding: 10px 15px; border: 1px solid #e5e5e5; border-radius: 0px 0px 10px 10px; border-top: 0;}
  .txt_box h3{margin:10px 0 15px 0; font-size: 20px; font-weight: 500;}
  .txt_box p{font-weight: 300; font-size: 15px; color: #333; margin:5px 0;}
  .txt_box .dt{display: flex; justify-content: space-between;  align-items: flex-end;}
  .txt_box .dt span{font-weight: 300; font-size: 16px; color: #333; letter-spacing: 1px; line-height: 1;}
  .txt_box .dt .cut_num{  font-weight: 700; font-size: 18px; color: #2196f3; line-height: 1;}


  .ch_tb tr th, .ch_tb tr td{font-size:16px;}
  .ch_tb .high_line{}

  .dark_btn{border-radius:30px;}


   /* ----------------------------- 暗黑模式 ------------------------------ */
   .dark_model b, 
   .dark_model strong,
   .dark_model .an_list_div h2,
   .dark_model #search_date_div p,
   .dark_model .an_title{color: #fff;}
   .dark_model .sel_time_btn{ color:#badfff;}
   .dark_model .btn-primary.btn-outline{color:#5cf5d6;}

   .dark_model.gray-bg{background-color: #202125;}
   .dark_model .white-bg{background-color: #2e3349;}
   .dark_model .border-bottom{border-bottom: 1px solid #2e3349 !important;}
   .dark_model .tabs-container .tab-pane .panel-body{background-color: #2e3349;}
   .dark_model .tabs-container .panel-body{border:none;}
   .dark_model #search_date_div.search_c{background-color: #2e3349;}
   .dark_model .chart_box .float-e-margins{ background-color: #2e3349;}
   .dark_model .ibox-title{background-color: #2e3349; color: #fff;}
   .dark_model .ibox-content{ background-color: #2e3349; border-color: #545a74;}
   .dark_model .btn-default:hover, 
   .dark_model .btn-default:focus, 
   .dark_model .btn-default:active, 
   .dark_model .btn-default.active{background-color: #6c6c6c !important;}
   .dark_model .legend_div li i,
   .dark_model .legend_div li b{color: #fff;}
   .dark_model .st_ch_box .st_dt_box .s_sec{color: #fff;}
   .dark_model .st_ch_box{box-shadow: 1px 1px 5px rgb(255 255 255 / 35%);}
   .dark_model .txt_box h3{color: #fff;}
   .dark_model .txt_box p{color: #fff;}
   .dark_model .txt_box .dt span{color: #fff;}
   .dark_model #an_mail_div th,
   .dark_model #an_mail_div td,
   .dark_model #ch_status,
   .dark_model .label{color: #fff;}
   .dark_model tbody tr:nth-child(odd){background-color: #232738;}
   .dark_model tbody tr:nth-child(even){background-color: #1b1f30;}
   .dark_model .txt_box{ border: 1px solid #444444;}
   .dark_model .ibox-title h5 small{color: #dbdbdb;}
   .dark_model [name="is_process"]{background: #333;}
   .dark_model .footer{background-color: #2e3349; border: 0;}
   .dark_model .footer div{color:#fff;}



 
 @media (max-width:1400px) {

  .chart_box{flex-wrap: wrap;}
  .chart_item .flex_item{width: 31.3%;}
  .chart_box.line_group{flex-direction: column-reverse;}
  .chart_box .box_30{  flex: 1 1 50%; width: 50%;}

  .chart_box .line_ch_div{flex: 1 1 100%; width: 100%;}
  .chart_box .chart_item{flex: 1 1 100%; width: 100%; flex-direction: initial;}

  /*-- 使用人數 --*/
  /* .user_box .line_ch_div{flex: 1 1 100%; width: 100%;}
  .user_box .chart_item{flex: 1 1 100%; width: 100%; flex-direction: inherit;} */

  .re_visit, .media_item{margin: 0;}
  .chart_item .flex_item.inline_item{width: 100%;}

  /*-- 互動比率 --*/
  .BounceRate_box .chart_item .flex_item{margin-bottom: 0px; margin-right: 30px;}
  
   
 }

 @media (max-width:768px) {
   #ph_tool{display:none; bottom: 10px; right: 0px; left: 0; text-align: center;}
   #ph_tool ul{display: none; justify-content: space-around; margin: 0;}
   #ph_tool .tool_btn{width: 50px; height: 50px; font-size: 13px;}
   #ph_tabs_btn, #ph_all_time_btn{ box-shadow: 1px 1px 5px rgb(0 0 0 / 40%);}
   #search_date_div input{width: 125px;}
   #search_date_div.search_c{padding: 10px;}
   .date_s_box{display: inline-flex !important; justify-content: space-between; align-items: center; }
   

   .tabs-container.p-fixed{width: 100%;}

   .an_tool{display:none;}
   .anchor_box{display:inline-flex !important; margin: 10px 0 0 0;}
   .anchor_box button{margin-right: 5px;}
   .ibox-content{padding: 15px 15px 20px 15px;}
   .lineChart{height: 350px !important; }
   .line_chart_div{width: 1200px !important;}
 }

 @media (max-width:500px){
   .tabs-container.p-fixed .panel-body{padding:0;}
   .an_title{padding:0 10px;}
   .chart_box{margin:0;}
   .chart_box .chart_item{flex-wrap: wrap;}
   .chart_item .flex_item{width: 100%; height: 100px; margin-bottom: 15px;}
   .chart_item .new-title{font-size: 16px; font-weight: 500;}
   .chart_item .flex_item .top_num .ibox-content{font-size: 28px;}
   .chart_box .box_30{flex: 1 1 100%; width: 100%;}
   .chart_box>div{padding:7.5px;}


   .BounceRate_box .chart_item .flex_item{    margin-bottom: 15px;  margin-right: 0px;}

   .ibox-title h5 small{ line-height: 1.3;}

   .txt_box .dt span{font-size: 13px;}
   .date_s_box{display: flex !important;}
   #search_date_div input{margin: 0;}

   #ch_status span{display: block;}
   #ch_status .label{width: 49%; display: inline-block;}
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
       <div class="new_div">
         <input name="admin_per" type="hidden" value="<?php echo $_SESSION['admin_per'];?>">
      </div>
    </div>

   <?php 
     if ($_SESSION['admin_per']=='admin' || $_SESSION['admin_per']=='group2020040610522078' || $_COOKIE['is_an_all']=='1') {
   ?>

   
   <div class="col-lg-12 ">
   <div class="tab_fixed_h">
     <!-- 手機佔位 -->
   </div>
   <div class="tabs-container print_none">
       <div id="tabs_list" class="slideUp">

       <a class="tabs_list_close" href="javascript:;">Ｘ</a>
       </div>
       
       <div class="tab-content">

       <div id="all" class="tab-pane active">
          <div class="panel-body">
            <div class="an_list_div">
              <div class="flex_box ai-center">
                 <h2><?php echo $row_name['aTitle'];?> Google分析 </h2>
                 <div class="an_tool">
                   <a class="print_btn btn btn-success" href="javascript:;"><i class="fa fa-print"></i> 列印報表</a>
                   <a class="fancybox btn btn-success" data-fancybox-type="iframe" href="../case_url/catch_web.php?Tb_index=<?php echo $_GET['Tb_index'];?>"><i class="fa fa-globe"></i> 分析網址</a>
                  </div>
              </div>
              
              <div id="search_date_div" class="search_c" >
                <p>
                  <span>篩選時間：</span>
                    <a class="sel_time_btn active" href="javascript:;" id="search_all_btn" data-toggle="tooltip" data-placement="bottom" data-original-title="總數統計">全部</a>
                    <a class="sel_time_btn" href="javascript:;" id="search_week_btn" >前一週</a>
                    <a class="sel_time_btn" href="javascript:;" id="search_week2_btn" >前兩週</a>
                    <a class="sel_time_btn" href="javascript:;" id="search_month_btn" >前一個月</a>
                    <a class="sel_time_btn" href="javascript:;" id="search_month2_btn" >前兩個月</a>
                  <span class="date_s_box">
                    <input type="text" id="an_StartDate" readonly> ~ <input type="text" id="an_EndDate" readonly> <button id="search_date_btn" class="btn btn-success" type="button"><i class="fa fa-search"></i> 查詢</button>
                  </span>
                  <span class="anchor_box">
                      <button class="btn btn-outline btn-primary" anchor_id="#user_anchor" type="button"><i class="fa fa-users"></i> 使用者</button>
                      <button class="btn btn-outline btn-primary" anchor_id="#media_anchor" type="button"><i class="fa fa-laptop"></i> 媒體</button>
                      <button class="btn btn-outline btn-primary" anchor_id="#src_anchor" type="button"><i class="fa fa-search"></i> 來源</button>
                  </span>
                  <button class="btn btn-outline btn-default dark_btn" type="button"><i class="fa fa-lightbulb-o"></i> 深色模式</button>
                </p>
              </div>
            
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
    <p>
      <span>篩選時間：</span><input type="text" id="an_StartDate" readonly> ~ <input type="text" id="an_EndDate" readonly> <button id="search_date_btn" class="btn btn-success" type="button">查詢</button>
      <span class="anchor_box">
          <button class="btn btn-outline btn-primary" type="button"><i class="fa fa-users"></i> 使用者</button>
          <button class="btn btn-outline btn-primary" type="button"><i class="fa fa-laptop"></i> 媒體</button>
          <button class="btn btn-outline btn-primary" type="button"><i class="fa fa-search"></i> 來源</button>
      </span>
   </p>
  </div>
 </div>


<?php }?>
    
    <div id="top_div" class="col-lg-12" >

      <!-- 新分析畫面 -->
      <div class="an_div">

          <h2 id="user_anchor" class="an_title"><i class="fa fa-users"></i> 使用者</h2>

          <!-- 每日使用人數 -->
          <div class="user_box chart_box line_group">
             <div class="line_ch_div">
                <div class=" float-e-margins">
                      <div class="ibox-title">
                          <h5>每日使用人數 (人數)</h5>
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

              <div id="user_data_div" class="flex_box column jc-space-between chart_item">
                   <div class="flex_item">
                      <div id="week_users" class=" one_week_h5 top_num ">
                          <i class="fa fa-user"></i>
                          <h5 class="new-title"><span class="title_txt">一周<span class="ph_none">瀏覽</span>人數</span> </h5>
                          <div id="max_user" class="ibox-content user_num"> 讀取中... </div>
                      </div>
                    </div>

                    <div class="flex_item">
                      <div id="month_users" class=" one_month_h5 top_num ">
                          <i class="fa fa-user"></i>
                          <h5 class="new-title" ><span class="title_txt">一個月<span class="ph_none">瀏覽</span>人數</span></h5>
                          <div id="min_user" class="ibox-content user_num"> 讀取中...</div>
                      </div>
                    </div>

                    <div class="flex_item">
                      <div id="all_users" class=" all_user_h5 top_num ">
                          <i class="fa fa-users"></i>
                          <h5 class="new-title">總瀏覽人數</h5>
                          <div id="all_user" class="ibox-content user_num"> 讀取中...</div>
                      </div>
                    </div>
              </div>
          </div>
          <!-- 每日使用人數 END -->


          <div class="chart_box">
            <!-- 使用者性別 -->
            <div class="sex_box pie_ch_div box_30">
              <div class="float-e-margins">
                    <div class="ibox-title">
                        <h5>使用者性別 (人數)</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="sex_chart_div">
                          <canvas id="sex_chart" class="pieChart"></canvas>
                        </div>
                        <ul id="sex_legend" class="legend_div"></ul>
                    </div>
              </div>
            </div>
            <!-- 使用者年齡 -->
            <div class="yaers_box bar_ch_div box_30">
               <div class="float-e-margins">
                    <div class="ibox-title">
                        <h5>使用者年齡 (人數)
                        </h5>
                    </div>
                    <div class="ibox-content">
                        <div class="years_chart_div">
                          <canvas id="years_chart" class="barChart"></canvas>
                        </div>
                    </div>
               </div>
            </div>
            <!-- 使用者地區 -->
            <div class="area_box bar_ch_div box_30">
              <div class="float-e-margins">
                  <div class="ibox-title">
                      <h5>各地區使用者 (人數)</h5>
                  </div>
                  <div class="ibox-content">
                      <div id="city_chart_div">
                        <canvas id="city_chart" class="barChart"></canvas>
                      </div>
                  </div>
              </div>
            </div>
          </div>

          

          <div class="chart_box">
            <div class="bar_ch_div box_30">
                <div class=" float-e-margins">
                    <div class="ibox-title">
                        <h5>使用者興趣 (人數)</h5>
                    </div>
                    <div class="ibox-content" >
                        <div class="interest_chart_div">
                            <canvas id="interest_chart" class="barChart"></canvas>
                        </div>

                        <div class="interest_item interest_max chart_item">
                          <div class="flex_item inline_item">
                            <div class=" one_week_h5 top_num ">
                                <i class="fa fa-gratipay"></i>
                                <h5 class="new-title"> <span class="title_txt">主要興趣</span> </h5>
                                <div class="ibox-content user_num">讀取中...</div>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bar_ch_div box_30">
                <div class=" float-e-margins">
                    <div class="ibox-title">
                        <h5>使用者興趣的互動比率 (百分比)</h5>
                    </div>
                    <div class="ibox-content" >
                        <div class="interest_br_chart_div">
                            <canvas id="interest_br_chart" class="barChart"></canvas>
                        </div>
                        <div class="interest_item interest_max_br chart_item">
                          <div class="flex_item inline_item">
                            <div class=" one_week_h5 top_num ">
                                <i class="fa fa-gratipay"></i>
                                <h5 class="new-title"> <span class="title_txt">最佳互動興趣</span> </h5>
                                <div class="ibox-content user_num">讀取中...</div>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="area_box pie_ch_div box_30">
              <div class=" float-e-margins">
                  <div class="ibox-title">
                      <h5>新舊訪客/回訪率 (人數)</h5>
                  </div>
                  <div class="ibox-content">
                      <div class="visit_chart_div">
                        <canvas id="visit_chart" class="pieChart sm_chart"></canvas>
                      </div>
                      <ul id="visit_legend" class="legend_div"></ul>
                      <div class="re_visit chart_item">
                          <div class="flex_item inline_item" >
                            <div class=" one_week_h5 top_num ">
                                <i class="fa fa-pie-chart"></i>
                                <h5 class="new-title"> <span class="title_txt">回訪率</span> </h5>
                                <div class="ibox-content user_num" data-toggle="tooltip" data-placement="right" data-original-title="新訪客 / (新訪客+回訪客)">讀取中...</div>
                            </div>
                          </div>
                      </div>
                  </div>
               </div>
            </div>
          </div>
          


          <h2 id="media_anchor" class="an_title"><i class="fa fa-laptop"></i> 媒體</h2>

          <!-- 每日來信來電 -->
          <div class="user_mail_box chart_box line_group">

              <div class="line_ch_div">
                  <div class=" float-e-margins">
                      <div class="ibox-title">
                          <h5>每日來信來電 (人數)</h5>
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

              <div id="user_data_div" class="flex_box column jc-space-between chart_item">
                <div class="flex_item">
                  <div id="adv_mails" class=" one_week_h5 top_num ">
                      <i class="fa fa-envelope"></i>
                      <h5 class="new-title"> <span class="title_txt">來信數</span> </h5>
                      <div id="adv_mail" class="ibox-content user_num">讀取中...</div>
                  </div>
                </div>
                <div class="flex_item">
                  <div id="adv_phones" class=" one_month_h5 top_num ">
                      <i class="fa fa-phone"></i>
                      <h5 class="new-title"> <span class="title_txt">來電數</span></h5>
                      <div id="adv_phone" class="ibox-content user_num">讀取中...</div>
                  </div>
                </div>
                <div class="flex_item">
                  <div id="adv_calls" class=" all_user_h5 top_num ">
                      <i class="fa fa-pie-chart"></i> 
                      <h5 class="new-title"><span class="title_txt">聯絡比率</span></h5>
                      <div id="adv_call" class="ibox-content user_num">讀取中...</div>
                  </div>
                </div>
              </div>
          </div>
          <!-- 每日來信來電 END -->

  
          <div class="chart_box">
            
            <div class="media_box bar_ch_div box_30">
              <div class="float-e-margins">
                    <div class="ibox-title">
                        <h5>使用的媒體 (人數)</h5>
                    </div>
                    <div class="ibox-content">
                        <div id="media_chart_div">
                          <canvas id="media_chart" class="barChart"></canvas>
                        </div>
                        <div class="media_item chart_item">
                          <div class="flex_item inline_item">
                            <div class=" one_week_h5 top_num ">
                                <i class="fa fa-pie-chart"></i>
                                <h5 class="new-title"> <span class="title_txt">行動裝置比率</span> </h5>
                                <div class="ibox-content user_num">讀取中...</div>
                            </div>
                          </div>
                      </div>
                    </div>
              </div>
            </div>

            <div class="media_box bar_ch_div box_30">
              <div class="float-e-margins">
                    <div class="ibox-title">
                        <h5>使用的瀏覽器 (人數)</h5>
                    </div>
                    <div class="ibox-content">
                        <div id="broswer_chart_div">
                          <canvas id="broswer_chart" class="barChart"></canvas>
                        </div>
                        <div class="media_item broswer_item chart_item">
                          <div class="flex_item inline_item">
                            <div class=" one_week_h5 top_num ">
                                <i class="fa fa-cogs"></i>
                                <h5 class="new-title"> <span class="title_txt">最多人使用的瀏覽器</span> </h5>
                                <div class="ibox-content user_num">讀取中...</div>
                            </div>
                          </div>
                        </div>
                    </div>
              </div>
            </div>

            <div class="event_box bar_ch_div box_30">
              <div class="float-e-margins">
                  <div class="ibox-title">
                      <h5>使用的功能鈕 (人數)</h5>
                  </div>
                  <div class="ibox-content">
                      <div id="event_chart_div">
                        <canvas id="event_chart" class="pieChart"></canvas>
                      </div>
                      <ul id="event_legend" class="legend_div"></ul>
                  </div>
              </div>
            </div>
          </div>

          <!-- 互動比率 -->
          <div class="BounceRate_box chart_box line_group">
              <div class="line_ch_div">
                <div class=" float-e-margins">
                      <div class="ibox-title">
                          <h5><span>互動比率 (百分比) </span><small>互動數 / 總曝光 (備註：互動數依據點擊功能鈕或瀏覽畫面一半以上)</small></h5>
                      </div>
                      <div class="ibox-content" style="overflow-x: auto;">
                          <div id="BounceRate_div" class="line_chart_div">
                            <canvas id="BounceRate_chart" class="lineChart"></canvas>
                          </div>
                          <div class="ph_time_txt">
                            可往右拖曳顯示全部 <i class="fa fa-arrow-right"></i>
                          </div>
                      </div>
                  </div>
              </div>

              <div id="user_data_div" class="flex_box column jc-space-between chart_item ">
                <div class="flex_item">
                  <div id="max_BounceRates" class=" one_week_h5 top_num ">
                      <i class="fa fa-pie-chart"></i>
                      <h5 class="new-title"> <span class="title_txt">最大互動比率</span> </h5>
                      <div id="max_BounceRate" class="ibox-content user_num">讀取中...</div>
                  </div>
                </div>
                <div class="flex_item">
                  <div id="min_BounceRates" class=" one_week_h5 top_num ">
                      <i class="fa fa-pie-chart"></i>
                      <h5 class="new-title"> <span class="title_txt">最小互動比率</span> </h5>
                      <div id="min_BounceRate" class="ibox-content user_num">讀取中...</div>
                  </div>
                </div>
                <div class="flex_item">
                  <div id="avg_BounceRates" class="one_month_h5 top_num ">
                      <i class="fa fa-pie-chart"></i> 
                      <h5 class="new-title"><span class="title_txt">平均互動比率</span></h5>
                      <div id="avg_BounceRate" class="ibox-content user_num">讀取中...</div>
                  </div>
                </div>
              </div>
          </div>
          <!-- 互動比率 END -->


          

          <h2 id="src_anchor" class="an_title"><i class="fa fa-search"></i> 來源</h2>

          <div class="chart_box">

            <div class="month_src_box pie_ch_div box_30">
              <div class="float-e-margins">
                  <div class="ibox-title">
                      <h5><span><?php echo date('n');?>月流量來源 (人數)</span><input type="month" id="month_src_ch" max="<?php echo date('Y-m');?>"  value="<?php echo date('Y-m');?>"> </h5>
                  </div>
                  <div class="ibox-content">
                      <div id="month_src_chart_div">
                        <canvas id="month_src_chart" class="pieChart"></canvas>
                      </div>
                      <ul id="month_src_legend" class="legend_div"></ul>
                  </div>
              </div>
            </div>

            <div class="src_box pie_ch_div box_30">
              <div class="float-e-margins">
                  <div class="ibox-title">
                      <h5><span>流量來源 (人數)</span><small>默認總流量來源 此項目會隨著所選時間變換分析數字</small></h5>
                  </div>
                  <div class="ibox-content">
                      <div id="src_chart_div">
                        <canvas id="src_chart" class="pieChart"></canvas>
                      </div>
                      <ul id="src_legend" class="legend_div"></ul>
                  </div>
              </div>
            </div>

            <div class="src_time_box tb_ch_div box_30">
              <div class="float-e-margins">
                  <div class="ibox-title">
                      <h5>來源使用時間 (秒數)</h5>
                  </div>
                  <div class="ibox-content">
                      <div id="src_time_chart_div">
                              
                      </div>

                      <!-- <div class="st_ch_box">
                        <h3>Yahoo原生 / 網址</h3>
                        <div class="st_dt_box">
                          <p class="s_sec">平均使用時間(秒)：24秒</p>
                          <p class="s_num">來源數：16170人</p>
                        </div>
                      </div> -->
                  </div>
              </div>
            </div>
          </div>


          <div class="chart_box">
            <div class="an_completion_box">
              <div class="float-e-margins">
                <div class="ibox-title">
                    <h5>網頁瀏覽程度 (人數)</h5>
                </div>
                <div class="ibox-content">
                    <div id="an_completion" class="row">
                      
                    </div>
                </div>
              </div>
            </div>
          </div>


          <div class="chart_box">
            <div class="an_mail_box">
              <div class=" float-e-margins">
                  <div class="ibox-title">
                      <h5>預約賞屋來信</h5>
                      <div class="tool">
                        <a href="../../module/msg/put_excel.php?case_id=<?php echo $_GET['Tb_index'];?>" class="btn btn-primary btn-sm">下載Excel檔</a>
                        <button type="button" class="btn btn-success btn-sm slide_mail_btn">展開</button>
                      </div>
                      
                  </div>
                  <div class="ibox-content">
                      
                      <div id="an_mail_div"  style="display:none;">   
                        <div class="text-right">
                          <div id="ch_status">
                            篩選狀態：
                            <span>
                              <a href="javascript:;" class="label status_btn" status="">全部：<b>10</b></a>
                              <a href="javascript:;" class="label label-danger status_btn" status="0">未處理：<b>10</b></a>
                              <a href="javascript:;" class="label label-warning status_btn" status="2">處理中：<b>10</b></a>
                              <a href="javascript:;" class="label label-primary status_btn" status="1">已處理：<b>10</b></a>
                            </span>
                          </div>
                        </div>                           
                        <div class="table-responsive">
                          <table class="table no-margin">
                          <thead>
                            <tr>
                              <th width="20">#</th>
                              <th width="100">時間</th>
                              <th width="80">姓名</th>
                              <th>電話</th>
                              <th class="print_none">E-mail</th>
                              <th>其他</th>
                              <th width="120">來源</th>
                              <th class="print_none">狀態</th>
                              <th class="print_none">管理</th>
                              <th width="200">備註</th>
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
      <!-- 新分析畫面 END -->

     <div class="row">

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
              
            
        </div>
    </div>
    
    
		

	</div>

  <!-- 手機標籤按鈕 -->
  <?php 
    $an_manage='';

    if ($_SESSION['admin_per']=='admin' || $_SESSION['admin_per']=='group2020040610522078' || $_COOKIE['is_an_all']=='1'){
      // $an_manage='<li><a class="tool_btn fancybox" data-fancybox-type="iframe" href="new_tag/index.html?'.$_GET['Tb_index'].'">新增比較</a></li>
      //             <li><a id="manage1_btn" class="tool_btn" href="javascript:;">管理比較</a></li>';
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
<!-- <script type="text/javascript" src="../../js/plugins/jsPDF/jspdf.min.js"></script> -->
<!-- <script src="../../js/plugins/chartjs/Chart.min.js?1"></script>
<script src="../../js/plugins/chartjs/chartjs-plugin-annotation.min.js"></script>
<script src="../../js/plugins/chartjs/chartjs-plugin-datalabels.min.js"></script> -->
<script src="../../js/plugins/chartjs/3.6.0/chart.min.js"></script>
<script src="../../js/plugins/chartjs/3.6.0/chartjs-plugin-annotation.min.js"></script>
<script src="../../js/plugins/chartjs/3.6.0/chartjs-plugin-datalabels.min.js"></script>
<script src="../../js/an_Class/Chart_class_v3.js?10"></script>
<script src="../../js/an_Class/an_Class_v3.js?10"></script>



<script type="text/javascript">
  
  //-- 建案ID --
  var Tb_index =location.search.split('&');
      Tb_index=Tb_index[1].split('=');
      Tb_index=Tb_index[1];

  /*-- 分析表 --*/
  var _user__chart, 
      _mail_date__chart, 
      _sex__chart, 
      _years__chart, 
      _city__chart, 
      _interest__chart,
      _interest_br__chart,
      _visit__chart, 
      _media__chart, 
      _broswer__chart, 
      _event__chart, 
      _month_src__chart, 
      _src__chart, 
      _src_time_chart, 
      _BounceRate__chart;


  //----------------- 新分析 --------------------
  var AnAll=new CaseAn;

    var an_StartDate='';
    var an_EndDate='';
    var com_StartDate='';
    var com_EndDate='';


	$(document).ready(function() {

     $('[data-toggle="tooltip"]').tooltip();

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


    //-- 查詢狀態來信 --
    $('.status_btn').click(function (e) { 
      $.ajax({
        type: "POST",
        url: "an_chart_ajax.php",
        data: {
          type:'an_mail',
          case_id:$('#Tb_index').val(),
          s_date:$('#an_StartDate').val(),
          e_date:$('#an_EndDate').val(),
          is_process:$(this).attr('status')
        },
        dataType: "json",
        success: function (data) {
          //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 預約賞屋來信 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
          AnAll.mail_list({
            DIV_id:'#an_mail_div table tbody',
            show_is_process:true,
            user_data: data.data
          });
        }
      });
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



    /*================================== 全部 =========================================*/
    $('#search_all_btn').click(function (e) { 
      $('.sel_time_btn').removeClass('active');
      $(this).addClass('active');
      $('#an_StartDate').val('');
      $('#an_EndDate').val('');
      
      get_an({
        destroy:true
      });
    });


    /*========================================== 月流量來源 切換 ==========================================*/
    $('#month_src_ch').change(function (e) { 
      
      let month_num=$(this).val();

      $.ajax({
        type: "POST",
        url: "an_chart_ajax.php",
        data: {
          type:'month_src_ch',
          case_id: $('#Tb_index').val(),
          month_num: month_num
        },
        dataType: "json",
        success: function (data) {
          //console.log(data);
          _month_src__chart.destroy();
          _month_src__chart= AnAll.src_chart({
                  chart_id: 'month_src_chart', 
                  user_data: data.data, 
                  legend_id: 'month_src_legend'
                });
          let month_arr = month_num.split('-');
          let month_txt= month_arr[1].slice(1);
          $('.month_src_box h5 span').html(`${month_txt}月流量來源 (人數)`);
        }
      });
      
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

          get_an_mail ();
        }
      });
    });


    //-- 來信備註 --
    $('#an_mail_div').on('click', '.remark_btn', function (){

      var _this=$(this);

      let remark_txt=prompt('來信備註文字', _this.next().html());
      if(remark_txt==null){
      
      }
      else{
      $.ajax({
        type: "POST",
        url: "an_chart_ajax.php",
        data: {
          type: 'ed_mail_remark',
          Tb_index: _this.attr('case_id'),
          remark:remark_txt
        },
        success: function(data) {
          get_an_mail ();
        }
      });
      }
    });



    //-- 卷軸監聽 --
    $(window).bind('scroll resize', function() {

      var top=$(this).scrollTop();
      //console.log(top);
      if (top>150){
          $('.tabs-container, #top_div, #search_date_div').addClass('p-fixed');
      }
      else{
          $('.tabs-container, #top_div, #search_date_div').removeClass('p-fixed');
      }
      
      });


    $('.anchor_box button').click(function (e) { 
      e.preventDefault();
      let offset_cu=0;
      if($(window).width()>800){
        offset_cu=120;
      }
      else{
        offset_cu=180;
      }
      let _this=$(this);
      $('html,body').animate({
          scrollTop: $(`${_this.attr('anchor_id')}`).offset().top-offset_cu
      },1000);
    });


  //-- 切換黑暗模式 --
    $('.dark_btn').click(function (e) { 
      let chart_arr=[
          _user__chart, 
          _mail_date__chart,
          _years__chart,
          _city__chart,
          _interest__chart,
          _interest_br__chart,
          _media__chart,
          _broswer__chart,
          _BounceRate__chart
          ];
      if($('#page-wrapper').attr('class').indexOf('dark_model')==-1){
        $('#page-wrapper').addClass('dark_model');
        chart_arr.forEach(chart => {
          chart.options.scales.y.grid.color='#ffffff30';
          chart.options.scales.x.grid.color='#ffffff30';
          chart.options.scales.y.ticks.color='#fff';
          chart.options.scales.x.ticks.color='#fff';
          chart.options.plugins.legend.labels.color='#fff';
          chart.update();
        });
        
      }
      else{
        $('#page-wrapper').removeClass('dark_model');
        chart_arr.forEach(chart => {
          chart.options.scales.y.grid.color='#E5E5E5';
          chart.options.scales.x.grid.color='#E5E5E5';
          chart.options.scales.y.ticks.color='#333';
          chart.options.scales.x.ticks.color='#333';
          chart.options.plugins.legend.labels.color='#333';
          chart.update();
        });
      }
    });




}); //JQUERY END



function get_an_mail () {
  $.ajax({
    type: "POST",
    url: "an_chart_ajax.php",
    data: {
      type:'an_mail',
      case_id:$('#Tb_index').val(),
      s_date:$('#an_StartDate').val(),
      e_date:$('#an_EndDate').val(),
      is_process:''
    },
    dataType: "json",
    success: function (data) {
      //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 預約賞屋來信 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
      AnAll.mail_list({
        DIV_id:'#an_mail_div table tbody',
        show_is_process:true,
        user_data: data.data
      });
    }
  });
}



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
              _interest__chart.destroy();
              _interest_br__chart.destroy();
              _media__chart.destroy();
              _broswer__chart.destroy();
              _event__chart.destroy();
              // _month_src__chart.destroy();
              _src__chart.destroy();
              _BounceRate__chart.destroy();
              _visit__chart.destroy();
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


          if ($('#an_StartDate').val() != ''){
            //-- 來信數 --
            let total_user_mail=data.data.user_mail.reduce( (a,b)=>{ return a + b;});
            //-- 來電數 --
            let total_user_phone=data.data.user_phone.reduce( (a,b)=> { return a + b; });
            //-- 聯絡比率 --
            let adv_call=Math.round(((total_user_mail+total_user_phone)/data.data.total_user)*10000)/100;
            $('#adv_mails .user_num').html(total_user_mail + '封');
            $('#adv_phones .user_num').html(total_user_phone + '通');
            $('#adv_calls .user_num').html(adv_call + '%');
          }
          else{
            let total_user_mail= parseInt(data.data.all_mail);
            let total_user_phone=parseInt( data.data.all_phone);
            let adv_call=Math.round(((total_user_mail+total_user_phone)/data.data.total_user)*10000)/100;
            $('#adv_mails .user_num').html(total_user_mail + '封');
            $('#adv_phones .user_num').html(total_user_phone + '通');
            $('#adv_calls .user_num').html(adv_call + '%');
          }
          

          

          //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 每日互動率 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
          //-- 跳出率轉互動率 --
          let br_arr=[];
          data.data.BounceRate.forEach(br => {
            let new_br=(100-br)==100 ? 0:Math.round((100-br)*10)/10;
            br_arr.push(new_br);
          });
          //-- 最大互動率 --
          let max_br_index = br_arr.indexOf(Math.max(...br_arr));
          //-- 最小互動率 --
          let min_br_index = br_arr.indexOf(Math.min(...br_arr));
          _BounceRate__chart= AnAll.BounceRate_chart({
            chart_id:'BounceRate_chart', 
            user_data: br_arr, 
            date: data.data.date, 
            max_br_date: data.data.date[max_br_index],
            min_br_date: data.data.date[min_br_index]
          });
          //-- 平均互動率 --
          let total_BounceRate=br_arr.reduce( (a,b)=>{ return a + b;});
          let close_0_br_arr=br_arr.filter(function (item) {
            return item > 0;
          });
          let avg_BounceRate=Math.round((total_BounceRate/close_0_br_arr.length)*10)/10;

          $('#max_BounceRates .user_num').html(br_arr[max_br_index]+'%');
          $('#min_BounceRates .user_num').html(br_arr[min_br_index]+'%');
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


          //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 興趣 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
          _interest__chart= AnAll.interest_chart({
            chart_id: 'interest_chart', 
            user_data: data.data.interest
          });

          if(data.data.interest.length>0){
            let interest_total_arr=[];
            data.data.interest.forEach(interest => {
              interest_total_arr.push(parseInt( interest.total));
            });
            //-- 最大互動率 --
            let max_interest_index = interest_total_arr.indexOf(Math.max(...interest_total_arr));
            $('.interest_max .user_num').html(data.data.interest[max_interest_index].tw_name);
          }
          else{
            $('.interest_max .user_num').html('無資料');
          }
          


          //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 興趣互動比率 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
          //-- 跳出率轉互動率 --
          let interest_br_arr=[];
          data.data.interest_br.forEach((br,index) => {
            let new_br=(100-br.total)==100 ? 0:Math.round((100-br.total)*10)/10;
            data.data.interest_br[index].total=new_br;
          });
          _interest_br__chart= AnAll.interest_br_chart({
            chart_id: 'interest_br_chart', 
            user_data: data.data.interest_br
          });

          if(data.data.interest_br.length>0){
            let interest_br_total_arr=[];
            data.data.interest_br.forEach(interest_br => {
              interest_br_total_arr.push(parseInt( interest_br.total));
            });
            //-- 最大互動率 --
            let max_interest_br_index = interest_br_total_arr.indexOf(Math.max(...interest_br_total_arr));
            $('.interest_max_br .user_num').html(data.data.interest[max_interest_br_index].tw_name);
          }
          else{
            $('.interest_max_br .user_num').html('無資料');
          }

          


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

          //-- 行動裝置比率 --
          let media_total=0;
          data.data.media.forEach(one => {
              media_total+=parseInt( one.total);
          });
          let phone_num=data.data.media[1]!=undefined ? parseInt(data.data.media[1].total):0;
          let table_num=data.data.media[2]!=undefined ? parseInt(data.data.media[2].total):0;
          let phone_media_total=phone_num+table_num;
          let avg_media=Math.round( (phone_media_total/media_total)*1000)/10;
          $('.media_item .user_num').html(`${avg_media}%`);


          //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 使用的瀏覽器 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
          _broswer__chart=AnAll.broswer_chart({
            chart_id: 'broswer_chart',
            user_data: data.data.broswer
          });

          //-- 最多人使用的瀏覽器 --
          let broswer_type_arr=[];
          let broswer_total_arr=[];
          data.data.broswer.forEach(one => {
              broswer_type_arr.push(one.broswer_type);
              broswer_total_arr.push(one.total);
          });
          
          let max_broswer_index = broswer_total_arr.indexOf(Math.max(...broswer_total_arr).toString());
          $('.broswer_item .user_num').html(`${broswer_type_arr[max_broswer_index]}`);



          //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 使用功能 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
          _event__chart= AnAll.event_chart({
            chart_id: 'event_chart', 
            user_data: data.data.event, 
            legend_id: 'event_legend'
          });


          //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 當月流量 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
          if(destroy==false){
             _month_src__chart= AnAll.src_chart({
              chart_id: 'month_src_chart', 
              user_data: data.data.month_src, 
              legend_id: 'month_src_legend'
            });
          }
         


          //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 總流量 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
          _src__chart= AnAll.src_chart({
            chart_id: 'src_chart', 
            user_data: data.data.src, 
            legend_id: 'src_legend'
          });


          //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 來源使用時間 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
          _src_time_chart= AnAll.src_time_chart({
            chart_id: 'src_time_chart_div', 
            user_data: data.data.src_time,
            src_data:data.data.src
          });


          //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 網頁瀏覽程度 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
          AnAll.completion_chart({
            DIV_id: 'an_completion', 
            chart_id: 'mixChart', 
            div_class: 'col-lg-4 col-md-6',
            completion_data: data.data.completion, 
            date: data.data.date
          });


          //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 預約賞屋來信 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
          AnAll.mail_list({
            DIV_id:'#an_mail_div table tbody',
            show_is_process:true,
            user_data: data.data.mail
          });
          //-- 各狀態數量 --
          let is_process_arr=[0,0,0];
          data.data.mail.forEach(mail => {
            switch (mail.is_process) {
              case '0':
                is_process_arr[0]++;
              break;
              case '1':
                is_process_arr[1]++;
              break;
              case '2':
                is_process_arr[2]++;
              break;
            }
          });
          $('.status_btn[status=""] b').html(data.data.mail.length);
          $('.status_btn[status="0"] b').html(is_process_arr[0]);
          $('.status_btn[status="1"] b').html(is_process_arr[1]);
          $('.status_btn[status="2"] b').html(is_process_arr[2]);
      }
  });
}
  
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>