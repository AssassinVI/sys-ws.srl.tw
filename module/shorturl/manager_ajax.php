<?php
/*短網址管理新增修改(Ajax)*/
require_once '../../core/inc/config.php';
require_once '../../core/inc/function.php';
require '../../core/inc/pdo_fun_calss.php';

if ($_POST)
{
	$pdo = new PDO_fun('short');
	$type = $_POST['type'];
	$table_name = "appShort";
	
	switch ($type)
	{
		//-- 新增資料 --
		case 'add':
			$Tb_index = $_POST['Tb_index'];
			
			$query = "SELECT ifnull(max(OrderBy), 0) + 1 AS OrderBy
			         FROM {$table_name} WHERE ifnull(url_id, '') != '' ";
			$row_OrderBy = $pdo->select($query, 'no', 'one');
			
			$OrderBy = $row_OrderBy['OrderBy'];
			$param = array(
				'aUrl'=>$_POST['aUrl'],
				'aTitle'=>$_POST['aTitle'],
				'url_id'=>$_POST['url_id'],
				'StartDate'=>date('Y-m-d'),
				'OnlineOrNot'=>"1",
				'OrderBy'=>$OrderBy,
				'CreateTime'=>date('Y-m-d H:i:s')
			);
			$where = array('Tb_index'=>$Tb_index);
			$pdo->update($table_name, $param, $where);
			$response = array('data'=>'');
			echo json_encode($response);
			break;
		//-- 修改資料 --
		case 'mod':
			$Tb_index = $_POST['Tb_index'];
			$param = array(
				'aUrl'=>$_POST['aUrl'],
				'aTitle'=>$_POST['aTitle'],
				'OnlineOrNot'=>$_POST['OnlineOrNot'],
				'UpdateTime'=>date('Y-m-d H:i:s'),
				'UpdateAdm_Pk'=>$_SESSION['admin_index']
			);
			$where = array('Tb_index'=>$Tb_index);
			$pdo->update($table_name, $param, $where);
			$response = array('data'=>'');
			echo json_encode($response);
			break;
		//-- 刪除資料 --	
		case 'del':
			$Tb_index = $_POST['Tb_index'];
			$where = array('Tb_index'=>$Tb_index, 'url_id'=>'');
			$pdo->delete($table_name, $where);
			$response = array('data'=>'');
			echo json_encode($response);
			break;	
		//-- 檢查短網址 --
		case 'check_url_id':
			$url_id = $_POST['url_id'];
			$Tb_index = $_POST['Tb_index'];
			$query = "SELECT count(Tb_index) AS url_id_total
			         FROM {$table_name} WHERE url_id =:url_id AND Tb_index !=:Tb_index";
			$row_url_id_total = $pdo->select($query, [
				'url_id'=>$url_id,
				'Tb_index'=>$Tb_index
			], 'one');
			$url_id_total = $row_url_id_total['url_id_total'];
			$response = array(
				'errCode' => ($url_id_total == 0) ? '0' : '1',
				'resData' => ''
			);
			echo json_encode($response);
			break;
		//-- 產生短網址 --
		case 'get_shorturl':
			$link_url = $_POST['link_url'];
			/* 取得 URL 頁面數據 */  
            // 初始化 CURL  
			$html_curl = curl_init();  
  
			// 設置 URL   
			curl_setopt($html_curl, CURLOPT_URL, $link_url);   
			// 讓 curl_exec() 獲取的信息以數據流的形式返回，而不是直接輸出。  
			curl_setopt ($html_curl, CURLOPT_RETURNTRANSFER, 1);  
			// 在發起連接前等待的時間，如果設置為0，則不等待  
			curl_setopt ($html_curl, CURLOPT_CONNECTTIMEOUT, 0);  
			// 設置 CURL 最長執行的秒數  
			curl_setopt ($html_curl, CURLOPT_TIMEOUT, 30);  
  
			// 嘗試取得文件內容  
			$html_content = curl_exec($html_curl); 
			
			// 檢查文件是否正確取得  
            if (curl_errno($html_curl))
			{
				$short_url_ftitle = "";
			}  

			// 關閉 CURL  
            curl_close($html_curl);
			
			preg_match_all("/<title>.*?<\/title>/s", $html_content, $match);
			if (count($match[0]) > 0)
			{
				$short_url_ftitle = $match[0];
				foreach ($match[0] as $key => $value)
				{
					$short_url_ftitle = $value;
					$short_url_ftitle = str_replace('<title>', '', $short_url_ftitle);
					$short_url_ftitle = str_replace('</title>', '', $short_url_ftitle);	
				}
			}
			elseif (count($match[0]) == 0)
			{
				$short_url_ftitle = "";
			}
			
			if ($short_url_ftitle != "")
			{
				$short_url_first = "srl.tw";
				$short_url_second = getrand_id(5);
				$short_url = $short_url_first."/sh".$short_url_second;
				
				$resData = array($short_url_ftitle, $short_url, $short_url_second);
				$response = array(
					'errCode' => '0',
					'resData' => $resData
				);
			}
			else
			{
				$response = array(
					'errCode' => '1',
					'resData' => ""
				);
			}
			echo json_encode($response);
			break;
		default:
			break;
	}
}

//-- 利用亂數取的隨機的英數字帳號 --
function getrand_id($id_len)
{
	$id = '';
	$word = 'abcdefghijkmnpqrstuvwxyz23456789';//字典檔 你可以將 數字 0 1 及字母 O L 排除
	$len = strlen($word);//取得字典檔長度
		
	for($i = 0; $i < $id_len; $i++)
	{ 
		//總共取 幾次
		$id .= $word[rand() % $len];//隨機取得一個字元
	}
	return $id;//回傳亂數帳號
}
?>