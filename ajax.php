<?php
/*
*ファイルパス:C:\xampp\htdocs\DT\buy\ajax.php
*ファイル名:ajax.php
*アクセスURL:http://localhost/DT/buy/ajax.php
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

if(isset($_POST['item_id'])=== true && isset($_POST['favorite'])=== true && isset($_POST['mem_id'])=== true){
    //echo $_POST['item_id'];
    //echo $_POST['favorite'];
    //echo $_POST['mem_id'];
  
    $table = " favorite";
    $column = " like_flg ";
    $where = "mem_id=".$_POST['mem_id']. " and item_id=".$_POST['item_id'];
    $data = $db->select($table,$column, $where);
    //配列の長さ
    $count = count($data);
    //配列の長さが１以上のときupdateする
    if($count >= 1){
        //DBにupdate
        $table =' favorite';
        //配列の中のlikeflgが1の場合

        foreach($data[0] as $value){ 

            if($value == 1){
                echo 'first';
                $insData = [
                    'like_flg' => 0 
                ];
            }   
            //配列の中のlikeflgが0の場合
            else{     
 
                $insData = [
                    'like_flg' => 1
                ];
            }
        }
        $where ="mem_id=".$_POST['mem_id']." and item_id=".$_POST['item_id'];
        //echo 'where!!!'.$where;
        $res = $db->update($table,$insData,$where);
    //配列の長さが0の場合insertする
    }else{
        //DBにinsert
        //echo 'insert';
        $table =' favorite';
        $insData = [
        "like_flg"=>$_POST['favorite'], 
        "item_id"=>$_POST['item_id'],
        "mem_id"=>$_POST['mem_id']
        ];
        $res =$db->insert($table, $insData);
    }
}
