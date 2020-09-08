<?php
/*
*ファイルパス:C:\xampp\htdocs\DT\buy\lib\Item.class.php
*ファイル名:Item.class.php
*アクセスURL:http://localhost/DT/buy/lib/Item.class.php
*/
namespace buy\lib;

class Item
{
    public $cateArr = [];
    public $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getCategoryList()
    {
        $table = ' category ';
        $col = ' ctg_id, category_name ';
        $res = $this->db->select($table, $col);
        return $res;
    }

    public function getareaCategoryList()
    {
        $table = ' area_category ';
        $col = ' area_id, area_category_name ';
        $res = $this->db->select($table, $col);
        return $res;
    }

    public function getItemList($ctg_id)
    {
        $table = ' item ';
        $col = ' item_id, item_name, price,image, ctg_id ';
        $where = ($ctg_id !== '') ? ' ctg_id = ? ' : '';
        $arrVal = ($ctg_id !== '') ? [$ctg_id] : [];

        $res = $this->db->select($table, $col, $where, $arrVal);

        return ($res !== false && count($res) !== 0) ? $res : false;   
    }

    public function getAreaItemList($area_id)
    {
        $table = ' item ';
        $col = ' item_id, item_name, price,image, area_id ';
        $where = ($area_id !== '') ? ' area_id = ? ' : '';
        $arrVal = ($area_id !== '') ? [$area_id] : [];

        $res = $this->db->select($table, $col, $where, $arrVal);

        return ($res !== false && count($res) !== 0) ? $res : false;   
    }

    public function getItemDetailData($item_id)
    {
        $table = ' item ';
        $col = ' item_id, item_name, detail, price, image, ctg_id ';
        $where = ($item_id !== '') ? ' item_id = ? ' : '';
        $arrVal = ($item_id !== '') ? [$item_id] : [];
        $res = $this->db->select($table, $col, $where, $arrVal);
        return ($res !== false && count($res) !== 0) ? $res : false;     
    }   

    public function getResult($search)
    {
        $table = ' item ';
        $col = ' item_name, price, item_id, image  ';
        $text = '"'.$search.'"';
        $where = 'item_name like "%'.$search.'%" or price='.$text;
        $arrVal = [$search];
        $res = $this->db->select($table, $col, $where, $arrVal);
        return ($res !== false && count($res) !== 0) ? $res : false; 
    }
}