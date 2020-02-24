<?php
	require_once("inc/config.php");

	$logged_in = isset($_SESSION["user_id"]);


// Helper functions
function get_all_profiles () {
	global $pdo;

	$sql = "SELECT Profile.user_id, profile_id, CONCAT(first_name, ' ', last_name) as full_name, headline 
			FROM Profile JOIN users ON Profile.user_id = users.user_id 
			WHERE 1";

	return $pdo->query($sql);

}

function profile_table($profiles) {
	// take a PDO object and return a string that generates a table in html
	// gets all rows and fields
	$table = "<table border=1>\n<tbody>";
	$table .= "<thead><tr><th>Name</th><th>Headline</th><th>Action</th></thead>";
	
	while ( $row = $profiles->fetch() ) {
		$table .= "\n<tr>";

		$user_id = $row["user_id"];
		$profile_id = $row["profile_id"];
		$full_name = $row["full_name"];
		$headline = $row["headline"];

		$table .= "\n\t<td><a href='view.php?profile_id=$profile_id'>$full_name</a></td>";
		$table .= "\n\t<td>$headline</td>";
		$table .= "\n\t<td><a href='edit.php?profile_id=$profile_id'>Edit</a>&nbsp;" . 
				          "<a href='delete.php?profile_id=$profile_id'>Delete</a></td>";

		$table .= "\n</tr>";
	}

	$table .= "\n</tbody></table>";

	return $table;
}

?>

<!DOCTYPE html>
<html lang='en'>
<head>
	<?php include("inc/header.php");?>
</head>
<body>
<div class="container" id="main-content">

	<h1>Welcome to the Profiles Database</h1>

	<?php 
		include("inc/flash.php");

		if ( $logged_in ) {
			echo "<p><a href='logout.php'>Log Out</a></p>";
		} else {
			echo "<p><a href='login.php'>Please log in</a></p>";
		}

		$profiles = get_all_profiles();

		echo profile_table($profiles);

		if ($logged_in) echo "<p>Please <a href='add.php'>Add New Entry</a></p>";
	?>

</div>

	<?php include("inc/footer.php");?>
</body>

</html>
