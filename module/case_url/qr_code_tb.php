<?php
 require '../../core/inc/config.php'; 
 require '../../core/inc/function.php';
 require '../../core/inc/pdo_fun_calss.php';
 require '../../core/inc/security.php';
 $pdo=new PDO_fun;

 $case=$pdo->select("SELECT aTitle FROM build_case WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['case_id']], 'one');
?>
<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $case['aTitle'];?>｜短網址PDF</title>
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
        .A4 table tr td{font-size: 13px;  font-weight: 400; padding: 10px; border-bottom: 1px solid #ccc; }

        .A4>div{padding:10px 0;}

        .print_tool{position: fixed; background-color: #333; z-index: 10; padding: 10px 20px; text-align: right; 
                    width: 1000px; left: 0; right: 0; margin: auto;}
        .print_tool .print_btn{color: #fff;  font-weight: 300;  font-size: 18px;  text-decoration: none;}

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
        <h2><?php echo $case['aTitle'];?>QR code短網址</h2>
        <table>
           <thead>
               <tr>
                 <th>QR code</th>
                 <th>短網址</th>
                 <th>媒體名稱</th>
               </tr>
           </thead>
           <tbody>
               <?php 
                 $row=$pdo->select("SELECT * FROM QRcode_tb WHERE case_id=:case_id ORDER BY Tb_index DESC", ['case_id'=>$_GET['case_id']]);
                 
                 foreach ($row as $one) {
                    $url_id=explode('srl.tw/sh', $one['QRcode_url']);
                    $url='https://ucy.tw/'.$url_id[1];
                    echo '<tr>
                            <td class="QRcode_tb" qr_code_url="'.$url.'"></td>
                            <td>'.$url.'</td>
                            <td>'.$one['source'].'</td>
                        </tr>';
                 }
               ?>
               
           </tbody>
        </table>
    </section>

    <script src="../../js/jquery-2.1.1.js"></script>
    <!-- qrcode -->
    <script src="../../js/qrcode.min.js"></script>

    <script>
        $.each($('.QRcode_tb'), function (index, valueOfElement) { 
            var qrcode = new QRCode($(this)[0], {
                width : 150,
                height : 150
            });

            qrcode.makeCode($(this).attr('qr_code_url'));
        });


        //-- 列印按鈕 --
        $('.print_btn').click(function (e) { 
            e.preventDefault();
            window.print();
        });

        //----------------------------- 列印 ------------------------------------
        setTimeout(() => {
            window.print();
        }, 500);
    </script>
</body>
</html>