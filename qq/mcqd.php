<?php
/*
 *牧场签到
*/
require 'qq.inc.php';

$qq=isset($_POST['qq']) ? $_POST['qq'] : $_GET['qq'];
$sid=isset($_POST['sid']) ? $_POST['sid'] : $_GET['sid'];
$skey=isset($_POST['skey']) ? $_POST['skey'] : $_GET['skey'];

if($qq && $sid){}else{echo"<font color='red'>输入不完整!<a href='javascript:history.back();'>返回重新填写</a></font>";exit;}


curl_get('http://mcapp.z.qq.com/mc/cgi-bin/wap_pasture_index?sid='.$sid.'&uin='.$qq.'&g_ut=2&source=qzone');
curl_get('http://mcapp.z.qq.com/mc/cgi-bin/wap_pasture_index?sid='.$sid.'&uin='.$qq.'&g_ut=1&signin=1&yellow=1&optflag=2&pid=0&v=1');
echo '牧场签到成功！';
?>