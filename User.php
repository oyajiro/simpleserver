<?php
class User extends Model
{
    private $userDataTable = 'UserData';

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

    //Валидация входящих данных, здесь в идеале надо реализовать обработку каждого поля по типу (если ожидаем инт, проходим регуляркой на инт к примеру)
    private function validate($data) {
        $result = true;
        foreach($data as $name=>$value)
        {
            if(!in_array($name, $this->fields)) {
                $this->$name = $value;
                $result = false;
            }
        }
        return $result;
    }

    public function init()
    {
        if (!empty($this->user_id)) {
            $row = $this->getRowById($this->user_id, $this->userDataTable);
            $this->setAttributes($row);
        }
    }

}
