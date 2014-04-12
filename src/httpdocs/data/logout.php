<?php
    require_once realpath('main.php');

    session_destroy();
    echo json_encode(true);
?>