<?php

namespace App;

use Exception;
use PDO;
use PDOException;

define('SQL', 'mysql');
define('HOST', 'localhost');
define('DATABASE', 'sharegifs');
define('USERNAME', 'root');
define('PASSWORD', '');

define('LEFT', 'left');
define('RIGHT', 'right');
define('BOTH', 'both');

class Database
{

    public static $instance;
    private $query = '';
    private $where = '';
    private $join = '';
    private $limit = '';
    private $table = '';




    /* 
        SELECT u.id, u.name, u.avatar, u.email, u.amount_followers 

            FROM users as u

                WHERE u.id NOT IN (
                    SELECT f.id_followed FROM followers as f WHERE f.id_follower = 22
                ) AND U.id != 22;
     */





    public function __construct()
    {
    }




    public function toSql()
    {
        file_put_contents('database_monitor', "\n" . $this->query . "\n", FILE_APPEND);
        return $this->query;
    }





    public function setTable($table, $alias = false)
    {
        $this->table = $table . ($alias ? ' as ' . $alias : '');
        return $this;
    }





    public function custom($sql)
    {
        $this->connect();
        $result = self::$instance->query($sql, PDO::FETCH_ASSOC);
        $this->close();

        return $result;
    }





    public function update(array $columns, array $values)
    {
        if (count($columns) !== count($values))
            throw new Exception("Número de parametros de colunas e campos não combinam.", 500);

        $this->query = 'UPDATE ';
        $this->query .= ' ' . $this->table . ' SET ';

        for ($i = 0; $i < count($columns); $i++)
            $this->query .= $columns[$i] . '=' . "'$values[$i]'" . (($i == count($columns) - 1) ? '' : ', ');

        return $this;
    }





    public function select(array $columns = ['*'])
    {
        $this->query .= 'SELECT ' . (!count($columns) ? '*' : (count($columns) === 1 ? current($columns) : implode(', ', $columns))) . ' FROM ';
        $this->query .= $this->table . ' ';

        return $this;
    }





    public function delete()
    {
        $this->query = 'DELETE FROM ';
        $this->query .= $this->table . ' ';
        $this->query .= $this->where . ' ';

        return $this;
    }




    /*
        SELECT u.id, u.name, u.email, u.amount_followers 
        FROM 
        `followers` as f 
        RIGHT JOIN 
        `users` as u 
        ON 
        u.id != f.id_followed
    */
    public function join($table, $alias, array $on, $direction = "INNER", $boolean = "AND")
    {
        $this->join .= ' ' . $direction . ' JOIN ';
        $this->join .= $table . ($alias ? ' as ' . $alias . ' ' : ' ');
        $this->join .= 'ON ';

        if (count($on) && getType(current($on)) === "array" && count(current($on))) {
            foreach ($on as $cond) :
                list($column, $condition, $value) = $cond;
                $this->join .= $column . ' ' . $condition . ' ' . $value . ' ' . $boolean . ' ';
            endforeach;
            $this->join = preg_replace("/ $boolean$/", '', trim($this->join));
        } else if (count($on) && getType(current($on)) === 'string') {
            list($column, $condition, $value) = $on;
            $this->join .= $column . ' ' . $condition . ' ' . $value . ' ';
        }

        $this->query .= ' ' . $this->join;

        return $this;
    }





    public function where(array $wheres = [])
    {
        $this->where .= ' WHERE ';

        if (count($wheres) && getType(current($wheres)) === "array" && count(current($wheres))) {

            foreach ($wheres as $where) :
                list($column, $condition, $value) = $where;
                $this->where .= $column . " " . $condition . " '" . $value . "' and ";
            endforeach;

            $this->where = preg_replace('/ and$/', '', trim($this->where));
        } else if (count($wheres) && getType(current($wheres)) === "string") {

            list($column, $condition, $value) = $wheres;
            $this->where .= $column . " " . $condition . " '" . $value . "' ";
        }

        $this->query .= ' ' . $this->where;
        $this->where = '';

        return $this;
    }





    public function whereOr(array $wheres = [])
    {
        $this->where .= ' WHERE ';

        if (count($wheres) && getType(current($wheres)) === "array" && count(current($wheres))) {

            foreach ($wheres as $where) :
                list($column, $condition, $value) = $where;
                $this->where .= $column . " " . $condition . " '" . $value . "' or ";
            endforeach;

            $this->where = preg_replace('/ or$/', '', trim($this->where));
        } else if (count($wheres) && getType(current($wheres)) === "string") {

            list($column, $condition, $value) = $wheres;
            $this->where .= $column . " " . $condition . " '" . $value . "' ";
        }

        $this->query .= ' ' . $this->where;
        $this->where = '';

        return $this;
    }





    public function limit($amount)
    {
        $this->limit .= ' LIMIT ' . $amount . ' ';
        $this->query .= ' ' . $this->limit;

        return $this;
    }





    public function like($value, $type)
    {
        if ($type === LEFT)
            $this->query .= ` LIKE '%$value' `;
        if ($type === RIGHT)
            $this->query .= ` LIKE '$value%' `;
        else if ($type === BOTH)
            $this->query .= ` LIKE '%$value%' `;

        return $this;
    }





    public function first()
    {
        $this->connect();

        $stmt = self::$instance->query($this->toSql());
        $rowCount = $stmt->rowCount();
        $results = [];

        if ($rowCount)
            return $stmt->fetch(PDO::FETCH_ASSOC);

        $this->close();

        $this->query = '';

        return $results;
    }





    public function create(array $columns, array $values)
    {
        if (count($columns) !== count($values))
            throw new Exception("Número de parametros de colunas e campos não combinam.", 500);

        $this->query = 'INSERT INTO ';
        $this->query .= ' ' . $this->table . ' (';

        foreach ($columns as $key => $column)
            $this->query .= $column . ($key + 1 < count($columns) ? ', ' : '');

        $this->query .= ') VALUES(';

        foreach ($values as $key => $value)
            $this->query .= " '" . $value . ($key + 1 < count($values) ? "', " : "'");

        $this->query .= ")";

        $this->connect();
        $created = self::$instance->query($this->toSql());
        $this->lastInsertId = self::$instance->lastInsertId();
        $this->close();

        $this->query = '';

        return $created;
    }





    public function exec()
    {
        $this->connect();
        $exec = self::$instance->query($this->toSql())->rowCount();
        $this->close();

        $this->query = '';

        return $exec;
    }





    public function get()
    {
        $this->connect();

        $stmt = self::$instance->query($this->toSql());
        $rowCount = $stmt->rowCount();
        $results = [];

        if ($rowCount)
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $result)
                array_push($results, $result);

        $this->close();

        $this->query = '';

        return $results;
    }





    private function connect()
    {
        try {
            self::getInstance();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }





    private static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new PDO(
                'mysql:host=localhost;dbname=sharegifs',
                'root',
                '',
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );
            self::$instance->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
            self::$instance->setAttribute(
                PDO::ATTR_ORACLE_NULLS,
                PDO::NULL_EMPTY_STRING
            );
        }

        return self::$instance;
    }





    private function close()
    {
        self::$instance = null;
    }
}
