<?php
/*
*ファイルパス:C:\xampp\htdocs\DT\buy\check.php
*ファイル名:check.php
*アクセスURL:http://localhost/DT/buy/check.php
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
$name = '';

session_start();
$arr= isset($_POST)?$_POST:'';
foreach ($arr as $key => $value){
$_SESSION[$key] =$value;
}
$context =[];
$context['arr'] = $arr;
$template = $twig->loadTemplate('confirm_contact.html.twig');
$template->display($context);
