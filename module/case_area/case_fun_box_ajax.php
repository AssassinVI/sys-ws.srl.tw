<?php 
 require '../../core/inc/config.php';
 require '../../core/inc/function.php';
 require '../../core/inc/pdo_fun_calss.php';
 require_once '../../core/inc/PHPZip.php';

 if ($_POST) {

  $pdo_new= new PDO_fun();
  //$pdo_job= new PDO_fun('job');
 	
 	//------- 新增功能區塊關聯 ------
 	if($_POST['type']=='insert'){

 	  $Tb_index='re'.date('YmdHis').rand(0,99);

 	  $OrderBy_num=pdo_select("SELECT OrderBy FROM Related_tb WHERE case_id=:case_id ORDER BY OrderBy DESC LIMIT 0,1", ['case_id'=>$_POST['case_id']]);

      $param=[
           'Tb_index'=>$Tb_index,
           'case_id'=>$_POST['case_id'],
           'funbox_id'=>$_POST['funbox_id'],
           'OrderBy'=>(int)$OrderBy_num['OrderBy']+1
      ];
      pdo_insert('Related_tb', $param);

      echo $Tb_index;
 	}
    //------- 撈取功能區塊關聯 ------
 	elseif($_POST['type']=='select'){
      
      $pdo=pdo_conn();
      $sql=$pdo->prepare("SELECT rt.*, fb.box_name, fb.aUrl, fb.btn_type, fb.btn_icon
                          FROM Related_tb as rt
                          INNER JOIN FunBox as fb ON fb.Tb_index=rt.funbox_id
                          WHERE rt.case_id=:case_id 
                          ORDER BY rt.OrderBy ASC");
      $sql->execute(['case_id'=>$_POST['case_id']]);
      $row=$sql->fetchAll(PDO::FETCH_ASSOC);
      echo json_encode($row);
 	}

  //------- 更新功能區塊排序 ------
 	elseif($_POST['type']=='update'){

       $related_id_array=explode(',', $_POST['related_id_array']);
       
       for ($i=0; $i <count($related_id_array) ; $i++) { 
       	 pdo_update('Related_tb', ['OrderBy'=>($i+1)], ['Tb_index'=>$related_id_array[$i]]);
       	//echo $_POST['related_id_array'][$i].'\n';
       }
 	}
  //------- 刪除功能區塊 ------
  elseif($_POST['type']=='delete'){
    
    $row=pdo_select("SELECT fun_id, funbox_id FROM Related_tb WHERE Tb_index=:Tb_index", ['Tb_index'=>$_POST['related_id']]);

    if(empty($row['fun_id'])){
       //-- 刪除關聯 --
       pdo_delete('Related_tb', ['Tb_index'=>$_POST['related_id']]);
    }
    else{
       
       //-- 功能資料表 --
       $funBox_row=pdo_select("SELECT fun_tb FROM FunBox WHERE Tb_index=:Tb_index", ['Tb_index'=>$row['funbox_id']]);


       //----------- 刪除功能區塊所有檔案 -------------

       if($funBox_row['fun_tb'=='slideshow_tb']){

          $show_row=pdo_select("SELECT show_img, case_id FROM slideshow_tb WHERE Tb_index=:Tb_index", ['Tb_index'=>$row['fun_id']]);
          $show_img=explode(',', $show_row['show_img']);
          for ($i=0; $i <count($show_img)-1 ; $i++) { 
            unlink('../../../product_html/'.$show_row['case_id'].'/img/'.$show_img[$i]);
          }
       }
       elseif($funBox_row['fun_tb'=='youtube_tb']){

         $you_row=pdo_select("SELECT video_file, case_id FROM youtube_tb WHERE Tb_index=:Tb_index", ['Tb_index'=>$row['fun_id']]);
         unlink('../../../product_html/'.$you_row['case_id'].'/video/'.$you_row['video_file']);

       }

       //-- 刪除關聯 --
       pdo_delete('Related_tb', ['Tb_index'=>$_POST['related_id']]);
       //-- 刪除功能區塊 --
       pdo_delete($funBox_row['fun_tb'], ['Tb_index'=>$row['fun_id']]);
       //-- (未完) --
    }
  }
  // ------------ 錨點名稱 -------------
  elseif($_POST['type']=='anchor_name'){
    $an_name=pdo_select("SELECT anchor_name FROM anchor_tb WHERE Tb_index=:Tb_index", ['Tb_index'=>$_POST['fun_id']]);
    echo $an_name['anchor_name'];
  }

  // //-- 匯出網站(資料庫紀錄) --
  // elseif($_POST['type']=='put_website'){

  //   $job=$pdo_job->select("SELECT COUNT(*) as total FROM put_website WHERE case_id=:case_id", ['case_id'=>$_POST['case_id']], 'one');
  //   if((int)$job['total']>0){
  //     $pdo_job->insert('put_website', ['case_id'=>$_POST['case_id']]);
  //     echo json_encode(['success'=>true, 'msg'=>'已編排匯出']);
  //   }
  //   else{
  //     echo json_encode(['success'=>false, 'msg'=>'匯出已編排，請勿重複!!']);
  //   }

  // }
  
  //-- 匯出index.html+ 壓縮檔案 --
  elseif($_POST['type']=='index_html'){
     
     $web_url='website_tmp/'.$_POST['case_id'].'/index.html';
     $fp=fopen($web_url, "w");
     fwrite($fp, $_POST['html']);
     fclose($fp);

     //-- 壓縮檔案 --
     // Get real path for our folder
     $rootPath = realpath('website_tmp/'.$_POST['case_id']);

     // Initialize archive object
     $zip = new ZipArchive();
     $zip->open('website_tmp/'.$_POST['case_id'].'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

     // Create recursive directory iterator
     /** @var SplFileInfo[] $files */
     $files = new RecursiveIteratorIterator(
         new RecursiveDirectoryIterator($rootPath),
         RecursiveIteratorIterator::LEAVES_ONLY
     );

     foreach ($files as $name => $file)
     {
         // Skip directories (they would be added automatically)
         if (!$file->isDir())
         {
            // Get real and relative path for current file
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);

            // Add current file to archive
            $zip->addFile($filePath, $relativePath);
         }
     }

     // Zip archive will be created only after closing object
     $zip->close();

     //-- 路徑加密 --
     echo urlencode(aes_encrypt_7($aes_key, 'website_tmp/'.$_POST['case_id'].'.zip'));
   }

   $pdo_new->close();
   //$pdo_job->close();
 }
?>