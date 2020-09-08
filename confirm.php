<?php
/*
*ファイルパス:C:\xampp\htdocs\DT\buy\confirm.php
*ファイル名:confirm.php
*アクセスURL:http://localhost/DT/buy/confirm.php
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

//生年月日取得　//list:右辺の配列の要素を、左辺の変数に代入することができる
list($yearArr, $monthArr, $dayArr) = initMaster::getDate();
//男女の配列　取得
$sexArr = initMaster::getSex();

$template ='';
$mode ='';
//モード判定（どの画面から来たのか判断）
if (isset($_POST['confirm']) === true) {
    $mode = 'confirm';
}
// if (isset($_POST['confirm1']) === true) {
//     $mode = 'confirm1';
// }
echo $mode;
$errmail ='';
$mask_pass = '';
$dataArr1 = '';
//ボタンのモードよって処理をかえる
switch ($mode) {
    case 'confirm'://変更ボタンを押したとき
        unset($_POST['confirm']);
        $dataArr = $_POST;

        //この値を入れないでPOSTするとUndefinedとなるので未定義の場合は空白状態としてセットしておく
        if (isset($_POST['sex']) === false) {
            $dataArr['sex'] = "";
        }
        //メールアドレス重複チェック
        // if($dataArr['email'] !==''){
        //     $table= ' buy_member';
        //     $column = " email ";
        //     $res = $db->select($table,$column);
        //     $emailArray = array_column($res, 'email');
        //     $result = array_search($dataArr['email'], $emailArray);
        //     if($result !== false){
        //     $errmail='すでに登録されているメールアドレスです';
        //     }
        // }
        //エラーメッセージの配列作成
        $errArr = $common->errorCheck($dataArr);
        $err_check = $common->getErrorFlg();
        //err_check = false →エラーがあります
        //err_check = true →エラーがないです
        //エラーがなければconfirm.tpl 　あるとregist.tpl
        //$template = ($err_check === true && $errmail === '') ? 'confirm.html.twig' : 'mypage.twig';
        //$template = ($err_check === true) ? 'confirm.html.twig' : 'err.html.twig';
        if($err_check === true){
            $errEmpty=[];
            $_SESSION['errArr'] = $errEmpty;
            $template ='confirm.html.twig';
        }else{
            session_start();
            $_SESSION['errArr'] = $errArr;
            header("Location: http://localhost/DT/buy/mypage.php");
            
            
            exit;
        }
        break;
    // case 'confirm1':
    //             //ポストされたデータを元に戻すので、$dataArrに入れる
    //     $dataArr1 = $_POST;
    //     unset($dataArr1['confirm1']);
        
    //     //エラーも定義しておかないと、Undefinedエラーがでる
    //     // foreach ($dataArr as $key => $value) {
    //     //     $errArr[$key] = '';
    //     // }
    //     var_dump($_POST);
    //     $errArr = $common->errorCheck($dataArr1);
    //     $err_check = $common->getErrorFlg();
        
    //     $template = ($err_check === true) ? 'confirm.html.twig' : 'err.html.twig';
    //     break;
    // case 'update'://登録完了
    //     $dataArr =  $_POST;
    //     //↓この情報はいらないので外しておく
    //     unset($dataArr['update']);
    //     $column = '';
    //     $insData = '';
    //     //foreach の中でSQL文を作る
    //     foreach ($dataArr as $key => $value) {
    //         $column .= $key . ', ';
    //         if ($key === 'password') {
    //             $value = password_hash ($dataArr['password'], PASSWORD_DEFAULT);
    //         }
    //         if ($key === 'password1') {
    //             $value = password_hash ($dataArr['password1'], PASSWORD_DEFAULT);
    //         }
    //         $insData .= ($key === 'sex') ? $db->quote($value) . ',' :
    //         $db->str_quote($value) . ', ';
    //     }

    // $query = " INSERT INTO buy_member ( "
    //         . $column
    //         . " regist_date "
    //         ." ) VALUES ( "
    //         . $insData
    //         ." NOW() "
    //         . " ) ";
 
    // $res = $db->execute($query);
    // $db->close();
    
    // if ($res === true) {
    //     //登録成功時は完成時はページへ
    //     header('Location: ' . Bootstrap::ENTRY_URL . 'complete.php');
    //     exit();
    // } else {
    //     //登録失敗時は登録画面に戻る
    //     $template = 'regist.html.twig';
       
    //     foreach ($dataArr as $key => $value) {
    //         $errArr[$key] = '';
    //     }
    // }
    //break;
}




$context = [];

$context['yearArr'] = $yearArr;
$context['monthArr'] = $monthArr;
$context['dayArr'] = $dayArr;
$context['errArr'] = $errArr;

$context['sexArr'] = $sexArr;
$context['dataArr'] = $dataArr; 
$context['dataArr1'] = $dataArr1;
$context['mem_id'] = $mem_id;
$template = $twig->loadTemplate($template);
$template->display($context);
