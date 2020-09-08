<?php
/*
*ファイルパス:C:\xampp\htdocs\DT\buy\list.php
*ファイル名:list.php
*アクセスURL:http://localhost/DT/buy/list.php
*/

namespace buy;

require_once dirname(__FILE__). '/Bootstrap.class.php';

use buy\Bootstrap;
use buy\lib\PDODatabase;
use buy\lib\Session;
use buy\lib\Item;

$db = new PDODatabase(Bootstrap::DB_HOST,Bootstrap::DB_USER, Bootstrap::DB_PASS,
Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$ses = new Session($db);
$itm = new Item($db);
//$like  = new Like($db);

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

$admin = '';
$username ='';
//カートに入れるボタンを押すとき実行する
//$ses->checkSession();

$ctg_id = (isset($_GET['ctg_id']) === true && preg_match('/^[0-9]+$/',$_GET['ctg_id'])=== 1) ? $_GET['ctg_id'] : '';
$area_id = (isset($_GET['area_id']) === true && preg_match('/^[0-9]+$/',$_GET['area_id'])=== 1) ? $_GET['area_id'] : '';

//カテゴリー取得
$cateArr = $itm->getCategoryList();
//エリアカテゴリー取得
$areacateArr = $itm->getareaCategoryList();

//DBからitemの詳細取得
$dataArr = $itm->getItemList($ctg_id);
$areaDataArr = $itm->getAreaItemList($area_id);


//検索ボタンが押されたとき
$search =(isset($_GET['search']) === true) ? $_GET['search'] : '';
$search_bl= isset($_GET['search']);
$arr = $itm->getResult($search);
//ログインしているか確認
$session = (isset($_SESSION['family_name'])) ? true : false;
//ユーザーがログインした場合
if(isset($_SESSION['family_name'])=== true && $_SESSION['family_name'] !== 'admin'){
    $username = $_SESSION['family_name'];
}
//管理者がログインしてる場合
if(isset($_SESSION['family_name'])=== true && $_SESSION['family_name'] === 'admin'){
    $admin = $_SESSION['family_name'];
    $username = $_SESSION['family_name'];
}
$errEmpty=[];
$_SESSION['errArr'] = $errEmpty;

$context = [];

$context['cateArr'] = $cateArr;
$context['areacateArr'] = $areacateArr;
$context['dataArr'] = $dataArr;
$context['areaDataArr'] = $areaDataArr;
$context['ctg_id'] = $ctg_id;
$context['arr'] = $arr;
$context['username'] = $username;
$context['admin'] = $admin;
$context['search_bl'] = $search_bl;
$context['session'] = $session;

$template = $twig->loadTemplate('list.html.twig');
$template->display($context);
