<?php
class App {
	//
	// ──────────────────────────────────────────────────────────── LOAD FUNCTION ─────
	//
	static $_Functions;
	function __construct() {
        include('system/function.php');
        self::$_Functions = new Functions();
    }
	//
	// ──────────────────────────────────────────────────────────── INIT FUNCTION ─────
	//
	public static function init($phpdebug){
		if($phpdebug==true){
			error_reporting(E_ALL);
			ini_set('display_errors', 1);
		}
		mb_internal_encoding("UTF-8");
		date_default_timezone_set('Europe/Moscow');
		header("Cache-Control: no-cache, max-age=0, must-revalidate, no-store");
		return;
	}
	//
	// ────────────────────────────────────────────────────────────── READ CONFIG ─────
	//
	public function readconfig($coreDir, &$error){
		//read main config file
		try {
			if (! @include_once( $coreDir."/config.php" ))
				throw new Exception ("can't load config");
 		}
		catch(Exception $e) {    
			$error=$e->getMessage();
			return;
		}

		$arr = get_defined_vars();
		$config = new stdClass();
		//read vars
		foreach($arr as $key => $value){ 
			$config->$key = $value;
		}
		
		$config->publicDir = getcwd();
		$config->coreDir = $coreDir;

		return $config;
	}
	//
	// ─────────────────────────────────────────────────────────────────── SMARTY ─────
	//
	public function initsmarty($config, &$error){
		try {
			if (! @include_once( $config->smartyDir.'/libs/Smarty.class.php' ))
				throw new Exception ("can't load smarty");
 		}
		catch(Exception $e) {    
			$error=$e->getMessage();
			return;
		}
		$smarty = new Smarty;
		$smarty->caching = $config->smartycaching;
		$smarty->cache_lifetime = $config->smartycache_lifetime;
		$smarty->setTemplateDir($config->smartyTemplateDir)
		       ->setCompileDir($config->smartysetCompileDir)
		       ->setCacheDir($config->smartysetCacheDir);
		$smarty->debugging = $config->smartydebugging;
		return $smarty;
	}
	//
	// ──────────────────────────────────────────────────────────────────── MYSQL ─────
	//
	public function initmysql($config, &$error){
		$mysqlObj = new mysqli( $config->mysqlServer, $config->mysqlUser, $config->mysqlPass, $config->mysqlDB);
		/* check connection */
		if ($mysqlObj->connect_errno) {
		    $error="Connect failed: ". $mysqlObj->connect_error;
		    return;
		}
		if (!$mysqlObj->query("SET NAMES 'UTF8'")) {
		    $error="Errormessage: ". $mysqlObj->error;
		    $mysqlObj->close();
		    return;
		}
		return $mysqlObj;
	}

	public function releasemysql($mysqlObj){
		$mysqlObj->close();
		return;
	}

	//
	// ────────────────────────────────────────────────────────────────── LOGGING ─────
	//
	public function writelog($config, $log, $type){
		$log.="\n";
		$type.=": ";
		$datetime = date_create()->format('Y-m-d H:i:s');
		$date = date_create()->format('Y-m-d');
		$datetime.=" ";
		error_log($datetime.$type.$log, 3, $config->coreDir."/logs/error_".$date.".log");
		return;
	}
		
	//
	// ──────────────────────────────────────────────────────────────────── OTHER ─────
	//
	
}