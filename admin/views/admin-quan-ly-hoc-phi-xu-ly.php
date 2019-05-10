<?php
include "../../inc/myconnect.php";
include "../../inc/myfunction.php";

if(isset($_GET['load_list_hoc_phi'])) {
    $str = "SELECT	hoc_phi.id as 'hoc_phi_id', lop_hoc_id, nien_khoa_id, so_tien, ngay_tao, ten_nien_khoa, ten_lop FROM hoc_phi 
            INNER JOIN nienkhoa ON hoc_phi.nien_khoa_id = nienkhoa.id 
            INNER JOIN lophoc ON hoc_phi.lop_hoc_id = lophoc.id 
            ORDER BY hoc_phi.id DESC";
    $query = mysqli_query($dbc, $str);
    $result = array();

    if (mysqli_num_rows($query) > 0)
    {
        $index = 1;
        while ($row = mysqli_fetch_array($query)){
            $result[] = array (
                'hoc_phi_id'    => $row['hoc_phi_id'],
                'lop_hoc_id'    => $row['lop_hoc_id'],
                'ngay_tao'      => date_format(date_create($row['ngay_tao']),'d/m/Y'),
                'nien_khoa_id'  => $row['nien_khoa_id'],
                'so_tien'       => number_format((float)$row['so_tien']),
                'ten_nien_khoa' => $row['ten_nien_khoa'],
                'ten_lop'       => $row['ten_lop']
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


// ĐÓng tiền học phí

if(isset($_GET['danh_sach_hoc_phi'])) {
    $nien_khoa = $_GET['loc_nien_khoa'];
    $lop = (isset($_GET['loc_lop_hoc']) && $_GET['loc_lop_hoc'] > 0) ? $_GET['loc_lop_hoc'] : 0;
    $str = "SELECT
            *,
            (SELECT so_tien FROM hoc_phi WHERE hoc_phi.nien_khoa_id = {$nien_khoa} AND hoc_phi.lop_hoc_id = c.lop_hoc_id LIMIT 1) AS 'hoc_phi',	
            (SELECT ngay_thanh_toan FROM hoc_phi_chi_tiet WHERE hoc_phi_chi_tiet.be_id = b.id LIMIT 1) as 'ngay_thanh_toan'
            FROM
                be AS b
                INNER JOIN lophoc_be AS l ON b.id = l.be_id
                INNER JOIN lophoc_chitiet AS c ON l.lop_hoc_chi_tiet_id = c.id
                INNER JOIN nienkhoa as n ON n.id = c.nien_khoa_id
                WHERE n.id = {$nien_khoa} ";
    if ($lop > 0) $str .= "AND l.lop_hoc_chi_tiet_id = {$lop}";
    $str .= "GROUP BY b.id";

    $query = mysqli_query($dbc, $str);
    $result = array();

    if (mysqli_num_rows($query) > 0)
    {
        $index = 1;
        while ($row = mysqli_fetch_array($query)){
            $result[] = array (
                'lop_hoc_id'    => $row['lop_hoc_id'],
                'ngay_thanh_toan' => ($row['ngay_thanh_toan']) ? date_format(date_create($row['ngay_thanh_toan']),'d/m/Y') : "",
                'nien_khoa_id'  => $row['nien_khoa_id'],
                'hoc_phi'       => number_format((float)$row['hoc_phi']),
                'ten_nien_khoa' => $row['ten_nien_khoa'],
                'be_id'    => $row['be_id'],
                'ten'      => $row['ten'],
                'ngaysinh' => ($row['ngaysinh']) ? date_format(date_create($row['ngaysinh']),'d/m/Y') : 0,
                'gioitinh' => ($row['gioitinh'] == 1) ? "Nam" : "Nữ",
                'tencha'  => $row['tencha'],
                'sdtcha'   => $row['sdtcha'],
                'tenme'   => $row['tenme'],
                'sdtme'    => $row['sdtme'],
                'diachi'   => $row['diachi'],
                'chieucao' => $row['chieucao'],
                'mo_ta'    => $row['mo_ta'],
                'matracuu'    => $row['matracuu'],
                'lop_hoc_chi_tiet_id'    => $row['lop_hoc_chi_tiet_id'],
                'trangthai'    => ($row['ngay_thanh_toan']) ? 1 : 0,
            );
        }
    }
    echo json_encode($result);
}

// Đóng tiền học phí
if(isset($_POST['dong_tien'])) {
    $so_tien = str_replace(",","", $_POST['so_tien']);
    $be_id = $_POST['be_id'];
    $lop_hoc_chi_tiet = $_POST['lop_hoc_chi_tiet'];
    $nhan_vien = $_POST['nhan_vien'];
    $str = "INSERT INTO hoc_phi_chi_tiet (so_tien, be_id, nhan_vien_id, lop_hoc_chi_tiet_id, ngay_thanh_toan) VALUES ({$so_tien}, {$be_id}, {$nhan_vien}, {$lop_hoc_chi_tiet}, NOW())";
//    echo $str;
    $result = mysqli_query($dbc, $str);
    if(mysqli_affected_rows($dbc) == 1) {
        echo 1;
    }
    else{
        echo -1;
    }
}