<?php

function show_news($parent_id="0",$insert_text="-")
{
	global $dbc;
	$query_dq="SELECT * FROM loaitin WHERE the_loai_cha=".$parent_id." ORDER BY the_loai_cha DESC";
	$news=mysqli_query($dbc,$query_dq);
	while($new=mysqli_fetch_array($news,MYSQLI_ASSOC))
	{
		echo("<option value='".$new["id"]."'>".$insert_text.$new['ten']."</option>");
		show_news($new["id"],$insert_text."-");
	}
	return true;
}
function selectCtrl()
{
	global $dbc;
	show_news();
	echo "</select>";
}
//thể loại Tin tức sửa
function show_news_e($uid,$parent_id1="0",$insert_text1="-")
{
	global $dbc;
	$query_dq1="SELECT DISTINCT * FROM loaitin where the_loai_cha=".$parent_id1."";	
	$news=mysqli_query($dbc,$query_dq1);
	while($new=mysqli_fetch_array($news,MYSQLI_ASSOC))
	{				
		if($uid==$new["id"])
		{			
			echo("<option selected='selected' value='".$new["id"]."'>".$insert_text1.$new['ten']."</option>");
		}
		else
		{
			echo("<option value='".$new["id"]."'>".$insert_text1.$new['ten']."</option>");
		}
		show_news_e($uid,$new["id"],$insert_text1."-");
	}
	return true;
}
function selectCtrl_e($uid)
{
	global $dbc;
	show_news_e($uid);
	echo "</select>";
}
function formatCurrencyCustom( $text ) {
	if ( strlen( $text ) > 3 ) {
		for ( $i = strlen( $text ) - 3; $i > 0; $i -= 3 ) {
			$text = substr( $text, 0, $i ) . "." . substr( $text, $i );
		}
	}
	return $text;
}

function randomDigitsLame($numDigits) {
    $digits = '';

    for ($i = 0; $i < $numDigits; ++$i) {
        $digits .= mt_rand(0, 9);
    }

    return $digits;
}
function getAge($birthDate){
	$birthDate = explode("-", $birthDate);
  	$age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md")
		? ((date("Y") - $birthDate[0]) - 1)
		: (date("Y") - $birthDate[0]));
	if($age<=0)
		$age = 1;
	else if($age>=1)
		$age = $age +1;
	return $age;
}

function lay_thong_tin_lop_hoc_cua_be ($dbc, $be_id) {
    $str = "SELECT be_id, lophoc_chitiet.nien_khoa_id,ten_nien_khoa, lophoc_be.lop_hoc_chi_tiet_id, lophoc_chitiet.mo_ta, lophoc_chitiet.lop_hoc_id as 'khoi',
            (SELECT so_tien FROM hoc_phi WHERE hoc_phi.nien_khoa_id = lophoc_chitiet.nien_khoa_id AND hoc_phi.lop_hoc_id = lophoc_chitiet.lop_hoc_id LIMIT 1) AS 'hoc_phi',
            (SELECT ngay_thanh_toan FROM hoc_phi_chi_tiet WHERE hoc_phi_chi_tiet.be_id = {$be_id} LIMIT 1) as 'ngay_thanh_toan'
            FROM lophoc_be 
            INNER JOIN lophoc_chitiet ON lophoc_be.lop_hoc_chi_tiet_id = lophoc_chitiet.id 
            INNER JOIN nienkhoa ON lophoc_chitiet.nien_khoa_id = nienkhoa.id
            WHERE be_id = {$be_id}";
    $query = mysqli_query($dbc, $str);
    $result = array();

    if (mysqli_num_rows($query) > 0)
    {
        while ($row = mysqli_fetch_array($query)){
            $result[] = array(
                'be_id' => $row['be_id'],
                'lop_hoc_chi_tiet_id' => $row['lop_hoc_chi_tiet_id'],
                'nien_khoa_id' => $row['nien_khoa_id'],
                'ten_nien_khoa' => $row['ten_nien_khoa'],
                'mo_ta' => $row['mo_ta'],
                'hoc_phi' => $row['hoc_phi'],
                'ngay_thanh_toan' => $row['ngay_thanh_toan'],
            );
        }
    }
    return json_encode($result);
}

function kiem_tra_quyen_nguoi_dung($chuc_nang_id) {
    $arr_quyen = $_SESSION['phan_quyen'];
    if(is_array($arr_quyen) && count($arr_quyen) > 0) {
        $idx = array_search($chuc_nang_id, array_column($arr_quyen, 'id_chuc_nang'));
        if($idx >= 0) {
            return $arr_quyen[$idx];
        }
        else{
            echo '<script>alert("Bạn không có quyền truy cập chức năng này!")</script>';
            header('Location: login.php');
        }
    }
    else{
        header('Location: login.php');
    }
}

?>