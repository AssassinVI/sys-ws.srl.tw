</head>

<body class="fixed-sidebar pace-done">
<div id="wrapper">

    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                            <div class="img-circle" id="cLogo"><?php echo mb_substr($company['name'], 0, 1, 'UTF-8') ?></div>
                            <div id="cLogoWord"><?php echo $company['name'] ?>｜<br>管理介面</div>
                    </div>
                    <div class="logo-element">
                        W
                    </div>
                </li>

                     <?php
                      if($_COOKIE['admin_per']=='admin'){
                     ?>
                       
                             <!-- <li>
                                <a href="../../module/Dashboard/index.php">
                                    <i class="fa fa-th-large"></i>
                                    <span class="nav-label">網站儀錶板</span>
                                </a>
                            </li> -->

                     <?php   
                      }
                     ?>
                            



                <?php
$sql_query = "SELECT *, mt.Tb_index as mt_Tb_index
                             FROM maintable as mt left JOIN sysModule as md ON mt.UseModuleID=md.Tb_index
                             WHERE mt.parent_id='' AND mt.weblang=:weblang AND OnLineOrNot='1' ORDER BY OrderBy DESC,mt.Tb_index ASC";
$pdo = pdo_conn();
$sql = $pdo->prepare($sql_query);
$sql->execute(array(':weblang' => $weblang));
while ($bar = $sql->fetch(PDO::FETCH_ASSOC)) {
    

    if (isset($_SESSION['group'])) {
        if (!in_array($bar['mt_Tb_index'], $_SESSION['group'])) {
           continue;
        }
    }elseif ($_SESSION['admin_per']!='admin') {
        continue;
    }


	// ------------------------ 單元 ----------------------------------
	if ($bar['is_data'] == '1') {

		$bar_txt = ' <li>
                                      <a href="../../module/' . $bar['Mod_code'] . '/admin.php?MT_id=' . $bar['mt_Tb_index'] . '">
                                        <i class="fa fa-file-o"></i>
                                        <span class="nav-label">' . $bar['MT_Name'] . '</span>
                                      </a>
                                    </li>';
	}

	// ------------------------- 資料夾 -------------------------------------
	else {

		$bar_txt = '
                            <li>
                                <a href="#">
                                    <i class="fa fa-folder-open-o"></i>
                                    <span class="nav-label">' . $bar['MT_Name'] . ' </span>
                                    <span class="fa arrow"></span>
                                </a>
                                        <ul class="nav nav-second-level">';

		$sql_query = "SELECT *, mt.Tb_index as mt_Tb_index
                                        FROM maintable as mt left JOIN sysModule as md ON mt.UseModuleID=md.Tb_index
                                        WHERE mt.parent_id=:parent_id AND mt.weblang=:weblang AND OnLineOrNot='1' ORDER BY OrderBy DESC,mt.Tb_index ASC";
		$sql_tree = $pdo->prepare($sql_query);
		$sql_tree->execute(array(":parent_id" => $bar['mt_Tb_index'], ':weblang' => $weblang));
		while ($row_tree = $sql_tree->fetch(PDO::FETCH_ASSOC)) {

    if (isset($_SESSION['group'])) {
        if (!in_array($row_tree['mt_Tb_index'], $_SESSION['group'])) {
           continue;
        }
    }elseif ($_SESSION['admin_per']!='admin') {
        continue;
    }

			$bar_txt .= bar_tree_tb($row_tree['mt_Tb_index'], $row_tree['Mod_code'], $row_tree['MT_Name'], $row_tree['is_data']);
		}

		$bar_txt .= '</ul></li>';
	}
	echo $bar_txt;
}
$pdo = NULL;

