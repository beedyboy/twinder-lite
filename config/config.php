<?php

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
define('DEBUG', true);
/*
|--------------------------------------------------------------------------
| DATABASE Configuration Settings
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
define('DB_NAME', 'event'); //database name

define('DB_USER', 'root'); // database user

define('DB_PASSWORD', 'kaydee');
 // database password
define('DB_HOST', '127.0.0.1'); //database host ***use IP address to avoid DNS look up on live server


define('PAGE_LIMIT', 5); //database host ***use IP address to avoid DNS look up on live server

/*
|--------------------------------------------------------------------------
| Other Default Settings
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
define('DEFAULT_CONTROLLER', 'HomeController');
define('DEFAULT_CONTROLLER_METHOD', 'index');
//contoller name is restricted
define('ACCESS_RESTRICTED', 'RestrictedController'); 
//if no layout is set in the controller use this layout

define('DEFAULT_LAYOUT', 'app');

define('SITE_TITLE', 'Event Manager'); //this will be used if no title is set

//
define('base_url', '/workspace/event/');
/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
define('CURRENT_USER_SESSION_NAME', 'kwteyruopisnamjcyr479EYndmOU');
define('REMEMBER_ME_COOKIE_NAME', 'JASDFGTYinfgvr84649vu');
//30 days time is in seconds for 30 days
define('REMEMBER_ME_COOKIE_EXPIRY', 2592000);