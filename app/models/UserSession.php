<?php
/**
* 
*/
class UserSession extends Model
{
	
	public function __construct()
	{
		# code...
		$table =  'usersessions'; 
		parent::__construct($table);
	}



public static function getFromCookie()
{
	if(Cookie::exists(REMEMBER_ME_COOKIE_NAME))
	{
		$userSession  = new self();

	$userSession = $userSession->findFirst([

		'conditions' => 'user_agent = ? AND session = ?',
		'bind' => [Session::uagent_no_version(), Cookie::exists(REMEMBER_ME_COOKIE_NAME)]
		]);

	}
	if(!$userSession) return false;
	return $userSession;
}











}