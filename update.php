<?php
/*
*ファイルパス:C:\xampp\htdocs\DT\buy\update.php
*ファイル名:update.php
*アクセスURL:http://localhost/DT/buy/update.php
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

$mem_id = isset($_SESSION['mem_id'])? $_SESSION['mem_id']:'';
//データをupdate
$table= ' buy_member';
$insData = ["family_name" => $_POST['family_name'],
            "first_name" =>$_POST['first_name'],
            "first_name_kana" =>$_POST['first_name_kana'],
            "family_name_kana" =>$_POST['family_name_kana'],
            "sex" =>$_POST['sex'],
            "year" =>$_POST['year'],
            "month" =>$_POST['month'],
            "day" =>$_POST['day'],
            "zip1" =>$_POST['zip1'],
            "zip2" =>$_POST['zip2'],
            "address" =>$_POST['address'],
            "email" =>$_POST['email'],
            "tel1" =>$_POST['tel1'],
            "tel2" =>$_POST['tel2'],
            "tel3" =>$_POST['tel3']
          ];
$where = "mem_id=" .$mem_id;
$dataArr = $db->update($table,$insData,$where);

$context=[];
$template = $twig->loadTemplate('update.html.twig');
$template->display($context);
