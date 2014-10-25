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

    //Валидация входящих данных, здесь в идеале надо реализовать обработку каждого поля по типу (если ожидаем инт, проходим регуляркой на инт к примеру)
    protected function validate($params)
    {
        $result = true;
        foreach ($params as $key => $value) {
            if (!in_array($key, $this->fields)) {
                $result = false;
            }
        }
        return $result;
    }

    protected function selectOne($sql, $params)
    {
        $result = false;
        if ($this->validate($params)) {
            $query = $this->db->prepare($sql);
            $query->execute($params);
            $result = $query->fetch($this->fetch_style);
        }
        return $result;
    }

    protected function selectRows($sql)
    {
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchAll($this->fetch_style);
    }

    protected function selectPreparedRows($sql, $params)
    {
        $query = $this->db->prepare($sql);
        $query->execute($params);
        return $query->fetchAll($this->fetch_style);
    }

    protected function query($sql, $params)
    {
        $query = $this->db->prepare($sql);
        $query->execute($params);
    }

    protected function queryTransaction($sql)
    {
        $this->db->beginTransaction();
        $this->db->exec($sql);
        $this->db->commit();
    }

    public function __construct()
    {
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

    public function getRowById($id)
    {

        $sql = 'SELECT ' . implode(',', $this->fields) . ' FROM ' . $this->table . ' WHERE ' . $this->id_field . ' = :' . $this->id_field . ' LIMIT 1';
        $params = array($this->id_field => $id);
        return $this->selectOne($sql, $params);
    }

    public function getRowByField($field, $value)
    {
        $sql = 'SELECT ' . implode(',', $this->fields) . ' FROM ' . $this->table . ' WHERE ' . $field . ' = :' . $field . ' LIMIT 1';
        $params = array($field => $value);
        return $this->selectOne($sql, $params);
    }

    // public function getRowsByField($field, $values)
    // {
    //     $sql = 'SELECT ' . implode(',', $this->fields) . ' FROM ' . $this->table . ' WHERE ' . $field . ' IN (' . implode(', ', $values) . ')';
    //     return $this->selectRows($sql);
    // }

    // public function getRows($data)
    // {
    //     $sql = 'SELECT ' . implode(',', $this->fields) . ' FROM ' . $this->table . ' WHERE ';
    //     $in = array();
    //     foreach ($data as $row) {
    //         //Валидация требует доработки
    //         foreach ($row as $key => $value) {
    //             $in['`' . $key . '`'][] = '\''. $value . '\'';
    //         }
    //     }
    //     if (!empty($in)) {
    //         $conditions = array();
    //         foreach ($in as $key => $value) {
    //             $conditions[] = $key . ' IN (' . implode(', ', $value) . ')';
    //         }
    //     }
    //     return $this->selectRows($sql . implode(' OR ', $conditions));
    // }

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
            }
            $values =  '(' . implode(', ', $params_arr) . ')';

            $sql = 'REPLACE INTO ' . $this->table . ' ' . $str . ' VALUES ' . $values;
            $this->query($sql, $params);
        }
    }

    // public function saveRows($data)
    // {
    //     $fields = array();
    //     foreach ($this->fields as $field) {
    //         $fields[] = '`' . $field . '`';
    //     }
    //     $sql = 'REPLACE INTO ' . $this->table . ' ( ' . implode(', ', $fields) . ') VALUES ';
    //     foreach ($data as $row) {
    //         if ($this->validate($row)) {
    //             $values = array();
    //             foreach ($row as $key => $value) {
    //                 $values[] = '\'' . $value . '\'';
    //             }
    //             $value_rows[] = '(' . implode(', ', $values) . ')';
    //         }
    //     }
    //     $this->queryTransaction($sql . implode(', ', $value_rows));
    // }

    public function getAttributes()
    {
        $values=array();
        foreach($this->fields as $field) {
            if (!empty($this->$field)) {
                $values[$field] = $this->$field;
            }
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
            if(in_array($name, $this->fields) && !empty($value)) {
                $this->$name = $value;
            }
        }
    }
}