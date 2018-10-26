<?php
/**
* 
*/
class Auth extends Model
{
	private $_isLoggedIn, $_sessionName, $_cookieName; 
	public $sid = null;
	public static $currentLoggedInUser = null;
	
	public function __construct($user = '')
	{
		# code...
 
		//$table=  Pluralizer::plural($user);
	$table =  'users'; 
		parent::__construct($table);
		$this->_sessionName = CURRENT_USER_SESSION_NAME;
		$this->_cookieName = REMEMBER_ME_COOKIE_NAME;
	$this->_softDelete = true;

 if($user != '')
	{	 

		if(is_int($user))
		{
			$u = $this->_db->findFirst($table, ['conditions'=>'id= ?', 'bind'=>[$user]]);

		}
		else 
		{

			$u = $this->_db->findFirst($table, ['conditions'=>'acc_email= ?', 'bind'=>[$user]]);
		}

		if($u)
		{
			foreach ($u as $key => $value) 
			{
				# code...
			$this->$key  = $value;
			}
		}
	}

 

	}

	public function findByEmail($email)
	{ 
 
		 return $this->findFirst(['conditions'=> 'acc_email  = ?', 'bind'=> [$email]]);
	 
	}
	
public static function check()
{
// return	(isset($_SESSION)) ? true : false;
	if( empty($_SESSION))
	{
		return false;
	}

	else{
		return true;
	}

}
	public static function auth($field)
	{ 
  		  $u = new User('user');
	   $uid = Session::get(CURRENT_USER_SESSION_NAME); 

	   if($u->check()):

	 return $u->findById($uid)->$field;
	else:
		return false;
	endif;
	}
	
public static function currentLoggedInUser()
{
	if(!isset(self::$currentLoggedInUSer) && Session::exists(CURRENT_USER_SESSION_NAME))
	{ 
		 $u = new Auth((int)Session::get(CURRENT_USER_SESSION_NAME)); 
 		 self::$currentLoggedInUser = $u;
	 
	}
	 // dnd(self::$currentLoggedInUser);
	return self::$currentLoggedInUser;
}
	public function login($rememberme = false)
	{
		Session::set($this->_sessionName, $this->id);

		if($rememberme)
		{

			$hash = md5(uniqid() + rand(0, 100));

			$user_agent = Session::uagent_no_version();
	
			Cookie::set($this->_cookieName, $hash, REMEMBER_ME_COOKIE_EXPIRY);

			$fields = ['session'=>$hash, 'user_agent'=>$user_agent, 'user_id'=>$this->id];
 
			$this->_db->query("DELETE FROM usersessions WHERE user_id = ? AND user_agent = ?", [$this->id, $user_agent]);
		$qry =	$this->_db->insert('usersessions', $fields);
		$this->sid = $qry->_lastInsertedID;
		}
	}


public static function loginUserFromCookie()
{
	$userSession = UserSession::getFromCookie();
	 
 
	if($userSession->user_id != '')
	{
		$user = new self((int)$userSession->user_id);
	}
		if($user)
		{

	$user->login(); 
		}
	return $user;
}
 

public function logout()
{
	$userSession = UserSession::getFromCookie();
	 //if($userSession) $userSession->_db->query("DELETE FROM usersessions WHERE user_id = ?", [$_SESSION[CURRENT_USER_SESSION_NAME]]); 
	 if($userSession) $userSession->_db->query("DELETE FROM usersessions WHERE user_id = ?", [$_SESSION[CURRENT_USER_SESSION_NAME]]); 
	 //if($userSession) $userSession->delete(currentUser()->id);
	Session::delete(CURRENT_USER_SESSION_NAME);
	if(Cookie::exists(REMEMBER_ME_COOKIE_NAME))
	{
		Cookie::delete(REMEMBER_ME_COOKIE_NAME);
	}
	unset($_SESSION);
	return true;
}

 
public function registerNewUser($params)
{

	$this->assign($params);
	$this->acc_password = password_hash($this->acc_password, PASSWORD_DEFAULT);
	$this->save();
}

//check if 
public function acls()
{
 dnd(222);
if(empty($this->rid)):

	return [];
else:
$Role = new Role('roles');
 $permission = $Role->findById($this->rid)->permissions;
 
	return  json_decode($permission, true);
endif;
	// if(empty($this->acl)) return [];
	// return json_decode($this->rid, true);
}
}