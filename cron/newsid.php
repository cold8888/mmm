<?php
//自动更新SID&Skey 监控文件

function login_sig(){
	$url="http://xui.ptlogin2.qq.com/cgi-bin/xlogin?proxy_url=http%3A//qzs.qq.com/qzone/v6/portal/proxy.html&daid=5&pt_qzone_sig=1&hide_title_bar=1&low_login=0&qlogin_auto_login=1&no_verifyimg=1&link_target=blank&appid=549000912&style=22&target=self&s_url=http%3A//qzs.qq.com/qzone/v5/loginsucc.html?para=izone&pt_qr_app=手机QQ空间&pt_qr_link=http%3A//z.qzone.com/download.html&self_regurl=http%3A//qzs.qq.com/qzone/v6/reg/index.html&pt_qr_help_link=http%3A//z.qzone.com/download.html";
	$ret = get_curl($url,0,1,0,1);
	preg_match('/pt_login_sig=(.*?);/',$ret,$skey);
	return $skey[1];
}
function checkvc($uin){
	$url='http://check.ptlogin2.qq.com/check?regmaster=&pt_tea=1&pt_vcode=1&uin='.$uin.'&appid=549000912&js_ver=10132&js_type=1&login_sig=&u1=http%3A%2F%2Fqzs.qq.com%2Fqzone%2Fv5%2Floginsucc.html%3Fpara%3Dizone&r=0.397176'.time();
	$data=get_curl($url);
	if(preg_match('/ptui_checkVC'."\(".'\'(.*?)\''."\)".';/', $data, $arr)){
		$r=explode('\',\'',$arr[1]);
		if($r[0]==0){
			return array('0',$r[1],$r[3]);
		}else{
			return array('1');
		}
	}else{
		return array('2');
	}
}
function getsid($url, $do = 0)
{
	$do++;
	if ($ret = get_curl($url)) {
		$ret = preg_replace('/([\x80-\xff]*)/i','',$ret);
		if (preg_match('/sid=(.{24})&/iU', $ret, $sid)) {
			return $sid[1];
		} else {
			if ($do < 5) {
				return getsid($url, $do);
			} else {
				return;
			}
		}
	} else {
		return;
	}
}
function qqlogin($uin,$p,$vcode,$pt_verifysession){
	$v1=0;
	$url='http://ptlogin2.qq.com/login?u='.$uin.'&verifycode='.$vcode.'&pt_vcode_v1='.$v1.'&pt_verifysession_v1='.$pt_verifysession.'&p='.$p.'&pt_randsalt=0&u1=http%3A%2F%2Fqzs.qq.com%2Fqzone%2Fv5%2Floginsucc.html%3Fpara%3Dizone&ptredirect=0&h=1&t=1&g=1&from_ui=1&ptlang=2052&action=2-10-'.time().'7584&js_ver=10133&js_type=1&login_sig='.login_sig().'&pt_uistyle=32&aid=549000912&daid=5&pt_qzone_sig=0&';
	$sidurl = 'http://ptlogin2.qzone.com/login?verifycode='.$vcode.'&u='.$uin.'&p='.$p.'&pt_randsalt=0&ptlang=2052&low_login_enable=0&u1=http%3A%2F%2Fsqq2.3g.qq.com%2Fhtml5%2Fsqq2vip%2Findex.jsp&from_ui=1&fp=loginerroralert&device=2&aid=549000912&pt_ttype=1&daid=147&pt_3rd_aid=0&ptredirect=1&h=1&g=1&pt_uistyle=9&pt_vcode_v1='.$v1.'&pt_verifysession_v1='.$pt_verifysession.'&';
	$ret = get_curl($url,0,0,0,1);
	$sidret = get_curl($sidurl,0,0,0,1,"'Mozilla/5.0 (Linux; U; Android 4.0.4; es-mx; HTC_One_X Build/IMM76D) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0");
	if(preg_match("/ptuiCB\('(.*?)'\);/", $ret, $arr) && preg_match("/ptuiCB\('(.*?)'\);/", $sidret, $sidarr)){
		$r=explode("','",str_replace("', '","','",$arr[1]));
		$sr=explode("','",str_replace("', '","','",$sidarr[1]));
		if($r[0]==0){
			preg_match('/skey=@(.{9});/',$ret,$skey);
			$data=get_curl($r[2],0,0,0,1);
			if($data) {
				preg_match("/p_skey=(.*?);/", $data, $matchs);
				$pskey = $matchs[1];
			}
			$data=get_curl($sr[2],0,0,0,1,"'Mozilla/5.0 (Linux; U; Android 4.0.4; es-mx; HTC_One_X Build/IMM76D) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0");
			if($data) {
				preg_match("/p_skey=(.*?);/", $data, $matchs);
				$pskey2 = $matchs[1];
				preg_match("/Location: (.*?)\r\n/iU", $data, $matchs);
				$sid=getsid($matchs[1]);
			}
			if($pskey && $sid){
				$array['sid']=$sid;
				$array['skey']='@'.$skey[1];
				$array['pskey']=$pskey;
				$array['pskey2']=$pskey2;
			}
			return $array;
		}elseif($r[0]==4){
			return 4;
		}elseif($r[0]==3){
			return 3;
		}elseif($r[0]==19){
			return 19;
		}else{
			return 0;
		}
	}else{
		return 0;
	}
}

