<?php include "admin-header.php";?>
<?php include "../../inc/myconnect.php";?>
<?php include "../../inc/myfunction.php";?>
<!-- End header-->

<link rel="stylesheet" href="../../library/datepicker/bootstrap-datepicker.css">
<script src="../../library/datepicker/bootstrap-datepicker.js"></script>
<script src="../../library/datepicker/moment.js"></script>

<script>
	$( document ).ready( function () {
		$( '#heading5 .panel-heading' ).attr( 'aria-expanded', 'true' );
		$( '#collapse5' ).addClass( 'show' );
		$( '#collapse5 .list-group a:nth-child(1)' ).addClass( 'cus-active' );
	} );
</script>

<!-- LOAD DỮ LIỆU -->
<?php
    $data_lop_hoc = mysqli_query($dbc,"SELECT lophoc_chitiet.id, lophoc_chitiet.mo_ta, lophoc.id AS 'khoi_id' FROM lophoc_chitiet INNER JOIN lophoc ON lophoc_chitiet.lop_hoc_id = lophoc.id");

    // lấy danh sách niên khóa
    $data_nien_khoa = mysqli_query($dbc,"SELECT * FROM nienkhoa ORDER BY nam_ket_thuc DESC");

    // tinhs nieen khoa hien tai month > 6 && month
    $year = date("Y");
    if(date("m") > 6)
        $nien_khoa_hien_tai = $year . "-" . $year + 1;
    else
        $nien_khoa_hien_tai = ($year - 1) . "-" . $year;
?>

