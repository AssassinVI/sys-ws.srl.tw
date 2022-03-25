<?php 
require '../../core/inc/config.php';
require '../../core/inc/function.php';

if ($_POST) {

	

	pdo_delete('build_case', ['Tb_index'=>$_POST['Tb_index']], 'srltw_test_case');

  pdo_delete('Related_tb', ['case_id'=>$_POST['Tb_index']], 'srltw_test_case');
  pdo_delete('base_word', ['case_id'=>$_POST['Tb_index']], 'srltw_test_case');
  pdo_delete('slideshow_tb', ['case_id'=>$_POST['Tb_index']], 'srltw_test_case');
  pdo_delete('youtube_tb', ['case_id'=>$_POST['Tb_index']], 'srltw_test_case');
  pdo_delete('googlemap_tb', ['case_id'=>$_POST['Tb_index']], 'srltw_test_case');
  pdo_delete('call_us_tb', ['case_id'=>$_POST['Tb_index']], 'srltw_test_case');
  pdo_delete('img_wall_tb', ['case_id'=>$_POST['Tb_index']], 'srltw_test_case');
  pdo_delete('other_tb', ['case_id'=>$_POST['Tb_index']], 'srltw_test_case');
  pdo_delete('mathHome_tb', ['case_id'=>$_POST['Tb_index']], 'srltw_test_case');
  pdo_delete('anchor_tb', ['case_id'=>$_POST['Tb_index']], 'srltw_test_case');
  pdo_delete('life_tb', ['case_id'=>$_POST['Tb_index']], 'srltw_test_case');
  pdo_delete('change_css', ['Tb_index'=>$_POST['Tb_index']], 'srltw_test_case');
  pdo_delete('color', ['Tb_index'=>$_POST['Tb_index']], 'srltw_test_case');
  pdo_delete('case_news', ['case_id'=>$_POST['Tb_index']], 'srltw_test_case');
	
	deleteDir('../../../product_html/'.$_POST['Tb_index']);
   
   
}
?>