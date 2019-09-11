<?php
/*
 * This file is part of the bolt result portal application.
 * (c) Bethsaida ICT Solution
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Wrapper [Database Wrapper Class - Using Medoo].
 *
 * @package    laser
 * @subpackage includes/database
 * @author     Akosile Opeyemi Samuel <opeyemiakosile@gmail.com>
 * @version    Path: includes.database.Wrapper - v1.0
 */

namespace includes\database;

class Wrapper{
    public $db;

    public function __construct(){
        $this->db = new \libs\medoo\Medoo(DB_SETTINGS);
    }

    public function select(){
        if(func_num_args() == 2 ){
            return $this->db->select(func_get_arg(0), func_get_arg(1));
        }
        elseif(func_num_args() == 3){
            return $this->db->select(func_get_arg(0), func_get_arg(1), func_get_arg(2));
        }
        elseif(func_num_args() == 4){
            return $this->db->select(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
        }
        else{
            throw new \Exception('Invalid number of arguments');
        }
    }

    public function insert($table, $data){
        $this->db->insert($table, $data);
    }

    public function update(){
        if(func_num_args() == 2 ){
            return $this->db->update(func_get_arg(0), func_get_arg(1));
        }
        elseif(func_num_args() == 3){
            return $this->db->update(func_get_arg(0), func_get_arg(1), func_get_arg(2));
        }
        else{
            throw new \Exception('Invalid number of arguments');
        }
    }

    public function delete($table, $where){
        $this->db->delete($table, $where);
    }

    public function replace(){
        if(func_num_args() == 2 ){
            return $this->db->replace(func_get_arg(0), func_get_arg(1));
        }
        elseif(func_num_args() == 3){
            return $this->db->replace(func_get_arg(0), func_get_arg(1), func_get_arg(2));
        }
        else{
            throw new \Exception('Invalid number of arguments');
        }
    }

    public function get(){
        if(func_num_args() == 2 ){
            return $this->db->get(func_get_arg(0), func_get_arg(1));
        }
        elseif(func_num_args() == 3){
            return $this->db->get(func_get_arg(0), func_get_arg(1), func_get_arg(2));
        }
        elseif(func_num_args() == 4){
            return $this->db->get(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
        }
        else{
            throw new \Exception('Invalid number of arguments');
        }
    }

    public function has(){
        if(func_num_args() == 1 ){
            return $this->db->has(func_get_arg(0));
        }
        elseif(func_num_args() == 2){
            return $this->db->has(func_get_arg(0), func_get_arg(1));
        }
        elseif(func_num_args() == 3){
            return $this->db->has(func_get_arg(0), func_get_arg(1), func_get_arg(2));
        }
        else{
            throw new \Exception('Invalid number of arguments');
        }
    }

    public function rand($table, $columns, $where=null){
        if(func_num_args() == 2 ){
            return $this->db->rand(func_get_arg(0), func_get_arg(1));
        }
        elseif(func_num_args() == 3){
            return $this->db->rand(func_get_arg(0), func_get_arg(1), func_get_arg(2));
        }
        else{
            throw new \Exception('Invalid number of arguments');
        }
    }
    
    /**
     * Create atomic transaction
     * @param: (callable) $actions
     */
    public function atomic_transaction($actions){
        $this->db->action($actions);
    }
}
?>