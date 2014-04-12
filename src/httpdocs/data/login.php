<?php
/**
 * @author daniel treptow
 * @version 0.1
 * @filename login.php
 */

require_once realpath('main.php');
$params = json_decode(file_get_contents('php://input'));

try {
    if (empty($params)) {
        //header("HTTP/1.0 401 Unauthorized");
        throw new Exception('invalid data from client '.var_export($params,true));
    }

    if ($params->auth == true){
        // if (empty($_SESSION) || !array_key_exists('sessionId', $_SESSION)) {
        //     //header("HTTP/1.0 401 Unauthorized");
        //     throw new Exception('invalid Session Id '.var_export($params,true));
        // }

        if (session_id() != $params->sessionId) {
            throw new Exception('login failed');
        }

        if (!array_key_exists('auth', $_SESSION)) {
            throw new Exception('session error: authentication params not available');
        }

        echo json_encode($_SESSION['auth']);
        exit;
    }

    if (   !array_key_exists('email', $params)
        || !array_key_exists('password', $params)) {
        //header("HTTP/1.0 401 Unauthorized");
        throw new Exception('invalid login data from client '.var_export($params,true));
    }

    if (true != login($params->email, $params->password)) {
        //header("HTTP/1.0 401 Unauthorized");
        throw new Exception('login failed');
    }
} catch(Exception $e) {
    header("HTTP/1.0 401 Unauthorized");
    echo json_encode($e->getMessage());
}
?>