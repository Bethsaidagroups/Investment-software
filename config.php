<?php
/**
 * This file connects to the database using PDO Object
 * The base configuration for cbt
 *
 * The script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "cbt-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Database table prefix
 *
 * @link 
 *
 * @package Laser
 */

/**
 * The default timezone.
 */
date_default_timezone_set("Africa/Lagos");

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for Big Fish */
define('DB_NAME', 'laser');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'samuelopex');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Website Domain name */
define('DOMAIN_NAME', 'emmaskingconcept.com');

/** Website Root directory */
define('ROOT_DIR', "/laser");

/** Website Root url */
define('ROOT_URL', $_SERVER["DOCUMENT_ROOT"] . ROOT_DIR);

/** Website Domain name */
define('ADMIN_EMAIL', 'samakos2217@gmail.com');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/** Context Feedback Message for Redirctions */
define('CXT_REDIRECT', 'https://emmaskingconcept.com/cxt-redirect');

/** Website Homepage */
define('HOMEPAGE', 'https://emmaskingconcept.com');
/**
 * Laser Project Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
define('DB_PREFIX', 'ls_');

 /**
 * The Database accsessor class that connects to the Database management system
 */
 
 //the class definition
 class DatabaseConnection {

     //Class Properties
     private $name = DB_NAME;

     private $host = DB_HOST;

     private $user = DB_USER;

	 private $password = DB_PASSWORD;

	 public $conn;
     
    public function __construct(){
		
       $this->initDb($this->name,$this->host,$this->user,$this->password);
    }
	public function initDb($DBname, $DBhost, $DBusername, $DBpassword){
		$DBsn = "mysql:host=$DBhost;dbname=$DBname";
		try{
			$this->conn = new PDO($DBsn,$DBusername,$DBpassword);
			$this->conn->setAttribute( PDO::ATTR_PERSISTENT, true );
			$this->conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			return $this->conn;
		}
		catch ( PDOException $e ){
			//What to do when an invalid access to the data base is spoted
			die($e);
		}

	}
 }

 ?>