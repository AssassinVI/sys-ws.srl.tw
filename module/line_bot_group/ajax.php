<?php
require '../../core/inc/config.php';
require '../../core/inc/function.php';
require '../../core/inc/pdo_fun_calss.php';

$pdo=new PDO_fun();
switch ($_POST['type']) {
    case 'update':
       $param=[
        'groupName' => $_POST['groupName'],
        'case_id' => $_POST['case_id'],
       ];
       $pdo->update('line_msg_bot_group', $param, ['rowid' => $_POST['rowid']]);
       echo json_encode(['success' => true]);
    break;
    default:
        # code...
    break;
}
$pdo->close();
?>