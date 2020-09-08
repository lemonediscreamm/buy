<?php
/*
*ファイルパス:C:\xampp\htdocs\DT\buy\contact.php
*ファイル名:contact.php
*アクセスURL:http://localhost/DT/buy/contact.php
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
$template = '';
$mailCheck =false;
$result = false;
//POSTされたデータがあれば変数に格納、なければ NULL（変数の初期化）
$name = isset( $_POST[ 'name' ] ) ? $_POST[ 'name' ] : NULL;
$email = isset( $_POST[ 'email' ] ) ? $_POST[ 'email' ] : NULL;
$tel = isset( $_POST[ 'tel' ] ) ? $_POST[ 'tel' ] : NULL;
$subject = isset( $_POST[ 'subject' ] ) ? $_POST[ 'subject' ] : NULL;
$body = isset( $_POST[ 'body' ] ) ? $_POST[ 'body' ] : NULL;
$send ='';

$arr= isset($_POST)?$_POST:'';

$context =[];
$context['mailCheck'] = $mailCheck;
// $context['mail'] = $mail;
$context['send'] = $send;
$context['arr'] = $arr;
$template = $twig->loadTemplate('contact.html.twig');
$template->display($context);
