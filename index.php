<?php

    // xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
    function __autoload($class_name) {
        include $class_name . '.php';
    }

    function _check_auth_key($person_id, $network_key, $auth_key) {
        return true;
    }

    $user = new User();
    $result = $user->getRows($data);
    print_r(count($result));

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
                        if (is_array($data)) {
                            $user->saveRows($data);
                        } else {
                            $user->saveRow($data);
                        }
                    }
                    break;
                case 'get':
                    if (!empty($_POST['data'])) {
                        $data = json_decode($_POST['data'], true);
                        if (is_array($data)) {
                            echo json_encode($user->getRows($data));
                        } else {
                            echo json_encode($user->getRow($data));
                        }
                    }
                    break;
            }
        }
    }
    // $xhprof_data = xhprof_disable();
    // include_once "/var/www/xhprof/xhprof_lib/utils/xhprof_lib.php";
    // include_once "/var/www/xhprof/xhprof_lib/utils/xhprof_runs.php";
    // $xhprof_runs = new XHProfRuns_Default();
    // $run_id = $xhprof_runs->save_run($xhprof_data, "test");
?>