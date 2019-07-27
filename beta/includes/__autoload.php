<?php
/*
 * This file is part of the bolt result portal application.
 * (c) Bethsaida ICT Solution
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Autoload Function  [PHP Class Autoloader].
 *
 * @package    bolt
 * @subpackage includes
 * @author     Akosile Opeyemi Samuel <opeyemiakosile@gmail.com>
 * @version    Path: includes.__autoload.php - v1.0
 */

    spl_autoload_register(function($className){
        $className= str_replace("\\", DIRECTORY_SEPARATOR, $className);
        require_once ROOT_URL . '/' . $className . '.php';
    })
 ?>