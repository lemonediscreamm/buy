<?php
/*
*ファイルパス:C:\xampp\htdocs\DT\buy\edit.php
*ファイル名:delete.php
*アクセスURL:http://localhost/DT/buy/edit.php
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
$id = isset($_GET['id'])?$_GET['id']:'';
$data[0] = '';
$templates ='';
//更新するが押されたとき
if(isset($_POST['update'])){
    // $table = ' board ';
    // $column = " contents ";
    // $where = "id=".$id;
    // $data = $db->select($table,$column,$where);
    // var_dump($data);
    $name = isset($_POST['name'])?$_POST['name']:'';
    $subject = isset($_POST['subject'])?$_POST['subject']:'';
    $contents = isset($_POST['contents'])?$_POST['contents']:'';
    $table = ' board ';
    $insData =[
        "name"=>$name,
        "subject"=>$subject,
        " contents "=>$contents
    ];   
    $where = "id=".$_GET['id'];
    $res=$db->update($table, $insData, $where);
    if($res){
        $templates ='boardcomplete.html.twig';
    }
}else{
    $templates = 'edit.html.twig';
}

//DBからデータをとる
$table = ' board ';
$column = " name,subject,contents,item_id ";
$where = "id=".$id;
$data = $db->select($table,$column,$where);
$context = [];
$context['data'] =$data[0];

$template = $twig->loadTemplate($templates);
$template->display($context);