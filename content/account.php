<?php if(!isset($LANTOOL)) die('No direct script-access allowed.'); ?>


<h2>Account</h2>

<?php

if(!empty($login_error))
	echo '<span class="error">'.$login_error.'</span><br /><br />';

if($logged_in)
{
	echo 'Angemeldet als: <br />';
	echo '<span id="nick"><b>'.$user['name'].'</b> <a href="#" onclick="return change_nick(\''.$user['name'].'\');"><img src="icons/pencil.png" alt="ändern"></a></span><br /><br />';
	echo '<a href="?action=logout" style="float:right"><button>logout</button></a><br clear="both" />';
}
else
{
	echo '<form action="?action=login" method="post">Benutzer:<br/><input type="text" name="user" size=19><br />';
	echo 'Passwort:</br><input type="password" name="password" size=19><br />';
	echo '<br /> <a href="?action=register_user">registrieren</a> <input type="submit" value="login" style="float:right"><br clear="both" /></form>';
}
?>
