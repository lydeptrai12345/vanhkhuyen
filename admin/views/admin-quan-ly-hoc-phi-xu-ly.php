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


// Them moi hoc phi
if(isset($_POST['add'])) {
    $nien_khoa = isset($_POST['nien_khoa']) ? (int)$_POST['nien_khoa'] : 0;
    $khoi = isset($_POST['khoi']) ? (int)$_POST['khoi'] : 0;
    $hoc_phi = isset($_POST['hoc_phi']) ? (float)str_replace(".","", $_POST['hoc_phi']) : 0;

    if($nien_khoa > 0 && $khoi > 0 && $hoc_phi > 1000){
        // kiểm tra niên khóa và khối có học phí hay chưa
        $query_check = mysqli_query($dbc,"SELECT * FROM hoc_phi WHERE nien_khoa_id = {$nien_khoa} AND lop_hoc_id = {$khoi}");
        if(count(mysqli_fetch_all($query_check)) > 0) echo -3;
        else {
            $str = "INSERT INTO hoc_phi (nien_khoa_id, lop_hoc_id, so_tien, ngay_tao) VALUES ({$nien_khoa}, {$khoi}, {$hoc_phi}, NOW())";
            $query = mysqli_query($dbc, $str);
            if(mysqli_affected_rows($dbc) == 1) {
                echo 1;
            }
            else{
                echo -1;
            }
        }
    }
    else echo -2;
}