<?php
	require_once("inc/config.php");

	if ( isset($_POST['login'])) { // login value from form

		// Check to make sure the fields are filled in
		if ( $_POST['email'] == "" or $_POST['pass'] == "" ) {

			$_SESSION["error"] = ERR_EMPTY_FIELDS;
			header("Location: login.php");
			exit;
		
		} else if (! strrpos($_POST["email"], "@") ){
			$_SESSION["error"] = ERR_EMAIL;
			header("Location: login.php");
			exit;

		} 

		if ( check_password() ) {
			$row = get_user();
			$_SESSION['user_id'] = $row['user_id'];
			$_SESSION['email'] = htmlentities($row['email']);
			$_SESSION['name'] = htmlentities($row['name']);
			$_SESSION['success'] = "Welcome, ".$_SESSION["name"].".";
			header("Location: index.php");
			exit;
			
		} else {
			$_SESSION["error"] = "Invalid password.";
			header("Location: login.php");
			exit;
		}

	}

/* PHP Helper functions */

function get_user () {
	global $pdo;

	$stmt = $pdo->prepare("SELECT user_id, name, email 
						   FROM users 
						   WHERE email = :em");

	$stmt->execute(array(':em' => $_POST['email']));

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	return $row;

}

function check_password() {
	
	// Note that the salt here is fixed as a variable (set in config.php). 
	// This function assumes that isset has been checked for password and
	// a valid password structure has been entered.
	global $salt, $pdo;
	
	$check = hash('md5', $salt.$_POST['pass']);
	error_log("Checking: $check");

	$stmt = $pdo->prepare("SELECT COUNT(email) as found
						   FROM users 
						   WHERE email = :em AND password = :pw");

	$stmt->execute(array( 
		':em' => $_POST['email'], 
		':pw' => $check));

	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$password_ok = $row["found"];

	return $password_ok;

}

function check_pwd_2() {
    global $salt, $pdo;
    
	$check = hash('md5', $salt.$_POST['pass']);
	
	$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
	$stmt->execute([$_POST['email']]);
	$user = $stmt->fetch();

	if ($user && ( $check === $user['pass'])) {
	    return true;
	} else {
	    return false;
	}
}

?>

<!DOCTYPE html>
<html lang='en'>
<head>
	<script type="text/javascript" src="inc/jsfunc.js"></script>
	<?php include("inc/header.php");?>
</head>
<body>

	<div class="container" id="main-content">
		<h1>Please Login</h1>
		<!-- flash error -->
		<?php include("inc/flash.php"); ?>
		
		<form method="POST">
			<label for="txt_email">User Name</label>
			<input type="text" name="email" id="txt_email"><br/>
			
			<label for="pwd_pass">Password</label>
			<input type="password" name="pass" id="pwd_pass"><br/>
			
			<input type="submit" value="Log In" name="login" 
				   onclick="return doValidate();">
			<a href="index.php">Cancel</a>
		</form>

	</div>

	<?php include("inc/footer.php");?>
</body>

</html>