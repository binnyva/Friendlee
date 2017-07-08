<?php
class User extends DBTable {
	var $id = 0;
	var $sql;
	
	//Configs
	var $cookie_expire = 0;
	var $cookie_prefix_for_site = '';
	
	//The constructor
	//Get the details of the current user on every page load.
	function __construct() {
		global $sql, $config;
		$this->cookie_prefix_for_site = unformat($config['site_title']) . '_';
		$this->cookie_expire = time() + (60*60*24*30);//Will expire in 30 days
		parent::__construct("User");

		if(isset($_SESSION['user_id']) and isset($_SESSION['user_name'])) {
			$this->setCurrentUser($_SESSION['user_id'], $_SESSION['user_name']);
			return; //User logged in already.
		}
		
		//This is a User who have enabled the 'Remember me' Option - so there is a cookie in the users system
		if(isset($_COOKIE[$this->cookie_prefix_for_site.'username']) and $_COOKIE[$this->cookie_prefix_for_site.'username'] and isset($_COOKIE[$this->cookie_prefix_for_site.'password_hash'])) {

			$user_details = $sql->getAssoc("SELECT id,name FROM User WHERE username='" . $_COOKIE[$this->cookie_prefix_for_site . 'username'] . "' "
											. " AND MD5(CONCAT(password,'#c*2u!'))='" . $_COOKIE[$this->cookie_prefix_for_site . 'password_hash'] . "'");
		
			if($user_details) { //If it is valid, store it in session
				$this->setCurrentUser($user_details['id'], $_COOKIE[$this->cookie_prefix_for_site . 'username'], $user_details['name']);
				
			} else { //The user details in the cookie is invalid - force a logout to clear cookie
				$this->logout();
			}
		} else {
			unset($_SESSION['user_id']);
			unset($_SESSION['user_name']);
		}
	}

	/**
	 * Login the user with the username and password given as the argument
	 */
	function login($username,$password,$remember=0) {
		global $sql;
		$this->id = -1;
		
		$user_details = $sql->getAssoc("SELECT id,name FROM User WHERE (username='$username' OR email='$username') AND password='$password'");
		if(!$user_details) { //Query did not run correctly
			showMessage("Invalid Username/Password", "user/login.php", "error");

		} else {
			//Store the necessy stuff in the sesson
			$this->setCurrentUser($user_details['id'],$username,$user_details['name']);

			//Keep some token in the cookie so as to login the user automatically the next time
			if($remember) {
				setcookie($this->cookie_prefix_for_site . 'username', $username, $this->cookie_expire, '/');
				setcookie($this->cookie_prefix_for_site . 'password_hash', md5($password.'#c*2u!'), $this->cookie_expire,'/');
			}
		}
		
		return $this->id;
	}

	/**
	 * Logout the user. If the user have set the 'remember me' option, that will be reset as well.
	 */
	function logout() {
		$_SESSION['user_id'] = '';
		unset($_SESSION['user_id']);
		
		//Remove the remember me cookies as well.
		if(isset($_COOKIE[$this->cookie_prefix_for_site . 'username']) or isset($_COOKIE[$this->cookie_prefix_for_site . 'password_hash'])) {
			setcookie($this->cookie_prefix_for_site . 'username','',time()-1,'/');
			setcookie($this->cookie_prefix_for_site . 'password_hash','',time()-1,'/');
			unset($_COOKIE[$this->cookie_prefix_for_site . 'username']);
			unset($_COOKIE[$this->cookie_prefix_for_site . 'password_hash']);
		}
	}
	
	/**
	 * Sets the current user.
	 */
	function setCurrentUser($user_id, $username = '', $real_name = '') {
		if($user_id > 0) {
			$_SESSION['user_id'] = $this->id = $user_id;
			$_SESSION['user_name'] = ($real_name) ? $real_name : $username ;
		}
	}
	
	/**
	 * Registers the user with the details provided in the arguments. If the specified username is already taken, an error will be shown.
	 */
	function register($username, $password, $name, $email) {
		global $sql, $QUERY;
		
		//Check if the username is already taken.
		$email_check = '';
		if($email) $email_check = "OR email='$email'";
		$result	= $sql->getSql("SELECT id FROM User WHERE username='$username' $email_check");
		$username_taken = $sql->fetchNumRows($result);
	
		if ($username_taken == 0) {
			$errors = check(array(
				array('name'=>'username','is'=>'empty'),
				array('name'=>'password','is'=>'empty'),
				array('name'=>'password','is'=>'not','value'=>$_REQUEST['confirm_password'],'error'=>"Password and Confirm password fields don't match"),
				array('name'=>'name','is'=>'empty'),
				array('name'=>'email','is'=>'empty'),
			),2);
			
			if($errors) $QUERY['error'] = $errors;
			else {
				$this->newRow();
				$this->field['username'] = $username;
				$this->field['password'] = $password;
				$this->field['name'] = $name;
				$this->field['email'] = $email;
				$this->field['added_on'] = 'NOW()';
				$id = $this->save();
				$this->setCurrentUser($id, $username, $name);
				return $id;
			}
		} else {
			$QUERY['error'] = "User with username '$username' or email '$email' already exists.";
		}

		return false;
	}

