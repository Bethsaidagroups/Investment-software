<?php
/*
 * This file is part of the bolt result portal application.
 * (c) Bethsaida ICT Solution
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Config file [Application Configuration File].
 *
 * @package    bolt
 * @subpackage -
 * @author     Akosile Opeyemi Samuel <opeyemiakosile@gmail.com>
 * @version    Path: config.php - v1.0
 */

/**
 * The default timezone
 */
date_default_timezone_set("Africa/Lagos");

/**
 * Database settings- PHP Constant Dictoinary. will work with v7 or greater, use 'const' if otherwise
 */ 
define('DB_SETTINGS', [
        // required
        'database_type' => 'mysql',
        'database_name' => 'laser',
        'server' => 'localhost',
        'username' => 'root',
        'password' => '',

        // [optional]
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_general_ci',

        // [optional] Table prefix
        'prefix' => 'ls_',
]);

/**
 * Application URL
 */
define('APP_URL', 'placeholder-app_url');

/**
 * Website Domain Name
 */
define('DOMAIN_NAME', 'placeholder-domain_name');

/**
 * Website Root Directory
 */
define('ROOT_DIR', "/laser/beta");

/**
 * Website Root URL
 */
define('ROOT_URL', $_SERVER["DOCUMENT_ROOT"] . ROOT_DIR);

/**
 * Static File Root URL
 */
define('STATIC_ROOT', 'placeholder-static_root');

/**
 * Content File Root URL
 */
define('CONTENT_ROOT', 'placeholder-content_root');

/**
 * Registered Modules with relative path to APP_URL
 */
define('MODULES',[
        'login'=>'/login',
        'generaladmin'=>'/general_admin',
        'classadmin'=>'/class_admin',
        'student'=>'/student'
]);

/**
 * Secret Pass Hash
 */
define('SECRET_PASS_HASH', 'placeholder-secret_pass_hash');

/**
 * License Key Hash
 */
define('LICENSE_KEY_HASH', 'placeholder-license_key_hash');
?>