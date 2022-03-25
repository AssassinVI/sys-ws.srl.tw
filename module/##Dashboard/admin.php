<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<style type="text/css">
    .flot-chart{ height: 500px; }
    .m-b-xl{ margin: 0px; }
    .text-no{ color: #F44336; }
     .c3 svg{ font-size: 11px; }
    .c3-legend-item{ font-size: 13px; }
    #sel_case{ padding: 4px 12px; font-size: 15px; }
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 
if ($_GET) {
	
	if ($_GET['type']=='contact_del') { //刪除訊息-聯絡
		$where=array('Tb_index'=>$_GET['Tb_index']);
		pdo_delete('appContacts', $where);
	}
    elseif ($_GET['type']=='con_process') { //訊息處理-聯絡
        $param=array("process"=>$_GET['process']);
        $where=array('Tb_index'=>$_GET['Tb_index']);
        pdo_update('appContacts', $param, $where);
    }
    elseif($_GET['type']=='service_del'){ //刪除訊息-維修
        $where=array('Tb_index'=>$_GET['Tb_index']);
        pdo_delete('appService', $where);
    }
    elseif($_GET['type']=='ser_process'){ //訊息處理-維修
        $param=array("is_deal"=>$_GET['process']);
        $where=array('Tb_index'=>$_GET['Tb_index']);
        pdo_update('appService', $param, $where);
    }
}

?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="p-w-md m-t-sm">
        <div class="col-lg-12">
                <h1 class="text-primary">網站儀錶板</h1>
                <h3>檢視網站數據分析</h3>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>每日流量 </h5>
                             <div class="ibox-tools">
                                    <select id="sel_case">
                                    <?php 
                                     $pdo=pdo_conn();
                                     $sql=$pdo->prepare("SELECT Tb_index, aTitle FROM build_case WHERE OnLineOrNot='1' AND google_view_code!='' ORDER BY OrderBy DESC, Tb_index DESC");
                                     $sql->execute();
                                     while ($row=$sql->fetch(PDO::FETCH_ASSOC)) {
                                        echo ' <option value="'.$row['Tb_index'].'">'.$row['aTitle'].'</option>';
                                     }

                                    ?>
                                       
                                    </select>
                             </div>
                    </div>
                    <div class="ibox-content">
                        <div class="flot-chart m-b-xl">
                            <div class="flot-chart-content" id="date_use"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        
<?php  include("../../core/page/footer01.php");//載入頁面heaer02?>
   <!-- Flot -->
    <script src="../../js/plugins/flot/jquery.flot.js"></script>
    <script src="../../js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="../../js/plugins/flot/jquery.flot.spline.js"></script>
    <script src="../../js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="../../js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="../../js/plugins/flot/jquery.flot.symbol.js"></script>
    <script src="../../js/plugins/flot/jquery.flot.time.js"></script>



    <!-- Sparkline -->
    <script src="../../js/plugins/sparkline/jquery.sparkline.min.js"></script>



    <script>
        $(document).ready(function() {

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
                        labels: true
                    },
                    axis:{
                       x:{
                         type:'timeseries',
                          tick:{
                              
                              count:4,
                              format: '%m-%d'
                          }
                       }
                    }
                });


     ajax_date_user(data_use, $('#sel_case').val());

        
    $('#sel_case').change(function(event) {

        ajax_date_user(data_use, $(this).val());
    });





// var test_arr={
//               columns: [
//                     ['x','20171221','20171220','20171219','20171218','20171217','20171216','20171214','20171213','20171212','20171209','20171208','20171207','20171206','20171205','20171204','20171202','20171129','20171127','20171126','20171124','20171123','20171122','20171121','20171120','20171118','20171117','20171116','20171115','20171114','20171113',],
//                     ['使用人數',5,9,6,3,2,2,3,6,1,12,17,2,5,3,1,1,3,2,5,1,1,7,22,3,3,2,2,7,12,12,],
//                        ]
//             };

//       setTimeout(function () {
//           data_use.load(test_arr);
//       },1500);

        });

 function ajax_date_user(an_ch ,Tb_index) {
     $.ajax({
            url: 'an_google_ajax.php',
            type: 'POST',
            data: {
               type : 'date_use',
               Tb_index : Tb_index
            },
            success:function (data) {
              var an_data=data.split('|');
              var date=an_data[0].split(',');
              var users=an_data[1].split(',');
              date.splice(0,0,'x');
              users.splice(0,0,'使用人數');

              an_ch.load({
                columns:[date,users]
              });
           }
        });
 }
    </script>

<?php  include("../../core/page/footer02.php");//載入頁面heaer02?>

