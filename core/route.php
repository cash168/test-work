<?php
class Route {
    //
    // ────────────────────────────────────────────────────────── SMARTY TPL LIST ─────
    //
    protected $smartyroute=array(
        ""          => "index.tpl",
        "login"     => "blank.tpl",
        "badlogin"  => "index.tpl",
        "pay"       => "index.tpl",
        "badpay"    => "index.tpl",
        "logout"    => "blank.tpl"
    );
    
    public function smartyTpl($request){
        if (array_key_exists($request,$this->smartyroute))
            return $this->smartyroute[$request];
        else
            return "";
    }
    
    //
    // ────────────────────────────────────────────────────────── PHP WORKER LIST ─────
    //
    protected $systemroute=array(
        ""          => "index.php",
        "login"     => "login.php",
        "badlogin"  => "index.php",
        "pay"       => "pay.php",
        "badpay"    => "index.php",
        "logout"     => "logout.php"
    );
    public function systemPhp($request){
        if (array_key_exists($request,$this->systemroute))
            return $this->systemroute[$request];
        else
            return "";
    }

    //
    // ───────────────────────────────────────────────────────────── SERVICE LIST ─────
    //
    protected $serviceroute=array(
        ""          => array("mysql"),
        "login"     => array("mysql"),
        "badlogin"  => array("mysql"),
        "pay"       => array("mysql"),
        "badpay"    => array("mysql"),
        "logout"    => array("mysql")
    );
    public function service($request){
        if (array_key_exists($request,$this->serviceroute))
            return $this->serviceroute[$request];
        else
            return array();
    } 
}