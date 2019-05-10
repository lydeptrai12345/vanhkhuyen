<?php
include "../../inc/myconnect.php";
include "../../inc/myfunction.php";


if(isset($_POST['get_data_lop_hoc'])) {
    $id_nien_khoa = (int)$_POST['id_nien_khoa'];
    $query = mysqli_query($dbc, "SELECT lophoc_chitiet.id, lophoc_chitiet.mo_ta, lophoc.id AS 'khoi_id' FROM lophoc_chitiet INNER JOIN lophoc ON lophoc_chitiet.lop_hoc_id = lophoc.id WHERE nien_khoa_id = {$id_nien_khoa}");

    $result = array();

    if (mysqli_num_rows($query) > 0)
    {
        while ($row = mysqli_fetch_array($query)){
            $result[] = array(
                'id' => $row['id'],
                'mo_ta' => $row['mo_ta'],
                'khoi_id' => $row['khoi_id']
            );
        }
    }
    echo json_encode($result);
}


if(isset($_GET['load_list_be'])) {
    $str = "SELECT be.id as 'be_id',be.ten,be.ngaysinh,be.gioitinh,be.chieucao,lophoc_chitiet.mo_ta,be.cannang,be.diachi,be.tinhtrangsuckhoe,be.benhbamsinh,be.hinhbe,be.tencha,be.sdtcha,be.tenme,be.sdtme,be.matracuu,be.chieucao,be.trangthai 
            FROM be INNER JOIN lophoc_be ON be.id = lophoc_be.be_id 
            INNER JOIN lophoc_chitiet ON lophoc_be.lop_hoc_chi_tiet_id = lophoc_chitiet.id ORDER BY be.id DESC";
    $query = mysqli_query($dbc, $str);
    $result = array();

    if (mysqli_num_rows($query) > 0)
    {
        $index = 1;
        while ($row = mysqli_fetch_array($query)){
            $result[] = array (
                'be_id'    => $row['be_id'],
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


// Lấy thông tin học phí theo khôi
if(isset($_GET['get_hoc_phi_theo_khoi'])) {
    $nien_khoa = $_GET['nien_khoa'];
    $khoi = $_GET['khoi'];
//    echo "SELECT * FROM hoc_phi WHERE nien_khoa_id = {$nien_khoa} AND lop_hoc_id = {$khoi}";
    $query = mysqli_query($dbc, "SELECT * FROM hoc_phi WHERE nien_khoa_id = {$nien_khoa} AND lop_hoc_id = {$khoi}");
    $result = mysqli_fetch_object($query);
    echo json_encode($result);

}