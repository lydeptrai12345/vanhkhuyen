<?php
include "../../inc/myconnect.php";
include "../../inc/myfunction.php";

if(isset($_GET['ten_lop']) && $_GET['check_lop']) {
    $reslut = mysqli_query($dbc, "SELECT * FROM lophoc_chitiet WHERE mo_ta = '{$_GET['ten_lop']}'");
    if(mysqli_fetch_array($reslut)) echo 1;
    else echo -1;
}


// Thêm mới lớp học
if (isset($_POST['add'])) {
    $ten_lop = $_POST['ten_lop'];
    $id_lop = $_POST['id_lop'];
    $id_nien_khoa = $_POST['id_nien_khoa'];
    $arr_nhan_vien = (array)$_POST['arr_nhan_vien'];

    $query = "INSERT INTO lophoc_chitiet (lop_hoc_id, nien_khoa_id, mo_ta) VALUES ({$id_lop}, {$id_nien_khoa}, '{$ten_lop}')";
    $reslut = mysqli_query($dbc, $query);
    if ($reslut) {
        if (is_array($arr_nhan_vien) && count($arr_nhan_vien) > 0) {
            // lấy id lớp học mới được thêm vào
            $id_lop_hoc_moi_them = mysqli_fetch_row(mysqli_query($dbc, "SELECT id FROM lophoc_chitiet ORDER BY id DESC LIMIT 1"));

            // thêm nhân viên vào lớp học
            for ($i = 0; $i < count($arr_nhan_vien); $i++) {
                $query_insert = "INSERT INTO lophoc_nhanvien (nhan_vien_id, lop_hoc_chi_tiet_id) VALUES ({$arr_nhan_vien[$i]}, {$id_lop_hoc_moi_them[0]})";

                mysqli_query($dbc, $query_insert);
            }
        }
        echo 1;
    } else echo $query;

}

// Xóa lớp học
if (isset($_POST['delete']) && isset($_POST['id_chi_tiet_lop_hoc'])) {
    $id = (int)$_POST['id_chi_tiet_lop_hoc'];

    // Kiểm tra lớp học có bé hay không nếu có thì ko cho xóa
    $sum_be = mysqli_fetch_row(mysqli_query($dbc, "SELECT COUNT(id) as sum_be FROM lophoc_be WHERE lop_hoc_chi_tiet_id = {$id}"));
    if ($sum_be[0] > 0) {
        echo -2;
    } else {
        $query = "DELETE FROM lophoc_chitiet WHERE id = {$id}";
        if (mysqli_query($dbc, $query)) echo 1;
        else echo -1;
    }

}

// Load chi tiết một lớp học
if (isset($_POST['load_info_item']) && isset($_POST['id_chi_tiet_lop_hoc'])) {
    $id = (int)$_POST['id_chi_tiet_lop_hoc'];
    //Load
    $data = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM lophoc_chitiet WHERE id = {$id}"));

    // Load thông tin nhân viên của lớp học
    $nhan_vien = (mysqli_query($dbc, "SELECT nhan_vien_id FROM lophoc_nhanvien WHERE lop_hoc_chi_tiet_id = {$id}"));
    $ar_nv = [];
    foreach ($nhan_vien as $item) {
        $ar_nv[] = $item['nhan_vien_id'];
    }
    $data['nv'] = $ar_nv;

    echo json_encode($data);
}

// Cập nhật lớp học
if (isset($_POST['edit'])) {
    $ten_lop = $_POST['ten_lop'];
    $id_lop = $_POST['id_lop'];
    $id_chi_tiet_lop = $_POST['id_chi_tiet_lop'];
    $id_nien_khoa = $_POST['id_nien_khoa'];
    $arr_nhan_vien = isset($_POST['arr_nhan_vien']) ? (array)$_POST['arr_nhan_vien'] : [];

    $query = "UPDATE lophoc_chitiet
              SET lop_hoc_id = {$id_lop}, nien_khoa_id = {$id_nien_khoa}, mo_ta = '{$ten_lop}'
              WHERE id = {$id_chi_tiet_lop} 
              ";
    $reslut = mysqli_query($dbc, $query);
    if ($reslut) {
        if (count($arr_nhan_vien) > 0) {

            // Xóa những nhân viên cũ trong lớp
            mysqli_query($dbc, "DELETE FROM lophoc_nhanvien WHERE lop_hoc_chi_tiet_id = {$id_chi_tiet_lop}");
            $query_xoa_nhan_vien = mysqli_affected_rows($dbc);

            // thêm nhân viên vào lớp học
            for ($i = 0; $i < count($arr_nhan_vien); $i++) {
                $query_insert = "INSERT INTO lophoc_nhanvien (nhan_vien_id, lop_hoc_chi_tiet_id) VALUES ({$arr_nhan_vien[$i]}, {$id_chi_tiet_lop})";

                mysqli_query($dbc, $query_insert);
            }
        }
        echo 1;
    } else echo -1;
}


//load danh sách bé theo lớp học
if(isset($_POST['get_list_be_theo_lop_hoc']) && isset($_POST['lop_hoc_id'])) {
    $query = mysqli_query($dbc, "SELECT * FROM be WHERE id IN (SELECT be_id FROM lophoc_be WHERE lophoc_be.lop_hoc_chi_tiet_id = {$_POST['lop_hoc_id']})");

    $result = array();

    if (mysqli_num_rows($query) > 0)
    {
        while ($row = mysqli_fetch_array($query)){
            $result[] = array(
                'id' => $row['id'],
                'mo_ta' => $row['ten']
            );
        }
    }
    echo json_encode($result);
}

// thêm mới niên khóa
if (isset($_POST['add_nien_khoa'])) {
    $date_start = isset($_POST['date_start']) ? (int)$_POST['date_start'] : 0;
    $date_end = isset($_POST['date_end']) ? (int)$_POST['date_end'] : 0;

    if ($date_end - $date_start == 1) {
        $ten_nien_khoa = $date_start . "-" . $date_end;

        // Kiểm tra niên khóa tồn tại hay chưa
        $old_nk = mysqli_query($dbc, "SELECT * FROM nienkhoa WHERE ten_nien_khoa = '{$ten_nien_khoa}'");
        if (count(mysqli_fetch_all($old_nk)) > 0) {
            echo -3;
        } else {
            mysqli_query($dbc, "INSERT INTO nienkhoa (ten_nien_khoa, nam_ket_thuc) VALUES ('{$ten_nien_khoa}', {$date_end})");
            if (mysqli_affected_rows($dbc) == 1) {
                echo 1;
            } else echo "INSERT INTO nienkhoa (ten_nien_khoa) VALUES ('{$ten_nien_khoa}')";
        }
    } else echo -2;
}

// Lấy danh sách bé theo lớp học
if(isset($_GET['load_list_be'])) {
    $id_lop = isset($_GET['id_lop']) ? (int)$_GET['id_lop'] : 0;

    if($id_lop > 0) {
        $str = "SELECT * FROM be INNER JOIN lophoc_be ON be.id = lophoc_be.be_id WHERE lophoc_be.lop_hoc_chi_tiet_id = {$id_lop}";
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
                    'sdtcha'   => $row['sdtcha'],
                    'sdtme'    => $row['sdtme'],
                    'diachi'   => $row['diachi'],
                    'chieucao' => $row['chieucao'],
                );
            }
        }
        echo json_encode($result);
    }
}