@chdir(dirname(__FILE__));
include_once("../includes/cron.inc.php");

/*更新sid配置*/
$szie=3; //每次更新的QQ个数

$result=$DB->query("select * from `".DBQZ."_qq` where status='0' or status2='0' limit {$szie}");

while($row=$DB->fetch($result)){

$uin=$row['qq'];
$pwd=authcode($row['pw'],'DECODE',SYS_KEY);
$sql='';
$check=checkvc($uin);
if($check[0]==0){
	$vcode=$check[1];
	$p=get_curl('http://qqapp.aliapp.com/?uin='.$uin.'&pwd='.strtoupper(md5($pwd)).'&vcode='.strtoupper($vcode));
	if($p=='error'||$p=='')exit($uin.' getp failed!<br/>');
	$arr=qqlogin($uin,$p,$vcode,$check[2]);
	if($arr==3){
		if($row['status']==0)$DB->query("UPDATE ".DBQZ."_qq SET status='4' WHERE qq='".$row['qq']."'");
		$DB->query("UPDATE ".DBQZ."_qq SET status2='4' WHERE qq='".$row['qq']."'");
//		$DB->query("DELETE FROM ".DBQZ."_qq WHERE qq='$uin'");
//		$DB->query("DELETE FROM ".DBQZ."_job WHERE proxy='$uin'");
		//发送邮件
		$myrow=$DB->get_row("SELECT * FROM ".DBQZ."_user WHERE user='{$row['lx']}' limit 1");
		if(!empty($myrow['email']) && $myrow['mail_on']==1) send_mail_qqgx($myrow['email'],$uin);
		echo $uin.' Invaid Password!<br/>';
	}elseif(is_array($arr)){
		$sid=$arr['sid'];
		$skey=$arr['skey'];
		$ptsig=$arr['ptsig'];
		$pskey=$arr['pskey'];
		$pskey2=$arr['pskey2'];
		$DB->query("UPDATE ".DBQZ."_qq SET sid='$sid',skey='$skey',ptsig='$ptsig',pskey='$pskey',pskey2='$pskey2',status='1',status2='1' WHERE qq='".$uin."'");
		echo $uin.' Update Success!<br/>';
	}else{
		if($row['status']==0)$DB->query("UPDATE ".DBQZ."_qq SET status='4' WHERE qq='".$row['qq']."'");
		$DB->query("UPDATE ".DBQZ."_qq SET status2='4' WHERE qq='".$row['qq']."'");
		echo $uin.' Update failed!<br/>';
		//发送邮件
		$myrow=$DB->get_row("SELECT * FROM ".DBQZ."_user WHERE user='{$row['lx']}' limit 1");
		if(!empty($myrow['email']) && $myrow['mail_on']==1) send_mail_qqgx($myrow['email'],$uin);
	}
}else{
	if($row['status']==0)$DB->query("UPDATE ".DBQZ."_qq SET status='4' WHERE qq='".$row['qq']."'");
	$DB->query("UPDATE ".DBQZ."_qq SET status2='4' WHERE qq='".$row['qq']."'");
	echo $uin.' Need Code!<br/>';
	//发送邮件
	$myrow=$DB->get_row("SELECT * FROM ".DBQZ."_user WHERE user='{$row['lx']}' limit 1");
	if(!empty($myrow['email']) && $myrow['mail_on']==1) send_mail_qqgx($myrow['email'],$uin);
}

}

echo 'OK!';
?>