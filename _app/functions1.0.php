<?php
require $_SERVER['DOCUMENT_ROOT'].'/config.php';

if ( $settings['siteurl'] !== $_SERVER['SERVER_NAME'] ) {

	$posts = $hyperDB->queryContent('posts');
	$pages = $hyperDB->queryContent('pages');
	$postKey = 	($posts['template'] == "categories" || isset($posts['entries'])) ? 'categories': 'post';
	$collection =[

	$postKey => $posts,
	'pages'=> $pages

				  ];

	header('Content-Type: application/json');
	print_r(json_encode($collection,JSON_PRETTY_PRINT));

}else{
	$apiBase = 'http://api.'.$_SERVER['SERVER_NAME'];
	$queryData= jsonDecoder($apiBase.'/'.$requesturl,true);
	$staticQuery= jsonDecoder($apiBase.'/',true);
	$template = getTemplate($queryData);
	$siteSettings = [
	'SITENAME'=>$settings['sitename'],
	'SITEAUTHOR'=>$settings['author'],
	'SITEDESCRIPTION'=>$settings['description'],
	'SITEKEYWORDS'=>$settings['keywords'],
	'SITESOCIAL'=>$settings['social'],
	'SITECREDITS'=>$settings['credits'],
	'URL'=>$requesturl,
	'startsession', session_start(),
	'session', $_SESSION['username'],
	'pagenav'=> pageNavButtons(),
	'_pages'=>$staticQuery['pages']['entries'],
	'_categories'=>$staticQuery['categories']['list'],
	'categories'=>[
		'list'=> isset($queryData['categories']['list']) ? $queryData['categories']['list'] : '',
		'entries'=>isset($queryData['categories']['entries']) ? $queryData['categories']['entries']: '',
		'test'=>isset($queryData['categories']['entries']) ? current($queryData['categories']['entries']): '',
		'name'=>isset($queryData['categories']['name']) ? $queryData['categories']['name']: ''
		],
	'articles'=> dynamicQuery($requesturl, $hyperDB),
	'page'=> $queryData['pages'],
	'article'=> isset($queryData['post']) ? $queryData['post'] : ''
	];





// debug(staticQuery());
	switch ($requesturl) {

		case $requesturl:
			echo $twig->render($template.'.html',$siteSettings);
			break;
		default:
			// echo $twig->render('index.html');			
			break;
	}


}

function dynamicQuery ($url) {
	global $apiBase,$requesturl, $suburl;
	//build new array from api
	$response = jsonDecoder($apiBase.'/'.$url,true);
	//set the data variable to be parsed
	$data = isset($response['categories']['entries'])?$response['categories']['entries']:'Sorry no entries found' ;
	//define a container for page links
	$pageLinks= [];
	//lets set the array count for later comparisons
	$total_pages = count($data);

	// debug($data);
	return $data;
}

function pageNavButtons (){

	global $apiBase,$requesturl, $suburl;
	//build new array from api
	$response = jsonDecoder($apiBase.'/'.$suburl,true);
	//set the data variable to be parsed
	$data = isset($response['categories']['entries'])?$response['categories']['entries']:'' ;
	//define a container for page links
	$pageLinks= [];
	//lets set the array count for later comparisons
	$total_pages = count($data);

	if ($data):
   	foreach ($data as $urlkey => $val) 
   	{
       if ($val['url'] === $requesturl) 
       {
           $next 		= ($urlkey === $total_pages-1)?['url'=>$suburl,'title'=>'Back to Category'] :['url'=>$data[$urlkey+1]['url'],'title'=>$data[$urlkey+1]['title']];
           $prev 		= ($urlkey === 0)?['url'=>$suburl,'title'=>'Back to Category'] :['url'=>$data[$urlkey-1]['url'],'title'=>$data[$urlkey-1]['title']];
           $pageLinks   = ['next'=>$next,'prev'=>$prev];
       }
   	}
   	return $pageLinks;
   endif;

}



