<?php
require 'qq.inc.php';

$qq=isset($_POST['qq']) ? $_POST['qq'] : $_GET['qq'];
$sid=isset($_POST['sid']) ? $_POST['sid'] : $_GET['sid'];
$skey=isset($_POST['skey']) ? $_POST['skey'] : $_GET['skey'];
if($qq && $sid && $skey){}else{echo"<font color='red'>输入不完整!<a href='javascript:history.back();'>返回重新填写</a></font>";exit;}

require_once 'qzone.class.php';
$qzone=new qzone($qq,$sid,$skey);
$url=$qzone->get_curl('http://user.qzone.qq.com/p/base.s2/cgi-bin/user/cgi_userinfo_get_all?uin='.$qq.'&vuin='.$qq.'&fupdate=1&format=json&rd=0.516339'.time().'&g_tk='.$qzone->gtk,0,'http://user.qzone.qq.com/',$qzone->cookie);
$arr=json_decode($url, true);
if($arr['code']==-3000) sendsiderr($qq,$sid,'skey');

echo '执行完毕[code:'.$arr['code'].']';
?>