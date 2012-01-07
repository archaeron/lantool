<?php if(!isset($LANTOOL)) die('No direct script-access allowed.'); ?>

<h2>Neuer Benutzeraccount anlegen</h2>

<form action="?action=create_user" method="post">
	
	Benutzername:<br />
	<input type="input" name="user" /><br />
	<br />
	
	Passwort:<br />
	<input type="password" name="password" /><br />
	<br />
	
	<input type="submit" value="registrieren">
</form>
