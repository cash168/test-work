<?php
defined( 'INIT' ) or die( 'Direct Access to this location is not allowed.' );

$errorArray=array(
    "login"=>"",
    "password"=>""
);
$login="";
$password="";
$error="";
//
// ────────────────────────────────────────────────────────────── POST VALUES ─────
//
if(sizeof($_POST)>0){
    foreach ( $_POST as $key => $value )
    {
        switch ($key) {
            case "login":
                $POSTlogin = $Functions->clearuserpostdata($value);
                break;
            case "password":
                $POSTpassword = $Functions->clearuserpostdata($value);
                break;
        }
    }
    
    $password = $Functions->passwordhash($POSTpassword);
    $query="SELECT id, balance FROM users WHERE login = ? AND password = ?";
    $dataArr=array(
        "login"=>array('s', $POSTlogin),
        "password"=>array('s', $password)
    );
    $userArray=$mysql->mysqlquery($mysqlObj, $query, $dataArr, $error);
    if(sizeof($userArray)!=1){
        $redirect="/badlogin";
        $log="User $POSTlogin bad login";
        $App->writelog($config, $log, "INFO");
    }
    else{
        $id=$userArray[0]["id"];
        $token=$Functions->token();
        setcookie('id', $id, $timestamp+$config->registerTimeout, '/');
        setcookie('token', $token, $timestamp+$config->registerTimeout, '/');
        $query="INSERT INTO sessions ( `user_id`, `token` ) VALUES ( ?, ? )";
        $dataArr=array(
            "user_id"=>array('i', $id),
            "token"=>array('s', $token)
        );
        $mysql->mysqlquery($mysqlObj, $query, $dataArr, $error);
        $log="User id=$id login";
        $App->writelog($config, $log, "INFO");
        $redirect="/";
    }
}
else
    $redirect="/";
