<?php
namespace buy\lib;

class like
{
    private $db = null;

    public function __construct($db = null)
    {
        $this->db = $db;
    }

    private function insertLike($mem_id, $item_id, $like_flg)
    {
        $table = ' favorite ';
        $insData = [
            ' mem_id' => $mem_id, 
            'item_id' => $item_id, 
            'like_flg' => $like_flg
        ];
        return $this->db->insert($table, $insData);
    }
}    