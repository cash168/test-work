<?php
defined( 'INIT' )
    or die( 'Direct Access to this location is not allowed.' );

if($usertype=="client" and $id>0){
    if($requestArr[1]=="badpay")
        $error="Неверно введена сумма";
    $query="SELECT balance FROM users WHERE id = ?";
    $dataArr=array(
        "id"=>array('i', $id)
    );
    $userArray=$mysql->mysqlquery($mysqlObj, $query, $dataArr, $error);
    $balance=$userArray[0]["balance"];
    $smarty->assign("balance", $balance);
}

elseif($requestArr[1]=="badlogin")
    $error="Неверно введены логин и пароль";
