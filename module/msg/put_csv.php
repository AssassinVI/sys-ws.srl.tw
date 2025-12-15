<?php 
//  ini_set('display_errors','1');
//  error_reporting(E_ALL);
 require '../../core/inc/config.php';
 require '../../core/inc/function.php';
 require '../../core/inc/pdo_fun_calss.php';


 if ($_GET) {
   
   $pdo=new PDO_fun;
   
   $row=$pdo->select("SELECT crt.*, c.aTitle
                      FROM call_record_tb as crt
                      INNER JOIN build_case as c ON c.Tb_index=crt.case_id
                      WHERE case_id=:case_id 
                      ORDER BY set_time DESC", 
                      ['case_id'=>$_GET['case_id']]);
    
                      header('Content-Type: application/csv');
                      header('Content-Disposition: attachment; filename="'.$row[0]['aTitle']."-留名單資料-".date('Ymd').'.csv";');
   $data=[[
    '編號',
    '填寫時間',
    '姓名',
    '電話',
    'Email',
    '內容',
    '來源',
    '媒體',
    // '活動',
    
   ]];

   $header=[
    '編號',
    '填寫時間',
    '姓名',
    '電話',
    'Email',
    '內容',
    '來源',
    '媒體',
    // '活動',
    
   ];
    $x=1;
    foreach ($row as $one) {
        $data_one=[
          $x,
          $one['set_time'],
          $one['use_name'],
          'tel:'.$one['phone'],
          $one['use_mail'],
          $one['call_content'],
          $one['utm_source'],
          $one['utm_medium'],
        //   $one['utm_campaign'],
          
        ];
        array_push($data, $data_one);
        $x++;
      }
   

    //print_r($data);

    

   

   //建立一個暫存的CSV檔案 
    // $temp_file = tmpfile();
    $temp_file = fopen('php://output', 'w');

    fwrite($temp_file, "\xEF\xBB\xBF");

    //將資料寫入CSV檔案 
    $delimiter = ','; // 指定欄位分隔符號
    foreach($data as $data_item) {
        fputcsv($temp_file, $data_item, $delimiter);
    }

    

    
   

    //關閉暫存的CSV檔案 
    fclose($temp_file);
 }


 /**
* 数据导出
* @params array $data 需要导出的数据
* @params array $header 标题栏
* @params string $filename 文件名
* @params array $spectials 需要加下拉框的单元格信息 [['column'=>'A','select_options'=>['共青团员','中共党员']]]
*
*/
function exportDataSelectOptions ($data, $header, $filename = "data", $spectials=[])
{
	$objPHPExcel= new PHPExcel();

    // Add some data
    $objPHPExcel->setActiveSheetIndex(0);
    //添加头部
    $hk = 0;
    foreach ($header as $k => $v)
    {
        $colum = \PHPExcel_Cell::stringFromColumnIndex($hk);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum."1", $v);
        $hk += 1;
    }
    
    $column = 2;
    $objActSheet = $objPHPExcel->getActiveSheet();
    
    $objActSheet->setTitle('留名單資料');

    $objActSheet->getColumnDimension('C')->setWidth(25);//設定欄寬
    $objActSheet->getColumnDimension('D')->setWidth(50);//設定欄寬
    $objActSheet->getColumnDimension('E')->setWidth(25);//設定欄寬
    
    //设置下拉框
    foreach($spectials as $spectial)
    {
        $optionsString = implode(',', $spectial['select_options']);
        
        $n = 0;
        // 我这里设置1000行，可自行设置
        while($n < 1000) {
            $objValidation = $objActSheet->getCell($spectial['column'].(string)$n)->getDataValidation(); //这一句为要设置数据有效性的单元格
            // $objValidation的各项设置参数可详见phpexcel文件，
            // 目录大概为/.../phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel5/Worksheet.php 
            // 2767行 方法名：writeDataValidity
            $objValidation -> setType(\PHPExcel_Cell_DataValidation::TYPE_LIST)
            -> setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_STOP)
            -> setAllowBlank(true)
            -> setShowInputMessage(true)
            -> setShowErrorMessage(true)
            -> setShowDropDown(true)
            -> setErrorTitle('输入的值有误')
            -> setError('您输入的值不在下拉框列表内.')
            -> setPromptTitle('')
            -> setPrompt('')
            -> setOperator(\PHPExcel_Cell_DataValidation::OPERATOR_BETWEEN)
            -> setFormula1('"'.$optionsString.'"');
            
            $n++;
        }
    }
    foreach($data as $key => $rows)  //行写入
    {
        $span = 0;
        foreach($rows as $keyName => $value) // 列写入
        {
            $j = \PHPExcel_Cell::stringFromColumnIndex($span);
            $objActSheet->setCellValue($j.$column, $value);
            
            $span++;
        }
        $column++;

        //-- 框線 --
        $style_array=[
            'borders'=>[
                'allborders'=>[
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' =>['rgb' => '333333']
                ]
            ]
        ];
        $colum_id = \PHPExcel_Cell::stringFromColumnIndex(($hk-1));
        $objActSheet->getStyle("A1:".$colum_id.($column-1)."")->applyFromArray($style_array);

    }
    ob_end_clean();
    ob_start();
    $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
    //设置输出文件名及格式
    // header('Content-Type : application/vnd.ms-excel');
    // header('Content-Disposition:attachment;filename="'.$filename.'.xls"');
    //导出.xls格式的话使用Excel5,若是想导出.xlsx需要使用Excel2007
    // $objWriter= \PHPExcel_IOFactory::createWriter($objPHPExcel->phpExcel,'Excel5');
    // $objWriter->save('php://output');
    ob_end_flush();
    header("Content-Type:application/force-download");
    header("Content-Type:application/octet-stream");
    header("Content-Type:application/download");
    header('Content-Disposition:inline;filename="'.$filename.'.xlsx"');
    header("Content-Transfer-Encoding:binary");
    header("Last-Modified:" . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
    header("Pragma:no-cache");
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    //清空数据缓存
    unset($data);
}
?>
