<?php 
$sourceServer = '186.125.158.194';
$sourceUser = 'sa';
$sourcePass = 'sistemacup';
// Connect to MSSQL
$conn = mssql_connect($sourceServer, $sourceUser, $sourcePass);

if (!$conn || !mssql_select_db('CUP', $conn)) {
	die('Unable to connect or select database!');
}
?>