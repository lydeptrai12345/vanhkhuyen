<?php
    include "xuly-data.php";
    class QuanLyLop extends xuly {
        function __contructor()
        {
            parent::__contructor();
        }

        public function getDataNienKhoa($select=[], $where=null)
        {
            return ($this->getTable("nienkhoa", $select, $where));
        }

        public function getLopHocTheoNienKhoa($idNienKhoa)
        {
            return $this->getTable('lophoc_chitiet', ['id', 'nien_khoa_id', 'mo_ta'], "WHERE nien_khoa_id = " . (int)$idNienKhoa);
        }

        public function insertNienKhoa()
        {
            $data = array(
                array('ten_nien_khoa' => 'ggggggg', 'nam_ket_thuc' => 233232),
                array('ten_nien_khoa' => 'hhhhhhh', 'nam_ket_thuc' => 999999)
            );
            echo json_encode($this->insertTable("nienkhoa", $data));
        }

        public function updateNienKhoa()
        {
            $arrayDataUpdate = array("ten_nien_khoa" => '20188888', "nam_ket_thuc" => '2019');
            echo $this->updateTable("nienkhoa", $arrayDataUpdate, "WHERE id = 1");
        }
    }
?>