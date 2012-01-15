<?php

//  ACCESS CONTROL
////////////////////////////////////////////////////////////////////////

$users     = load_array_form_file('data/users.txt');

function username_exists($new_name, $id=0)
{
	global $users;
	
	foreach($users as $i => $u)
	{
		if($u['name'] == $new_name and $id == 0)
			return true;
		
		if($u['name'] == $new_name and $id != 0 and $i != $id)
			return true;
	}
	
	return false;
}

//$users = array(1 => array('name' => 'iliis', 'password' => '098f6bcd4621d373cade4e832627b4f6', 'session_id' => '', 'logged_in' => false, 'is_admin' => true));
//save_array_to_file($users, 'data/users.txt');

$logged_in = false;
$is_admin  = false;
$user      = array();
$login_error = '';
$userid    = -1;
$user_ip   = $_SERVER['REMOTE_ADDR'];

if(session_id() != '')
{
	foreach($users as $uid => $u)
	{
		if($u['session_id'] == session_id())
		{
			$user   = $u;
			$userid = $uid;
			break;
		}
	}
}

if(isset($users[$userid]) and $users[$userid]['logged_in'])
{
	$logged_in = true;
	
	if(isset($user['is_admin']) and $user['is_admin'])
		$is_admin = true;
}


if($action == 'login')
{
	if(isset($_POST['user']) and isset($_POST['password']))
	{
		//$user_ip = $_SERVER["REMOTE_ADDR"];
		foreach($users as $id => $u)
		{
			//if(mb_strtolower($u['name'], 'UTF-8') == mb_strtolower($_POST['user'], 'UTF-8'))
			if($u['name'] == html_encode($_POST['user'])) // usernames are html-encoded when registring
			{
				$user = $u;
				$userid = $id;
				break;
			}
		}
		
		if(empty($user))
			$login_error = 'Benutzer nicht gefunden.';
		else
		{
			if($user['password'] != md5($_POST['password']))
				$login_error = 'Falsches Passwort.';
			else
			{
				$logged_in = true;
				$is_admin = $users[$userid]['is_admin'];
				$users[$userid]['logged_in']  = true;
				$users[$userid]['session_id'] = session_id();
				$users[$userid]['ip'] = $user_ip;
				
				save_array_to_file($users, 'data/users.txt');
			}
		}
	}
}
else if($action == 'logout' and $logged_in)
{
	$users[$userid]['logged_in']  = false;
	$users[$userid]['session_id'] = '';
	//$users[$userid]['ip'] = '';
	
	$logged_in = false;
	$is_admin  = false;
	$user      = array();
	
	session_destroy();
	
	save_array_to_file($users, 'data/users.txt');
}
else if($action == 'set_nick' and $logged_in)
{
	$name = html_encode($_POST['nick']);
	
	if(username_exists($name, $userid))
		$login_error = 'Dieser Benutzername ist bereits vergeben.';
	else
	{
		$user['name']           = $name;
		$users[$userid]['name'] = $name;
		
		save_array_to_file($users, 'data/users.txt');
	}
}
else if($action == 'create_user')
{
	$name = html_encode($_POST['user']);
	$pw   = md5($_POST['password']);
	
	if(username_exists($name))
		{$login_error = 'Diser Benutzername ist bereits vergeben.'; $action = 'register_user';}
	else
	{
		$user = array('name' => $name, 'password' => $pw, 'session_id' => session_id(), 'logged_in' => true, 'is_admin' => false);
		$users[] = $user;
		save_array_to_file($users, 'data/users.txt');
		$logged_in = true;
		
		foreach($users as $i => $u)
		{
			if($u['name'] == $name)
			{
				$userid = $i;
				break;
			}
		}
	}
}

?>