	function oAuthCheckUser($user_data = array()){
		global $sql;

		// For some reason, I'm not geting oauth_provider/oauth_uid from google. So using email.
    	//$user_details = $sql->getAssoc("SELECT id,name FROM User WHERE oauth_provider = '".$user_data['oauth_provider']."' AND oauth_uid = '".$user_data['oauth_uid']."'");
    	$user_details = $sql->getAssoc("SELECT id,name,email,status FROM User WHERE email='$user_data[email]'");

    	if(!$user_details) { // User not found in Database - insert.
	    	$user_details = $this->oAuthRegister($user_data);
	    }

		if($user_details) {
			//Store the necessy stuff in the sesson
			$this->setCurrentUser($user_details['id'],$username,$user_details['name']);
		}
        
        //Return user data
        return $user_details;
    }

    function oAuthRegister($user_data, $only_insert = false) {
    	global $sql;
		list($username, $del) = explode("@", $user_data['email']);
		// Check if another user exists with the same username. If so, 
		$user_details = $sql->getAssoc("SELECT id,name FROM User WHERE username = '$username'");
		if($user_details) {
			$username = $username . '-' . substr(md5(uniqid(mt_rand(), true)), 0, 3); // Make sure the username is unique
		}

		$user_details =  array(
								'username'		=> $username,
								'email'			=> $user_data['email'],
								// 'oauth_provider'=> $user_data['oauth_provider'],
								// 'oauth_uid'		=> $user_data['oauth_uid'],
								'name'			=> $user_data['given_name'] . ' ' . $user_data['family_name'],
								'gender'		=> ($user_data['gender'] == 'male') ? 'm' : 'f',
								'image'			=> $user_data['picture']);
		$user_id = $sql->insert("User", $user_details);
		$user_details['id'] = $user_id;

		return $user_details;
	}

  	/**
	 * Login the user with the given email - usually done after a third party oAuth authentication
	 */
	function oAuthIdVerify($id_token) {
		global $sql;
		$this->id = -1;
		include_once '../includes/vendor/google/gpConfig.php';

		$payload = $gClient->verifyIdToken($id_token);
		if ($payload) {
			$details = $payload->getAttributes();
			$user_data = $details['payload'];
			$user_details = $sql->getAssoc("SELECT id,name,username FROM User WHERE email = '$user_data[email]'");
			if(!$user_details) $user_details = $this->oAuthRegister($user_data, true);
			if($user_details) {
				//Store the necessy stuff in the sesson
				$this->setCurrentUser($user_details['id'],$user_details['username'],$user_details['name']);
			}
			
			// showMessage("Hello, $user_data[name]", 'index.php', 'success');
		} else {
			// showMessage("Invalid Token", 'user/login.php', 'error');
		}

		return $this->id;
	}

	
	/**
	 * Edits the current user's profile.
	 */
	function update($id, $password, $name, $email, $url) {
		global $sql, $QUERY;
		
		$errors = check(array(
			array('name'=>'username','is'=>'empty'),
			array('name'=>'password','is'=>'not','value'=>$_REQUEST['confirm_password'],'error'=>"Password and Confirm password fields don't match"),
			array('name'=>'name','is'=>'empty'),
			array('name'=>'email','is'=>'empty'),
		),2);
			
		if($errors) $QUERY['error'] = $errors;
		else {
			$this->newRow($id);
			if($password) $this->field['password'] = $password;
			$this->field['name'] = $name;
			$this->field['email'] = $email;
	
			return $this->save();
		}
	}

	/// Returns the details of the current user as a associative array.
	function getDetails($id = 0) {
		$id = ($id) ? $id : $this->id; 
		return $this->find($id);
	}
	
	/**
	 * Emails the password of the user whose email OR username is provided as the argument. 
	 * The argument must be given as a associative array.  If no such user is found, an error 
	 * will be shown.
	 * Example: $user->passwordRetrival(array('username'=>'binnyva'));
	 * 	OR
	 * 	$user->passwordRetrival(array('email'=>'binnyva@gmail.com'));
	 */
	 function passwordRetrival($data) {
	 	global $sql, $config;
	 	
	 	if(isset($data['username'])) {
		 	extract($sql->getAssoc("SELECT name,username,password,email FROM User WHERE username='$data[username]'"));
	 	} elseif(isset($data['email'])) {
	 		extract($sql->getAssoc("SELECT name,username,password,email FROM User WHERE email='$data[email]'"));
	 	} else {
	 		showMessage("Please provide either the username or the password.", "forgot_password.php", "error");
	 	}
	 	
	 	if(!$username) showMessage("No user found with the given email", "forgot_password.php", "error");
	 	if(!$email) showMessage("The specified account don't have an email address", "forgot_password.php", "error");
	 	
	 	$display_name = ($name) ? $name : $username;
	 	$email_message = "Hi $display_name,

Someone(hopefully you) requested that we send your password to the email you have chosen. 
So here is the login details for your account at $config[site_title]...

Username : $username
Password : $password

Thanks,
$config[site_title] Team";

		mail($email, "Password for $config[site_title] Account", $email_message);
		return true;
	 }
}
