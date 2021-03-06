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
	if(!isset($poll['options'])) return false;
	else
	{
		foreach($poll['options'] as $opts)
		{
			if(in_array($userid, $opts['voters'])) return true;
		}
		return false;
	}
}

//  CUSTOM ERROR HANDLERS (to fit in design)
////////////////////////////////////////////////////////////////////////

// catches fatal errors etc.:
function custom_shutdown_handler()
{
	$e = error_get_last();
    if(empty($e))
		return; // no error
    else if(is_array($e))
		custom_error_handler($e['type'], $e['message'], $e['file'], $e['line']);
	else
	{
		echo '<div class="_php_error">';
        echo "<h2>Unknown error</h2>";
		print_r($e);
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
        echo "<h2><img src=\"icons/exclamation.png\" alt=\"error\"> ERROR</h2> $errstr<br /><br />\n";
        echo "  <small><b>Fatal error</b> on line $errline in file $errfile";
        echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
        echo "<b>Aborting...</b></small></div><br />\n";
        exit(1);
        break;

    case E_USER_WARNING: case E_WARNING:
    	echo '<div class="_php_warning">';
        echo "<h2><img src=\"icons/error.png\" alt=\"warning\"> WARNING</h2> $errstr <br><br>\n
        <small>on line $errline in file $errfile.</small></div><br />\n";
        break;

    case E_USER_NOTICE: case E_NOTICE:
    	echo '<div class="_php_notice">';
        echo "<h2><img src=\"icons/information.png\" alt=\"notice\"> NOTICE</h2> $errstr <br><br>\n
        <small>on line $errline in file $errfile.</small></div><br />\n";
        break;

    default:
    	echo '<div class="_php_error">';
        echo "<h2>Unknown error type: [$errno]</h2> $errstr <br><br>\n
		<small>on line $errline in file $errfile.</small></div><br />\n";
        break;
    }

    /* Don't execute PHP internal error handler */
    return true;
}

//  SAVE & LOAD
////////////////////////////////////////////////////////////////////////

function save_array_to_file($arr, $path)
{
	file_put_contents($path, utf8_encode(serialize($arr)));
}


function load_array_form_file($path)
{
	$arr = unserialize(utf8_decode(file_get_contents($path)));
	return (empty($arr)?array():$arr);
}
?>
