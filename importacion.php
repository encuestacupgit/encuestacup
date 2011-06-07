<?php
$sourceServer = '186.125.158.194';
$sourceUser = 'sa';
$sourcePass = 'sistemacup';
// Connect to MSSQL
$linkSource = mssql_connect($server, $sourceUser, $sourcePass);

if (!$link || !mssql_select_db('CUP', $link)) {
    die('Unable to connect or select database!');
}


$query  = mssql_query($sql,$link);
if (!mssql_num_rows($query)) {
    echo 'No records found';
}
else
{
    while ($row = mssql_fetch_assoc($query)) {
     var_dump($row);  exit; 
    }
}

?>