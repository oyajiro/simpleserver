<?php
class Model
{
    private $mysql_user = 'test';
    private $mysql_host = 'localhost';
    private $mysql_password = 'raPvPcBcMhHYWGGb';
    private $mysql_database = 'test';
    private $db;
    private $fetch_style = PDO::FETCH_ASSOC;
    public $fields = array();

    public function __construct() {
        try {
            $this->db = new PDO('mysql:host='. $this->mysql_host . ';dbname=' . $this->mysql_database, $this->mysql_user, $this->mysql_password);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
        }
    }

    private function validate($params)
    {
        return true;
    }

    private function init()
    {

    }

    private function queryOne($sql, $params)
    {
        $result = false;
        if ($this->validate($params)) {
            $query = $this->db->prepare($sql);
            $query->execute($params);
            $result = $query->fetch($this->fetch_style);
        }
        return $result;
    }

    public function getField($field, $table)
    {
        $sql = 'SELECT :field FROM :table WHERE user_id=:user_id LIMIT 1';
        $params = array(':field' => $field, ':table' => $table, ':user_id' => $this->user_id);
        return $this->queryOne($sql, $params);
    }

    public function getRowById($id, $table)
    {

        $sql = 'SELECT ' . implode(',', $this->fields) . ' FROM ' . $table . ' WHERE user_id=:user_id LIMIT 1';
        $params = array(':user_id' => $id);
        return $this->queryOne($sql, $params);
    }

    public function saveData()
    {
        if ($this->validate()) {
            if (empty($this->user_id)) {
                mysqli_insert();
            } else {
                mysqli_insert('');
            }
        }
    }

    public function saveObject($user_id)
    {

    }

    public function getAttributes()
    {
        $values=array();
        foreach($this->fields as $field) {
            $values[$field] = $this->$field;
        }
        return $values;
    } 

    public function setAttributes($values)
    {
        if(!is_array($values)) {
            return;
        }
        foreach($values as $name=>$value)
        {
            if(in_array($name, $this->fields)) {
                $this->$name = $value;
                // var_dump($name,$this->$name);
            }
        }
    }
}