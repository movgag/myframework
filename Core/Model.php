<?php

namespace Core;
use PDO;

abstract class Model {

    protected $db;
    protected $st;
    protected $table;

    public function __construct()
    {
        $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $this->db = new PDO($dsn, DB_USER, DB_PASS, $options);
    }

    public function insert($data)
    {
        $this->st->execute($data);
    }

    public function update($data)
    {
        $this->st->execute($data);
    }

    public function listFields($table)
    {
        $q = "DESCRIBE ".$table;
        $r = $this->db->prepare($q);
        $r->execute();
        return $r->fetchAll(PDO::FETCH_COLUMN);
    }

    public function showTables()
    {
        $this->prepareQuery("SHOW TABLES FROM ".DB_NAME);
        return $this->all();
    }

    public function addColumn($column_name, $table_name, $type = 'LONGTEXT')
    {
        $sql = "ALTER TABLE `$table_name` ADD `$column_name` $type DEFAULT NULL";

        $this->lazyQuery($sql);
    }

    public function exec($q)
    {
        $this->db->exec($q);
    }

    public function lazyQuery($q)
    {
        return $this->db->query($q);
    }

    public function prepareQuery($q)
    {
        $this->st = $this->db->prepare($q);
    }

    public function execute()
    {
        $this->st->execute();
    }

    public function execute2($arr)
    {
        $this->st->execute($arr);
    }

    public function all2($arr)
    {
        $this->execute2($arr);
        return $this->st->fetchAll();
    }

    public function rowCount()
    {
        return $this->st->rowCount();
    }

    public function first()
    {
        $this->execute();
        return $this->st->fetch();
    }

    public function all()
    {
        $this->execute();
        return $this->st->fetchAll();
    }

    public function lastId()
    {
        return $this->db->lastInsertId();
    }

    public function beginTransaction()
    {
        $this->db->beginTransaction();
    }

    public function commit()
    {
        $this->db->commit();
    }

    public function rollback()
    {
        $this->db->rollback();
    }

    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->st->bindValue($param, $value, $type);
    }





}
