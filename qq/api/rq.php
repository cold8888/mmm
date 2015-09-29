<?php
session_start();
header("content-Type: text/html; charset=utf-8");

require_once '../cron.inc.php';

if($islogin!=1)exit('{"code":-1,"msg":"未登录"}');
if(in_array('10',$vip_func) && $isvip==0 && $isadmin==0)exit('{"code":-1,"msg":"您不是VIP，无法使用"}');

$uin=$_POST['uin'];

$myrow=$DB->get_row("SELECT * FROM ".DBQZ."_qq WHERE qq='{$uin}' limit 1");
if($myrow['lx']!=$gl && $isadmin==0)exit('{"code":-1,"msg":"你只能操作自己的QQ哦！"}');

$qid=is_numeric($_POST['qid'])?$_POST['qid']:exit('{"code":-1,"msg":"QID不能为空"}');
$row = $DB->get_row("SELECT * FROM ".DBQZ."_qq where id='{$qid}' limit 1");
if(!$row){
	exit('{"code":-1,"msg":"QID'.$qid.'不存在"}');
}
$gtk=getGTK($row['skey']);
$cookie='uin=o0'.$row['qq'].'; skey='.$row['skey'].';';
//$url='http://wap.m.qzone.com/wap/profile.jsp?hostuin='.$uin.'&sid='.$row['sid'];
$url='http://g.cnc.qzone.qq.com/fcg-bin/cgi_emotion_list.fcg?uin='.$uin.'&loginUin='.$row['qq'].'&s=250195&num=3&g_tk='.$gtk;
$res=get_curl($url,0,'http://user.qzone.qq.com/',$cookie);
$_SESSION['r_'.$cell][$row['qq']]=1;
++$_SESSION['rqcount'];
exit('{"code":0,"msg":"'.$row[qq].'刷人气成功！"}');
