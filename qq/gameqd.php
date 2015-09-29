<?php
/*
 *游戏大厅签到
*/
require 'qq.inc.php';

$qq=isset($_POST['qq']) ? $_POST['qq'] : $_GET['qq'];
$skey=isset($_POST['skey']) ? $_POST['skey'] : $_GET['skey'];
if($qq && $skey){}else{echo"<font color='red'>输入不完整!<a href='javascript:history.back();'>返回重新填写</a></font>";exit;}
include '../includes/authcode.php';

$data=curl_get('http://api.cccyun.cn/api/gqd.php?uin='.$qq.'&skey='.$skey.'&url='.$_SERVER['HTTP_HOST'].'&authcode='.$authcode);
echo $data;

//SID失效通知
if(strpos($data,'SKEY')!==false){
	sendsiderr($qq,$sid,'skey');
}
?>