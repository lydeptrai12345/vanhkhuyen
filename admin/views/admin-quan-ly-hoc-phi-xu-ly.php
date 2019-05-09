<?php
include "../../inc/myconnect.php";
include "../../inc/myfunction.php";

if(isset($_GET['load_list_be'])) {
    $str = "SELECT be.id,be.ten,be.ngaysinh,be.gioitinh,be.chieucao,lophoc_chitiet.mo_ta,be.cannang,be.diachi,be.tinhtrangsuckhoe,be.benhbamsinh,be.hinhbe,be.tencha,be.sdtcha,be.tenme,be.sdtme,be.matracuu,be.chieucao,be.trangthai 
            FROM be INNER JOIN lophoc_be ON be.id = lophoc_be.be_id 
            INNER JOIN lophoc_chitiet ON lophoc_be.lop_hoc_chi_tiet_id = lophoc_chitiet.id ORDER BY be.id DESC";
    $query = mysqli_query($dbc, $str);
    $result = array();

    if (mysqli_num_rows($query) > 0)
    {
        $index = 1;
        while ($row = mysqli_fetch_array($query)){
            $result[] = array (
                'index'    =>$index++,
                'ten'      => $row['ten'],
                'ngaysinh' => date_format(date_create($row['ngaysinh']),'d/m/Y'),
                'gioitinh' => $row['gioitinh'] ? "Nam" : "Nữ",
                'cannang'  => $row['cannang'],
                'tencha'  => $row['tencha'],
                'sdtcha'   => $row['sdtcha'],
                'tenme'   => $row['tenme'],
                'sdtme'    => $row['sdtme'],
                'diachi'   => $row['diachi'],
                'chieucao' => $row['chieucao'],
                'mo_ta'    => $row['mo_ta'],
                'tinhtrangsuckhoe'    => $row['tinhtrangsuckhoe'],
                'benhbamsinh'    => $row['benhbamsinh'],
                'hinhbe'    => $row['hinhbe'],
                'matracuu'    => $row['matracuu'],
                'trangthai'    => $row['trangthai'],
            );
        }
    }
    echo json_encode($result);
}