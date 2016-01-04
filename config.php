<?php
/*
|--------------------------------------------------------------------------
| SITE SETTINGS
|--------------------------------------------------------------------------
|
| Global definitions used throughout the app.
|
*/


$root_url = '/Users/derry.spann/Dev/wordpress/sites/gullahcelebration/content/';
	define("ABSPATH", $_SERVER['DOCUMENT_ROOT']);
	// Site settings are stored inside json the _config.json file
	$settings 	= jsonDecoder(ABSPATH.'/_config.json');
	define('USERSDIR', ABSPATH.'/users/');
	// define('CONTENTDIR', $root_url);
	define('CONTENTDIR', ABSPATH.'/content/');
	// define('PAGESDIR', $root_url.'pages/');
	define('PAGESDIR', ABSPATH.'/content/pages/');
	// define('ARTICLESDIR', $root_url.'posts/');
	define('ARTICLESDIR', ABSPATH.'/content/posts/');
	define('SITETHEME', $settings['theme']);
	define('LIBDIR', ABSPATH.'/_app/lib/');
	define('CURRDATETIME', date("Y-m-d h:i:sa"));
	define('CONFIG_EXT', '.json');
	define('PAGE_EXT', '.md');
	define('TEMPLATEPATH', ABSPATH.'/_themes/'.SITETHEME.'/');

	// Password hashing arguments.
	$hash_options = array('cost' => '08', );

/*
|--------------------------------------------------------------------------
| REQUIRE APP DEPENDENCIES
|--------------------------------------------------------------------------
|
| Below are files for Classes, Schemas and other libraries used to power the app
|
*/

	require $_SERVER['DOCUMENT_ROOT'].'/_app/controllers/classes.php';
	require $_SERVER['DOCUMENT_ROOT'].'/_app/models/schema.php';
	require $_SERVER['DOCUMENT_ROOT'].'/_app/lib/parsedownextra.php';
	require_once LIBDIR.'Twig/Autoloader.php';
	Twig_Autoloader::register();
	$loader 	= new Twig_Loader_Filesystem(TEMPLATEPATH);
	$twig   	= new Twig_Environment($loader,[
				// 'cache' => 'cache/twig/',
				// 'auto_reload' => true,
				'auto_escape' => true,
				]);
	$twig->addFunction('hyperQuery',
		new Twig_Function_Function('hyperQuery')
	);
	$twig->addFunction('redirectTo', new Twig_Function_Function('redirectTo'));
	$twig->addFunction('destroySession', new Twig_Function_Function('destroySession'));
// $twig->addExtension(new Twig_Extensions_Extension_Text());

	$requesturl 	= isset($_GET['url']) ? '/'.$_GET['url'] : '';
	$requesturl		= filter_var($requesturl, FILTER_SANITIZE_URL);
	$suburl 		= pathinfo($requesturl,PATHINFO_DIRNAME);
	$getSession 	= isset($_SESSION['username']) ? true : false;
	$ignore 		= [".","..","cgi-bin",".DS_Store","_"];

	$hyperDB 		= new Query;
	// var_dump($requesturl);

