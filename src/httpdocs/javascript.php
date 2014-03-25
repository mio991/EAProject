<?php
    $base_scripts = array();

    //libarys
    $base_scripts[]	= 'lib/jquery.min.js';
    $base_scripts[] = 'lib/underscore.min.js';
    $base_scripts[]	= 'lib/backbone.min.js';
    $base_scripts[] = 'src/functions.js';

    // modules
    $base_scripts[] = 'src/modules/auth_session.js';
    $base_scripts[] = 'src/modules/current_user_panel.js';
    $base_scripts[] = 'src/modules/list.js';

    // pages
    $base_scripts[] = 'src/pages/home.js';
    $base_scripts[] = 'src/pages/settings.js';
    $base_scripts[] = 'src/pages/imprint.js';
    $base_scripts[] = 'src/pages/contact.js';
    $base_scripts[] = 'src/pages/login.js';

    $base_scripts[]	= 'src/application.js';

    ini_set('output_handler', '');
    ini_set("zlib.output_compression", "On");

    ob_start();

    $max_age = 60 * 60 * 24 * 7;

    // Set application type
    header ("Content-Type: text/javascript");
    header ("cache-control: must-revalidate; max-age: " . $max_age);
    header ("Expires: " . gmdate ("D, d M Y H:i:s", time() + $max_age) . " GMT");

    // set variables
    $str_output = array();

    // get content of real javascript files
    foreach ($base_scripts as $script) {
        $str_output[]  = file_get_contents($script);
    }

    $buffer = implode(' ', $str_output);

    // remove multi/single line comments
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
    $buffer = preg_replace('#^\s*//.+$#m', "", $buffer);

    echo $buffer;

    ob_end_flush();
?>