/* ====================== 子樹狀階層 ====================== */
function bar_tree_tb($Tb_index, $Mod_code, $MT_Name, $is_data) {
	if ($is_data == '1') {
		// -- 子單元 --
		$txt = '<li> <a href="../../module/' . $Mod_code . '/admin.php?MT_id=' . $Tb_index . '">' . $MT_Name . '</a> </li>';

	} else {
		// -- 子資料夾 --

		$txt = '<li><a href="#">' . $MT_Name . ' <span class="fa arrow"></span></a>';
		$txt .= '         <ul class="nav nav-third-level">';

		$sql_query = "SELECT *, mt.Tb_index as mt_Tb_index
                         FROM maintable as mt left JOIN sysModule as md ON mt.UseModuleID=md.Tb_index
                         WHERE parent_id=:parent_id AND OnLineOrNot='1' ORDER BY OrderBy DESC,mt.Tb_index ASC";
		$pdo = pdo_conn();
		$sql = $pdo->prepare($sql_query);
		$sql->execute(array(':parent_id' => $Tb_index));
		while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {

    if (isset($_SESSION['group'])) {
        if (!in_array($row['mt_Tb_index'], $_SESSION['group'])) {
           continue;
        }
    }elseif ($_SESSION['admin_per']!='admin') {
        continue;
    }
			$txt .= bar_tree_tb($row['mt_Tb_index'], $row['Mod_code'], $row['MT_Name'], $row['is_data']);
		}

		$txt .= '</ul>';
		$txt .= '</li>';
		$pdo = NULL;
	}
	return $txt;
}
?>


                         <?php if ($_SESSION['admin_per'] == 'admin') {?>
                            <li class="landing_link">
                                <a href="../../system/contact/admin.php">
                                    <i class="fa fa-info-circle"></i>
                                    <span class="nav-label">公司基本資料設定</span>
                                </a>
                            </li>
                            <li class="special_link">
                                <a href="#">
                                    <i class="fa fa-cog"></i>
                                    <span class="nav-label">系統設定</span>
                                    <span class="fa arrow"></span></a>
                                </a>
                                <ul class="nav nav-second-level">
                                    <!-- <li><a href="../../system/lang/admin.php">語系設定</a></li> -->
                                    <li><a href="../../module/company/admin.php?MT_id=">公司管理</a></li>
                                    <li><a href="../../system/google_an/admin.php">Google 分析更新</a></li>
                                    <li><a href="../../system/funBox_edit/admin.php">功能區塊-編輯</a></li>
                                    <li><a href="../../system/site/admin.php">網頁架構</a></li>
                                    <li><a href="../../system/md/admin.php">模組管理</a></li>
                                    <li><a href="../../system/admin/admin.php">管理者管理</a></li>
                                    <li><a href="../../system/admin_group/admin.php">群組權限管理</a></li>
                                 
                                </ul>
                            </li>
                             <?php }?>



            </ul>

        </div>
    </nav>

    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                  <?php 
                    if($_COOKIE['admin_per']=='admin'){
                      echo '<a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>';
                    }
                    else{
                      echo '<a class="an_home_btn btn btn-primary " href="/sys/module/case_analysis/admin.php?MT_id=site2018040210520640"><i class="fa fa-home"></i> </a>';
                    }
                  ?>
                    

                </div>
                <ul id="right_tool" class="nav navbar-top-links navbar-right">
                    <li class="dropdown">

                        <?php

// --------------------------------- 語系 ------------------------------
// $pdo = pdo_conn();
// $sql = $pdo->prepare("SELECT Lang_name, Lang_code FROM sysLang WHERE is_use='1'");
// $sql->execute();

// echo '<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">';
// echo '   <i class="fa fa-globe"></i>  <span class="label label-warning">' . $sql->rowcount() . '</span>';
// echo '</a>';
// echo '<ul class="dropdown-menu dropdown-messages">';

// while ($lang = $sql->fetch(PDO::FETCH_ASSOC)) {
// 	echo "<li><a href='?lang=" . $lang['Lang_code'] . "'>" . $lang['Lang_name'] . "</a></li>";
// }
// $pdo = NULL;

// echo '</ul>';
?>

                    </li>
                    <li>
                        <?php 
                          if($_COOKIE['admin_per']=='admin'){
                            echo '<b>管理者</b>';
                          }
                          else{
                            echo "<b>使用者：".$_COOKIE['admin_name']."</b>";
                          }
                        ?>
                        
                    </li>
                    <li>
                        <a id="logout_btn" href="../../login.php?login=out" onclick="if (!confirm('是否要登出?')) {return false;}">
                            <i class="fa fa-sign-out"></i> 帳號登出
                        </a>
                    </li>
                    
                </ul>

            </nav>
        </div>

<?php
$break_txt='';
$where = array("Tb_index" => $_GET['MT_id']);
$page_name = pdo_select("SELECT MT_Name, parent_id FROM maintable WHERE Tb_index=:Tb_index", $where);

if (!empty($page_name['parent_id'])) {
    $break_txt.=break_top($page_name['parent_id']);
}

$break_txt.='<li class="active"><strong>'.$page_name['MT_Name'].'</strong></li>';


function break_top($parent_id)
{
  $where = array("Tb_index" => $parent_id);
  $page_name = pdo_select("SELECT MT_Name, parent_id FROM maintable WHERE Tb_index=:Tb_index", $where);

  if (!empty($page_name['parent_id'])) {
     $break_txt.=break_top($page_name['parent_id']);
  }
  $break_txt.='<li class="active"><strong>'.$page_name['MT_Name'].'</strong></li>';
  return $break_txt;
}
?>


        <!-- <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2><?php //echo $page_name['MT_Name'] ?> </h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="../Dashboard/index.php">Home</a>
                        </li>
                        <?php //echo $break_txt?>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div> -->
