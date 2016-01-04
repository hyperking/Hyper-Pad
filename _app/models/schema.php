<?php
/**
* Schema for creating json setting for users
*/

class createUser
{
public $username 	= '';
public $password 	= '';
public $firstname 	= '';
public $lastname 	= '';
public $useremail 	= '';
public $bio 		= '';
public $created_on 	= '';
public $last_updated= '';
public $role 		= array(
					"admin"  => '',
					"author" => '',
					"member" => ''
					);
}