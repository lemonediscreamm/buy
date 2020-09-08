<?php
/*
*ファイルパス:C:\xampp\htdocs\DT\buy\cart.php
*ファイル名:cart.php
*アクセスURL:http://localhost/DT/buy/cart.php
*/
namespace buy;

require_once dirname(__FILE__). '/Bootstrap.class.php';

use buy\Bootstrap;
use buy\lib\PDODatabase;
use buy\lib\Session;
use buy\lib\Cart;

$db = new PDODatabase(Bootstrap::DB_HOST,Bootstrap::DB_USER, Bootstrap::DB_PASS,
Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$ses = new Session($db);
$cart = new Cart($db);

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader,[
    'cache' => Bootstrap::CACHE_DIR
]);

$ses->checkSession();
//$customer_no = $_SESSION['customer_no'];

$mem_id = $_SESSION['mem_id'];

//カートを追加したときのitem_id
$item_id = (isset($_GET['item_id']) === true && preg_match('/^\d+$/',$_GET['item_id'])=== 1) ? $_GET['item_id']:'';

//削除したときのcart_id
$crt_id = (isset($_GET['crt_id']) === true && preg_match('/^\d+$/',$_GET['crt_id'])=== 1) ? $_GET['crt_id']:'';
var_dump($_GET);
$res = '';

//detail.html.twigからのPOST
if(isset($_POST['cart_in'])){
    $cart_in=$_POST['cart_in'];
    var_dump($cart_in);
}


if($item_id !== ''){
    $res = $cart->insCartData($mem_id,$item_id);
    if($res === false){
        echo "商品購入に失敗しました。";
        exit();
    }
//}
}


//カートの削除
if($crt_id !== ''){
    $res = $cart->delCartData($crt_id);
    // $item_id = (isset($_GET['item_id']) === true && preg_match('/^\d+$/',$_GET['item_id'])=== 1) ? $_GET['item_id']:'';
    // echo 'mem_id:'.$mem_id;
    // echo 'item_id:'.$item_id;
    // $res = $cart->delCartData($item_id,$mem_id);
}

//カートの数と合計
//list($sumNum, $sumPrice) = $cart->getItemAndSumPrice($customer_no);
list($sumNum, $sumPrice) = $cart->getItemAndSumPrice($mem_id);


//カートの表示
$groupby = " c.item_id ";
$db->setGroupBy($groupby);
$dataArr =$cart->getCartData($mem_id);
//$dataArr =$cart->getCartData($customer_no);



//DBからカートの中のアイテムとその数を取得
$table = " cart";
$column = " count(item_id) as count, item_id ";
$where = " customer_no=".$mem_id." and delete_flg=0";
$groupby = " item_id ";
$db->setGroupBy($groupby);
$cartData = $db->select($table,$column, $where);



//新しい照合番号を発番する
//$_SESSION["chkno"] = $chkno = mt_rand();

$context = [];
$context['sumNum'] = $sumNum;
$context['sumPrice'] = $sumPrice;
$context['dataArr'] = $dataArr;
//$context['chkno'] = $chkno;

$context['cartData']=$cartData;

$template = $twig->loadTemplate('cart.html.twig');
$template->display($context);