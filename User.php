<?php
class User extends Model
{

    public $table = 'UserData';
    public $id_field = 'user_id';
    public $fields = array(
        'user_id',
        'level',
        'coins',
        'last_visit',
        'visit_days',
        'continious_visit_days',
    );

    public $user_id;
    public $level;
    public $coins;
    public $last_visit;
    public $visit_days;
    public $continious_visit_days;

    public function init()
    {
        if (!empty($this->user_id)) {
            $row = $this->getRowById($this->user_id, $this->table);
            $this->setAttributes($row);
        }
    }

    public function __construct($id = '') {
        parent::__construct();
        if (!empty($id)) {
            $row = $this->getRowById($id, $this->table);
            $this->setAttributes($row);
        }
    }

    public function refresh() {
        $row = $this->getRowById($this->user_id, $this->table);
        $this->setAttributes($row);
    }
}
