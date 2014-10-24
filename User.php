<?php
class User extends Model
{
    private $table = 'UserData';

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

}
