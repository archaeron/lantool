<?php if(!isset($LANTOOL)) die(error('No direct script-access allowed.'));

function post($id)
{
	if(isset($_POST[$id]))
		return $_POST[$id];
	else
		return '';
}

function postq($id)
{
	if(isset($_POST[$id]))
		return '"'.$_POST[$id].'"';
	else
		return '""';
}

?>

<h2>Teamgenerator</h2>

<form action="?action=team_generator" method="post">
	Mitspieler: <small>(Ein Name pro Zeile)</small><br />
	<textarea name="names"><?=post('names');?></textarea>
	<br />
	Anzahl Teams: <input type="text" name="nr_teams" value=<?=postq('nr_teams');?> >
	<input style="float:right; width:50%; font-weight:bold" type="submit" value="w&uuml;rfeln">
</form>


<?php

if(isset($_POST['names']))
{
	echo '<hr>';
	
	$names = array();
	$lines = explode("\n", $_POST['names']);
	$nr_teams = abs(intval($_POST['nr_teams']));
	
	foreach($lines as $name)
	{
		$name = trim($name);
		if(!empty($name))
			$names[] = $name;
	}
	
	if(empty($names))
		die(error('Ich brauche Namen.'));
	
	if(empty($nr_teams))
		die(error('Null Teams. Das macht keinen Sinn.'));
	
	shuffle($names);
	
	echo '<table cellspacing=0 rowspacing=0 border=0 width="100%"><tr>';
	
	for($i = 1; $i <= $nr_teams; $i++)
		echo '<td width="'.(100/$nr_teams).'%"><b>Team '.$i.'</b></td>';
	
	echo '</tr>';
	
	while(!empty($names))
	{
		echo '<tr>';
		for($i = 0; $i<$nr_teams and !empty($names); ++$i)
			echo '<td>'.html_encode(array_pop($names)).'</td>';
		echo '</tr>';
	}
	
	echo '</table>';
}
?>
