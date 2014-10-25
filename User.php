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
        $result = false;
        $fields_str = implode(', ', $fields);
        if ($this->memcache) {
            $result = $this->memcache->get(md5($id));
            if ($result) {
                if ($result['fields_str'] == $fields_str) {
                    echo 'c - ' . $id . "\n";
                    unset($result['fields_str']);
                    return $result;
                }
            }
        }
        $includeObjects = false;
        if (is_array($fields)) {
            if (in_array('objects', $fields)) {
                $includeObjects = true;
                $fields = array_diff($fields, ['objects']);
            }
            $fields = implode(', ', $fields);
        }
        $params = array(':' . $this->id_field => $id);
        $sql = 'SELECT ' . $fields . ' FROM ' . $this->table . ' WHERE ' . $this->id_field . ' = :' . $this->id_field . ' LIMIT 1';
        $result = $this->selectOne($sql, $params);
        if ($includeObjects && !empty($result)) {
            $result['objects'] = $this->getObjectsById($id);
        }
        if ($this->memcache) {
            $result['fields_str'] = $fields_str;
            $this->memcache->set(md5($id), $result, 60);
        }
        return $result;
    }

    public function save()
    {
        $data = $this->getAttributes();
        $params = array();
        $params_arr = array();
        $str = '';
        if ($this->validate($data)) {
            if (!empty($data['objects'])) {
                $this->saveObjects($data['objects']);
                unset($data['objects']);
            }

            foreach ($data as $key => $value) {
                $params_arr[] = ':' . $key;
                $params[':' . $key] = $value;
                $update_params[] = $key . ' = ' . ' :' . $key;
            }
            $str =  '(' . implode(', ', array_keys($data)) . ')';
            $values =  '(' . implode(', ', $params_arr) . ')';
            $update_values = implode(', ', $update_params);

            $sql = 'INSERT INTO ' . $this->table . ' ' . $str . ' VALUES ' . $values . ' ON DUPLICATE KEY UPDATE ' . $update_values;
            $this->query($sql, $params);
        }
        if ($this->memcache) {
            $this->memcache->delete(md5($this->user_id));
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
            $sql = 'REPLACE INTO ' . $this->objectsTable . ' (`user_id`, `name`, `data`) VALUES ' . implode(', ', $objects) . ';';
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

    public function getObjectsById($id) {
        if (empty($this->objects)) {
            $sql = 'SELECT name, data FROM ' . $this->objectsTable . ' WHERE user_id = :user_id';
            $params = array(':user_id' => $id);
            $this->objects = $this->selectPreparedRows($sql, $params);
        }
        return $this->objects;
    }

}
