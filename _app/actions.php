<?php

	# code...

	require 'functions.php';	
if (isAjax()) {
	foreach ($_POST as $key => $value){
		if ( isset($_POST['usersubmit']) && !empty($_POST[$key]) ) {
			$post = $_POST;

		}else{
			echo 'please complete entire form';
			exit();
		}
	}


// Below are routes for  processing form inputs
	if ( $post['usersubmit'] == 'register' )
	{
		createUser($post['username'],$post['userpass'],$post['firstname'],$post['lastname']);
	}
	elseif ( $post['usersubmit'] == 'update')
	{
		updateUser($post['username'],$post['firstname'],$post['lastname'],$post['content']);
	}
	elseif ( $post['usersubmit'] == 'delete')
	{
		deleteUser( $post['username'] );
	}
	elseif ($post['usersubmit'] == 'login')
	{
		// debug(strip_tags($post['username']));
		loginUser($post['username'],$post['userpass']);
	}
}
?>