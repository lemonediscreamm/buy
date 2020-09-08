<?php
/*
*ファイルパス:C:\xampp\htdocs\DT\buy\mypage.php
*ファイル名:mypage.php
*アクセスURL:http://localhost/DT/buy/mypage.php
*/
namespace buy;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use buy\Bootstrap;
use buy\lib\PDODatabase;
use buy\lib\Session;
use buy\lib\Item;
use buy\lib\Common;

//buy_memberのDB
$db = new PDODatabase('localhost','buy_member_user','buy_member_pass',
'buy_member_db','mysql');
$ses = new Session($db);
$common = new Common();

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

//mem_id取得
$mem_id = isset($_SESSION['mem_id'])? $_SESSION['mem_id']:'';
//データ取得
$table= ' buy_member';
$column = " family_name, first_name, family_name_kana, first_name_kana, sex, year, month, day, zip1, zip2, address, email, tel1, tel2, tel3 ";
$where = " mem_id = " . $mem_id;
$dataArr = $db->select($table,$column,$where);

//エラーメッセージの定義、初期
$errArr = [];
foreach ($dataArr as $key => $value) {
    $errArr[$key] = '';
}

$errArr = isset($_SESSION['errArr'])?$_SESSION['errArr']:'';

//生年月日取得　//list:右辺の配列の要素を、左辺の変数に代入することができる
list($yearArr, $monthArr, $dayArr) = initMaster::getDate();
//男女の配列　取得
$sexArr = initMaster::getSex();

$context = [];

$context['yearArr'] = $yearArr;
$context['monthArr'] = $monthArr;
$context['dayArr'] = $dayArr;
$context['errArr'] = $errArr;

$context['sexArr'] = $sexArr;
$context['dataArr'] = $dataArr; 
$context['mem_id'] = $mem_id;
$template = $twig->loadTemplate('mypage.twig');
$template->display($context);
