<?php
/*
*ファイルパス:C:\xampp\htdocs\DT\buy\delete.php
*ファイル名:delete.php
*アクセスURL:http://localhost/DT/buy/delete.php
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
//削除を押されたときflg=1にする
if(isset($_GET['id'])){
    $table = ' board ';
    $insData =[
        " flg "=>1
    ];   
    $where = "id=".$_GET['id'];
    $res=$db->update($table, $insData, $where);
}

//DBからデータをとる
$table = ' board ';
$column = " item_id ";
$where = "id=".$_GET['id'];
$data = $db->select($table,$column,$where);
$context = [];
$context['data'] =$data[0];

$template = $twig->loadTemplate('delete.html.twig');
$template->display($context);