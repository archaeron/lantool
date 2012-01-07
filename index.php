<?php session_start(); $_SESSION['asdf'] = 'foo';
error_reporting(E_ALL);

$LANTOOL='asdf';

if(session_id() == "") die("No session-ID.");?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>LAN-TOOL</title>
		<script type="text/javascript" src="jquery.js"></script>
		<script type="text/javascript" src="jquery.countdown.min.js"></script>
		<script type="text/javascript" src="jquery.countdown-de.js"></script>
		<script type="text/javascript" src="highcharts.js"></script>
		<script type="text/javascript" src="code.js"></script>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>

<?php


//  DEFINITIONS
////////////////////////////////////////////////////////////////////////

$POLL_TYPES = array('yesno'  => 'Ja / Nein',
					'choice' => 'Auswahl',
					'team' => 'Team-Auswahl');


require_once('functions.php');

//  PARAMETERS
////////////////////////////////////////////////////////////////////////

$action = (isset($_GET['action']) ? $_GET['action'] : 'none');
$id     = (isset($_GET['id'])     ? intval($_GET['id'])  : 0);


//  READ DATA
////////////////////////////////////////////////////////////////////////

$current_polls = load_array_form_file('data/polls.txt');

/*$current_polls = array( 1 => array('title' => 'test 1',
									'type' => 'yesno',
									'expiration_date' => '1303337000'),
						2 => array('title' => 'another test',
									'type' => 'choice',
									'expiration_date' => '1303333999'),
						3 => array('title' => 'third',
									'type' => 'choice',
									'expiration_date' => '1304430139'),
						4 => array('title' => 'last one',
									'type' => 'choice',
									'expiration_date' => '1303331999'));*/
require_once('user.php');

if($action == 'delete' and $logged_in and isset($current_polls[$id]))
{
	unset($current_polls[$id]);
	save_array_to_file($current_polls, 'data/polls.txt');
}

?>
	
	<body>
		<div id="menu">
			<div id="links">
				<?php include('content/menu.php'); ?>
			</div>
			
			<div id="account">
				<?php include('content/account.php'); ?>
			</div>
		</div>
		<div id="content">
		 
		 <?php
		 
		 switch($action)
		 {
			 case 'create_poll':
			 case 'receive_new_poll':
				include('content/create_poll.php');
				break;
		 	
		 	case 'receive_comment':
		 	case 'receive_vote':
		 	case 'view':
				include('content/view_poll.php');
				break;
		 	
			case 'register_user':
				include('content/register_user.php'); break;
			
			case 'manage_users':
			case 'set_password':
			case 'delete_user':
				if($is_admin){ include('content/usermanagement.php'); break;}
				
			case 'create_user':
		 	case 'login':
		 	case 'logout':
		 	case 'set_nick':
		 	case 'none':
		 	case 'delete':
		 		include('content/main.php');
		 		break;
		 	
		 	case 'team_generator':
		 		include('content/team_generator.php');
		 		break;
		 	
		 	default:
		 		echo '<h1 class="error">404 - Das gibts nicht!</h1>';
		 		echo 'Tja, das wurde noch nicht implementiert, oder du hast nen Bug erwischt. Oder du spielst gerade mit den Parametern herum. Oder dies ist nicht die Realität und du bist vor deinem PC eingepennt.';
		}
		?>
		</div>
		
		
		
	</body>
</html>


<?php

// SAVE EVERYTHING
////////////////////////////////////////////////////////////////////////

flush();
//save_array_to_file($current_polls, 'data/polls.txt');

?>
