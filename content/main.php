<?php if(!isset($LANTOOL)) die('No direct script-access allowed.'); ?>

<h2>Abstimmungen</h2>
		 		
<table class="striped" border=0 cellspacing=0 cellpadding=0 vspace=0 width="100%">
<thead><tr> <td></td> <td>Titel</td> <td>Typ</td> <td>Stimmen</td> <td>Zeit &uuml;brig</td> <td></td>  </tr></thead><tbody>

<?php
foreach(array_reverse($current_polls,true) as $id => $poll)
{
	echo '<tr class="tr_link" onclick="window.location.href=\'?action=view&id='.$id.'\'">';
	
	if($logged_in and has_voted_on($poll,$userid))
		echo '<td width="16px"><img src="icons/accept.png" alt="bereits gewählt"></td>';
	else
		echo '<td width="0"></td>';
	
	echo '<td><b>'.$poll['title'].'</b></td><td>'.get_poll_type_name($poll['type']).'</td>';
	echo '<td>'.count_total_votes($poll).'</td>';
	
	echo '<td class="countdown" timestamp="'.$poll['expiration_date'].'"></td>';
	
	if($logged_in and ($poll['creator'] == $userid or $is_admin))
		echo '<td width="16px" style="padding-right:5px"><a href="?action=delete&id='.$id.'" class="ask_if_sure" question="Diese Abstimmung löschen?"><img alt="löschen" src="icons/cancel.png"></a></td>';
	
	echo '</tr>';
}
?>
	
</tbody></table>
