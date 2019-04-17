<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$database = 'dbs12754';
$user = 'dbu11513';
$pass = 'Opeyemi.2217';
$host = 'db5000017142.hosting-data.io';
$dir = 'db-backup' . '/' .date('Y-m-d'). '.sql';
//echo "<h3>Backing up database to `<code>{$dir}</code>`</h3>";
exec("mysqldump --user={$user} --password={$pass} --host={$host} {$database} --result-file={$dir} 2>&1", $output);
//var_dump($output);