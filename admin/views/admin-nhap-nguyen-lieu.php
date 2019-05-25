<?php include "admin-header.php";?>
<?php include "../../inc/myconnect.php";?>
<?php include "../../inc/myfunction.php";?>


<link rel="stylesheet" href="../styles/admin/datatables.min.css">
<script src="../js/datatables.min.js"></script>

<link rel="stylesheet" href="../../library/datepicker/bootstrap-datepicker.css">
<script src="../../library/datepicker/bootstrap-datepicker.js"></script>


<script src="../../library/collapse/jquery.collapse.js"></script>
<!-- End header-->
<script>
    $('#heading4 .panel-heading').attr('aria-expanded','true');
    $('#collapse4').addClass('show');
    $('#collapse4 .list-group a:nth-child(2)').addClass('cus-active');
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
                    <h5 class="text-info">Danh sách nguyên liệu</h5>
                    <form action="" method="get" class="row">
                        <div class="col-md-2">
                            <button id="btn-show-add-nien-khoa" type="button" name="them" data-toggle="modal" data-target="#myModal" class="btn btn-success">Thêm nguyên liệu</button>
                        </div>
                        <div class="col-md-6"></div>
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
                                    <th>Nguyên liệu</th>
                                    <th>Đơn vị tính</th>
                                    <th>Số lượng</th>
                                    <th>Giá tiền</th>
                                    <th>Thanh tiền</th>
                                    <th>Ngày nhập</th>
                                    <th>Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="6" style="text-align:right">Total:</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </tfoot>
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
                                    <input type="hidden" id="nguyen_lieu_id">
                                    <div class="form-group col-md-12">
                                        <label for="">Tên nguyên liệu <span class="dot-required">*</span></label>
                                        <input name="ten_nguyen_lieu" maxlength="255" type="text" class="form-control">
                                        <small id="err_ten_nguyen_lieu" class="dot-required d-none-mam-non">Vui lòng nhập tên nguyên liệu</small>
                                    </div>

                                    <div class="form-group col-md-8">
                                        <label for="">Giá tiền <span class="dot-required">*</span></label>
                                        <input name="gia_tien" type="text" class="form-control text-right">
                                        <small id="err_gia_tien" class="dot-required d-none-mam-non">Vui lòng nhập gia tiền</small>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="">Số lượng <span class="dot-required">*</span></label>
                                        <input name="so_luong" type="number" class="form-control text-right" value="0">
                                        <small id="err_so_luong" class="dot-required d-none-mam-non">Vui lòng nhập số lượng</small>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="">Đơn vị tính <span class="dot-required">*</span></label>
                                        <input name="dvt" type="text" maxlength="255" class="form-control">
                                        <small id="err_dvt" class="dot-required d-none-mam-non">Vui lòng nhập đơn vị tính</small>
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

<script src="../js/nhap.nguyen.lieu.js"></script>


<!-- Footer-->
<?php include "admin-footer.php";?>
<!-- End footer