function getTemplate ($queryData){
$pages_template = $queryData['pages']['template'];
$posts_template = isset($queryData['categories']['template']) ? $queryData['categories']['template'] : $queryData['post']['template'];
// $entry_template = $queryData['post']['template'];

	if ( isset($pages_template) && file_exists(TEMPLATEPATH.$pages_template.'.html')  ): //pages templates
			return $pages_template;
	elseif ( isset($posts_template) || file_exists(TEMPLATEPATH.$pages_template.'.html')  ): //lists templates
			return $posts_template;
		else:
			return 'index';
	endif;

}// end getTemplate

function jsonDecoder ($file) 
{
				$filecontent = file_get_contents($file);
				$data 		 = json_decode($filecontent, true);
				return $data;
}


function formatContent($markdownstring) {
	$parsedown = new ParsedownExtra();
	$content = $parsedown->text($markdownstring);
	return $content;
}



function sanitizeString ( $data ) {
				return strip_tags(( trim($data)  ));
}

function createUser ( $_username , $_userpass, $_firstname, $_lastname) {
	global $hash_options;
	$user = new createUser();
		$user->username		= $_username;
		$user->password		= password_hash($_userpass, PASSWORD_BCRYPT, $hash_options); 
		$user->bio			= $_username.'\'s bio area"';
		$user->firstname	= $_firstname;
		$user->lastname		= $_lastname;
		$user->created_on	= CURRDATETIME; 
		$user->role['admin']	=  0;
		$user->role['author']  	=  0;
		$user->role['member']  	=  1;//default role
						


	 if ( !file_exists(USERSDIR.$_username.CONFIG_EXT) ) {
	 				$fp = fopen(USERSDIR.$_username.CONFIG_EXT, 'w');
					fwrite($fp, json_encode($user,JSON_PRETTY_PRINT));
					fclose($fp);
					echo 'You have successfully registered!';
					// return header("location: /?login");
				}else
					echo '<h1> This user already exist.</h1>';
}//end registerUser


function updateUser ($_username,$_userbio,$_userpass) {

				global $hash_options;
				if ($_SESSION['ActiveUser']) {

				$file = USERSDIR.$_username.CONFIG_EXT;
				$filecontent = file_get_contents( $file );
				$data = json_decode( $filecontent,true );

				$data['username'] = $_username;
				$data['bio'] = $_userbio;
				$data['last_updated'] = CURRDATETIME;
				$data['password'] = password_hash($_userpass, PASSWORD_BCRYPT, $hash_options);

				$fh = fopen($file, 'w');
				fwrite($fh, json_encode($data, JSON_PRETTY_PRINT) );
				fclose($fh);
				}
}//end updateUser

function deleteUser ($file) {

				$file = USERSDIR.$file.CONFIG_EXT;

				if (file_exists($file) && $_SESSION['ActiveUser'] ) 
				{
					unlink($file);
					session_unset();
					session_destroy();

					echo 'Your account has been permanetly removed';
				}else
					echo 'This account no longer is active';
}

function loginUser ($_username, $_upass){

				if ( file_exists(USERSDIR.$_username.CONFIG_EXT) ) 
				{
					
					$filecontent = file_get_contents(USERSDIR.$_username.CONFIG_EXT);
					$data 		 = json_decode($filecontent, true);
					$user 		 = $data['username'];
					$password 	 = $data['password'];
					$role 	 	 = $data['role'];

					if ( password_verify($_upass, $password) )
					{
						$_SESSION = ['username'=>$user,'role'=>$role];
						echo '<h1>Welcome back '. $_SESSION['role']. '</h1>';
					}else{
						echo '<h1>Sorry, Those Credentials does not work.</h1>';
					}

				} else { 
						echo '<h1>user does not exist. Please register</h1>';
				}

}//end loginUser


function debug ($stuff) {
echo '<pre>'. print_r($stuff, true) . '</pre>';
}
?>