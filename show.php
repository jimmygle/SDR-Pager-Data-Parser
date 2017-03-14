<?php require_once __DIR__ . '/config.php';

// Connect to database
$dbConn = new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
if ($dbConn->connect_error) {
	echo "ERROR: Can't connect to database.\n";
	exit;
}

$sql = "SELECT * FROM `{$dbTble}` ORDER BY `timestamp` DESC";

$result = $dbConn->query($sql);

$dbConn->close();
?>

<html>
	<head>
		<title>Pager Data</title>
	<head>
	<body>
		<table border="2" width="100%" cellpadding="4">
			<thead>
				<tr><th>Destination</th><th>Date</th><th>Protocol</th><th>Format</th><th>Baud</th><th>Message</th></tr>
			</thead>
		<?php 
		while ($row = $result->fetch_row()) {
			echo "<tr><td>{$row[0]}</td><td>{$row[1]}</td><td>{$row[2]}</td><td>{$row[3]}</td><td>{$row[4]}</td><td>{$row[5]}</td></tr>";
		}
		?>
		</table>
	</body>
</html>
