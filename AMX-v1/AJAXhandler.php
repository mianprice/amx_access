<?php
include 'requests.php';
if (!isset($_SESSION)) {
	session_start();
}

/////////////////////////////////
//Setup return location strings//
/////////////////////////////////
$vHost = "152.2.129.53";
$vURI = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

/////////////////////////////
//Start database connection//
/////////////////////////////
define("HOST", "152.2.129.53");     // The host BLUEFISH wants to connect to.
define("USER", "mianprice");    // The database username. 
define("PASSWORD", "admin");    // The database password. 
define("DATABASE", "amx_access");    // The database name.
$vmysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

$vTag = mysqli_real_escape_string($vmysqli, $_POST["tag"]);

if ($vTag) {
	switch ($vTag) {
		case "create_user":
			$vUsername  = isset($_POST['username']) ? $_POST['username'] : '';
			$vPassword  = isset($_POST['password']) ? $_POST['password'] : '';
			break;
		case "login":
			$vUsername  = isset($_POST['username']) ? $_POST['username'] : '';
			$vPassword  = isset($_POST['password']) ? $_POST['password'] : '';
			break;
		case "logout":
			break;
		case "update_preset":
			$vUserID  	= isset($_POST['uid']) ? $_POST['uid'] : '';
			break;
		case "finish_preset":
			$vUserID  	= isset($_POST['uid']) ? $_POST['uid'] : '';
			$vName		= isset($_POST['name']) ? $_POST['name'] : '';
			$vPresetStr = isset($_POST['p_arr_str']) ? $_POST['p_arr_str'] : '';
			break;
		case "find_preset":
			$vPresetID  = isset($_POST['p_id']) ? $_POST['p_id'] : '';
			break;
		case "use":
			$vPresetID  = isset($_POST['p_id']) ? $_POST['p_id'] : '';
			break;
}
}

/////////////////////////////////
//Database Processing Functions//
/////////////////////////////////
function addValue(&$iFldList, $iFldName, &$iValue) {
  $iFldList   .= ", ".$iFldName."='";
  if(isset($iValue) && strlen($iValue)>0) {
    $iFldList .= mysqli_real_escape_string($iValue);
  }
  $iFldList .= "'";
}

function login($username, $password, $vmysqli)
{

    $username = mysqli_real_escape_string($vmysqli, $username);
    $password = mysqli_real_escape_string($vmysqli, $password);
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";

	$result = mysqli_query($vmysqli,$query)or die(mysqli_error($vmysqli));
	$num_row = mysqli_num_rows($result);

	if( $num_row == 1 )
	{
		while( $row=mysqli_fetch_array($result) ){
			setcookie("amxCookie", $row['user_id']);
			$_SESSION['user_id'] = $row['user_id'];
		}
	} else {
		return false;
	}

  return true;
}

function update($user_id, $vmysqli)
{

    $user_id = mysqli_real_escape_string($vmysqli, $user_id);
    $query = "SELECT * FROM presets WHERE user_id='$user_id'";

	$result = mysqli_query($vmysqli,$query)or die(mysqli_error($vmysqli));
	$num_row = mysqli_num_rows($result);

	if( $num_row > 0 ) {
		for ( $i=0; $i<$num_row; $i++ ){
			$row = mysqli_fetch_array($result);
			$arr[] = $row['id'];
		}
	} else {
		return false;
	}

  return $arr;
}

function find($preset_id, $vmysqli)	{

    $p_id = mysqli_real_escape_string($vmysqli, $preset_id);
    $query = "SELECT * FROM presets WHERE id='$p_id'";

	$result = mysqli_query($vmysqli,$query)or die(mysqli_error($vmysqli));
	$num_row = mysqli_num_rows($result);
	$arr = array();
	if( $num_row == 1 ) {
		$row=mysqli_fetch_array($result);
		$arr[] = $row['id'];
		$arr[] = $row['name'];
	} else {
		return false;
	}

  return $arr;
}

function usePreset($preset_id, $vmysqli)	{

    $p_id = mysqli_real_escape_string($vmysqli, $preset_id);
    $query = "SELECT * FROM presets WHERE `id`=$p_id ";

	$result = mysqli_query($vmysqli,$query)or die(mysqli_error($vmysqli));
	$num_row = mysqli_num_rows($result);
	if( $num_row == 1 ) {
		$row=mysqli_fetch_array($result);
		$presetstring = $row['p_arr_str'];
	} else {
		return false;
	}

  return $presetstring;
}

///////////////////////////////////////
//Handle all function calls to server//
///////////////////////////////////////

switch ($vTag) {
	case "create_user":
		$sql = "INSERT INTO `users` (`username`, `password`) VALUES ('".$vUsername."', '".$vPassword."')   ";
		$mysqli_stmt = mysqli_prepare($vmysqli, $sql);
		if (!mysqli_execute($mysqli_stmt) ) {
			die('Error:'.mysqli_error($vmysqli).'  on sql:'.$sql);
		}
		$vID = mysqli_insert_id($vmysqli);
		setcookie("amxCookie", $vID);
		mysqli_close($vmysqli);
		echo $vID;
		break;
	case "login":
		login($vUsername, $vPassword, $vmysqli);
		mysqli_close($vmysqli);
		echo $_SESSION['user_id'];
		break;
	case "logout":
		setcookie("amxCookie", "", time() - 1000);
		session_destroy();
		echo "y";
		exit();
		break;
	case "finish_preset":
		$sql = "INSERT INTO `presets` (`user_id`,`name`,`p_arr_str`) VALUES ('$vUserID','$vName','$vPresetStr')";
		$mysqli_stmt = mysqli_prepare($vmysqli, $sql);
		if ( !$mysqli_stmt ) {
			die('mysqli error: '.mysqli_error($vmysqli));
		}
		if ( !mysqli_execute($mysqli_stmt) ) {
			die('stmt error: '.mysqli_stmt_error($mysqli_stmt) );
		}
		mysqli_close($vmysqli);
		break;
	case "find_preset":
		$arr = find($vPresetID, $vmysqli);
		$x = json_encode($arr);
		echo $x;
		break;
	case "update_preset":
		$x = update($vUserID, $vmysqli);
		$y = json_encode($x);
		echo $y;
		break;
	case "use":
		$x = usePreset($vPresetID, $vmysqli);
		$y = json_encode($x);
		echo $y;
		break;
}
exit();