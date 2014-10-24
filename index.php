<?php
    function __autoload($class_name) {
        include $class_name . '.php';
    }

    $user = new User('aaaa1');
    print_r($user->getAttributes());
    $user->last_visit = time();
    $user->saveRow();
    $user->refresh();
    print_r($user->getAttributes());
?>