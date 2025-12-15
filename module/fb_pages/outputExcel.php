<?php
require '../../core/inc/config.php';
require '../../core/inc/function.php';
include "../../core/inc/ajax_fun.php";
require '../../core/inc/pdo_fun_calss.php';
require '../../core/inc/vendor/autoload.php';

// ini_set('display_errors','1');
// error_reporting(E_ALL);

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// 建立連線
$pdo = new PDO_fun;


$where=['pageId'=>$_GET['pageId']];
// 查詢所有不同的表單 ID
if(empty($_GET['form_id'])){
    $form_id='';
}
else{
    $form_id=" AND form_id = :form_id";
    $where['form_id']=$_GET['form_id'];
}
$pages=$pdo->select("SELECT form_id, form_name, form_json FROM appCase_form WHERE pageId=:pageId $form_id", $where);


$spreadsheet = new Spreadsheet();
$spreadsheet->removeSheetByIndex(0); // 移除預設空白 Sheet


foreach ($pages as $page) {
    //-- 表單群組 --
    $form_group[$page['form_id']][]=$page;
}

foreach ($form_group as $form_arr) {

    // 建立新的分頁（工作表）
    $sheet = $spreadsheet->createSheet();
    // 移除非法字元
    $invalid = ['\\', '/', '?', '*', ':', '[', ']'];
    $title = str_replace($invalid, '', $form_arr[0]['form_name']);
    $sheet->setTitle(substr($title, 0, 31)); // Excel 分頁標題最大31字


    foreach ($form_arr as $index => $form) {
       // 將 JSON 字串轉為陣列
        $data = json_decode($form['form_json'], true); 
        if (empty($data)) continue;

        // print_r($data);
        // echo "<br>";
        $headers = array_keys($data);

        // 動態產出欄位標題（第一列）
        if($index === 0) {
            foreach ($headers as $col => $header) {
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1);
                $cell = $columnLetter . '1'; // 第一列：標題
                $sheet->setCellValue($cell, $header);
            }
        }
        
        // 填入資料
        foreach ($headers as $col => $header) {
            $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1);
            $cell = $columnLetter . ((int)$index + 2); // 從第 2 列開始寫入資料
            $sheet->setCellValue($cell, $data[$header] ?? '');
        }
        
    }
    
}

//輸出檔案

//-- 粉絲專頁 --
$case=$pdo->select("SELECT pageName FROM appCase WHERE pageId=:pageId", ['pageId'=>$_GET['pageId']], 'one');
$writer = new Xlsx($spreadsheet);
$filename = "{$case['pageName']}_fb粉專留名單資料_".date('Ymd').".xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
$writer->save('php://output');
exit;
