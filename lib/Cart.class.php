<?php
/*
*ファイルパス:C:\xampp\htdocs\DT\buy\lib\Cart.class.php
*ファイル名:Cart.class.php
*アクセスURL:http://localhost/DT/buy/lib/Cart.class.php
*/

namespace buy\lib;

class Cart
{
    private $db = null;

    public function __construct($db = null)
    {
        $this->db = $db;
    }

    public function insCartData($customer_no, $item_id)
    {
        $table = ' cart';
        $insData = [
            'customer_no'=>$customer_no,
            'item_id' => $item_id
        ];
        return $this->db->insert($table, $insData);
    }

    public function getCartData($customer_no)
    {
        $table = ' cart c LEFT JOIN item i ON c.item_id = i.item_id ';
        $column = ' c.crt_id, i.item_id, i.item_name, i.price,i.image ';
        $where = ' c.customer_no = ? AND c.delete_flg = ? ';
        $arrVal = [$customer_no, 0]; 
        return $this->db->select($table, $column, $where, $arrVal);
    }

    public function delCartData($crt_id)
    {
        $table = ' cart ';
        $insData = ['delete_flg' => 1];
        $where = ' crt_id = ? ';
        $arrWhereVal = [$crt_id];

        return $this->db->update($table, $insData, $where, $arrWhereVal);   
    }
    
    // public function delCartData($item_id,$mem_id)
    // {
    //     $table = ' cart ';
    //     $insData = ['delete_flg' => 1];
    //     $where = ' item_id = ? AND customer_no = ?';
    //     $arrWhereVal = [$item_id, $mem_id];

    //     return $this->db->update($table, $insData, $where, $arrWhereVal);   
    // }
    public function getItemAndSumPrice($customer_no)
    {
        $table = " cart c  LEFT JOIN item i  ON c.item_id = i.item_id ";
        $column = " SUM( i.price ) AS totalPrice ";
        $where = ' c.customer_no  = ? AND c.delete_flg = ?';
        $arrWhereVal = [$customer_no, 0];
        
        $res = $this->db->select($table, $column, $where, $arrWhereVal);
        $price = ($res !== false && count($res) !== 0 )? $res[0]['totalPrice'] : 0;
        
        $table = ' cart c ';
        $column = ' SUM( num ) AS num ';
        $res = $this->db->select($table, $column, $where, $arrWhereVal);

        $num = ($res !== false && count($res) !== 0) ? $res[0]['num'] : 0;
        return [$num, $price];
    }
}    