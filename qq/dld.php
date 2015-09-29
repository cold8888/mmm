<?php
//大乐斗领礼包
require 'qq.inc.php';

$qq=isset($_POST['qq']) ? $_POST['qq'] : $_GET['qq'];
$sid=isset($_POST['sid']) ? $_POST['sid'] : $_GET['sid'];

if($qq && $sid){}else{echo"<font color='red'>输入不完整!<a href='javascript:history.back();'>返回重新填写</a></font>";exit;}

$url = curl_get('http://dld.qzapp.z.qq.com/qpet/cgi-bin/phonepk?zapp_uin='.$qq.'&B_UID=0&sid='.$sid.'&channel=0&g_ut=2&cmd=getdaygift');
 
if(preg_match('/领取徒弟经验/',$url)){
	curl_get("http://dld.qzapp.z.qq.com/qpet/cgi-bin/phonepk?zapp_uin={$qq}&B_UID=0&sid={$sid}&channel=213&g_ut=2&cmd=exp");
}
##领取徒弟经验

curl_get("http://dld.qzapp.z.qq.com/qpet/cgi-bin/phonepk?zapp_uin=".$qq."&sid=".$sid."&channel=0&g_ut=1&cmd=sharegame&subtype=6");
##一键分享 

curl_get("http://dld.qzapp.z.qq.com/qpet/cgi-bin/phonepk?zapp_uin=".$qq."&sid=".$sid."&channel=0&g_ut=1&cmd=intfmerid&sub=22");
##领取传功符

curl_get("http://dld.qzapp.z.qq.com/qpet/cgi-bin/phonepk?zapp_uin=".$qq."&sid=".$sid."&channel=0&g_ut=1&cmd=wish&sub=5");
##领取许愿奖励
curl_get("http://dld.qzapp.z.qq.com/qpet/cgi-bin/phonepk?zapp_uin=".$qq."&sid=".$sid."&channel=0&g_ut=1&cmd=wish&sub=1");
##上香许愿
curl_get("http://dld.qzapp.z.qq.com/qpet/cgi-bin/phonepk?zapp_uin=".$qq."&sid=".$sid."&channel=0&g_ut=1&cmd=wish&sub=6");
##连续许愿三天奖励

if(preg_match('/续费达人/',$url)){
	curl_get('http://dld.qzapp.z.qq.com/qpet/cgi-bin/phonepk?zapp_uin='.$qq.'&B_UID=0&sid='.$sid.'&channel=0&g_ut=2&cmd=wuzitianshudaygift');
	##无字天书
	curl_get('http://dld.qzapp.z.qq.com/qpet/cgi-bin/phonepk?zapp_uin='.$qq.'&B_UID=0&sid='.$sid.'&channel=0&g_ut=1&cmd=getDrDayGift');
	##达人礼包
}

$resultStr='执行完毕。';

echo $resultStr;
?>