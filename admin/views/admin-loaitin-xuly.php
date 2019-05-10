<?php


include "../../inc/myconnect.php";
include "../../inc/myfunction.php";


if (isset($_GET['load_list_loaitin'])) {
    $str = "SELECT * FROM loaitin as a";
    $query = mysqli_query($dbc, $str);
    $result = array();

    if (mysqli_num_rows($query) > 0) {
        $index = 1;
        while ($row = mysqli_fetch_array($query)) {
            $result[] = array(
                'id' => $row['id'],
                'ten' => $row['ten'],
                'the_loai_cha' => $row['the_loai_cha'],
                'ten_cha' => ($row['the_loai_cha'] == 0) ? 'Không có' : lay_cha_cua_loai_tin($dbc, $row['the_loai_cha'])
            );
        }
    }
    echo json_encode($result);
}

function lay_cha_cua_loai_tin($dbc, $id){
    $resu = mysqli_query($dbc, "SELECT * FROM loaitin WHERE id = {$id}");
    $a = mysqli_fetch_object($resu);
    return $a->ten;
}