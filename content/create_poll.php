<?php if(!isset($LANTOOL)) die('No direct script-access allowed.'); 

if($action == 'receive_new_poll')
{
	if($logged_in)
	{
		if(!isset($POLL_TYPES[$_POST['type']]))
			echo error('Unbekannter Typ');
		else if(($time = strtotime($_POST['time'])) === false)
			echo error('Konnte Zeitangabe nicht parsen.');
		else
		{
			$new_poll = array('type' => $_POST['type'],
			                  'title' => html_encode($_POST['title']),
			                  'expiration_date' => $time,
			                  'options' => array(),
			                  'creator' => $userid,
			                  'comment' => html_encode($_POST['comment']));
			
			if($new_poll['type'] == 'yesno')
			{
				$new_poll['options']['yes'] = array('voters' => array(), 'text' => 'Ja');
				$new_poll['options']['no']  = array('voters' => array(), 'text' => 'Nein');
			} elseif($new_poll['type'] == 'choice')
			{
				$lines = explode("\n", $_POST['choices']);
				
				foreach($lines as $line)
				{
					$line = html_encode($line);
					if(!empty($line))
						$new_poll['options'][] = array('voters' => array(), 'text' => $line);
				}
			} elseif($new_poll['type'] == 'team')
			{
				$new_poll['allow_new_teams'] = (isset($_POST['allow_new_teams']) and $_POST['allow_new_teams'] == 'yes');
				//$new_poll['max_per_team'] = abs(intval($_POST['max_per_team']));
				
				$nr_teams = abs(intval($_POST['nr_teams']));
				
				if($nr_teams == 0) die(error('Gar keine Teams zur Auswahl ist etwas sinnlos, oder?'));
				
				
				
				for($i = 1; $i <= $nr_teams; ++$i)
					$new_poll['options'][$i] = array('voters' => array(), 'text' => 'Team '.$i);
			}
			
			
			$current_polls[] = $new_poll;
			
			save_array_to_file($current_polls, 'data/polls.txt');
			
			echo '<h2>gespeichert</h2><br /><a href="?action=view&id='.array_pop(array_keys($current_polls)).'">&raquo; anschauen</a>';
		}
	}
	else
		echo error('Du musst eingeloggt sein, um eine neue Umfrage erstellen zu können.');
 
}
else if($action == 'create_poll')
{ ?>
	<h2>neue Abstimmung erstellen</h2>
	<!-- - Typ<br/>- editierbar (im nachhinein noch umentscheidbar)<br />- Zeitlimit<br />- Teilnehmerlimit, Teamlimit, Teams vorgegeben/userdefined<br /> -->
	
	<form action="?action=receive_new_poll" method="post" accept-charset="utf-8">
	<table size="100%" border=0>
		<tr>
			<td>Titel:</td> <td>Typ:</td>
		</tr>
		
		<tr>
			<td><input type="text" name="title" size=60 /></td>
			<td>
			<select name="type" id="new_poll_select">
				<?php
				foreach($POLL_TYPES as $id => $name)
					echo '<option value="'.$id.'">'.$name.'</option>';
				?>
			</select></td>
		</tr>
		
		<!--<tr>
			<td><input type="checkbox" name="final" checked="checked" /> Auswahl ist endg&uuml;ltig</td><td></td>
		</tr>-->
		<tr>
			<td>L&auml;ft ab in: <input type="text" name="time" value="10 minutes"> <small>("5 hours", "tomorrow", ...)</small></td> <td></td>
		</tr>
		
		<tr>
			<td colspan=2>Kommentar:<br /><textarea name="comment" style="height:50pt"></textarea></td>
		</tr>
	 </table>
	
	<hr class="light">
	<div id="new_poll_form">
		JavaScript muss aktiviert sein.
	</div>
	
	<hr class="light">
	<button type="submit" style="width:300px; float:right"><img src="icons/disk.png" style="vertical-align:middle"> speichern</button>
	
	</form>
	
	
	<?php
}; ?>
