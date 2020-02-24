<?php
	require_once("inc/config.php");

	if (! isset($_GET["profile_id"])) {
		$_SESSION["error"] = ERR_NO_PROFILE_ID;
		header("Location: index.php");		
		exit;

	} else if ( exists_in_db($pdo, "profile_id", "Profile", $_GET["profile_id"])) {
		$profile_id = $_GET["profile_id"];

		$sql = "SELECT first_name, last_name, email, headline, summary
				FROM profile
				WHERE profile_id=?"; 

		$stmt = $pdo->prepare($sql);
		$stmt->bindValue(1, $profile_id);
		$stmt->execute();
		$profile = $stmt->fetch(PDO::FETCH_ASSOC);

	} else {
		header("Location: index.php");
		$_SESSION["error"] = ERR_NO_PROFILE;
		exit;
	}

?>

<!DOCTYPE html>
<html lang='en'>
<head>
	<?php include("inc/header.php");?>
</head>
<body>
<div class="container" id="main-content">

	<h1>Profile for <?= $profile["first_name"] ?></h1>
	<?php
		foreach ($profile as $key => $value) {
			$title = str_replace("_", " ", $key);
			$title = ucwords($title);

			print "<p>$title: $value</<p>";
		}
	?>
	<p><a href="index.php">Done</a></p>

</div>

	<?php include("inc/footer.php");?>
</body>

</html>
