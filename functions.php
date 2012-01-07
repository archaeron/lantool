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

function custom_error_handler($errno, $errstr, $errfile, $errline)
{
	if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        return;
    }

	// see http://www.php.net/manual/en/errorfunc.constants.php
	switch ($errno) {
    case E_USER_ERROR: case E_ERROR:
    	echo '<div class="_php_error">';
        echo "<h2>ERROR</h2> $errstr<br />\n";
        echo "  Fatal error on line $errline in file $errfile";
        echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
        echo "Aborting...</div><br />\n";
        exit(1);
        break;

    case E_USER_WARNING: case E_WARNING:
    	echo '<div class="_php_warning">';
        echo "<h2>WARNING</h2> $errstr</div><br />\n";
        break;

    case E_USER_NOTICE: case E_NOTICE:
    	echo '<div class="_php_notice">';
        echo "<h2>NOTICE</h2> $errstr</div><br />\n";
        break;

    default:
    	echo '<div class="_php_error">';
        echo "Unknown error type: [$errno] $errstr</div><br />\n";
        break;
    }

    /* Don't execute PHP internal error handler */
    return true;
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
