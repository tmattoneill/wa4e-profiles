<?php
	function exists_in_db($pdo, $field, $table, $val) {
		// returns true if a given value exists in a database.
		// takes a PDO connection, a string of a field name and a string of a table
		// and the value to check.
		// returns true if found else false.

		$sql = "SELECT $field from $table where $field = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->bindValue(1, $val);
		$stmt->execute();

		if ($stmt->rowCount() ) {
			return true;
		}

		return false;
	}
?>
