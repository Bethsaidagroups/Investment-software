<?php
/**
 *The database backup script that backs up database file to storage every week.
 */

//include the general required file
require_once 'include/utilities/dumper.php';

$world_dumper = Shuttle_Dumper::create(array(
    'host' => 'localhost',
    'username' => 'root',
    'password' => 'samuelopex',
    'db_name' => 'lasr',
));

// dump the database to gzipped file
$today = date('Y-m-d');
$world_dumper->dump("db-backup/$today.sql.gz");

// dump the database to plain text file
//$world_dumper->dump('world.sql');