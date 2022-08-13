<?php 
include("../../core/page/header01.php");//載入頁面heaer01
include("../../core/page/header02.php");//載入頁面heaer02
?>
<?php

 $pdo=pdo_conn();
  if ($_POST) { //新增、修改
    
     if (empty($_POST['Tb_index'])) {

        //-- 歷史紀錄 --
        $new_pdo->hs_tb_name='sysAdmin';
        $new_pdo->hs_h_location='管理者管理';
        $new_pdo->hs_h_action_type='insert';
        $new_pdo->hs_h_title='建立管理者-'.$_POST['name'];
        //-- 舊資料 --
        $new_pdo->old_data();

        $StartDate=empty($_POST['StartDate']) ? '0000-00-00':$_POST['StartDate'];
        $EndDate=empty($_POST['EndDate']) ? '0000-00-00':$_POST['EndDate'];

       $param=array("Tb_index"=>'admin'.date('YmdHis').rand(0,99), 
                   "admin_per"=>$_POST['admin_per'], 
                    "admin_id"=>$_POST['admin_id'], 
                   "admin_pwd"=>aes_encrypt_7($aes_key, $_POST['admin_pwd']), 
                  "build_time"=>date('Y-m-d H:i:s'), 
                        "name"=>$_POST['name'], 
                      //  "phone"=>$_POST['phone'], 
                      //   "adds"=>$_POST['adds'],
                      //   "email"=>$_POST['email'],
                       "is_use"=>$_POST['is_use']=='1' ? '1':'0' ,
                    "is_an_all"=>$_POST['is_an_all']=='1' ? '1':'0',
                    "StartDate"=>$StartDate,
                    "EndDate"=>$EndDate,
                  );

       pdo_insert('sysAdmin', $param);//新增方法

       //-- 新增歷史紀錄 --
      $new_pdo->hs_new_param=$param;
      $new_pdo->add_history();

     }
     else{

      if (empty($_POST['admin_pwd'])) {

         //-- 歷史紀錄 --
        $new_pdo->hs_tb_name='sysAdmin';
        $new_pdo->hs_old_id=$_POST['Tb_index'];
        $new_pdo->hs_h_location='管理者管理';
        $new_pdo->hs_h_action_type='update';
        $new_pdo->hs_h_title='修改管理者-'.$_POST['name'];
          //-- 舊資料 --
        $new_pdo->old_data();


       $param=array( "admin_per"=>$_POST['admin_per'], 
                    "admin_id"=>$_POST['admin_id'], 
                  "build_time"=>date('Y-m-d H:i:s'), 
                        "name"=>$_POST['name'], 
                      //  "phone"=>$_POST['phone'], 
                      //   "adds"=>$_POST['adds'],
                      //   "email"=>$_POST['email'], 
                       "is_use"=>$_POST['is_use']=='1' ? '1':'0',
                    "is_an_all"=>$_POST['is_an_all']=='1' ? '1':'0',
                    "StartDate"=>$_POST['StartDate'],
                    "EndDate"=>$_POST['EndDate'], 
                  );
        $where=array('Tb_index'=>$_POST['Tb_index']);

        pdo_update('sysAdmin', $param, $where);//更新方法

        //-- 新增歷史紀錄 --
        $new_pdo->add_history();
      }
      else{

        //-- 歷史紀錄 --
        $new_pdo->hs_tb_name='sysAdmin';
        $new_pdo->hs_old_id=$_POST['Tb_index'];
        $new_pdo->hs_h_location='管理者管理';
        $new_pdo->hs_h_action_type='update';
        $new_pdo->hs_h_title='修改管理者-'.$_POST['name'];
          //-- 舊資料 --
        $new_pdo->old_data();


        $param=array( "admin_per"=>$_POST['admin_per'], 
                    "admin_id"=>$_POST['admin_id'], 
                   "admin_pwd"=>aes_encrypt_7($aes_key, $_POST['admin_pwd']), 
                  "build_time"=>date('Y-m-d H:i:s'), 
                        "name"=>$_POST['name'], 
                       "phone"=>$_POST['phone'], 
                        "adds"=>$_POST['adds'], 
                        "email"=>$_POST['email'],
                       "is_use"=>$_POST['is_use']=='1' ? '1':'0',
                    "is_an_all"=>$_POST['is_an_all']=='1' ? '1':'0' 
                  );
       $where=array('Tb_index'=>$_POST['Tb_index']);

       pdo_update('sysAdmin', $param, $where);//更新方法

       //-- 新增歷史紀錄 --
       $new_pdo->add_history();
      }

     

     }
  }
  elseif ($_GET) {
    
    $Tb_index=$_GET['Tb_index'];
    
    $sql=$pdo->prepare("SELECT * FROM sysAdmin WHERE Tb_index=:Tb_index");
    $sql->execute(array(":Tb_index"=>$Tb_index));
    $row=$sql->fetch(PDO::FETCH_ASSOC);
    $zipcode=substr($row['adds'], 0,3);
    $adds=explode(',', $row['adds']);

    $StartDate=empty($row['StartDate']) ? date("Y-m-d") : $row['StartDate'];
    $EndDate=empty($row['EndDate']) ? '' : $row['EndDate'];
  }
?>


