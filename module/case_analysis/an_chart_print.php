<?php
require '../../core/inc/config.php';
require '../../core/inc/function.php';
require '../../core/inc/pdo_fun_calss.php';

$pdo=new PDO_fun;
$case=$pdo->select("SELECT aTitle FROM build_case WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['Tb_index']], 'one');
?>
<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $case['aTitle'];?> - 分析列印</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="/font-awesome/css/font-awesome.css" rel="stylesheet">
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: 'Noto Sans TC', sans-serif;
            font-weight: 400;
        }
        body {
            background-color: #333;
            -webkit-print-color-adjust: exact;
        }
        .img-100{width: 100%; height:100%; object-fit: cover;}
        .date_none, .d-none{display:none !important;}
        .A4 {
            width: 1000px;
            background-color: #fff;
            margin: auto;
            padding: 15px;
            transform: translateY(43px);
        }

        .A4 h1 {
            font-size: 35px;
            margin-bottom:30px;
            line-height: 1;
        }
        .A4 h1 .date{font-size: 20px; font-weight: 300;}
        .A4 h2{
            padding: 10px 0 13px 10px;
            margin-bottom: 15px;
            line-height: 1;
            font-size: 19px;
            font-weight: 400;
            letter-spacing: 1px;
            color: #004c40;
            background: linear-gradient(90deg, rgb(94 210 192) 0%,rgb(255 255 255) 50%);
        }

        .A4 table{width: 100%;}
        .A4 table tr th{font-size: 15px; font-weight: 400; text-align: left; padding: 10px; border-left: 1px solid #00b398; background-color: #00907a !important; color: #fff !important;}
        .A4 table tr td{font-size: 13px;  font-weight: 400; padding: 10px; border-bottom: 1px solid #ccc; word-break: break-all;}

        .A4>div{padding:10px 0;}

        canvas{width: 100%;}
        .lineChart{height: 400px !important; }
        .pieChart{height:400px !important;}
        .barChart{height:450px !important;}

        .legend_div{ width: 80%; padding: 0; margin:20px auto; }
        .legend_div li{display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e2e2e2; padding: 3px 0;}
        .legend_div li span{ margin-right: 10px;  width: 10px; height: 10px; display: inline-block; border-radius: 30px; }
        .legend_div li i{font-style: normal; font-size: 16px; font-weight: 300; color: #333;}
        .legend_div li b {font-weight: 300; color: #333;}

        .box.panel{margin: 10px; border-radius: 10px; overflow: hidden;}
        .box.panel .head{background-color: #00907a;  color: #fff;  padding: 7px 15px;  letter-spacing: 1px; font-weight: 400;}
        .box.panel .head span small{ font-size:12px;}
        .box.panel h3{padding: 10px; text-align: center; font-size: 30px; border-radius: 0 0 10px 10px; border: 1px solid #ccc; border-top: 0;}
        .box_g{display:flex;margin:40px 0;}
        .box_g .box{flex:1 1;}
        .box_g .box:not(.panel){ padding:0 15px;}
        .box_g .box_s.box{flex:0 1 50%;}

        #an_completion{flex-wrap: wrap; margin: 0 -10px;}
        .box_s .img_box{margin: 0 10px; height: 240px; overflow: hidden; border-radius: 10px 10px 0px 0px;}
        .box_s .txt_box{margin: 0 10px 15px 10px; padding: 10px 15px; border: 1px solid #e5e5e5; border-radius: 0px 0px 10px 10px; border-top: 0;}
        .box_s .txt_box h3{margin-bottom:10px;}
        .box_s .txt_box p{font-weight: 500; font-size: 15px; color: #333;}
        .box_s .txt_box .dt{display: flex; justify-content: space-between;  align-items: flex-end;}
        .box_s .txt_box .dt span{font-weight: 300; font-size: 14px; color: #333; letter-spacing: 1px;}
        .box_s .txt_box .dt .cut_num{    font-weight: 700; font-size: 16px; color: #2196f3;}
        .box_s .an_box canvas{height:300px !important;}

        .label{padding: 2px 5px; border-radius: 4px; color: #fff; font-weight: 300;}
        .label.label-danger{ background-color: #d74545; }
        .label.label-warning{ background-color: #fdc701; color: #000;}
        .label.label-primary{background-color: #04b37a;}
        .print_tool{position: fixed; background-color: #333; z-index: 10; padding: 10px 20px; text-align: right; 
                    width: 1000px; left: 0; right: 0; margin: auto;}
        .print_tool .print_btn{color: #fff;  font-weight: 300;  font-size: 18px;  text-decoration: none;}

        #src_time_chart_div{display: flex; justify-content: space-between; flex-wrap: wrap;}
        .st_ch_box{ border-radius: 20px; overflow: hidden;  box-shadow: 1px 1px 0px rgb(17 84 126 / 40%); margin-bottom: 15px; flex: 0 1 49%;}
        .st_ch_box h3{font-size: 16px; font-weight:500; padding: 15px; margin: 0; color: #fff; letter-spacing: 1px;
        background: -moz-linear-gradient(0deg, rgb(20 69 100) 0%,rgb(28 132 198) 100%);
        background: -webkit-linear-gradient( 0deg, rgb(20 69 100) 0%,rgb(28 132 198) 100%);
        background: linear-gradient( 47deg, rgb(27 88 126) 0%,rgb(23 144 221) 100%);
        }

        .st_ch_box .st_dt_box{padding: 15px;}
        .st_ch_box .st_dt_box p{margin: 0;}
        .st_ch_box .st_dt_box .s_num{    color: #c9c9c9; font-weight: 300; font-size: 14px;}
        .st_ch_box .st_dt_box .s_sec{font-size: 17px;}

        .remark_btn{display:none;}

        @media print {
            .print_tool{display: none;}
            .A4{transform: translateY(0);}
            .print_page{page-break-after:always;}
        }
    </style>
</head>
<body>
    <div class="print_tool">
      <a class="print_btn" href="#"><i class="fa fa-print"></i> 列印</a>
    </div>
    <section class="A4">
       <div>
         <h1>GOOGLE分析 - <?php echo $case['aTitle'];?><br><small class="date"></small></h1>

         <div class="box_g">
           <div class="box">
             <h2>每日人數</h2>
             <div>
                <canvas id="user_chart" class="lineChart"></canvas>
             </div>
           </div>
         </div>

         <div class="box_g">
              <div id="week_users" class="box panel">
                <p class="head" style="background-color:#1a7cbb;"><i class="fa fa-user"></i> <span>一周瀏覽人數</span></p>
                <h3>讀取中...</h3>
              </div>
              <div id="month_users" class="box panel">
                <p class="head" style="background-color:#1a7cbb;"><i class="fa fa-user"></i> <span>一個月瀏覽人數</span></p>
                <h3>讀取中...</h3>
              </div>
              <div id="all_users" class="box panel">
                  <p class="head" style="background-color:#1a7cbb;"><i class="fa fa-user"></i> <span>總瀏覽人數</span></p>
                  <h3>讀取中...</h3>
              </div>
           </div>


         <div class="box_g">
            <div class="box">
                <h2>使用者性別</h2>
                <div>
                    <canvas id="sex_chart" class="pieChart"></canvas>
                </div>
                <ul id="sex_legend" class="legend_div"></ul>
            </div>
            <div class="box">
                <h2>使用者年齡</h2>
                <div>
                    <canvas id="years_chart" class="barChart"></canvas>
                </div>
            </div>
         </div>

         <!-- 換頁HTML -->
         <div style="page-break-after:always"></div>
         <!-- 換頁HTML END -->


         <div class="box_g">
            <div class="box">
                <h2>地區使用人數</h2>
                <div>
                    <canvas id="city_chart" class="barChart"></canvas>
                </div>
            </div>
            <div class="box">
                <h2>新舊訪客/回訪率</h2>
                <div>
                    <canvas id="visit_chart" class="pieChart"></canvas>
                </div>
                <ul id="visit_legend" class="legend_div"></ul>
                <div class="box panel re_visit" style="margin: 10px 50px;">
                    <p class="head" style="background-color: #20b722;"><i class="fa fa-pie-chart"></i> <span>回訪率</span></p>
                    <h3>讀取中...</h3>
                </div>
            </div>
         </div>

         <div class="box_g">
            <div class="box">
             <h2>互動比率 </h2>
             <div>
                <canvas id="BounceRate_chart" class="lineChart"></canvas>
             </div>
           </div>
         </div>
         <div class="box_g">
              <div id="max_BounceRates" class="box panel">
                <p class="head" style="background-color:#1a7cbb;"><i class="fa fa-pie-chart"></i> <span>最大互動比率</span></p>
                <h3 class="user_num">讀取中...</h3>
              </div>
              <div id="min_BounceRates" class="box panel">
                <p class="head" style="background-color:#1a7cbb;"><i class="fa fa-pie-chart"></i> <span>最小互動比率</span></p>
                <h3 class="user_num">讀取中...</h3>
              </div>
              <div id="avg_BounceRates" class="box panel">
                <p class="head" style="background-color:#1a7cbb;"><i class="fa fa-pie-chart"></i> <span>平均互動比率</span></p>
                <h3 class="user_num">讀取中...</h3>
              </div>
         </div>


         <!-- 換頁HTML -->
         <div style="page-break-after:always"></div>
         <!-- 換頁HTML END -->


         <div class="box_g">
            <div class="box">
                <h2>使用者興趣</h2>
                <div>
                    <canvas id="interest_chart" class="barChart"></canvas>
                </div>
                <div class="box panel interest_max" style="margin: 10px 50px;">
                    <p class="head" style="background-color: #d56217;"><i class="fa fa-gratipay"></i> <span>主要興趣</span></p>
                    <h3>讀取中...</h3>
                </div>
            </div>
            <div class="box">
                <h2>使用者興趣的互動比率</h2>
                <div>
                    <canvas id="interest_br_chart" class="barChart"></canvas>
                </div>
                <div class="box panel interest_max_br" style="margin: 10px 50px;">
                    <p class="head" style="background-color: #d56217;"><i class="fa fa-gratipay"></i> <span>最佳互動興趣</span></p>
                    <h3>讀取中...</h3>
                </div>
            </div>
         </div>

         <div class="box_g">
           <div class="box">
             <h2>每日來信來電</h2>
             <div>
                <canvas id="mail_date_chart" class="lineChart"></canvas>
             </div>
           </div>
         </div>

         <div class="box_g">
              <div id="adv_mails" class="box panel">
                <p class="head" style="background-color: #dd7d06;"><i class="fa fa-envelope"></i> <span>來信數</span></p>
                <h3>讀取中...</h3>
              </div>
              <div id="adv_phones" class="box panel">
                <p class="head" style="background-color:#06c2d5;"><i class="fa fa-phone"></i> <span>來電數</span></p>
                <h3>讀取中...</h3>
              </div>
              <div id="adv_calls" class="box panel">
                  <p class="head"><i class="fa fa-pie-chart"></i> <span>聯絡比率</span></p>
                  <h3>讀取中...</h3>
              </div>
           </div>



         <!-- 換頁HTML -->
         <div style="page-break-after:always"></div>
         <!-- 換頁HTML END -->



         <div class="box_g">
            <!-- <div class="box">
                <h2>使用的媒體</h2>
                <div>
                    <canvas id="media_chart" class="barChart"></canvas>
                </div>
            </div> -->
            <div class="box">
                <h2>使用的瀏覽器</h2>
                <div>
                    <canvas id="broswer_chart" class="barChart"></canvas>
                </div>
            </div>
            <div class="box">
                <h2>使用的功能鈕</h2>
                <div>
                    <canvas id="event_chart" class="pieChart"></canvas>
                </div>
                <ul id="event_legend" class="legend_div"></ul>
            </div>
         </div>
         
         

         <div class="box_g">
            <div class="box month_src_box">
                <h2>當月流量來源</h2>
                <div>
                    <canvas id="month_src_chart" class="pieChart"></canvas>
                </div>
                <ul id="month_src_legend" class="legend_div"></ul>
            </div>
            <div class="box src_box">
                <h2>總流量來源</h2>
                <div>
                    <canvas id="src_chart" class="pieChart"></canvas>
                </div>
                <ul id="src_legend" class="legend_div"></ul>
            </div>
         </div>

         <!-- 換頁HTML -->
         <div style="page-break-after:always"></div>
         <!-- 換頁HTML END -->

         <div class="box_g">
            <div class="box month_src_box">
                <h2>總來源使用時間</h2>
                <div id="src_time_chart_div">
                   
                </div>
            </div>
         </div>


         <!-- 換頁HTML -->
         <div style="page-break-after:always"></div>
         <!-- 換頁HTML END -->

         
         <div class="box_g">
             <div class="box">
                <h2>網頁瀏覽程度</h2>
                <div id="an_completion" class="box_g">
                    
                </div>
            </div>
         </div>

         <!-- 換頁HTML -->
         <div style="page-break-after:always"></div>
         <!-- 換頁HTML END -->

         <div class="box_g">
            <div class="box">
                <h2>預約賞屋來信</h2>
                <div class="mail_table">
                   <table cellspacing="0">
                       <thead>
                           <tr>
                               <th width="50">#</th>
                               <th width="90">時間</th>
                               <th width="70">姓名</th>
                               <th width="95">電話</th>
                               <th>E-mail</th>
                               <th width="200">其他</th>
                               <th width="100">來源</th>
                               <th width="80">狀態</th>
                               <th width="100">備註</th>
                           </tr>
                       </thead>
                       <tbody>
                           <tr>
                               <td></td>
                               <td></td>
                               <td></td>
                               <td></td>
                               <td></td>
                               <td></td>
                               <td></td>
                           </tr>
                       </tbody>
                   </table>
                </div>
            </div>
         </div>
         

       </div>
    </section>

    <script src="../../js/jquery-2.1.1.js"></script>
    <!-- <script src="../../js/plugins/chartjs/Chart.min.js?1"></script>
    <script src="../../js/plugins/chartjs/chartjs-plugin-annotation.min.js"></script>
    <script src="../../js/plugins/chartjs/chartjs-plugin-datalabels.min.js"></script>
    <script src="../../js/an_Class/Chart_class.js?6"></script>
    <script src="../../js/an_Class/an_Class.js?46"></script> -->
    <script src="../../js/plugins/chartjs/3.6.0/chart.min.js"></script>
    <script src="../../js/plugins/chartjs/3.6.0/chartjs-plugin-annotation.min.js"></script>
    <script src="../../js/plugins/chartjs/3.6.0/chartjs-plugin-datalabels.min.js"></script>
    <script src="../../js/an_Class/Chart_class_v3.js?95"></script>
    <script src="../../js/an_Class/an_Class_v3.js?95"></script>
    

    <!-- 自訂JS -->
    <script src="../../js/main.js?4"></script>
    <script>
        $(document).ready(function () {

            //----------------- 新分析 --------------------
            var AnAll=new CaseAn;

            //-- 列印按鈕 --
            $('.print_btn').click(function (e) { 
                e.preventDefault();
                window.print();
            });

            const pie_color=['#FF6258', '#2196F3', '#FFB22B', '#26C6DA', '#FF7F0E', '#2CA02C', '#1b671b', '#868686', '#d34b42', '#1c77bf', '#c58a24', '#5e5789', '#d634d9', '#27af9c'];

            //-- 網址get --
            let url_arr=url_get(); 

            //-- 時間 --
            let s_date=url_arr['s_date']=='' ? GetDateStr(-31, 'Y-m-d'):url_arr['s_date'];
            let e_date=url_arr['e_date']=='' ?  GetDateStr(-1, 'Y-m-d'):url_arr['e_date'];
            $('h1 .date').html(`日期：${s_date} ~ ${e_date}`);

            $.ajax({
                type: "POST",
                url: "an_chart_ajax.php",
                data: {
                    type:'an_get',
                    case_id : url_arr['Tb_index'],
                    s_date : url_arr['s_date'],
                    e_date : url_arr['e_date'],
                },
                dataType: "json",
                success: function (data) {

                    console.log(data);

                    //-- 一周人數 --
                    $('#week_users h3').html(fm_Thousands(data.data.week_user)+'人');
                    //-- 一月人數 --
                    $('#month_users h3').html(fm_Thousands(data.data.month_user)+'人');
                    //-- 總人數 --
                    $('#all_users h3').html(fm_Thousands(data.data.total_user)+'人');


                    //-- 設定時間區間 --
                    //-- 最大瀏覽人數 --
                    let max_user_index= data.data.user.indexOf( Math.max(...data.data.user));
                    //-- 最小瀏覽人數 --
                    let min_user_index= data.data.user.indexOf( Math.min(...data.data.user));
                    if(url_arr['s_date']!=''){
                        $('.month_src_box').addClass('date_none');
                        $('.src_box h2').html('區間流量來源');
                        
                        $('#week_users h3').html(`${fm_Thousands(data.data.user[max_user_index])}人`);
                        $('#month_users h3').html(`${fm_Thousands(data.data.user[min_user_index])}人`);
                        $('#week_users p span').html(`最大瀏覽人數 <small>${data.data.date[max_user_index]}</small>`);
                        $('#month_users p span').html(`最小瀏覽人數 <small>${data.data.date[min_user_index]}</small>`);
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
                    $('#adv_mails h3').html(total_user_mail + '封');
                    $('#adv_phones h3').html(total_user_phone + '通');
                    $('#adv_calls h3').html(adv_call + '%');


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
                        $('.interest_max h3').html(data.data.interest[max_interest_index].tw_name);
                    }
                    else{
                        $('.interest_max h3').html('無資料');
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
                        $('.interest_max_br h3').html(data.data.interest[max_interest_br_index].tw_name);
                    }
                    else{
                        $('.interest_max_br h3').html('無資料');
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
                    $('.re_visit h3').html(`${avg_re_visit}%`);


                    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 使用的媒體 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
                    // _media__chart=AnAll.media_chart({
                    //     chart_id: 'media_chart',
                    //     user_data: data.data.media
                    // });


                    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 使用的瀏覽器 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
                    _broswer__chart=AnAll.broswer_chart({
                        chart_id: 'broswer_chart',
                        user_data: data.data.broswer
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
                        user_data: data.data.src_time,
                        src_data:data.data.src
                    });


                    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 網頁瀏覽程度 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
                    AnAll.completion_chart({
                        DIV_id: 'an_completion', 
                        chart_id: 'mixChart', 
                        div_class: 'box_s box',
                        completion_data: data.data.completion, 
                        date: data.data.date
                    });


                    // //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 預約賞屋來信 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
                    AnAll.mail_list({
                        DIV_id:'.mail_table table tbody',
                        show_is_process:false,
                        user_data: data.data.mail
                    });
            
                    //----------------------------- 列印 ------------------------------------
                    setTimeout(() => {
                      window.print();
                    }, 1000);
                    
                }
            });
        });
    </script>
</body>
</html>
<?php
$pdo->close();
?>