<?php
class MySQLClass{
    
    public function mysqlquery($mysqlObj, $query, $dataArr, &$error){
        $returnArr=array();
        $lastid=0;
        if(sizeof($dataArr)==0)
            return $returnArr;

        $bind="";
        $params_arr=array();
        foreach ($dataArr as $key => $value) {
            $params_arr[] =$value[1];
            $bind.=$value[0];
        }
        
        $params= array_merge(array($bind), $params_arr);
        $stmt = $mysqlObj->prepare($query);
        if($mysqlObj->error){
            $error=$mysqlObj->error;
            return $returnArr;
        }
        $refs = array();
        foreach($params as $key => $value)
            $refs[$key] = &$params[$key];
        call_user_func_array(array($stmt, 'bind_param'), $refs);
        $stmt->execute();
        //last id:
        $affected_rows=$stmt->affected_rows;
        $lastid = $stmt->insert_id;
        if(strpos($query, 'SELECT')!== false){
            //Create results Array:
            $rows = $stmt->get_result();
            if($rows==false)
                $returnArr=array();
            else
                $returnArr = $rows->fetch_all(MYSQLI_ASSOC);
        }
        elseif(strpos($query, 'UPDATE')!== false){
            $returnArr=array("affected_rows"=>$affected_rows);
        }
        else{
            $returnArr=array("lastid"=>$lastid, "affected_rows"=>$affected_row);
        }
        $stmt->close();
        return $returnArr;
    }

    public function mysqltest($mysqlObj, &$error){

        $id="0";
        


        //UPDATE
        $password=time();
        $query = $mysqlObj->prepare( "UPDATE users SET password=? WHERE user_id=?");
        if($mysqlObj->error){
            $error=$mysqlObj->error;
            return;
        }
        $n=9;
        $query->bind_param( 'si', $password, $n);
        $query->execute();
        $query->close();


        /*
        //INSERT
        $name="name";
        $email="email";
        $password="password";
        $query = $mysqlObj->prepare( "INSERT INTO users( name, email, password) VALUES(?, ?, ?)");
        
        if($mysqlObj->error){$query = $mysqlObj
            $error=$mysqlObj->error;
            return;
        }
        $query->bind_param( 'sss', $name, $email, $password);
        $query->execute();
        $query->close();
        */

        //SELECT
        //$query = $mysqlObj->prepare( "SELECT * FROM users WHERE id > ? and chattime >= DATE_SUB(NOW(), INTERVAL 60 MINUTE) and room=? and roompassword=?");
        $query = $mysqlObj->prepare( "SELECT user_id FROM users WHERE user_id > ?");
        if($mysqlObj->error){
            $error=$mysqlObj->error;
            return;
        }
        $query->bind_param( 'i', $id);
        $query->execute();
        

        /*
        //Create results Array
        $rows = $query->get_result();
        $array = $rows->fetch_all(MYSQLI_ASSOC); //Array ( [0] => Array ( [user_id] => 4 [name] => ap [email] => apopov-83@mail.ru [password] => 123456 [status] => active [type] => user [date_created] => 2017-09-27 12:31:18 [first_name] => [last_name] => [about_me] => [last_login] => [avatar] => [email_checked] => 0 [email_check_code] => ) [1] => Array ( [user_id] => 5 [name] => cash168 [email] => cash168@mail.ru [password] => 22222222222222 [status] => active [type] => user [date_created] => 2017-09-29 13:43:13 [first_name] => [last_name] => [about_me] => [last_login] => [avatar] => [email_checked] => 0 [email_check_code] => ) )Array ( [user_id] => 4 [name] => ap [email] => apopov-83@mail.ru [password] => 123456 [status] => active [type] => user [date_created] => 2017-09-27 12:31:18 [first_name] => [last_name] => [about_me] => [last_login] => [avatar] => [email_checked] => 0 [email_check_code] => )
        $numrows=sizeof($array);
        */

        /*
        //Create results json
        $jsonData = '{"results":[';
        $query->bind_result( $user_id );
        $line = new stdClass;
        $array = array();
        while ($query->fetch()) {
          $line->user_id = $user_id;
          $array[] = json_encode($line);
        }
        $query->close();
        $jsonData .= implode(",", $array);
        $jsonData .= ']}';
        return $jsonData;
        */

        /*
        //numrows
        $query = $mysqlObj->prepare( "SELECT SQL_CALC_FOUND_ROWS user_id FROM users WHERE user_id > ?");
        $query->bind_param( 'i', $id);
        $query = $mysqlObj->prepare( "SELECT FOUND_ROWS()");
        $query->execute();
        $rows = $query->get_result();
        $numrows=$rows->fetch_all()[0][0];//int 2
        $query->close();
        return $numrows;
        */


    }
}