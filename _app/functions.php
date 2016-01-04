<?php

require $_SERVER['DOCUMENT_ROOT'].'/config.php';

$requestlimit = isset($_GET['q']) ? '?q='.$_GET['q'] :'';
if ( $settings['siteurl'] !== $_SERVER['SERVER_NAME'] ) {
  $posts = $hyperDB->queryContent('posts');
  $pages = $hyperDB->queryContent('pages');
  $postKey =  ($posts['template'] == "categories" || isset($posts['entries'])) ? 'categories': 'post';
  $collection =[$postKey => $posts,'pages'=> $pages];

  header('Content-Type: application/json');
  print_r(json_encode($collection,JSON_PRETTY_PRINT));

}else{
  $time = new loadTime();
  session_start();
  $apiBase = 'http://api.hyper.dev';
  // $apiBase = 'http://api.'.$_SERVER['SERVER_NAME'];
  $queryData= jsonDecoder($apiBase.'/'.$requesturl.$requestlimit,true);
  $staticQuery= jsonDecoder($apiBase.'/',true);
  $template = getTemplate($queryData);
  $userinfo = isset($_SESSION['name'])?jsonDecoder(USERSDIR.$_SESSION['name'].'/'.$_SESSION['name'].CONFIG_EXT):'can find user';
    $twig->addGlobal("session", $_SESSION);
    $twig->addGlobal("in_session", isset($_SESSION['activity']) ? 'yes':'no');
  $siteSettings = [
  'SITENAME'=>$settings['sitename'],
  'SITEAUTHOR'=>$settings['author'],
  'SITEDESCRIPTION'=>$settings['description'],
  'SITEKEYWORDS'=>$settings['keywords'],
  'SITESOCIAL'=>$settings['social'],
  'SITECREDITS'=>$settings['credits'],
  'PAY_PAYPAL_ACCOUNT'=>$settings['paypal_account'],
  'PAY_CURRENCY'=>$settings['paypal_currency'],
  'PAY_SUCCESSURL'=>'hyper.dev/success',
  'PAY_CANCELURL'=>'hyper.dev/cancel',
  'URL'=>$requesturl,
  'is_home' => ($requesturl == '') ? true : false,
  'pagenav'=> pageNavButtons(),
  '_pages'=>(isset($staticQuery['pages']['list']))?$staticQuery['pages']['list']:$staticQuery['pages'],
  '_categories'=>$staticQuery['articles']['categories'],
  'categories'=>[
    // list queries api for all sub categories within specific category
    'list'=> isset($queryData['articles']['categories']) ? $queryData['articles']['categories'] : '',
    // entries response will return all articles within a specifc category or sub category
    'entries'=>isset($queryData['articles']['list']) ? $queryData['articles']['list']: '',
    // name response will return the name of the category
    'name'=>isset($queryData['articles']['name']) ? $queryData['articles']['name']: ''
    ],
  'page'=> isset($queryData['pages']) ? $queryData['pages']: false,
  'article'=> isset($queryData['articles']) ? $queryData['articles'] : '',
  'user'=> $userinfo,
  ];


// debug($_GET);

  switch ($requesturl) {

        case $requesturl:
        if( !isAjax())//check if the request is via ajax. used for form submissions
            echo $twig->render($template.'.html',$siteSettings);
        else
          echo $twig->render('index.html');
            break;
    default:
      echo $twig->render('index.html');     
      break;
  }

}
function isAjax(){
    return isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
}

function hyperQuery ($url,$limiter='') 
{
    // The purpose for this function is to serve data based on page template configurations
    // This is also an extended twig function
	global $apiBase;
	$limit = is_int($limiter)&& isset($limiter) ? '?q='.$limiter:'';
	//build new array from api
	$response = jsonDecoder($apiBase.'/'.$url.$limit,true);
	//set the data variable to be parsed
	$data = isset($response['categories']['entries'])?$response['categories']['entries']:'Sorry no entries found' ;
	//define a container for page links
	$pageLinks= [];
	//lets set the array count for later comparisons
	$total_pages = count($data);

	// debug($data);
	return $data;
}// end hyperQuery





function pageNavButtons ()
{
	// This function builds previous and next buttons for articles pages only.
	global $apiBase,$requesturl, $suburl;
	//build new array from api
	$response = jsonDecoder($apiBase.'/'.$suburl,true);
	//set the data variable to be parsed
	$data = isset($response['articles']['list'])?$response['articles']['list']:'' ;
	//define a container for page links
	$pageLinks= [];
	//lets set store the length of our data to use within pagination
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

}// end pageNavButtons






