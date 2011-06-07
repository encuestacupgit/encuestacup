<?php

/* $Id: handler-prefix.php 1085 2008-04-18 12:54:43Z liedekef $ */

/* vim: set tabstop=4 shiftwidth=4 expandtab: */

// Written by James Flemer
// For eGrad2000.com
// <jflemer@alum.rpi.edu>

    if (!defined('ESP-FIRST-INCLUDED')) {
        echo "In order to conduct surveys, please include phpESP.first.php in your php script, not handler-prefix.php!";
        exit;
    }

    if (defined('ESP-HANDLER-PREFIX'))
        return;

    define('ESP-HANDLER-PREFIX', true);

    $GLOBALS['errmsg'] = '';

    if(isset($_REQUEST['results']) || isset($_REQUEST['results'])) {
        $GLOBALS['errmsg'] = mkerror(_('Error processing survey: Security violation.'));
        return;
    }

    if (isset($sid) && !empty($sid)) {
        $sid = intval($sid);
    }
    else if (isset($_REQUEST['sid']) && !empty($_REQUEST['sid'])) {
        $sid = intval($_REQUEST['sid']);
    }

    if(!isset($sid) || empty($sid)) {
        blur('/public/dashboard.php');
        assert('false; // NOTREACHED');
    }

    if(!isset($_css)) {
        $_css = "";
    }

    if (!isset($_title)) {
        $_title = "";
    }

    if (!isset($survey_name)) {
        $survey_name = "";
    }

    if(empty($_REQUEST['userid'])) {
        // find remote user id (takes the first non-empty of the following)
        //  1. a GET variable named 'userid'
        //  2. the REMOTE_USER set by HTTP-Authentication
        //  3. the query string
        //  4. the remote ip address
        if (!empty($_REQUEST['userid'])) {
            $_REQUEST['userid'] = $_REQUEST['userid'];
        } elseif(!empty($_SERVER['REMOTE_USER'])) {
            $_REQUEST['userid'] = $_SERVER['REMOTE_USER'];
        } elseif(!empty($_SERVER['QUERY_STRING'])) {
            $_REQUEST['userid'] = urldecode($_SERVER['QUERY_STRING']);
        } else {
            $_REQUEST['userid'] = $_SERVER['REMOTE_ADDR'];
        }
    }

    if(empty($_REQUEST['referer']))
        $_REQUEST['referer'] = isset($_SERVER['HTTP_REFERER']) ?
            $_SERVER['HTTP_REFERER'] : '';


    if (empty($_REQUEST['rid']))
        $_REQUEST['rid'] = 0;
    else
        $_REQUEST['rid'] = intval($_REQUEST['rid']) ?
                intval($_REQUEST['rid']) : 0;

    if($ESPCONFIG['auth_response']) {
        // check for authorization on the survey
        require_once($ESPCONFIG['include_path']."/lib/espauth".$ESPCONFIG['extension']);
        if ($GLOBALS['ESPCONFIG']['auth_mode'] == 'basic') {
            $espuser = ''; $esppass = '';
            if (isset($_SERVER['PHP_AUTH_USER']))
                $espuser = $_SERVER['PHP_AUTH_USER'];
            if (isset($_SERVER['PHP_AUTH_PW']))
                $esppass = $_SERVER['PHP_AUTH_PW'];
        }
        elseif ($GLOBALS['ESPCONFIG']['auth_mode'] == 'form') {
            if (!isset($_REQUEST['username'])) {
                $_REQUEST['username'] = "";
            }
            if ($_REQUEST['username'] != "") {
                $_SESSION['espuser'] = $_REQUEST['username'];
            }
            if (isset($_SESSION['espuser'])) {
                $espuser = $_SESSION['espuser'];
            }
            else {
                $espuser = "";
            }

            if (!isset($_REQUEST['password'])) {
                $_REQUEST['password'] = "";
            }
            if ($_REQUEST['password'] != "") {
                $_SESSION['esppass'] = $_REQUEST['password'];
            }
            if (isset($_SESSION['esppass'])) {
                $esppass = $_SESSION['esppass'];
            }
            else {
                $esppass = "";
            }

        }

        if(!survey_auth($sid, $espuser, _addslashes($esppass), $esppass, $_css, $_title))
            return;

        if (auth_get_option('resume')) {
            $_SESSION['rid'] = auth_get_rid($sid, $espuser,
                    $_REQUEST['rid']);

            if (!empty($_SESSION['rid']) && (!isset($_SESSION['sec']) ||
		 empty($_SESSION['sec']) || intval($_SESSION['sec']) < 1))
            {
                $_SESSION['sec'] = response_select_max_sec($sid,
                        $_SESSION['rid']);
            }
        }
    }

    if (!isset($_SESSION['sec']) || empty($_SESSION['sec']))
        $_SESSION['sec'] = 1;
    else
        $_SESSION['sec'] = (intval($_SESSION['sec']) > 0) ?
                intval($_SESSION['sec']) : 1;

    define('ESP-AUTH-OK', true);

?>
