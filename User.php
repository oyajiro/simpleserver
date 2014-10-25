<?php
class User extends Model
{
    protected $objects;

    public $table = 'UserData';
    public $objectsTable = 'UserObjects';
    public $id_field = 'user_id';
    public $fields = array(
        'user_id',
        'level',
        'coins',
        'last_visit',
        'visit_days',
        'continious_visit_days',
        'objects',
    );

    public $update_fields = array(
        'level',
        'coins',
        'objects',
    );

    public $user_id;
    public $level;
    public $coins;
    public $last_visit;
    public $visit_days;
    public $continious_visit_days;

    public function __construct($id = '') {
        parent::__construct();
        if (!empty($id)) {
            $row = $this->getRowById($id, '*');
            $this->setAttributes($row);
        }
    }

    public function refresh() {
        $row = $this->getRowById($this->user_id, $this->table);
        $this->setAttributes($row);
    }

    public function getRowById($id, $fields)
    {
        $includeObjects = false;
        $result = false;
        if (is_array($fields)) {
            if (in_array('objects', $fields)) {
                $includeObjects = true;
                foreach ($fields as $key => $value) {
                    $fields[$key] = $this->table . '.' . $value;
                    if (($value) == 'objects') {
                        unset($fields[$key]);
                    }
                }
                $fields['name'] = $this->objectsTable . '.' . 'name';
                $fields['data'] = $this->objectsTable . '.' . 'data';
            }
            $fields = implode(', ', $fields);
        }
        $params = array(':' . $this->id_field => $id);
        $sql = 'SELECT ' . $fields . ' FROM ' . $this->table;
        if ($includeObjects) {
            $sql .= ' RIGHT JOIN ' . $this->objectsTable . ' ON ' . $this->table . '.user_id = ' . $this->objectsTable . '.user_id WHERE ' . $this->table . '.' . $this->id_field . ' = :' . $this->id_field;
            $result = $this->selectPreparedRows($sql, $params);
            var_dump($result);
        } else {
            $sql .= ' WHERE ' . $this->id_field . ' = :' . $this->id_field . ' LIMIT 1';
            $result = $this->selectOne($sql, $params);
        }
        print_r($sql);
        //return $result;
    }

    public function save()
    {
        $data = $this->getAttributes();
        $params = array();
        $params_arr = array();
        $str = '';
        if ($this->validate($data)) {
            foreach ($data as $key => $value) {
                if ($key == 'objects') {
                    $this->saveObjects($data['objects']);
                    unset($data[$key]);
                } else {
                    $params_arr[] = ':' . $key;
                    $params[':' . $key] = $value;
                }
            }
            $str =  '(' . implode(', ', array_keys($data)) . ')';
            $values =  '(' . implode(', ', $params_arr) . ')';

            $sql = 'REPLACE INTO ' . $this->table . ' ' . $str . ' VALUES ' . $values;
            $this->query($sql, $params);
        }
    }

    public function saveObjects()
    {
        $objects = array();
        if (!empty($this->objects)) {
            if (!empty($this->objects[0])) {
                foreach ($this->objects as $object) {
                     $objects[] = '(\'' . $this->user_id . '\', \'' . $object['name'] . '\', \'' . $object['data'] . '\')';
                }
            } else {
                $objects[] = '(\'' . $this->user_id . '\', \'' . $this->objects['name'] . '\', \'' . $this->objects['data'] . '\')';
            }
            $sql = 'REPLACE INTO UserObjects (`user_id`, `name`, `data`) VALUES ' . implode(', ', $objects) . ';';
        }
        $this->queryTransaction($sql);
    }

    public function setObjects($objects)
    {
        $this->objects = $objects;
    }

    public function getUserObjectsByName($name) {
        $sql = 'SELECT ' . $fields . ' FROM ' . $this->objectsTable . ' WHERE name = :' . $name;
        $params = array(':name' => $name);
        return $this->selectPreparedRows($sql, $params);
    }

    public function getUserObjects() {
        if (empty($this->objects)) {
            $sql = 'SELECT ' . $fields . ' FROM ' . $this->objectsTable . ' WHERE user_id = :' . $user_id;
            $params = array(':user_id' => $user_id);
            $this->objects = $this->selectPreparedRows($sql, $params);
        }
        return $this->objects;
    }

    // public function getRows($fields, $data)
    // {
    //     $includeObjects = false;
    //     if (is_array($fields)) {
    //         if (isset($fields['objects'])) {
    //             $includeObjects = true;
    //         }
    //         $fields = implode(', ', $fields);
    //     }
    //     $conditions = array();
    //     $sql = 'SELECT ' . $fields . ' FROM ' . $this->table . ' WHERE ';
    //     if (!empty($data[0])) {
    //         $in = array();
    //         foreach ($data as $row) {
    //             //Валидация требует доработки
    //             foreach ($row as $key => $value) {
    //                 $in['`' . $key . '`'][] = '\''. $value . '\'';
    //             }
    //         }
    //         if (!empty($in)) {
    //             foreach ($in as $key => $value) {
    //                 $conditions[] = $key . ' IN (' . implode(', ', $value) . ')';
    //             }
    //         }
    //     } else {
    //         foreach ($data as $key => $value) {
    //             $conditions[] = '`' . $key . '` = \'' . $value . '\'';
    //         }
    //     }
    //     $sql .= implode(' OR ', $conditions);
    //     print_r($sql);
    //     return $this->selectRows($sql);
    // }



    // public function saveRows($fields, $data)
    // {
    //     $value_rows = array();
    //     $objects = array();
    //     foreach ($data as $row) {
    //         if ($this->validate($row)) {
    //             $values = array();
    //             foreach ($row as $key => $value) {
    //                 if ($key == 'objects') {
    //                     if (!empty($value[0])) {
    //                         foreach ($value as $object) {
    //                             $objects[] = '(\'' . $row['user_id'] . '\', \'' . $object['name'] . '\', \'' . $object['data'] . '\')';
    //                         }
    //                     } else {
    //                         $objects[] = '(\'' . $row['user_id'] . '\', \'' . $value['name'] . '\', \'' . $value['data'] . '\')';
    //                     }
    //                 } else {
    //                     $values[] = '\'' . $value . '\'';
    //                 }
    //             }
    //             $value_rows[] = '(' . implode(', ', $values) . ')';
    //         }
    //     }
    //     if (!empty($value_rows)) {
    //         $sql = 'REPLACE INTO ' . $this->table . ' ( ' . implode(', ', $fields) . ') VALUES ' . implode(', ', $value_rows) . ';';
    //         if (!empty($objects)) {
    //             $sql .= ' REPLACE INTO UserObjects (`user_id`, `name`, `data`) VALUES ' . implode(', ', $objects) . ';';
    //         }
    //         $this->queryTransaction($sql);
    //     }
    // }

}
