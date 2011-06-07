<?php

session_start();
$sid=2;
$rid=$_SESSION['rid'];
require_once("phpESP.first.php");
require_once($ESPCONFIG['include_path']."/funcs".$ESPCONFIG['extension']);
require_once($ESPCONFIG['handler_prefix']);
    if(!defined('ESP-AUTH-OK')) {
        if (!empty($GLOBALS['errmsg']))
            echo($GLOBALS['errmsg']);
        return;
    }

print_r(response_select_human($sid,$rid));

?>

