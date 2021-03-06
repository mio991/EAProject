<?php

function makeCustomerNo($customerId=null) {
    $randomhex = strtoupper(dechex($customerId + 100000));
    $year = date("Y",mktime());
    return 'EAHOLIDAY-'.$year.$randomhex;
}

/**
 * Authorization function
 */
function authorize($role = 3) {
    //return true; // disabled this mechanism
    if(!empty($_SESSION['auth'])) {
        if($_SESSION['auth']['role'] == $role ||
            $_SESSION['auth']['role'] == 2) {
            return true;
        } else {
            header("HTTP/1.0 403 Forbidden");
            return false;
            //throw new Exception('403 Forbidden: Role '.$_SESSION['user']['role'].' not adequate. required role: '.$role);
        }
    } else {
        header("HTTP/1.0 401 Unauthorized");
        return false;
        //throw new Exception('401 Unauthorized: Not logged in');
    }
}

/**
 * Quick and dirty login function
 */
function login($email, $pass) {
    if(!empty($email) && !empty($pass)) {
        $db = MySql :: getInstance();

        // get user data
        $user_select_query = 'SELECT * FROM `user` WHERE `username` = "%1$s" AND `password` = "%2$s";';
        $db_user_res = $db->queryf($user_select_query, $email, hash('SHA512', $pass));
        $user = $db->fetch($db_user_res);
        $affected = $db->getAffected();

        // no user found
        if ($affected !== 1) {
            $user_select_query = 'SELECT * FROM `user` WHERE `email` = "%1$s" AND `password` = "%2$s";';
            $db_user_res = $db->queryf($user_select_query, $email, hash('SHA512', $pass));
            $user = $db->fetch($db_user_res);
            $affected = $db->getAffected();

            if ($affected !== 1) {
                header("HTTP/1.0 401 Unauthorized");
                return false;
            }
        }

        if ($affected === 1) {

            // get customer data
            // $customer_select_query = 'SELECT * FROM `customer` WHERE `id` = %1$u;';
            // $db_customer_res = $db->queryf($customer_select_query, $user['customer_id']);
            // $customer = $db->fetch($db_customer_res);

            $_SESSION['auth'] = array(
                'sessionId' => session_id(),
                'role' => $user['role'],
                'username' => $user['username'],
                'user_id' => $user['id'],
                'no' => $user['no'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'company_name' => $user['company_name'],
                'email' => $user['email'],
                'telephone' => $user['telephone'],
                'no' => $user['no']
            );

            echo json_encode($_SESSION['auth']);
        }
    } else {
        header("HTTP/1.0 401 No Login Data");
        return false;
        //throw new Exception('401 Unauthorized: No Login Data given');
    }

    return true;
}
?>