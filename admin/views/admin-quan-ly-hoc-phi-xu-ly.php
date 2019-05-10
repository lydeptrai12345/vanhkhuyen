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