<!-- Page content-->
<div class="main-content-container container-fluid px-4">
	<!-- Page Header -->
	<div class="page-header row no-gutters py-4">
		<div class="col-12 col-sm-4 text-center text-sm-left mb-0">
			<span class="text-uppercase page-subtitle">Quản lý</span>
			<h3 class="page-title">Đào tạo</h3>
		</div>
	</div>
	<!-- End Page Header -->

	<!-- Default Light Table -->
	<div class="row">
		<div class="col">
			<div class="card card-small mb-4" style="padding: 40px 60px">
                <div class="row card-header" style="padding-left: 0">
                    <h5 class="text-info">THÊM MỚI THÔNG TIN CỦA BÉ</h5>
                </div>

                <div class="card-body">
                    <?php
                    if (isset($_POST['btn-submit-be'])) {
                        $errors = array();
                        $have_dad = false;
                        $have_mom = false;
                        if ( empty( $_POST[ 'txtTenBe' ] ) ) {
                            $errors[] = 'txtTenBe';
                        } else {
                            $name = $_POST[ 'txtTenBe' ];
                        }
                        if ( isset($_POST[ 'txtNgaySinh' ]) && empty( $_POST[ 'txtNgaySinh' ] ) ) {
                            $errors[] = 'txtNgaySinh';
                        } else {
                            $ngaysinh = $_POST[ 'txtNgaySinh' ];
                        }
                        if ( empty( $_POST[ 'txtDiaChi' ] ) ) {
                            $errors[] = 'txtDiaChi';
                        } else {
                            $diachi = $_POST[ 'txtDiaChi' ];
                        }

                        if(empty($_POST['temp-image-input'])){
                            if($_FILES['hinhBe']['size'] == 0) {
                                $errors[] = 'hinhBe';
                                $mesErrorHinhBe = "Bạn chưa chọn ảnh bé";
                            } else if ( ( $_FILES[ 'hinhBe' ][ 'type' ] != "image/gif" ) &&
                                ( $_FILES[ 'hinhBe' ][ 'type' ] != "image/png" ) &&
                                ( $_FILES[ 'hinhBe' ][ 'type' ] != "image/jpeg" ) &&
                                ( $_FILES[ 'hinhBe' ][ 'type' ] != "image/jpg" ) ) {
                                if(!in_array('hinhBe',$errors))
                                    $errors[] = 'hinhBe';
                                $mesErrorHinhBe = "File không đúng định dạnh";
                            } else if ( $_FILES[ 'hinhBe' ][ 'size' ] > 1000000 ) {
                                if(!in_array('hinhBe',$errors))
                                    $errors[] = 'hinhBe';
                                $mesErrorHinhBe = "Kich thước phải nhỏ hơn 1MB";
                            } else {
                                $temp_file = "../images/temp-image/".$_FILES[ 'hinhBe' ]['name'];
                                copy($_FILES[ 'hinhBe' ]['tmp_name'],$temp_file);
                            }
                        }
                        else{
                            $temp_file = $_POST['temp-image-input'];
                        }
                        if(is_numeric($_POST[ 'txtChieuCao' ])){
                            if($_POST[ 'txtChieuCao' ]<=0)
                                $errors[] = 'txtChieuCao';
                            else{
                                $chieucao = $_POST[ 'txtChieuCao' ];
                            }
                        }
                        else{
                            $errors[] = 'txtChieuCao';
                        }
                        if(is_numeric($_POST[ 'txtCanNang' ])){
                            if($_POST[ 'txtCanNang' ]<=0)
                                $errors[] = 'txtCanNang';
                            else{
                                $cannang = $_POST[ 'txtCanNang' ];
                            }
                        }
                        else{
                            $errors[] = 'txtCanNang';
                        }

                        if ( !empty( $_POST[ 'txtTenCha' ]) || !empty( $_POST[ 'txtSDTCha' ] )){
                            if ( empty( $_POST[ 'txtTenCha' ] ) ) {
                                $errors[] = 'txtTenCha';
                            } else {
                                $tencha = $_POST[ 'txtTenCha' ];
                            }
                            if ( is_numeric( $_POST[ 'txtSDTCha' ] ) ) {
                                if ( strlen( $_POST[ 'txtSDTCha' ] ) != 10 )
                                    $errors[] = 'txtSDTCha2';
                                else
                                    $sdtCha = $_POST[ 'txtSDTCha' ];
                            } else {
                                $errors[] = 'txtSDTCha';
                            }
                            if(isset($tencha) && isset($sdtCha)){
                                $have_dad = true;
                            }
                        }

                        if ( !empty( $_POST[ 'txtTenMe' ]) || !empty( $_POST[ 'txtSDTMe' ] )){
                            if ( empty( $_POST[ 'txtTenMe' ] ) ) {
                                $errors[] = 'txtTenMe';
                            } else {
                                $tenme = $_POST[ 'txtTenMe' ];
                            }
                            if ( is_numeric( $_POST[ 'txtSDTMe' ] ) ) {
                                if ( strlen( $_POST[ 'txtSDTMe' ] ) != 10 )
                                    $errors[] = 'txtSDTMe2';
                                else
                                    $sdtMe = $_POST[ 'txtSDTMe' ];
                            } else {
                                $errors[] = 'txtSDTMe';
                            }
                            if(isset($tenme) && isset($sdtMe)){
                                $have_mom = true;
                            }
                        }
                        if(!$have_dad && !$have_mom){
                            $errors[] = 'parents';
                        }

                        if (!$_POST['nien_khoa']) {
                            $errors[] = 'nien_khoa';
                        }

                        if (!isset($_POST['lop_hoc']) || !$_POST['lop_hoc']) {
                            $errors[] = 'lop_hoc';
                        }

                        if ( empty( $errors )) {
                            if(!$have_dad){
                                $tencha = '';
                                $sdtCha = '';
                            }
                            if(!$have_mom){
                                $tenme = '';
                                $sdtMe = '';
                            }
                            $tinhtrangsuckhoe = $_POST['txtSucKhoe'];
                            $benh = $_POST['txtBenhBS'];
                            $gioitinh = $_POST[ 'slGioiTinh' ];
                            $trangthai = 1;

                            $fileName = randomDigitsLame(4).'_'.randomDigitsLame(14).substr($temp_file,strrpos($temp_file, '.', -0));
                            copy($temp_file,"../images/hinhbe/".$fileName);

                            $ngaysinh = date_format(date_create($ngaysinh), 'Y-m-d');

                            $query_tt = "INSERT INTO be(ten, ngaysinh, gioitinh, chieucao, cannang, diachi, tinhtrangsuckhoe, benhbamsinh ,hinhbe , tencha, sdtcha, tenme, sdtme, trangthai) VALUES('{$name}','{$ngaysinh}',{$gioitinh},'{$chieucao}','{$cannang}','{$diachi}','{$tinhtrangsuckhoe}','{$benh}','{$fileName}','{$tencha}','{$sdtCha}','{$tenme}','{$sdtMe}',$trangthai) ";
                            $results_tt = mysqli_query( $dbc, $query_tt );
                            if ( mysqli_affected_rows( $dbc ) == 1 ) {
                                // lấy id bé mới được thêm vào
                                $data_be = mysqli_fetch_row(mysqli_query($dbc, "SELECT id FROM be ORDER BY id DESC LIMIT 1"));
                                $lop_hoc_chi_tiet_id = $_POST['lop_hoc'];
                                if($data_be > 0 && $lop_hoc_chi_tiet_id){
                                    $insert_vao_lop_hoc = mysqli_query($dbc, "INSERT INTO lophoc_be (be_id, lop_hoc_chi_tiet_id) VALUES ({$data_be[0]}, {$lop_hoc_chi_tiet_id})");

                                    if($_POST['thanh_toan'] == 1){
                                        $hoc_phi = str_replace(",","", $_POST['hoc_phi']);
                                        $q = "INSERT INTO hoc_phi_chi_tiet (be_id, lop_hoc_chi_tiet_id, nhan_vien_id, ngay_thanh_toan, so_tien) VALUES ({$data_be[0]}, {$lop_hoc_chi_tiet_id}, {$_SESSION['uid']}, NOW(), {$hoc_phi})";
                                        mysqli_query($dbc, $q);
                                    }
                                }
                                ?>
                                <script>
                                    alert( "Thêm thành công" );
                                    <?php //header('location: admin-be.php') ?>
                                </script>
                                <?php
                            } else {
                                echo "<script>";
                                echo 'alert("Thêm không thành công")';
                                echo "</script>";
                            }
                        } else {
                            $message = "<p class='text-danger'>Bạn hãy nhập đầy đủ thông tin</p>";
                        }
                    }
                    if ( isset( $message ) ) {
                        echo $message;
                    }
                    ?>
                    <form id="form-them-be" action="" method="post" enctype="multipart/form-data" style="margin-top: 0px">
                        <script>
                            function showPreview( objFileInput ) {
                                var filename = objFileInput.value.split('.').pop().toLowerCase();
                                var fileTypes = ['jpg', 'jpeg', 'png', 'gif'];
                                isValid = fileTypes.indexOf(filename) > -1;
                                if (isValid) {
                                    //if ( objFileInput.files[ 0 ] ) {
                                    var fileReader = new FileReader();
                                    fileReader.onload = function ( e ) {
                                        $( "#targetLayer" ).addClass( 'have-photo' );
                                        $( "#targetLayer" ).css( 'background-image', 'url("' + e.target.result + '")' );
                                        sessionStorage.setItem('have-photo',e.target.result);
                                        $( '#targetLayer .glyphicon' ).fadeOut();
                                        $('input[name="temp-image-input"]').val('');
                                    }
                                    fileReader.readAsDataURL( objFileInput.files[ 0 ] );
                                }
                            };
                            function loadPhoto(url){
                                $( "#targetLayer" ).addClass( 'have-photo' );
                                $( "#targetLayer" ).css( 'background-image', 'url("' + url + '")' );
                                $( '#targetLayer .glyphicon' ).fadeOut();
                            }
                        </script>
                        <style>
                            #targetLayer {
                                width: 250px;
                                height: 320px;
                                margin: 20px auto 0 auto;
                                border: 5px solid skyblue;
                                border-radius: 9px;
                                background-repeat: no-repeat;
                                background-position: center;
                                background-size: cover;
                                background-color: #f4f5f757;
                                position: relative;
                            }

                            #targetLayer .glyphicon {
                                position: absolute;
                                top: 108px;
                                left: 75px;
                                font-size: 65pt;
                                text-align: center;
                                color: white;
                                cursor: pointer;
                                text-shadow: 0 1px 4px rgba(0, 0, 0, 0.5);
                                transform-origin-x: center;
                                -webkit-transform-origin-x: center;
                            }

                            #targetLayer .glyphicon:hover {
                                transform: scale(1.02);
                                text-shadow: 0 1px 6px rgba(0, 0, 0, 0.5);
                            }

                            #targetLayer button:active .glyphicon {
                                transform: scale(.98);
                            }

                            #targetLayer .input {
                                position: absolute;
                                top: 100px;
                                left: 66px;
                                width: 60px;
                                height: 60px;
                                overflow: hidden;
                                opacity: 0;
                            }

                            #targetLayer input {
                                display: none;
                            }

                            .dot-required {
                                color: red;
                            }

                            .parent-zone {
                                margin: 35px -30px 15px -30px;
                                padding: 30px 15px 10px 15px;
                                border-radius: 5px;
                                box-shadow: 0 .5px 4px rgba(0,0,0,0.2);
                                position: relative;
                                background-color: #f4f5f757;
                            }
                            .parent-zone .title-absolute span{
                                position: absolute;
                                top: -16px;
                                left: 15px;
                                background: white;
                                color: skyblue;
                                text-transform: uppercase;
                                padding: 4px 10px;
                                font-size: 14px;
                                font-weight: 500;
                                /*								border-radius: 3px;*/
                                border: 2px solid skyblue;
                                /*								box-shadow: 0 .5px 2px rgba(0,0,0,.3);*/
                            }
                            .footer-detail{
                                float: right;
                                color: dimgray;
                                font-size: 11px;
                            }
                        </style>

                        <div class="row parent-zone" style="margin-top: 0">
                            <div class="title-absolute"><span>Thông tin bé</span></div>
                            <div class="row w-100">
                                <div class="col-8">
                                    <div class="form-group">
                                        <label>Họ và tên bé <span class="dot-required">*</span></label>
                                        <input class="form-control" name="txtTenBe" placeholder="Vui lòng nhập tên bé" value="<?php if(isset($_POST['txtTenBe'])) {echo $_POST['txtTenBe'];} ?>">
                                        <?php
                                        if(isset($errors) && in_array('txtTenBe',$errors))
                                        {
                                            echo "<p class='text-danger'>Bạn chưa nhập tên bé</p>";
                                        }
                                        ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Ngày sinh <span class="dot-required">*</span></label>

                                        <input type="text" autocomplete="off" class="form-control txtNgaySinh" name="txtNgaySinh" placeholder="Vui lòng nhập ngày sinh" value="<?php if(isset($_POST['txtNgaySinh'])) {echo $_POST['txtNgaySinh'];} ?>">
                                        <?php
                                            if(isset($errors) && in_array('txtNgaySinh',$errors))
                                            {
                                                echo "<p class='text-danger'>Bạn chưa nhập ngày sinh bé</p>";
                                            }
                                        ?>
                                        <p class='error_tuoi err_7tuoi text-danger' style="display: none">Bé không được lớn hơn 7 tuổi</p>
                                        <p class='error_tuoi err_6thang text-danger' style="display: none">Bé không được nhỏ hơn 6 tháng tuổi</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Giới tính  <span class="dot-required">*</span></label>
                                        <select class="form-control" name="slGioiTinh">
                                            <option value="1">Nam</option>
                                            <option value="2">Nữ</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Chiều cao(cm) <span class="dot-required">*</span></label>
                                        <input type="number" class="form-control" name="txtChieuCao" placeholder="Vui lòng nhập chiều cao của bé" value="<?php if(isset($_POST['txtChieuCao'])) {echo $_POST['txtChieuCao'];} ?>">
                                        <?php
                                        if(isset($errors) && in_array('txtChieuCao',$errors))
                                        {
                                            echo "<p class='text-danger'>Chiều cao bé không hợp lệ</p>";
                                        }
                                        ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Cân nặng(kg) <span class="dot-required">*</span></label>
                                        <input type="number" class="form-control" name="txtCanNang" placeholder="Vui lòng nhập cân nặng của bé" value="<?php if(isset($_POST['txtCanNang'])) {echo $_POST['txtCanNang'];} ?>">
                                        <?php
                                        if(isset($errors) && in_array('txtCanNang',$errors))
                                        {
                                            echo "<p class='text-danger'>Cân nặng bé không hợp lệ</p>";
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="col-3" style="margin-right: -25px">
                                    <div>
                                        <div id="targetLayer">
                                            <button type="button"><span class="glyphicon glyphicon-picture"></button>
                                            </span><input type="file" id="hinhBe" name="hinhBe"
                                                          onChange="showPreview(this);"></div>
                                        <?php if (isset($temp_file) && !empty($temp_file)) echo '<script>loadPhoto(sessionStorage.getItem("have-photo"));</script>' ?>
                                        <input type="hidden" name='temp-image-input'
                                               value="<?php if (isset($temp_file) && !empty($temp_file)) echo $temp_file; else echo ''; ?>"/>
                                        <p class='text-danger'
                                           style="margin: 5% auto 0 auto; display: <?php if (isset($errors) && in_array('hinhBe', $errors)) echo 'table'; else echo 'none'; ?>"><?php if (isset($errors) && in_array('hinhBe', $errors)) echo $mesErrorHinhBe; ?></p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Địa chỉ <span class="dot-required">*</span></label>
                                        <input class="form-control" name="txtDiaChi" placeholder="Vui lòng nhập địa chỉ" value="<?php if(isset($_POST['txtDiaChi'])) {echo $_POST['txtDiaChi'];} ?>">
                                        <?php
                                        if(isset($errors) && in_array('txtDiaChi',$errors))
                                        {
                                            echo "<p class='text-danger'>Bạn chưa nhập địa chỉ</p>";
                                        }
                                        ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Tình trạng sức khoẻ</label>
                                        <textarea rows="3" class="form-control" name="txtSucKhoe" placeholder="Vui lòng nhập tình trạng sức khoẻ"><?php if(isset($_POST['txtSucKhoe'])) {echo $_POST['txtSucKhoe'];}?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Bệnh bẩm sinh(nếu có)</label>
                                        <textarea rows="3" class="form-control" name="txtBenhBS" placeholder="Vui lòng nhập bệnh bẩm sinh"><?php if(isset($_POST['txtBenhBS'])) {echo $_POST['txtBenhBS'];} ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row parent-zone">
                            <div class="title-absolute"><span>Thông tin phụ huynh</span></div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Tên cha</label>
                                    <input class="form-control" name="txtTenCha" placeholder="Vui lòng nhập tên cha" value="<?php if(isset($_POST['txtTenCha'])) {echo $_POST['txtTenCha'];} ?>">
                                    <?php
                                        if(isset($errors) && in_array('txtTenCha',$errors))
                                        {
                                            echo "<p class='text-danger'>Bạn chưa nhập tên cha</p>";
                                        }
                                    ?>
                                </div>
                                <div class="form-group">
                                    <label>Số điện thoại cha</label>
                                    <input class="form-control" maxlength="10" name="txtSDTCha" placeholder="Vui lòng nhập số điện thoại cha" value="<?php if(isset($_POST['txtSDTCha'])) {echo $_POST['txtSDTCha'];} ?>">
                                    <?php
                                        if(isset($errors) && in_array('txtSDTCha',$errors))
                                        {
                                            echo "<p class='text-danger'>Vui lòng nhập số điện thoại cha</p>";
                                        }
                                        if(isset($errors) && in_array('txtSDTCha2',$errors))
                                        {
                                            echo "<p class='text-danger'>Số điện thoại hợp lệ có 10 số</p>";
                                        }
                                    ?>
                                </div>
                                <?php
                                if(isset($errors) && in_array('parents',$errors))
                                {
                                    echo "<div class='footer-detail text-danger' style='float:left;font-size:13px;'><span>Bắt buộc phải nhập cha hoặc mẹ</span></div>";
                                }?>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Tên mẹ</label>
                                    <input class="form-control" name="txtTenMe" placeholder="Vui lòng nhập tên mẹ" value="<?php if(isset($_POST['txtTenMe'])) {echo $_POST['txtTenMe'];} ?>">
                                    <?php
                                    if(isset($errors) && in_array('txtTenMe',$errors))
                                    {
                                        echo "<p class='text-danger'>Bạn chưa nhập tên mẹ</p>";
                                    }
                                    ?>
                                </div>
                                <div class="form-group" style="margin-bottom: 7px;">
                                    <label>Số điện thoại mẹ</label>
                                    <input class="form-control" maxlength="10" name="txtSDTMe" placeholder="Vui lòng nhập số điện thoại mẹ" value="<?php if(isset($_POST['txtSDTMe'])) {echo $_POST['txtSDTMe'];} ?>">
                                    <?php
                                        if(isset($errors) && in_array('txtSDTMe',$errors))
                                        {
                                            echo "<p class='text-danger'>Vui lòng nhập số điện thoại mẹ</p>";
                                        }
                                        if(isset($errors) && in_array('txtSDTMe2',$errors))
                                        {
                                            echo "<p class='text-danger'>Số điện thoại hợp lệ có 10 số</p>";
                                        }
                                    ?>
                                </div>
                                <div class="footer-detail"><span>* Cho phép chỉ nhập cha hoặc mẹ</span></div>
                            </div>
                        </div>

                        <!-- =========================== THÔNG TIN LOP HOC ===============================-->
                        <div class="row parent-zone">
                            <div class="title-absolute"><span>Thông Lớp học</span></div>
                            <div class="row w-100">
                                <div class="col-md-3 col-3">
                                    <div class="form-group">
                                        <label>Niên khóa</label>
                                        <select name="nien_khoa" id="" class="form-control">
                                            <option value="0">Chọn Niên khóa</option>
                                            <?php foreach ($data_nien_khoa as $item):?>
                                                <option <?php if($nien_khoa_hien_tai == $item['ten_nien_khoa']) echo "selected"?>
                                                        data-nam-ket-thuc="<?php echo $item['nam_ket_thuc'];?>"
                                                        value="<?php echo $item['id']?>"><?php echo $item['ten_nien_khoa']?>
                                                </option>
                                            <?php endforeach;?>
                                        </select>

                                        <?php
                                        if(isset($errors) && in_array('nien_khoa',$errors))
                                        {
                                            echo "<p class='text-danger'>Bạn chưa chọn niên khóa</p>";
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Lớp học</label>
                                        <select name="lop_hoc" id="" class="form-control" disabled>
                                            <option value="0">Chọn lớp học</option>
                                            <?php foreach ($data_lop_hoc as $item):?>
                                                <option data-khoi="<?php echo $item['khoi_id']?>" value="<?php echo $item['id']?>"><?php echo $item['mo_ta']?></option>
                                            <?php endforeach;?>
                                        </select>

                                        <?php
                                        if(isset($errors) && in_array('lop_hoc',$errors))
                                        {
                                            echo "<p class='text-danger'>Bạn chưa chọn niên khóa</p>";
                                        }
                                        ?>

                                        <p class='error_lop text-danger' style="display: none"></p>
                                    </div>
                                </div>

                                <div class="col-3 div_hoc_phi" style="display: none">
                                    <div class="form-group">
                                        <label for="">Học phí</label>
                                        <input name="hoc_phi" class="form-control" type="text" readonly>
                                    </div>
                                </div>

                                <div class="col-2 div_thanh_toan" style="display: none">
                                    <div class="form-group">
                                        <label for="">Thanh toán</label>
                                        <br>
                                        <div class="">
                                            <a class="btn-thanh-toan" style="cursor: pointer">
                                                <i class="material-icons action-icon">check_box_outline_blank</i>
                                            </a>
                                            <input value="0" type="hidden" name="thanh_toan">
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- =========================== END THÔNG TIN LOP HOC ===========================-->

                        <div class="row card-footer" style="padding-left: 0">
                            <button id="submit-be" name="btn-submit-be" type="submit" class="btn btn-info" style="margin-right: 5px;">Thêm Thông Tin</button>
                            <a href="admin-be.php" class="btn btn-warning formatCurrency">Quay về</a>
                        </div>
                    </form>
                </div>
				<!-- End thêm loại tin -->
			</div>
		</div>
	</div>
	<!-- End Default Light Table -->
</div>
<!-- End page content-->


<script>
    Number.prototype.format = function(n, x) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
        return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
    };
    $('select[name="nien_khoa"]').change(function () {
        get_data_lop_hoc_theo_nien_khoa($(this).val());
    });

    function check_tuoi(){
        var id_khoi = $('select[name="lop_hoc"]').children('option:selected').data('khoi');
        var id_lop = $('select[name="lop_hoc"]').children('option:selected').val();

        var d = $('.txtNgaySinh').val().split('-');
        var new_date = d[2] + '-' + d[1] + '-' + d[0];

        var fromDate = new Date(new_date);
        var toDate = new Date();

        var date = Date.getFormattedDateDiff(fromDate, toDate);

        var arr_compare_date = date.split(',');

        if(Number(arr_compare_date[1]) < 6 && Number(arr_compare_date[0] < 1)) {
            alert('Bé không được nhỏ hơn 6 tháng tuổi');
            $('#submit-be').attr('disabled', 'disabled');
        }
        else if(Number(arr_compare_date[0]) > 7) {
            // alert('Bé không được lớn hơn 7 tuổi');
            $('.error_tuoi').show();
            $('.err_6thang').hide();
            $('#submit-be').attr('disabled', 'disabled');
        }
        else{
            if(arr_compare_date[0] < 3) {
                if(id_khoi != 4){
                    // alert('Tuổi của bé chỉ phù hợp với lớp ở khối nhà trẻ');
                    $('.error_lop').text('Tuổi của bé chỉ phù hợp với lớp ở khối nhà trẻ');
                    $('.error_lop').show();
                    $('#submit-be').attr('disabled', 'disabled');
                    return;
                }
            }
            else if(arr_compare_date[0] == 3){
                if(id_khoi != 1){
                    // alert('Tuổi của bé chỉ phù hợp với lớp ở khối Mầm');
                    $('.error_lop').text('Tuổi của bé chỉ phù hợp với lớp ở khối Mầm');
                    $('.error_lop').show();
                    $('#submit-be').attr('disabled', 'disabled');
                    return;
                }
            }
            else if(arr_compare_date[0] == 4){
                if(id_khoi != 2){
                    // alert('Tuổi của bé chỉ phù hợp với lớp ở khối Trồi');
                    $('.error_lop').text('Tuổi của bé chỉ phù hợp với lớp ở khối Trồi');
                    $('.error_lop').show();
                    $('#submit-be').attr('disabled', 'disabled');
                    return;
                }
            }
            else if(arr_compare_date[0] == 5){
                if(id_khoi != 3){
                    // alert('Tuổi của bé chỉ phù hợp với lớp ở khối Lá');
                    $('.error_lop').text('Tuổi của bé chỉ phù hợp với lớp ở khối Lá');
                    $('.error_lop').show();
                    $('#submit-be').attr('disabled', 'disabled');
                    return;
                }
            }

            $('.error_lop').hide();
            $('#submit-be').removeAttr('disabled');

        }
    }


    Date.getFormattedDateDiff = function(date1, date2) {
        var b = moment(date1),
            a = moment(date2),
            intervals = ['years','months','weeks','days'],
            out = [];

        for(var i=0; i<intervals.length; i++){
            var diff = a.diff(b, intervals[i]);
            b.add(diff, intervals[i]);
            // out.push(diff + '-' + intervals[i]);
            out.push(diff);
        }
        return out.join(',');
    };

    function calculateInterval() {
        var start = new Date(document.getElementById('start').value),
            end   = new Date(document.getElementById('end').value);

        document.getElementById('out1').innerHTML
            = 'Time elapsed between "' + start.toISOString().split('T')[0]
            + '" and "' + end.toISOString().split('T')[0] + '":<br/>'
            + Date.getFormattedDateDiff(start, end);
    }

    function GetTodayDate() {
        var tdate = new Date();
        var dd = tdate.getDate(); //yields day
        var MM = Number(tdate.getMonth()) + 1; //yields month
        if(MM < 10) MM = '0'+MM;
        var yyyy = tdate.getFullYear(); //yields year
        var currentDate = dd + "-" + MM + "-" + yyyy;

        return currentDate;
    }

    $('.txtNgaySinh').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true
    });
    
    $('.txtNgaySinh').change(function () {
        var d = $(this).val().split('-');
        var new_date = d[2] + '-' + d[1] + '-' + d[0];

        var fromDate = new Date(new_date);
        var toDate = new Date();

        var date = Date.getFormattedDateDiff(fromDate, toDate);
        var arr_compare_date = date.split(',');

        var id_khoi = $('select[name="lop_hoc"]').children('option:selected').data('khoi');
        var id_lop = $('select[name="lop_hoc"]').children('option:selected').val();

        if(Number(arr_compare_date[1]) < 6 && Number(arr_compare_date[0] < 1)) {
            // alert('Bé không được nhỏ hơn 6 tháng tuổi');
            $('.error_tuoi').hide();
            $('.err_6thang').show();
            $('#submit-be').attr('disabled', 'disabled');
        }
        else if(Number(arr_compare_date[0]) > 7) {
            // alert('Bé không được lớn hơn 7 tuổi');
            $('.error_tuoi').show();
            $('.err_6thang').hide();
            $('#submit-be').attr('disabled', 'disabled');
        }
        else{
            $('.error_tuoi').hide();
            if(arr_compare_date[0] < 3) {
                if(id_khoi != 4){
                    // alert('Tuổi của bé chỉ phù hợp với lớp ở khối nhà trẻ');
                    $('.error_lop').text('Tuổi của bé chỉ phù hợp với lớp ở khối nhà trẻ');
                    $('.error_lop').show();
                    $('#submit-be').attr('disabled', 'disabled');
                    return;
                }
            }
            else if(arr_compare_date[0] == 3){
                if(id_khoi != 1){
                    // alert('Tuổi của bé chỉ phù hợp với lớp ở khối Mầm');
                    $('.error_lop').text('Tuổi của bé chỉ phù hợp với lớp ở khối Mầm');
                    $('.error_lop').show();
                    $('#submit-be').attr('disabled', 'disabled');
                    return;
                }
            }
            else if(arr_compare_date[0] == 4){
                if(id_khoi != 2){
                    // alert('Tuổi của bé chỉ phù hợp với lớp ở khối Trồi');
                    $('.error_lop').text('Tuổi của bé chỉ phù hợp với lớp ở khối Trồi');
                    $('.error_lop').show();
                    $('#submit-be').attr('disabled', 'disabled');
                    return;
                }
            }
            else if(arr_compare_date[0] == 5){
                if(id_khoi != 3){
                    // alert('Tuổi của bé chỉ phù hợp với lớp ở khối Lá');
                    $('.error_lop').text('Tuổi của bé chỉ phù hợp với lớp ở khối Lá');
                    $('.error_lop').show();
                    $('#submit-be').attr('disabled', 'disabled');
                    return;
                }
            }
            $('.error_lop').hide();
            $('#submit-be').removeAttr('disabled');
        }
    })

    function get_data_lop_hoc_theo_nien_khoa(id_nien_khoa) {
        $.ajax({
            type: "POST",
            url: 'admin-be-xuly.php',
            data: { 'get_data_lop_hoc' : 1, 'id_nien_khoa': id_nien_khoa },
            success : function (result){
                var data = JSON.parse(result);
                var str = "";
                if(data.length > 0) {
                    $('button[name="btn-submit-be"]').removeAttr('disabled');
                    data.forEach(function (item) {
                        str += '<option data-khoi="'+ item.khoi_id +'" value="'+ item.id +'">'+ item.mo_ta +'</option>'
                    });
                    $('select[name="lop_hoc"]').html(str);
                }
                else{
                    $('select[name="lop_hoc"]').html('<option data-khoi="0" value="0">Chưa có lớp</option>');
                    $('button[name="btn-submit-be"]').attr('disabled', 'disabled');
                }

            }
        });
        $('select[name="lop_hoc"]').removeAttr('disabled');
        setTimeout(function () {
            $('select[name="lop_hoc"]').change();
        },500);
    }


    function get_hoc_phi_theo_khoi() {
        var nien_khoa = $('select[name="nien_khoa"]').val();
        var khoi = $('select[name="lop_hoc"]').children("option:selected").data('khoi');
        $.ajax({
            type: "GET",
            url: 'admin-be-xuly.php?get_hoc_phi_theo_khoi=1&nien_khoa=' + nien_khoa + '&khoi=' + khoi,
            success : function (result){
                var data = JSON.parse(result);
                if(data) {
                    var money = parseFloat(data.so_tien);
                    $('input[name="hoc_phi"]').val(money.format());
                    $('.div_hoc_phi').show();
                    $('.div_thanh_toan').show();
                }
                else {
                    $('.div_thanh_toan').hide();
                    $('input[name="thanh_toan"]').val(1);
                    $('.btn-thanh-toan').click();
                    $('input[name="hoc_phi"]').val('Lớp học này chưa có học phí');
                }
                // console.log(data);
            }
        });
    }

    $(document).ready(function () {
        $('select[name="lop_hoc"]').change(function () {
            get_hoc_phi_theo_khoi();
            check_tuoi();
        });

        var nien_khoa = $('select[name="nien_khoa"]').val();

        if(nien_khoa > 0) {
            get_data_lop_hoc_theo_nien_khoa(nien_khoa);
        }


        $('.btn-thanh-toan').click(function () {
            var thanhtoan = $('input[name="thanh_toan"]').val();
            if(thanhtoan == 1) {
                $(this).html('<i class="material-icons action-icon">check_box_outline_blank</i>')
                $('input[name="thanh_toan"]').val(0);
            }
            else{
                $(this).html('<i class="material-icons action-icon">check_box</i>')
                $('input[name="thanh_toan"]').val(1);
            }
        });
    });
</script>

<!-- Footer-->
<?php include "admin-footer.php";?>
<!-- End footer