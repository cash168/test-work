<?php
defined( 'INIT' )
    or die( 'Direct Access to this location is not allowed.' );

if($usertype=="client" and $id>0){
    $log="User id=$id logout";
    $App->writelog($config, $log, "INFO");
    $id=0;
    $token=$Functions->token();
    setcookie('id', $id, $timestamp-$config->registerTimeout, '/');
    setcookie('token', $token, $timestamp-$config->registerTimeout, '/');
}
$redirect="/";