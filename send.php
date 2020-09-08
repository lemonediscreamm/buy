<?php
/*
*ファイルパス:C:\xampp\htdocs\DT\buy\send.php
*ファイル名:send.php
*アクセスURL:http://localhost/DT/buy/send.php
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

session_start();
$mailCheck =false;
$send = false;

//POSTされたデータがあれば変数に格納、なければ NULL（変数の初期化）
$name = isset( $_SESSION[ 'name' ] ) ? $_SESSION[ 'name' ] : NULL;
$email = isset( $_SESSION[ 'email' ] ) ? $_SESSION[ 'email' ] : NULL;
$tel = isset( $_SESSION[ 'tel' ] ) ? $_SESSION[ 'tel' ] : NULL;
$subject = isset( $_SESSION[ 'subject' ] ) ? $_SESSION[ 'subject' ] : NULL;
$body = isset( $_SESSION[ 'body' ] ) ? $_SESSION[ 'body' ] : NULL;
$result='';


//確認ボタンが押された場合の処理
if (isset($_POST['submitted'])) { 
  //POSTされたデータに不正な値がないかを別途定義した checkInput() 関数で検証 
  //$_POST = checkInput( $_POST );
  $send = true;
  //filter_var を使って値をフィルタリング
  if(isset($_SESSION['name'])) {
    //スクリプトタグがあれば除去
    $name = filter_var($_SESSION['name'], FILTER_SANITIZE_STRING);
  }
  
  if(isset($_SESSION['email'])) {
    //全ての改行文字を削除
    $email = str_replace(array("\r", "\n", "%0a", "%0d"), '', $_SESSION['email']);
    //E-mail の形式にフィルタ
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
  }
  
  if(isset($_SESSION['tel'])) {
    //数値の形式にフィルタ（数字、+ 、- 記号 以外を除去）
    $tel = filter_var($_SESSION['tel'], FILTER_SANITIZE_NUMBER_INT);
  }
  
  if(isset($_SESSION['subject'])) {
    $subject = filter_var($_SESSION['subject'], FILTER_SANITIZE_STRING);
  }
  
  if(isset($_SESSION['body'])) {
    $body = filter_var($_SESSION['body'], FILTER_SANITIZE_STRING);
  }
 
  //$_POST でのリクエストの場合
  if ($_SERVER['REQUEST_METHOD']==='POST') {    
    //メールアドレス等を記述したファイルの読み込み
 
    require 'mailvars.php'; 

    //メール本文の組み立て。値は h() でエスケープ処理
    $mail_body = 'コンタクトページからのお問い合わせ' . "\n\n";
    $mail_body .=  "お名前： " .h($name) . "\n";
    $mail_body .=  "Email： " . h($email) . "\n"  ;
    $mail_body .=  "お電話番号： " . h($tel) . "\n\n" ;
    $mail_body .=  "＜お問い合わせ内容＞" . "\n" . h($body);
 
    //-------- sendmail を使ったメールの送信処理 ------------
 
    //メールの宛先（名前<メールアドレス> の形式）。値は mailvars.php に記載
    //$mailTo = mb_encode_mimeheader(MAIL_TO_NAME) ."<" . MAIL_TO. ">";
    $mailTo = MAIL_TO;
 
    //Return-Pathに指定するメールアドレス
    //$returnMail = MAIL_RETURN_PATH; 
    $returnMail = $email;

    //mbstringの日本語設定
    mb_language( 'ja' );
    mb_internal_encoding( 'UTF-8' );
 
    // 送信者情報（From ヘッダー）の設定
    $header = "From: " . mb_encode_mimeheader($name) ."<" . $email. ">\n";
    //$header .= "Cc: " . mb_encode_mimeheader(MAIL_CC_NAME) ."<" . MAIL_CC.">\n";
    $header .= "Cc: " . mb_encode_mimeheader($name) ."<" . $email. ">\n";
    //$header .= "Bcc: <" . MAIL_BCC.">";
    $header .= "Bcc: " . mb_encode_mimeheader($name) ."<" . $email. ">\n";
 
    //メールの送信結果を変数に代入 （サンプルなのでコメントアウト）
    if ( ini_get( 'safe_mode' ) ) {
      //セーフモードがOnの場合は第5引数が使えない
      $result = mb_send_mail( $mailTo, $subject, $mail_body, $header );
      echo 'result1 :'.$result.'<br>';
    } else {
      $result = mb_send_mail( $mailTo, $subject, $mail_body, $header, '-f' . $returnMail );
      echo 'result2 :'.$result.'<br>';
    }
    echo 'result3 :'.$result.'<br>';

    //メールが送信された場合の処理
    if ( $result ) {
      //空の配列を代入し、すべてのPOST変数を消去
      $_POST = array(); 
      $mailCheck = true; 
      //変数の値も初期化
      $name = '';
      $email = '';
      $tel = '';
      $subject = '';
      $body = '';
      
      //再読み込みによる二重送信の防止
      $params = '?result='. $result;
      $url = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://').$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']; 
      header('Location:' . $url . $params);
      exit;
    } 
  }
}

function h($var) {
    if(is_array($var)){
      //$varが配列の場合、h()関数をそれぞれの要素について呼び出す（再帰）
      return array_map('h', $var);
    }else{
      return htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
    }
  }
   
  //入力値に不正なデータがないかなどをチェックする関数
  function checkInput($var){
    if(is_array($var)){
      return array_map('checkInput', $var);
    }else{
      //NULLバイト攻撃対策
      if(preg_match('/\0/', $var)){  
        die('不正な入力です。');
      }
      //文字エンコードのチェック
      if(!mb_check_encoding($var, 'UTF-8')){ 
        die('不正な入力です。');
      }
      //改行、タブ以外の制御文字のチェック
      if(preg_match('/\A[\r\n\t[:^cntrl:]]*\z/u', $var) === 0){  
        die('不正な入力です。制御文字は使用できません。');
      }
      return $var;
    }
  }

//DBに挿入
$table = ' inquiry ';
$insData = [
  'name'=>$name,
  'email'=>$email,
  'tel'=>$tel,
  'subject'=>$subject,
  'body'=>$body
];

$res = $db->insert($table,$insData);

$result = isset($_GET['result'])?$_GET['result']:'';
$context =[];
$context['mailCheck'] = $mailCheck;
$context['send'] = $send;
$context['result'] =$result;
$template = $twig->loadTemplate('send.html.twig');
$template->display($context);
