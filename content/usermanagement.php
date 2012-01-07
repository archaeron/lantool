<?php if(!isset($LANTOOL)) die(error('No direct script-access allowed.')); 
if(!$is_admin) die(error('Nicht gen&uuml;gend Rechte.'));


if($action == 'delete_user')
{
	unset($users[$id]);
	save_array_to_file($users, 'data/users.txt');
}
elseif($action == 'set_password')
{
	$users[$id]['password'] = md5($_POST['password']);
	save_array_to_file($users, 'data/users.txt');
}

echo '<h2>Benutzerverwaltung</h2>';

echo '<table><tr style="font-weight:bold"><td>Name</td> <td>Passwort setzen</td> <td></td></tr>';
foreach($users as $uid => $user)
{
	if(empty($user['sessionid']))
		echo '<tr>';
	else
		echo '<tr class="logged_in">';
		
	echo '<td>'.$user['name'].'</td>';
	echo '<td><form action="?action=set_password&id='.$uid.'" method="post"><input type="password" value="" name="password"><input type="submit" value="set"></form></td>';
	echo '<td width="16px" style="padding-right:5px"><a href="?action=delete_user&id='.$uid.'" class="ask_if_sure" question="Benutzer \''.$user['name'].'\' löschen?"><img alt="löschen" src="icons/cancel.png"></a></td>';
	echo '</tr>';
}
