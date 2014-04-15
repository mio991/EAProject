<?php
/**
* @author daniel treptow
* @version 0.1
* @filename backbone.php
*
* this script catch backbone requests from javascript
* and dispatch it to their php classes.
* sending the result as response to client
*
* catch: POST (create), PUT (update), GET, DELETE
**/

// require the main classes and functions
require_once realpath('main.php');
require_once realpath('backbone/bb_base.class.php');
require_once realpath('backbone/bb_events.class.php');
require_once realpath('backbone/bb_services.class.php');
require_once realpath('backbone/bb_departments.class.php');
require_once realpath('backbone/bb_user.class.php');

ini_set("zlib.output_compression", "On");
ini_set('display_errors', '0');  // don't show any errors...
error_reporting(E_ALL); // ...but do log them
ob_start();

    try {
    // checking request method
    $request_method = $_SERVER['REQUEST_METHOD'];
    if (!isset($request_method)) {
        throw new Exception('no request method given');
    }

    // check GET
    if (empty($_GET)) {
        throw new Exception('no GET given');
    }
    if (!isset($_GET['class'])) {
        throw new Exception('no class given');
    }
    if ('' == $_GET['class']) {
        throw new Exception('given class is empty');
    }

    // check remotely called class
    $class_name = '' . $_GET['class'];
    if (!class_exists($class_name, true) &&
        !is_subclass_of($class_name, 'bb_base'))
    {
        throw new Exception('given class (' . $class_name . ') not exists');
    }

    // wrap data from input stream
    $params = json_decode(file_get_contents('php://input'));
    $params->id = (isset($_GET['id'])) ? $_GET['id'] : null;

    switch ($request_method) {
        case 'POST': // create
            if (!in_array('create', get_class_methods($class_name))) {
                throw new Exception('no create method given in class: '.$class_name);
            }

            // call and return
            echo json_encode(
                call_user_func_array(
                    array($class_name, 'create'),
                    array($params)));

            break;

        case 'PUT': // update
            if (!isset($params->id)) {
                //throw new Exception('no id given');
            }

            if (!in_array('update', get_class_methods($class_name))) {
                throw new Exception('no update method given in class: '.$class_name);
            }

            // call and return
            echo json_encode(
                call_user_func_array(
                    array($class_name, 'update'),
                    array($params)));

            header("HTTP/1.0 200 OK");
            break;

        case 'GET': // get
            if (!isset($params->id)) {
                // throw new Exception('no id given');
            }

            if (!in_array('getData', get_class_methods($class_name))) {
                throw new Exception('no getData method given in class: '.$class_name);
            }

            // call and return
            echo json_encode(
                call_user_func_array(
                    array($class_name, 'getData'),
                    array($params->id)));

            break;

        case 'DELETE': // delete
            if (!isset($params->id)) {
                // throw new Exception('no id given');
            }

            if (!in_array('delete', get_class_methods($class_name))) {
                throw new Exception('no delete method given in class: '.$class_name);
            }

            // call and return
            echo json_encode(
                call_user_func_array(
                    array($class_name, 'delete'),
                    array($params->id)));

            header("HTTP/1.0 200 OK");
            break;

        default :
            throw new Exception('given request method ' . $_SERVER['REQUEST_METHOD'] . ' not supported');
    }

    exit();
} catch(Exception $e) {
    header("HTTP/1.0 500 Internal Server Error");
    echo json_encode('Internal server error occured.');
    echo json_encode($e->getMessage());
    exit();
}

ob_end_flush();
?>