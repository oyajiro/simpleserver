<?php
$longtext = 'aaaaaaaaЙВ ^ЙГ_‹Ж^ЙГ‹Ж^В ѓЗ‹рҐҐҐҐ_^ЙВ ‹рЌ{ҐҐҐҐиы  _^‰C‰S[ЙВ ‹EЙВ ѓмx‹EV‹сW‰uр‰Fѓeь hј=|ЌЌ|яяяЗx=|и:  P‹Оиq  ЌЌ|яяяие  йї  ѓмx‹EV‹с‰uр‰Fѓeь hј=|ЌЌ|яяяЗа=|ищ  P‹ОиK  ЌЌ|яяяи¤  ‹Mф‹Ж^d‰
    ЙВ ѓмx‹EV‹с‰uр‰Fѓeь hј=|ЌЌ|яяяЗ,=|и¬  P‹Оию
  ЌЌ|яяяиW  ‹Mф‹Ж^d‰
    ЙВ ^В WиЖ Y_ЌN^йћ  В ѓмVWj ЌMми  ‹5д2A|ѓeь №3A|‰uри‡#  ‹MPиђ“ йа  ‹Пиo  ѓMьяЌMмик  ‹Mф‹З_^d‰
    ЙГѓмVWj ЌMмиЄ  ‹5и2A|ѓeь №3A|‰uри0#  ‹MPи9“ йн  ‹Пи  ѓMьяЌMми“  ‹Mф‹З_^d‰
    ЙГѓмVWj ЌMмиS  ‹5м2A|ѓeь №3A|‰uриЩ"  ‹MPив’ йъ  ‹ПиБ  ѓMьяЌMми<  ‹Mф‹З_^d‰
    ЙГ‹Ж^ГГY‹ШйЙ  яu‹П‰_‰wих6  ‹Mф_^d‰
    [ЙВ _‹Ж^ГQV‹сЌN‰uрЗґ=|и?  ѓeь jиљљ й
  ‹Mф‹Ж^d‰
    ffffff
  QVЌEрPиY    ѓeь Pиn
  ѓMьяYЌMр‹риN"  яu‹‹ОяPй1
  ѓfH j яt$‹ОЗ =|иM@  й1
  ‹D$3Ыj ‹О‰F(‰^,ис  й+
  QV‹с‰uрѓeь иo„ йS
  ‹Mф^d‰
    ЙГяt$‹ОиC  ‹Ж^В ѓмSVW‹с‰eр3ЫVЌMа‰uи‰]миM  й`
  ѓMьяЌMаищ  ‹Mф_‹Ж^d‰
    [ЙВ zzzzzzzzzz';
    $fields = array(
        'level',
        'coins',
        'objects',
    );
    $objects = array();
    $objects[] = array(
        'name' => md5(rand(1, 15)),
        'data' => $longtext,
    );
    $objects[] = array(
        'name' => md5(rand(1, 15)),
        'data' => $longtext,
    );
    // xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
    function __autoload($class_name) {
        include $class_name . '.php';
    }

    function _check_auth_key($person_id, $network_key, $auth_key) {
        return true;
    }

    // $user = new User('0290348ad800cf400d36ec00b96c78bb');
    // $user->level = 20;
    // $user->coins = 5;
    // print_r($user->getAttributes());
    // $user->setObjects($objects);
    // $user->save();
    // $result = $user->save();
    // print_r($result);
    if (isset($_POST)) {
        print_r($_POST);
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
                            $user = new User($person_id);
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
    // $xhprof_data = xhprof_disable();
    // include_once "/var/www/xhprof/xhprof_lib/utils/xhprof_lib.php";
    // include_once "/var/www/xhprof/xhprof_lib/utils/xhprof_runs.php";
    // $xhprof_runs = new XHProfRuns_Default();
    // $run_id = $xhprof_runs->save_run($xhprof_data, "test");
?>