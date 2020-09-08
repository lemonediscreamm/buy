<?php
/*
*ファイルパス:C:\xampp\htdocs\DT\buy\detail.php
*ファイル名:detail.php
*アクセスURL:http://localhost/DT/buy/detail.php
*/
namespace buy;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use buy\Bootstrap;
use buy\lib\PDODatabase;
use buy\lib\Session;
use buy\lib\Item;

$db = new PDODatabase(Bootstrap::DB_HOST,Bootstrap::DB_USER, Bootstrap::DB_PASS,
Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$ses = new Session($db);
$itm = new Item($db);

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);


$item_id = (isset($_GET['item_id']) === true && preg_match('/^\d+$/',$_GET['item_id'])=== 1) ? $_GET['item_id']:'';
//$mem_id = (isset($_GET['mem_id']) === true && preg_match('/^\d+$/',$_GET['mem_id'])=== 1) ? $_GET['mem_id']:'';
// $area_id = (isset($_GET['area_id']) === true && preg_match('/^[0-9]+$/',$_GET['area_id'])=== 1) ? $_GET['area_id'] : '';
// var_dump($area_id);

if($item_id === ''){
    header('Location: ' . Bootstrap::ENTRY_URL. 'list.php');
}
//カテゴリー取得
$cateArr = $itm->getCategoryList();
$areaCateArr = $itm->getareaCategoryList();

$itemData = $itm->getItemDetailData($item_id);

$mem_id =isset($_SESSION['mem_id'])?$_SESSION['mem_id']:'';
$item_id =isset($_GET['item_id'])?$_GET['item_id']:'';
$session =isset($_SESSION)?$_SESSION:'';

//sessionがある時のみお気に入り機能を使用
if(isset($_SESSION['mem_id'])){
    //お気に入りのitem_idを取得
    $item_idArr=[];
    $item_idCheck =0;
    $table = " favorite";
    $column = " item_id ";
    $where = "mem_id=".$mem_id." and like_flg=1";
    $item_idData = $db->select($table,$column, $where);
    foreach($item_idData as $valArr){
        foreach($valArr as $val){
            if($val === $item_id){
            $item_idCheck=1; 
            }
        }
    }
}
$context = [];
$context['cateArr'] = $cateArr;
$context['areaCateArr'] = $areaCateArr;
$context['itemData'] = $itemData[0];
$context['mem_id']= $mem_id;
//$context['cart_in']=$cart_in;

//sessionがある時のみお気に入り機能を使用
if(isset($_SESSION['mem_id'])){
    $context['item_id']= $item_id;
    $context['item_idData']= $item_idData;
    $context['item_idCheck']= $item_idCheck;
}
$template = $twig->loadTemplate('detail.html.twig');
$template->display($context);

