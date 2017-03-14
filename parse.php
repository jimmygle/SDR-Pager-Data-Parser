<?php require_once __DIR__ . '/config.php';

$contents = file_get_contents($fileToParse);

// Prep data for parsing
echo "\nOpening file and setting it up for parsing...\n";
$contents = str_replace("\n", " ", $contents); // Replace all line breaks with spaces
$globalPattern = '/(\d+)\s+([0-9]{2}:[0-9]{2}:[0-9]{2} [0-9]{2}-[0-9]{2}-[0-9]{2})/'; // Match dest ID & timestamp
$contents = preg_replace($globalPattern, "\n$0", $contents); // Prefix all dest ID & timestamp with line break
$messages = explode("\n", $contents); // Split each message to its own array element
array_shift($messages); // First message is always blank
$messageCount = count($messages);

// Parse each line/message
echo "\nParsing {$messageCount} messages...\n";
$successCount = 0;
$failedCount = 0;
$parsed = [];
foreach ($messages as $msg) {
	$matches = [];
	//                               (dest)  (Hour    :Minute  :Second   Month   -Day     -Year    )   (protocol   )   (format    )   (bau)   (message)
	$parseSuccess = preg_match("/^\s?(\d+)\s+([0-9]{2}:[0-9]{2}:[0-9]{2} [0-9]{2}-[0-9]{2}-[0-9]{2})\s+([A-Z\-0-9]+)\s+([A-Z\/\-]+)\s+(\d+)\s+([\s\S]*)/i", $msg, $matches);	
	
	if ($parseSuccess == false) {
		// Log unparsed
		$ts = date('Y-m-d H:i:s');
		file_put_contents($logFile, "{$ts}--------\n{$msg}\n--------END_LOG_ENTRY\n", FILE_APPEND | LOCK_EX);
		$failedCount++;
	} else {
		$dateTime = DateTime::createFromFormat('H:i:s m-d-y', trim($matches[2])); // convert datetime to mysql compatible

		$parsed[] = [
			'dest' => trim($matches[1]),
			'time' => $dateTime->format('Y-m-d H:i:s'),
			'prot' => trim($matches[3]),
			'form' => trim($matches[4]),
			'baud' => trim($matches[5]),
			'mesg' => addslashes(trim($matches[6]))
		];
		$successCount++;
	}
}
echo "Successfully parsed {$successCount} messages.\n";
echo "Failed parsing {$failedCount} messages.\n";

// Save to database
echo "\nSaving parsed messages to database...\n";
$dbConn = new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
if ($dbConn->connect_error) {
	echo "ERROR: Can't connect to database.\n";
	exit;
}
$rowsInserted = 0;
foreach ($parsed as $msg) {
	$sql = "INSERT INTO {$dbTble} (destination, timestamp, protocol, format, baud, message) 
		VALUES ('{$msg['dest']}', '{$msg['time']}', '{$msg['prot']}', '{$msg['form']}', '{$msg['baud']}', '{$msg['mesg']}')";

	if ($dbConn->query($sql) !== true) {
		echo "ERROR: Can't insert row with SQL:\n  {$sql}\n  {$dbConn->error}\n";
	} else {
		$rowsInserted++;
	}
}
$dbConn->close();

echo "Successfully inserted {$rowsInserted} messages into database.\n";
echo "\nAll done. Exiting...\n";