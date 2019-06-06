<?php include "admin-header.php";?>
<?php include "../../inc/myconnect.php";?>
<?php include "../../inc/myfunction.php";?>


<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link rel="stylesheet" href="../styles/admin/datatables.min.css">
<script src="../js/datatables.min.js"></script>

<link rel="stylesheet" href="../../library/datepicker/bootstrap-datepicker.css">
<script src="../../library/datepicker/bootstrap-datepicker.js"></script>
<script src="../../library/collapse/jquery.collapse.js"></script>
<!-- End header-->
<script>
    $('#heading0 .panel-heading').attr('aria-expanded','true');
</script>

<style>
    span.select2-container { width: 100% !important; }
    .error-message { color: #ff392a; }
    .modal { z-index: 1050; }
    .modal-lg { max-width: 1000px !important; }

    #example_filter label input{ border: 1px solid #ddd !important; }
    #example_length label select{ border: 1px solid #ddd !important; }

    #example th { text-align: center }
    .d-none-mam-non { display: none; }
    .item-nhom-chuc-nang:hover {
        background-color: #eae1d4;
    }
    .item-nhom-chuc-nang {
        cursor: pointer;
    }
    .active-nhom {
        background-color: #ddd;
    }
</style>

<?php
// lấy danh sách niên khóa
$results_nien_khoa = mysqli_query($dbc,"SELECT * FROM nienkhoa ORDER BY nam_ket_thuc DESC");

// Lấy danh sách nhân viên
$results_nhan_vien_them_moi = mysqli_query($dbc,"SELECT id, ho_ten FROM nhanvien WHERE id NOT IN (SELECT nhan_vien_id FROM lophoc_nhanvien)");
$results_nhan_vien_cap_nhat = mysqli_query($dbc,"SELECT id, ho_ten FROM nhanvien");

$nien_khoa = isset($_GET['loc_nien_khoa']) ? $_GET['loc_nien_khoa'] : 0;

// tinhs nieen khoa hien tai month > 6 && month
$year = date("Y");
if(date("m") > 6)
    $nien_khoa_hien_tai = $year . "-" . $year + 1;
else
    $nien_khoa_hien_tai = ($year - 1) . "-" . $year;

if(!$nien_khoa) $nien_khoa = $nien_khoa_hien_tai;
// lấy danh sách khối
$results_lop_hoc = mysqli_query($dbc,"SELECT * FROM lophoc");
?>

<!-- Page content-->
<div class="main-content-container container-fluid px-4"style="margin-top:10px">

    <input id="nguoi_dung" type="hidden" value="<?php echo $_SESSION['uid']?>">
    <input id="ho_ten_nguoi_dung" type="hidden" value="<?php echo $_SESSION['ho_ten']?>">

    <div class="row">
        <div class="col">
            <div class="card card-small mb-4">
                <div class="card-header border-bottom">
                    <h5 class="text-info">Danh sách tài khoản</h5>
                    <form action="" method="get" class="row">
                        <div class="col-md-2">
                            <button id="btn-show-add-nien-khoa" type="button" name="them" data-toggle="modal" data-target="#myModal" class="btn btn-success">Thêm tài khoản</button>
                        </div>
                        <div class="col-md-2">
                            <button id="btn-show-add-nien-khoa" type="button" name="them" data-toggle="modal" data-target="#modal_nhom_nguoi_dung" class="btn btn-primary">Nhóm người dùng</button>
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-2 text-right" style="padding-right: 0;padding-top: 7px">Niên khóa</div>
                        <div class="col-md-2">
                            <input class="date_nguyen_lieu form-control" type="text">
                        </div>
                    </form>
                </div>
                <div class="card-body p-0 text-center" style="margin: 30px 0">
                    <!--                    copy cai nay -->
                    <div class="row" style="padding: 5px 20px;">
                        <div class="col-md-12">
                            <table id="tripRevenue" class="table display w-100 hover cell-border compact stripe">
                                <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên đăng nhập</th>
                                    <th>Nhóm người dùng</th>
                                    <th>Kích hoạt</th>
                                    <th>Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--  end copy cai nay -->
                </div>


                <!-- Modal -->
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Thông tin nguyên liệu</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="">Tên tài khoản</label>
                                        <input type="text" class="form-control form-control-sm">
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="">Mật khẩu</label>
                                        <input type="password" class="form-control form-control-sm">
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="">Nhân viên</label>
                                        <select name="" id="" class="form-control">
                                            <option value="">aaaaa</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="">Nhóm người dùng</label>
                                        <select name="" id="" class="form-control">
                                            <option value="">aaaaa</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button id="btn-save" type="button" class="btn btn-success"><i class="glyphicon glyphicon-floppy-saved"></i> Lưu lại</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                            </div>
                        </div>

                    </div>
                </div>


                <div id="modal_nhom_nguoi_dung" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-lg">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Nhóm người dùng</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body" style="padding: 5px">
                                <div id="tabs">
                                    <ul>
                                        <li><a href="#tabs-1">Danh sách nhóm</a></li>
                                        <li><a href="#tabs-2">Phân quyền nhóm</a></li>
                                    </ul>
                                    <div id="tabs-1">
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="">Tên tài khoản</label>
                                                <input type="text" class="form-control form-control-sm">
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label for="">Mật khẩu</label>
                                                <input type="password" class="form-control form-control-sm">
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label for="">Nhóm người dùng</label>
                                                <select name="" id="" class="form-control">
                                                    <option value="">aaaaa</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="tabs-2">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <ul id="list-chuc-nang-cha" class="list-group">
                                                    <li class="list-group-item item-nhom-chuc-nang">First item</li>
                                                    <li class="list-group-item item-nhom-chuc-nang">Second item</li>
                                                    <li class="list-group-item item-nhom-chuc-nang">Third item</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-8">
                                                <table id="table_chuc_nang" class="table table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>Tên chức năng</th>
                                                        <th class="text-center">All</th>
                                                        <th class="text-center">Xem</th>
                                                        <th class="text-center">Thêm</th>
                                                        <th class="text-center">Cập nhật</th>
                                                        <th class="text-center">Xóa</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>John dá  dá dasdasd dsada</td>
                                                        <td class="text-center"><input type="checkbox"></td>
                                                        <td class="text-center"><input type="checkbox"></td>
                                                        <td class="text-center"><input type="checkbox"></td>
                                                        <td class="text-center"><input type="checkbox"></td>
                                                        <td class="text-center"><input type="checkbox"></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button id="btn-save" type="button" class="btn btn-success"><i class="glyphicon glyphicon-floppy-saved"></i> Lưu lại</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                            </div>
                        </div>

                    </div>
                </div>


            </div>
        </div>
    </div>
    <!-- End Default Light Table -->

    <input type="hidden" id="flag_insert_update" value="1">


</div>
<!-- End page content-->

<script src="../js/he.thong.js"></script>


<!-- Footer-->
<?php include "admin-footer.php";?>
<!-- End footer