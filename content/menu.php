<?php if(!isset($LANTOOL)) die('No direct script-access allowed.'); ?>

<a href="index.php">
<img src="logo.png" style="padding: 0; margin: 0"></a>



<ul>
	<li><a href="?action=none">alle Abstimmungen</a></li>
	
	<?php
	
	if($logged_in)  echo '<li><a href="?action=create_poll">Abstimmung erstellen</a></li>';
	                echo '<li><a href="?action=team_generator">Team-Generator</a></li>';
	
	
	if($is_admin)   echo '<li><a href="?action=manage_users">Benutzerverwaltung</a></li>'; ?>
</ul>
