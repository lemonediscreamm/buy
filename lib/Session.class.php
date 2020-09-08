<?php
/*
*ファイルパス:C:\xampp\htdocs\DT\buy\lib\Session.class.php
*ファイル名:Session.class.php
*アクセスURL:http://localhost/DT/buy/lib/Session.class.php
*/
namespace buy\lib;
class Session
{
    public $session_key = '';
    public $mem_id = '';
    public $db = NULL;

    public function __construct($db)
    {
        session_start();
        $this->session_key = session_id();
        $this->db = $db;
    }

    public function checkSession()
    {
        //$customer_no = $this->selectSession();
        $mem_id = $this->selectSession();
        //$id= $this->selectSession();
        //var_dump($_SESSION['mem_id']);
        $mem_id = isset($_SESSION['mem_id'])? $_SESSION['mem_id']:'';
        //session idがある
        //if ($customer_no !== false) {
        if ($mem_id !== '') {
            //$_SESSION['mem_id'] = $mem_id;
            
        //session idがない
        } else {
            //session情報がない場合LogIn画面へ移動
            header("Location:http://localhost/DT/buy_login/regist.php");
            exit;


            //$res= $this->insertSession($mem_id);
            //$res= $this->insertSession();
            //if ($res === true) {
                //$_SESSION['customer_no'] = $this->db->getLastId();
                //$_SESSION['mem_id'] = $this->db->getLastId();
            //} else {
                //$_SESSION['customer_no'] = '';
                //$_SESSION['mem_id'] = '';
            //}
        }
    }

    private function selectSession()
    {
        $table = ' session ';
        //$col = ' customer_no ';
        $col =' mem_id ';
        $where = ' session_key = ? ';
        $arrVal = [$this->session_key];

        $res = $this->db->select($table, $col, $where, $arrVal);
        //return (count($res) !== 0) ? $res[0]['customer_no'] : false;
        // var_dump($res[0]);
        // exit();
        //return (count($res) !== 0) ? $res[0]['customer_no'] : false;
        //var_dump($res[0]);
        //exit();
        return (count($res) !== 0) ? $res[0]['mem_id'] : false;

    }

    private function insertSession($mem_id)
    //private function insertSession()
    {
        $table = ' session ';
        $insData = ['session_key ' => $this->session_key
                    ,'mem_id' =>$mem_id];
        $res = $this->db->insert($table, $insData);
        return $res;
    }
}