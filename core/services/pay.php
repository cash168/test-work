<?php
defined( 'INIT' )
    or die( 'Direct Access to this location is not allowed.' );

$POSTpay="";

//
// ────────────────────────────────────────────────────────────── POST VALUES ─────
//
if(sizeof($_POST)>0){
    foreach ( $_POST as $key => $value )
    {
        switch ($key) {
            case "pay":
                $POSTpay = $Functions->clearuserpostdata($value);
                break;
        }
    }
    
    if($usertype=="client" and $id>0 and is_numeric($POSTpay)){
        $query="UPDATE users SET `balance` = `balance` - ? WHERE id = ?";
        $dataArr=array(
            "pay"=>array('i', $POSTpay),
            "id"=>array('i', $id)
        );
        $userArray=$mysql->mysqlquery($mysqlObj, $query, $dataArr, $error);
        $redirect="/";
        $log="User id=$id pay summ=$POSTpay";
        $App->writelog($config, $log, "INFO");
    }
    elseif(!is_numeric($POSTpay)){
        $redirect="/badpay";
    }
}
else
    $redirect="/";