<?php
    include "xuly-data.php";
    class QuanLyLop extends xuly {
        protected $_tableName = "nienkhoa";

        function __contructor()
        {
            parent::__contructor();
        }

        public function get_danh_sach_khoi()
        {
            return $this->select('id, ten_lop as ten_khoi')->from('lophoc')->get();
        }

        public function get_danh_sach_lop_hoc_theo_nien_khoa_khoi($nien_khoa_id)
        {
//            $result = $this->select("l.id as lop_hoc_id, mo_ta, ten_nien_khoa, ten_lop as ten_khoi")
//                ->from("lophoc_chitiet as l")
//                ->join('lophoc', 'l.lop_hoc_id', '=', 'lophoc.id')
//                ->join('nienkhoa', 'l.nien_khoa_id', '=', 'nienkhoa.id')
//                ->where('nien_khoa_id = ' . $nien_khoa_id)
//                ->where('lophoc.id = ' . $khoi_id)
//                ->get();
            $result = $this->select('*')->from('lophoc_chitiet')->where('nien_khoa_id = ' . $nien_khoa_id)->get();
            return $result;
        }

        public function getLopHocTheoNienKhoa($idNienKhoa)
        {
            $queryString = "SELECT l.id, n.id AS 'nien_khoa_id', lh.ten_lop AS 'ten_khoi', l.mo_ta AS 'ten_lop', lh.id AS 'khoi_id' FROM nienkhoa AS n 
                            INNER JOIN lophoc_chitiet as l ON n.id = l.nien_khoa_id 
                            INNER JOIN lophoc AS lh ON l.lop_hoc_id = lh.id WHERE l.nien_khoa_id = {$idNienKhoa}";
            return $this->getQuery($queryString);
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


        public function test()
        {
            return $this->select("l.id as lop_hoc_id, mo_ta, ten_nien_khoa")->from("lophoc_chitiet as l")
                ->join('lophoc', 'l.lop_hoc_id', '=', 'lophoc.id')
                ->join('nienkhoa', 'l.nien_khoa_id', '=', 'nienkhoa.id')
                ->where('nien_khoa_id = 1')
                ->get();
        }
    }
?>