<div class="wrapper wrapper-content animated fadeInRight">
  <div class="col-lg-12">
    <h2 class="text-primary">管理者 編輯</h2>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-default">
        <div class="panel-body">
          <form class="form-horizontal">
            <div class="form-group">
              <label class="col-md-1 control-label" for="admin_per">權限名稱</label>
              <div class="col-md-2">
                <select id="admin_per" class="form-control">
                  <option value="admin" <?php echo $admin=$row['admin_per']=='admin' ? 'selected':'';?>>管理員</option>
                  <!--<option value="user" <?php //echo $admin=$row['admin_per']=='user' ? 'selected':'';?>>使用者</option>-->
                  <?php 
                    $sql_group=$pdo->prepare("SELECT Tb_index, Group_name FROM sysAdminGroup");
                    $sql_group->execute();
                    while ($row_group=$sql_group->fetch(PDO::FETCH_ASSOC)) {
                      $pre_group=$row['admin_per']==$row_group['Tb_index'] ? 'selected' : '';
                      echo "<option value='".$row_group['Tb_index']."' ".$pre_group.">".$row_group['Group_name']."</option>";
                    }
                  ?>
                </select>
              </div>
              <label class="col-md-1 control-label" for="admin_id">帳號</label>
              <div class="col-md-2">
                <input type="text" class="form-control" id="admin_id" value="<?php echo $row['admin_id'];?>">
              </div>
              <label class="col-md-1 control-label" for="admin_pwd">更新密碼</label>
              <div class="col-md-2">
                <input type="password" class="form-control" id="admin_pwd" value="">
              </div>
            </div>
            <div class="form-group">
                <label class="col-md-1 control-label" for="name">姓名</label>
                 <div class="col-md-2">
                  <input type="text" class="form-control" id="name" value="<?php echo $row['name'];?>">
                 </div>

                <label class="col-md-1 control-label" for="is_use">狀態</label>
                 <div class="col-md-2">
                  <input type="checkbox" class="checkbox" id="is_use" 
                  <?php echo $check=!isset($row['is_use']) || $row['is_use']==1 ? 'checked' : ''; ?> value="1">
                 </div>

                 <label class="col-md-1 control-label" for="is_an_all">分析進階功能</label>
                 <div class="col-md-2">
                  <input type="checkbox" class="checkbox" id="is_an_all" 
                  <?php echo $check=$row['is_an_all']==1 ? 'checked' : ''; ?> value="1">
                 </div>
            </div>

            <div class="form-group">
              <label class="col-md-1 control-label" >帳號啟用時間</label>
                 <div class="col-md-10">
                   <input type="text" name="StartDate" class="datepicker_range from" value="<?php echo $StartDate;?>"> ～ <input type="text" name="EndDate" class="datepicker_range to" value="<?php echo $EndDate;?>">
                 </div>
            </div>
            
            <div class="form-group">
               <label class="col-md-1 control-label" ></label>
               <div class="col-md-2">
              <?php if (empty($_GET['Tb_index'])) { ?>
                    <button type="button" id="admin_btn" class="btn btn-info btn-block btn-raised">儲存</button>
              <?php  }else{?>
                    <button type="button" id="admin_btn_up" class="btn btn-info btn-block btn-raised">更新</button>
                    <input type="hidden" id="Tb_index" value="<?php echo $row['Tb_index'];?>">
              <?php  }?>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  
</div>
<!-- /#page-content -->
<?php  include("../../core/page/footer01.php");//載入頁面footer01.php?>
<script type="text/javascript">
  $(document).ready(function() {

    // $('.twzipcode').twzipcode({
    // 'zipcodeSel'  : '<?php echo $zipcode;?>' // 此參數會優先於 countySel, districtSel
    // });

    /* -- 新增 -- */
    $("#admin_btn").click(function(event) {
      var data={
                 admin_per: $("#admin_per").val(),
                    admin_id: $("#admin_id").val(),
                   admin_pwd: $("#admin_pwd").val(),
                        name: $("#name").val(),
                      //  phone: $("#phone").val(),
                      //  email: $("#email").val(),
                      //   adds: $('[name="zipcode"]').val()+$('[name="county"]').val()+$('[name="district"]').val()+","+$("#adds").val(),
                      is_use: $(":checked#is_use").val(),
                      is_an_all: $(':checked#is_an_all').val(),
                      StartDate: $('[name="StartDate"]').val(),
                      EndDate: $('[name="EndDate"]').val(),
               };
      ajax_in('manager.php', data, '新增管理者', 'admin.php');
    });

    /* -- 修改 -- */
    $("#admin_btn_up").click(function(event) {
      var data={
                    Tb_index: $("#Tb_index").val(),
                   admin_per: $("#admin_per").val(),
                    admin_id: $("#admin_id").val(),
                   admin_pwd: $("#admin_pwd").val(),
                        name: $("#name").val(),
                      //  phone: $("#phone").val(),
                      //  email: $("#email").val(),
                      //   adds: $('[name="zipcode"]').val()+$('[name="county"]').val()+$('[name="district"]').val()+","+$("#adds").val(),
                      is_use: $(":checked#is_use").val(),
                      is_an_all: $(':checked#is_an_all').val(),
                      StartDate: $('[name="StartDate"]').val(),
                      EndDate: $('[name="EndDate"]').val(),
               };

      ajax_in('manager.php', data, '更新管理者', 'admin.php');
    });

  }); //JQUERY END
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
