<?php
/*
*ファイルパス:C:\xampp\htdocs\DT\buy\board5.php
*ファイル名:board5.php
*アクセスURL:http://localhost/DT/buy/board5.php
*/

namespace buy;

require_once dirname(__FILE__). '/Bootstrap.class.php';

use buy\Bootstrap;
use buy\lib\PDODatabase;
use buy\lib\Session;
use buy\lib\Item;

$db = new PDODatabase(Bootstrap::DB_HOST,Bootstrap::DB_USER, Bootstrap::DB_PASS,
Bootstrap::DB_NAME, Bootstrap::DB_TYPE);

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

session_start();

$mem_id = isset($_SESSION['mem_id'])?$_SESSION['mem_id']:'';
$msg = '';
$err_msg = '';
$templates = 'board5_1.html.twig';
//item_nameをDBから取得
$item_id = isset($_GET['item_id'])?$_GET['item_id']:'';
$table = " item";
$column = " item_name ";
$where = " item_id =".$item_id;
$item_nameArr = $db->select($table, $column, $where);
$item_name = $item_nameArr[0]['item_name'];

//書き込みボタンを押したとき
if (isset($_POST['send'])) {
    //トークン番号が一致したとき
    if((isset($_REQUEST["chkno"]) == true) && (isset($_SESSION["chkno"]) == true)
    && ($_REQUEST["chkno"] == $_SESSION["chkno"])){
        $templates = 'board5_1.html.twig';
        $name = $_POST['name'];
        $subject = $_POST['subject'];
        $contents = $_POST['contents'];
        if ($name !=='' && $contents !== '' && $subject !== '') {
            //DBにinsert
            $table = ' board ';
            $insData = [
            'name' =>$name,
            'subject' =>$subject,
            'contents' =>$contents,
            'mem_id' =>$mem_id,
            'item_id'=>$item_id
            ];
            $res = $db->insert($table, $insData);
            //エラーメッセージ
            if ($res !== false) {
                $msg = '書き込みに成功しました';   
            } else {
                $err_msg = '書き込みに失敗しました';
            }
        } else {
            $err_msg = 'すべての項目を記入してください';
        }
    }
}


//boardのデータ取り出し
$table = ' board ';
$column = " id,name,subject,contents,mem_id,flg ";
$where = "item_id=".$item_id;
$data = $db->select($table,$column,$where);

//idを降順にする
$idArr = [];
foreach($data as $value){
$idArr[] = $value['id'];
}
array_multisort($idArr, SORT_DESC, SORT_NUMERIC,$data);

//新しい照合番号を発番する
$_SESSION["chkno"] = $chkno = mt_rand();

$context = [];
$context['msg'] = $msg;
$context['err_msg'] = $err_msg;
$context['data'] = $data;
$context['mem_id'] = $mem_id;
$context['item_name'] = $item_name;
$context['chkno'] = $chkno;
$context['item_id'] = $item_id;
$template = $twig->loadTemplate($templates);
$template->display($context);