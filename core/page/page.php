<?php
 //-- 內容MT_id --
 $MT=empty($MT_id)? $_GET['MT_id']:$MT_id;
 //-- 總數 --
 $where=array('mt_id'=>$MT);
 $row_totle=pdo_select("SELECT COUNT(*) as totle FROM appProduct WHERE mt_id=:mt_id",$where);
 //-- 總頁數 --
 $page_totle=ceil((int)$row_totle['totle']/$num);
 //-- 單頁 --
 $page_one=empty($_GET['page'])? 1:$_GET['page'];
?>

        <!-- 頁碼 Begin -->
        <div class="page">


        <?php
             //-- 判斷 上一頁 --
               if ($page_one==1) {
                    echo '<span class="disabled"><i class="fa fa-angle-left" aria-hidden="true"></i></span>';
               }else{
                    echo '<a href="'.$url.'&page='.((int)$page_one-1).'"><i class="fa fa-angle-left" aria-hidden="true"></i></a>';
               }



      //------------------ 頁數小於X ------------------------
        if($page_totle<=10){

         for ($i=1; $i <= $page_totle ; $i++) {

              if ($page_one==$i) {
                 echo '<span class="current">'.$i.'</span>';
              }else{
                 echo '<a href="'.$url.'&page='.$i.'">'.$i.'</a>';
              }
          }

        }

       //------------------ 頁數大於X ------------------------
        else{

          //--- page 1-X 頁 ---
          if ($page_one-1<=3) {
            for ($i=1; $i <=5 ; $i++) {
              if ($page_one==$i) {
                 echo '<span class="current">'.$i.'</span>';
              }else{
                 echo '<a href="'.$url.'&page='.$i.'">'.$i.'</a>';
              }
            }
            echo ' ... <a href="'.$url.'&page='.$page_totle.'">'.$page_totle.'</a>';
          }
          //--- 最後X頁 ---
          elseif($page_totle-$page_one<=3){

             echo '<a href="'.$url.'&page=1">1</a> ... ';

             for ($i=$page_totle-4; $i <=$page_totle ; $i++) {
              if ($page_one==$i) {
                 echo '<span class="current">'.$i.'</span>';
              }else{
                 echo '<a href="'.$url.'&page='.$i.'">'.$i.'</a>';
              }
             }
          }
          //--- 中間頁數 ---
          else{

            echo '<a href="'.$url.'&page=1">1</a> ... ';

            for ($i=$page_one-2; $i <=$page_one+2 ; $i++) {
              if ($page_one==$i) {
                 echo '<span class="current">'.$i.'</span>';
              }else{
                 echo '<a href="'.$url.'&page='.$i.'">'.$i.'</a>';
              }
            }

            echo ' ... <a href="'.$url.'&page='.$page_totle.'">'.$page_totle.'</a>';
          }

        }


           //-- 判斷 下一頁 --
           if ($page_one==$page_totle) {
                echo '<span class="disabled"><i class="fa fa-angle-right" aria-hidden="true"></i></span>';
           }else{
               echo '<a href="'.$url.'&page='.((int)$page_one+1).'"><i class="fa fa-angle-right" aria-hidden="true"></i></a>';
           }

        ?>

        </div>
        <!-- 頁碼 End -->
