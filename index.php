<?php
    function __autoload($class_name) {
        include $class_name . '.php';
    }

    function _check_auth_key($person_id, $network_key, $auth_key) {
        return true;
    }

    if (isset($_POST['action'])&& isset($_POST['network_key'])) {
        $request = true;
        if (isset($_POST['person_id'])) {
            $person_id = $_POST['person_id'];
        } else {
            $request = false;
        }

        if (isset($_POST['network_key'])) {
            $network_key = $_POST['network_key'];
        } else {
            $request = false;
        }

        if (isset($_POST['auth_key'])) {
            $auth_key = $_POST['auth_key'];
        } else {
            $request = false;
        }

        if ($request && _check_auth_key($person_id, $network_key, $auth_key)) {
                switch ($_POST['action']) {
                    case 'save':
                        if (!empty($_POST['data'])) {
                            $data = json_decode($_POST['data'], true);
                            $user = new User();
                            $user->user_id = $person_id;
                            $user->setAttributes($data);
                            $user->save();
                        }
                        break;
                    case 'get':
                        if (!empty($_POST['fields'])) {
                            $user = new User();
                            echo json_encode($user->getRowById($person_id, json_decode($_POST['fields'], true)));
                        }
                        break;
                }
        }
    }
?>