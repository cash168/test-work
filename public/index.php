<?php
//
// ────────────────────────────────────────────────────────── INIT PHP VALUES ─────
//
$error="";
$redirect="";
$timestamp=time();
//
// ──────────────────────────────────────────────────────── START CORE CONFIG ─────
//
$coreconfig=getcwd()."/coreconfig.php";
$go_config="1";
include($coreconfig);
if(!$coreDir) {
	header('HTTP/1.1 500 Internal Server Error');
	die( "can't load coreconfig." );
}

//
// ──────────────────────────────────────────────────────────────── INIT CORE ─────
//
try {
	if (! @include_once( $coreDir."/core.php" ))
		throw new Exception ("Unable to load core.");
}
catch(Exception $e) {    
	header('HTTP/1.1 500 Internal Server Error');
	die( $e->getMessage() );
}
$App = new App();
$App->init($phpdebug);

$config = $App->readconfig($coreDir, $error);
if($error!="") {
	header('HTTP/1.1 500 Internal Server Error');
	die( $error );
}
$Functions = new Functions();
//
// ────────────────────────────────────────────────────────────── INIT SMARTY ─────
//
$smarty=$App->initsmarty($config, $error);
if($error!="") {
	header('HTTP/1.1 500 Internal Server Error');
	die( $error );
}
$smarty->assign("baseUrl", $config->baseUrl);
$smarty->assign("version", $config->version);

//
// ──────────────────────────────────────────────────────────── CHECK REQUEST ─────
//
//var_export($config);echo "<br><br><br>";
$requestUrl = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 'https' : 'http' ) . '://' .  $_SERVER['HTTP_HOST'];
$domain = $_SERVER['HTTP_HOST'];
$fullRequestUrl = $requestUrl . $_SERVER["REQUEST_URI"];
$request=$Functions->clearuserpostdata(strtok($_SERVER["REQUEST_URI"],'?'));
$requestArr = explode("&#47;", $request);
$requestArr = array_pad ($requestArr, 20, "");
$baseDir = str_replace($requestUrl, "", $config->baseUrl);
//$baseDir=substr($baseDir,1);

if(sizeof($_GET)>20 or sizeof($_POST)>20 or $request=="" or $fullRequestUrl="" or sizeof($requestArr)>20){
	header('HTTP/1.1 400 Bad Request');
	$smarty->display('400.tpl');
	exit();
}
//
// ────────────────────────────────────────────────────────────── START ROUTE ─────
//
$l=sizeof(explode("/", $config->baseUrl))-3;
$requestArr = array_slice($requestArr, $l);

require_once($coreDir."/route.php");
$Route = new Route();

$smartyTpl="";
$systemPhp="";
if(count($requestArr)>1){
	$smartyTpl = $Route->smartyTpl($requestArr[1]);
	$systemPhp = $Route->systemPhp($requestArr[1]);
	$service = $Route->service($requestArr[1]);
}
else{
	$smartyTpl="";
}
//BAD ROUTE - 404 ERROR
if($smartyTpl==""){
	header("HTTP/1.0 404 Not Found");
	$smarty->display('404.tpl');
	exit();
}
DEFINE('INIT', true);
//
// ──────────────────────────────────────────────────────────── START INIT DB ─────
//
if(in_array("mysql", $service)){
	$mysqlObj=$App->initmysql($config, $error);
	if($error!="") {
		header('HTTP/1.1 500 Internal Server Error');
		die( 'DB error. '. $error);
	}
	require_once($coreDir."/system/mysql.php");
	$mysql = new MySQLClass();
}

//
// ───────────────────────────────────────────────────────────── AUTHENTICATE ─────
//
$id=0;
$token="";
$usertype="guest";

if(isset($_COOKIE['id']) and isset($_COOKIE['token'])){
	$COOKIEid=$Functions->clearuserpostdata($_COOKIE['id']);
	$COOKIEtoken=$Functions->clearuserpostdata($_COOKIE['token']);
	if(is_numeric($COOKIEid)){
		$query="SELECT id, datecreate FROM sessions WHERE user_id = ? AND token = ?";
		$dataArr=array(
			"user_id"=>array('i', $COOKIEid),
			"token"=>array('s', $COOKIEtoken)
		);
		$userArray=$mysql->mysqlquery($mysqlObj, $query, $dataArr, $error);
		if(sizeof($userArray)==1){
			$usertype='client';
			setcookie('id', $COOKIEid, $timestamp+$config->registerTimeout, '/', $domain, true);
			setcookie('token', $COOKIEtoken, $timestamp+$config->registerTimeout, '/', $domain, true);
			$id=$COOKIEid;
		}
	}
}
//
// ───────────────────────────────────────────────────────────────────── WORK ─────
//

if($systemPhp!=""){
	require_once($coreDir."/services/".$systemPhp);
}

//
// ───────────────────────────────────────────────────────────── RELEASE WORK ─────
//
if(in_array("mysql", $service)){
	$App->releasemysql($mysqlObj);
}
//
// ──────────────────────────────────────────────────────── INIT SMARTY VALUE ─────
//
$smarty->assign("usertype", $usertype);
$smarty->assign("error", $error);
$smarty->assign("baseDir", $baseDir);
//
// ─────────────────────────────────────────────── REDIRECT OR SMARTY DISPLAY ─────
//
if($redirect!=""){
	ob_start();
	header('Location: '.$config->baseUrl.$redirect);
	ob_end_flush();
	die();
}
else{
	$smarty->display($smartyTpl);
}
//
// ────────────────────────────────────────────────────────────────────── END ─────
//
exit();