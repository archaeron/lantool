<?php


//  OUTPUT FUNCTIONS
////////////////////////////////////////////////////////////////////////

function get_poll_type_name($type)
{
	global $POLL_TYPES;
	
	if(isset($POLL_TYPES[$type]))
		return $POLL_TYPES[$type];
	else
		return 'Unbekannt';
}


function get_time_left($timestamp)
{
	$delta = $timestamp - time();
	
	if($delta <= 0) return 'Abgelaufen';
	
	$days  = intval($delta / 3600 / 24); $delta -= $days*3600*24;
	$hours = intval($delta / 3600);      $delta -= $hours*3600;
	$min   = intval($delta / 60);        $delta -= $min*60;
	
	return ($days > 0?$days.' Tage ':'').($hours > 0?$hours.' h ':'').$min.' m '.$delta.' s';
}

function count_total_votes($poll)
{
	$total = 0;
	foreach($poll['options'] as $option)
		$total += sizeof($option['voters']);
	
	return $total;
}

function html_encode($text)
{
	return str_replace("\n", '<br />', htmlentities(trim($text), ENT_COMPAT, 'UTF-8'));
}

function error($text)
{
	return '<h1 class="error">'.$text.'</h1>';
}

function has_voted_on($poll, $userid)
{
	if($userid == 0 or !isset($poll['options'])) return false;
	else
	{
		foreach($poll['options'] as $opts)
		{
			if(in_array($userid, $opts['voters'])) return true;
		}
		return false;
	}
}

//  SAVE & LOAD
////////////////////////////////////////////////////////////////////////

function save_array_to_file($arr, $path)
{
	file_put_contents($path, serialize($arr));
}


function load_array_form_file($path)
{
	$arr = unserialize(file_get_contents($path));
	return (empty($arr)?array():$arr);
}
?>
