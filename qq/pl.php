<?php
/*
 *QQ空间说说秒评API
*/
require 'qq.inc.php';

$qq=isset($_POST['qq']) ? $_POST['qq'] : $_GET['qq'];
$sid=isset($_POST['sid']) ? $_POST['sid'] : $_GET['sid'];
$skey=isset($_POST['skey']) ? $_POST['skey'] : $_GET['skey'];
$pskey=isset($_POST['pskey']) ? $_POST['pskey'] : $_GET['pskey'];
$pskey2=isset($_POST['pskey2']) ? $_POST['pskey2'] : $_GET['pskey2'];
$method=isset($_POST['method']) ? $_POST['method'] : $_GET['method'];
if(empty($method))$method=2;
$content=isset($_POST['content']) ? $_POST['content'] : $_GET['content'];
$forbid=isset($_POST['forbid']) ? $_POST['forbid'] : $_GET['forbid'];
$getss=isset($_POST['getss']) ? $_POST['getss'] : $_GET['getss'];
if($qq && $sid && $skey){}else{echo"<font color='red'>输入不完整!<a href='javascript:history.back();'>返回重新填写</a></font>";exit;}


//执行秒评
$forbid=explode('|',$forbid);
require_once 'qzone.class.php';
$qzone=new qzone($qq,$sid,$skey,$pskey,$pskey2);
if($method==1)
$qzone->mood_reply($content);
elseif($method==2)
$qzone->reply(0,$content,$forbid);
elseif($method==3)
$qzone->reply(1,$content,$forbid);

//结果输出
foreach($qzone->msg as $result){
	echo $result.'<br/>';
}

//SID失效通知
if($qzone->sidzt){
	sendsiderr($qq,$sid,'sid');
}elseif($qzone->skeyzt){
	sendsiderr($qq,$sid,'skey');
}
?>