function getTemplate ($queryData)
{
	global $requesturl;
	$pages_template = isset($queryData['pages']['template']) ? $queryData['pages']['template']: false ;
	$posts_template = isset($queryData['articles']['template']) ? $queryData['articles']['template'] : 'articles';


	if ( isset($pages_template) && file_exists(TEMPLATEPATH.$pages_template.'.html')  ): //pages templates
			return $pages_template;
	elseif ( isset($posts_template) || file_exists(TEMPLATEPATH.$pages_template.'.html')  ): //lists templates
			return $posts_template;
	else:
			return 'base';
	endif;

}// end getTemplate



function jsonDecoder ($file) 
{
    global $requesturl;
    // debug($requesturl);
    if ($requesturl !== '/_app/action.php') {
                $filecontent = file_get_contents($file);
                $data        = json_decode($filecontent, true);
                return $data;
    }

}


function formatContent($markdownstring) {
	$parsedown = new ParsedownExtra();
	$content = $parsedown->text($markdownstring);
	return $content;
}



function sanitizeString ( $data ) 
{
	return strip_tags(( trim($data)  ));
}



function createUser ( $_username , $_userpass, $_firstname, $_lastname) 
{
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
		
		$userFolder = USERSDIR.$_username;				
		if(!is_dir($userFolder)) mkdir($userFolder,0777,true);

	 if ( !file_exists($userFolder.'/'.$_username.CONFIG_EXT) ) {
	 				$fp = fopen($userFolder.'/'.$_username.CONFIG_EXT, 'w');
					fwrite($fp, json_encode($user,JSON_PRETTY_PRINT));
					fclose($fp);
					echo 'You have successfully registered!';
					// return header("location: /?login");
				}else
					echo '<h1> This user already exist.</h1>';
}//end registerUser


function updateUser ($_username,$_firstname,$_lastname,$_content) {

				global $hash_options;
				$userFolder = USERSDIR.$_username.'/';				
				if (isset($_SESSION['name']) && $_SESSION['name'] == $_username) {

				$file = $userFolder.$_username.CONFIG_EXT;
				$filecontent = file_get_contents( $file );
				$data = json_decode( $filecontent,true );

				$data['username'] = $_username;
				$data['firstname'] = $_firstname;
				$data['lastname'] = $_lastname;
				$data['bio'] = $_content;
				$data['last_updated'] = CURRDATETIME;
				// $data['password'] = password_hash($_userpass, PASSWORD_BCRYPT, $hash_options);

				$fh = fopen($file, 'w');
				fwrite($fh, json_encode($data, JSON_PRETTY_PRINT) );
				fclose($fh);
					echo 'profile update';
				}else{
					echo 'unable to update';
				}
}//end updateUser

function deleteUser ($_username) {
				$userFolder = USERSDIR.$_username.'/';				
				$file = $userFolder.$_username.CONFIG_EXT;

				if (file_exists($file) && $_SESSION['name'] ) 
				{
					unlink($file);
					session_unset();
					session_destroy();

					echo 'Your account has been permanetly removed';
				}else
					echo 'This account no longer is active';
}

function loginUser ($_username, $_upass){
				$userFolder = USERSDIR.$_username.'/';				
				if ( file_exists($userFolder.$_username.CONFIG_EXT) ) 
				{
					
					$filecontent = file_get_contents($userFolder.$_username.CONFIG_EXT);
					$data 		 = json_decode($filecontent, true);
					$user 		 = $data['username'];
					$password 	 = $data['password'];
					$role 	 	 = $data['role'];
					$member 	 = $data['role']['member'];

					if ( password_verify($_upass, $password) )
					{
						$_SESSION = [
						'activity'=>'yes',
                        'name'=>$user,
                        'role'=>$role];

						print_r('<h1>Welcome back '. $_SESSION['name']. '</h1><h3><a href="/profile">view your profile</a></h3>') ;
				
					}else{
						echo '<h1>Sorry, Those Credentials does not work.</h1>';
					}

				} else { 
						echo '<h1>user does not exist. Please <a href="/register">register</a></h1>';
				}

}//end loginUser

function destroySession () {
	    session_unset();
        session_destroy();
}
function redirectTo ($url) {
	header("Location: ".$url);
}

function debug ($stuff) {
echo '<pre style="background:white; padding:20px; font-weight:bold;">'. print_r($stuff, true) . '</pre>';
}

?>