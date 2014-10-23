<?php
    function __autoload($class_name) {
        include $class_name . '.php';
    }

    $user = new User;
    $user->user_id = 1;
    $user->init();
    // var_dump($user);
    print_r($user->getAttributes());
?>