<?php
 
error_reporting(E_ALL);
		ini_set('display_errors', 1);
define('DS', DIRECTORY_SEPARATOR);
 
define('ROOT', dirname(__FILE__));
 
//load configuration and helper functions
 

require_once(ROOT . DS . 'config' . DS . 'config.php');

require_once(ROOT . DS . 'app' . DS . 'lib' . DS . 'helpers' . DS . 'functions.php');

 
/*
| -------------------------------------------------------------------
| AUTO-LOADER
| -------------------------------------------------------------------
| This file specifies which systems should be loaded by default.
|
| In order to keep the framework as light-weight as possible only the
| absolute minimal resources are loaded by default. For example,
| the database is not connected to automatically since no assumption
| is made regarding whether you intend to use it.  This file lets
| you globally define which systems you would like loaded with every
| request.
|
| -------------------------------------------------------------------
| Instructions
| -------------------------------------------------------------------
|
| These are the things you can load automatically:
|
| 1. Packages
| 2. Libraries
| 3. Drivers
| 4. Helper files
| 5. Custom config files
| 6. Language files
| 7. Models
|
*/

function autoload($className)
{
	if(file_exists(ROOT . DS . 'core'. DS . $className . '.php'))
	{
		require_once(ROOT . DS . 'core'. DS . $className . '.php');
	}
	elseif(file_exists(ROOT . DS . 'app'. DS . 'controllers' . DS .$className . '.php'))
	{
		require_once(ROOT . DS . 'app'. DS . 'controllers' . DS .$className . '.php');
	}

	elseif(file_exists(ROOT . DS . 'app'. DS . 'models' . DS .$className . '.php'))
	{
		require_once(ROOT . DS . 'app'. DS . 'models' . DS .$className . '.php');
	}

}

/*
| -------------------------------------------------------------------
|  Auto-load Packages
| -------------------------------------------------------------------
| Prototype:
|
|  $autoload['packages'] = array(APPPATH.'third_party', '/usr/local/shared');
|
*/

spl_autoload_register('autoload');


session_start();


//$url = isset($_SERVER['PATH_INFO']) ? explode('/', rtrim($_SERVER['PATH_INFO'], '/')) : [];

//echo "Welcome Home";
//echo $_SERVER['PATH_INFO'];
//dnd($url);

//require_once(ROOT . DS . 'core' . DS . 'bootstrap.php');
//Route the request
//$db = DB::getInstance();

//echo __DIR__;

if(!Session::exists(CURRENT_USER_SESSION_NAME) && Cookie::exists(REMEMBER_ME_COOKIE_NAME))
{
	User::loginUserFromCookie();
}
Router::init();