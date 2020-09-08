<?php
/*
*ファイルパス:C:\xampp\htdocs\DT\buy\favorite.php
*ファイル名:favorite.php
*アクセスURL:http://localhost/DT/buy/favorite.php
*/
namespace buy;

require_once dirname(__FILE__). '/Bootstrap.class.php';

use buy\Bootstrap;
use buy\lib\PDODatabase;
use buy\lib\Session;
use buy\lib\Item;
use buy\lib\Cart;

$db = new PDODatabase(Bootstrap::DB_HOST,Bootstrap::DB_USER, Bootstrap::DB_PASS,
Bootstrap::DB_NAME, Bootstrap::DB_TYPE);

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);
$cart = new Cart($db);
//mem_id取得
session_start();
$mem_id = isset($_SESSION['mem_id'])?$_SESSION['mem_id']:'';

//お気に入りのitem_idを取得
$table = " favorite";
$column = " item_id ";
$where = "mem_id=".$mem_id." and like_flg=1";
$data = $db->select($table,$column, $where);

$arr = [];
foreach($data as $key => $value){
    foreach($value as $key1 => $value1){
        $table = " item";
        $column = " * ";
        $where = "item_id=".$value1;
        $item_data = $db->select($table,$column, $where);
        
        array_push($arr,$item_data[0]);
    }
}

//合計数、合計
list($sumNum, $sumPrice) = $cart->getItemAndSumPrice($mem_id);

//item_id取得
$item_id = isset($_GET['item_id'])?$_GET['item_id']:'';
//削除が押されたとき
if($item_id !== ''){
    $table = ' favorite';
    $insData = ['like_flg' => 0 ];
    $where = " item_id =".$item_id." and mem_id =".$mem_id;
    $res = $db->update($table, $insData, $where);
    //画面更新
    header("Location:http://localhost/DT/buy/favorite.php/");
}


$context = [];
$context['data'] =$data;
$context['sumNum'] = $sumNum;
$context['sumPrice'] = $sumPrice;
$context['item_data'] = $arr;
$template = $twig->loadTemplate('favorite.html.twig');
$template->display($context);
