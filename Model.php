<?php
require_once dirname(__FILE__) . "/configDB.php";
class Model
{
    private $mysql_user;
    private $mysql_host;
    private $mysql_password;
    private $mysql_database;
    private $db;
    private $fetch_style = PDO::FETCH_ASSOC;

    public $fields = array();
    public $table;
    public $id_field;

    public function __construct() {
        $options = get_options();
        if (!empty($options)) {
            foreach ($options as $key => $value) {
                $this->$key = $value;
            }
            try {
                $this->db = new PDO('mysql:host='. $this->mysql_host . ';dbname=' . $this->mysql_database, $this->mysql_user, $this->mysql_password);
            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
            }
        }
    }

    //Валидация входящих данных, здесь в идеале надо реализовать обработку каждого поля по типу (если ожидаем инт, проходим регуляркой на инт к примеру)
    private function validate($params)
    {
        return true;
    }

    private function init()
    {

    }

    private function selectOne($sql, $params)
    {
        $result = false;
        if ($this->validate($params)) {
            $query = $this->db->prepare($sql);
            $query->execute($params);
            $result = $query->fetch($this->fetch_style);
        }
        return $result;
    }

    private function queryOne($sql, $params)
    {
        if ($this->validate($params)) {
            $query = $this->db->prepare($sql);
            $query->execute($params);
        }
    }

    public function getRowById($id)
    {

        $sql = 'SELECT ' . implode(',', $this->fields) . ' FROM ' . $this->table . ' WHERE ' . $this->id_field . ' = :' . $this->id_field . ' LIMIT 1';
        $params = array(':user_id' => $id);
        return $this->selectOne($sql, $params);
    }

    public function saveRow()
    {
        $data = $this->getAttributes();
        $params = array();
        $params_arr = array();
        $str = '';
        if ($this->validate($this->getAttributes())) {
            $str =  '(' . implode(', ', array_keys($data)) . ')';
            foreach ($data as $key => $value) {
                $params_arr[] = ':' . $key;
                $params[':' . $key] = $value;
                $update_params[] = $key . ' = ' . ' :' . $key;
            }
            $values =  '(' . implode(', ', $params_arr) . ')';
            $update_values = implode(', ', $update_params);

            $sql = 'INSERT INTO ' . $this->table . ' ' . $str . ' VALUES ' . $values . ' ON DUPLICATE KEY UPDATE ' . $update_values;
            $this->queryOne($sql, $params);
        }
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
            }
        }
    }
}