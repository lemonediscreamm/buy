<?php
/*
*ファイルパス:C:\xampp\htdocs\DT\buy\leave.php
*ファイル名:leave.php
*アクセスURL:http://localhost/DT/buy/leave.php
*/
namespace buy;

require_once dirname(__FILE__). '/Bootstrap.class.php';

use buy\Bootstrap;
use buy_member\lib\Database;
use buy\lib\Session;
use buy\lib\Item;

//buy_memberのDBに接続
$db = new Database('localhost','buy_member_user','buy_member_pass',
'buy_member_db','mysql');
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);
$template = 'leave.html.twig';
session_start();
//退会処理
if (isset($_POST['yes'])) { 
    //flgを0から1にする
    // $sql = ' UPDATE buy_member SET flg = 1 where mem_id ='.$_SESSION['mem_id'];
    // $res = $db->update($sql);
    $sql = 'DELETE FROM buy_member WHERE mem_id ='.$_SESSION['mem_id'];
    $res = $db->delete($sql);
    //セッションを消す
    $_SESSION = array();
    session_destroy();
    $template = 'fin.html.twig';
}
$context =[];
$template = $twig->loadTemplate($template);
$template->display($context);
