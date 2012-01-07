<?php if(!isset($LANTOOL)) die('No direct script-access allowed.'); 


if($action == 'receive_comment')
{
	if($logged_in)
	{
		if(!isset($current_polls[$id]))
			die('<h1 class="error">Abstimmung '.$id.' existiert nicht.</h1>');
		
		$comment = html_encode($_POST['comment']);
		
		$current_polls[$id]['comments'][] = array('user' => $userid, 'text' => $comment, 'time' => time());
		save_array_to_file($current_polls, 'data/polls.txt');
	}
	else
		die(error('Du musst eingeloggt sein, um einen Kommentar posten zu k&ouml;nnen.'));
}


if(!isset($current_polls[$id]))
	die(error('Abstimmung '.$id.' existiert nicht.'));

$poll = $current_polls[$id];
$abgelaufen = $poll['expiration_date'] <= time();


echo '<a href="?action=view&id='.$id.'"><h2>'.$poll['title'].'</h2></a>';
if(isset($poll['comment']) and !empty($poll['comment'])) echo $poll['comment'].'<br /><br />';

if($poll['type'] == 'yesno')
{
	if(!$abgelaufen and $logged_in and $action=='receive_vote' and !has_voted_on($poll, $userid))
	{
		if($_POST['vote'] == 'yes')
			$poll['options']['yes']['voters'][] = $userid;
		else if($_POST['vote'] == 'no')
			$poll['options']['no']['voters'][]  = $userid;
		
		$current_polls[$id] = $poll;
		save_array_to_file($current_polls, 'data/polls.txt');
	}
	
	$yes = sizeof($poll['options']['yes']['voters']);
	$no  = sizeof($poll['options']['no' ]['voters']);

	if($abgelaufen or !$logged_in or has_voted_on($poll, $userid))
		echo '<div class="yes_no_pie" yes="'.$yes.'" no="'.$no.'">ja: '.$yes.', nein: '.$no.'</div>';
	else if($logged_in)
		echo '<form action="?action=receive_vote&id='.$id.'" method="post"><button class="yes_button" value="yes" name="vote">Ja</button> <button class="no_button" value="no" name="vote">Nein</button></form>';
	
	if($logged_in and has_voted_on($poll, $userid))
	{
		echo 'Deine Wahl: <b>';
		if(in_array($userid, $poll['options']['yes']['voters']))
			echo 'Ja'; else echo 'Nein';
			
		echo '</b>';
	}
} elseif($poll['type'] == 'choice' or $poll['type'] == 'team')
{
	if(!$abgelaufen and $logged_in and $action=='receive_vote' and !has_voted_on($poll, $userid))
	{
		$choice = 0;
		foreach($poll['options'] as $key => $option)
			if($key == $_POST['vote'])
			{
				$choice = $key;
				break;
			}
		
		if(!empty($choice))
		{
			$poll['options'][$choice]['voters'][] = $userid;
			$current_polls[$id] = $poll;
			save_array_to_file($current_polls, 'data/polls.txt');
		} else
			echo error('Irgendwas musst du schon ausw&auml;hlen.');
	}
	
	if($abgelaufen or !$logged_in or has_voted_on($poll, $userid))
	{
		echo '<div class="pie_chart">';
		foreach($poll['options'] as $option)
		{
			echo '<option votes="'.sizeof($option['voters']).'">'.$option['text'].'</option>';
		}
		echo '</div>';
	} else
	{
		echo '<form action="?action=receive_vote&id='.$id.'" method="post"><table cellspacing=0 border=0>';
		foreach($poll['options'] as $key => $option)
		{
			echo '<tr class="tr_hover"><td>&raquo; </td><td><b>'.$option['text'].'</b></td> <td><button name="vote" value="'.$key.'">w&auml;hlen</button></td></tr>';
			
			if($poll['type'] == 'team')
			{
				foreach($option['voters'] as $member)
					echo '<tr><td></td><td>'.$users[$member]['name'].'</td><td></td></tr>';
			}
		}
		echo '</table></form>';
	}
}


if(isset($_GET['view_details']) or $poll['type'] == 'team')
{
	echo '<table width="100%"><tr>';
	foreach($poll['options'] as $opts)
		echo '<td><b>'.$opts['text'].'</b></td>';
		
	echo '</tr><tr>';
	
	foreach($poll['options'] as $opts)
	{
		echo '<td style="vertical-align: top">';
		
		foreach($opts['voters'] as $voter)
			echo $users[$voter]['name'].'<br />';
		
		echo '</td>';
	}
	
	echo '</tr></table>';
}


{
	if(!$poll['type'] == 'team') echo '<span style="float:right"><a href="?action=view&id='.$id.'&view_details">&raquo; Details</a></span><br clear="both" />';
	
	echo '<br />Zeit &uuml;brig: <span class="countdown" timestamp="'.$poll['expiration_date'].'"></span> <span style="float:right">Total: '.count_total_votes($poll).' Stimmen</span><br clear="both" />';

	if(isset($poll['comments']))
		foreach($poll['comments'] as $comment)
			echo '<hr class="light">'.$comment['text'].'<br /><br /><small><b>'.$users[$comment['user']]['name'].'</b> - '.date('G:i - l d. F', $comment['time']).'</small>';

	if($logged_in)
		echo '<hr class="light"><div class="ausklappen"><div class="old">Senf dazu geben</div><div class="new"><form action="?action=receive_comment&id='.$id.'" method="post"><textarea style="width: 99%;margin: 5px;height: 100pt;" name="comment"></textarea><br /><input style="float:right; width:40%;" value="speichern" type="submit"></form></div></div>';
}

?>
