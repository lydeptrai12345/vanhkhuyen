<?php include "admin-header.php";?>
<?php include "../../inc/myconnect.php";?>
<?php include "../../inc/myfunction.php";?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" rel="stylesheet"/>

<!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">-->
<!--<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>-->

<link rel="stylesheet" href="../styles/admin/datatables.min.css">
<script src="../js/datatables.min.js"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>

<link rel="stylesheet" href="../../library/jsTree/themes/default/style.min.css" />
<script src="../../library/jsTree/jstree.min.js"></script>

<!--<link rel="stylesheet" href="../../library/collapse/collapse.css" />-->
<script src="../../library/collapse/jquery.collapse.js"></script>
<!-- End header-->
<script>
    $('#heading5 .panel-heading').attr('aria-expanded','true');
    $('#collapse5').addClass('show');
    $('#collapse5 .list-group a:nth-child(2)').addClass('cus-active');
</script>

<style>
    span.select2-container { width: 100% !important; }
    .error-message { color: #ff392a; }
    .modal { z-index: 1050; }
    .modal-lg { max-width: 1000px !important; }

    #example_filter label input{ border: 1px solid #ddd !important; }
    #example_length label select{ border: 1px solid #ddd !important; }

    #example th { text-align: center }
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
<div class="main-content-container container-fluid px-4">
    <!-- Page Header -->
    <div class="page-header row no-gutters py-4">
        <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
            <span class="text-uppercase page-subtitle">Dashboard</span>
            <h3 class="page-title">Lớp học</h3>
        </div>
    </div>
    <!-- End Page Headers -->

    <!-- Default Light Table -->
    <div class="row">
        <div class="col">
            <div class="card card-small mb-4">
                <?php
                if(isset($_GET['them']))
                {
                    ?>
                    <!-- Thêm loại tin -->
                    <?php
                if(isset($_POST['xacnhanthem']))
                {
                    $errors = array();
                    if(empty($_POST['txtTenTheLoai']))
                    {
                        $errors[] = 'txtTenTheLoai';
                    }
                    else
                    {
                        $name = $_POST['txtTenTheLoai'];
                    }
                    if(empty($errors))
                    {
                        if($_POST['theloaicha']==0)
                        {
                            $theloaicha = 0;
                        }
                        else
                        {
                            $theloaicha = $_POST['theloaicha'];
                        }
                        $query = "INSERT INTO loaitin(ten,the_loai_cha) VALUES('{$name}',$theloaicha)";
                        $results = mysqli_query($dbc, $query);
                        //Kiem tra them moi thanh cong hay chua
                        if(mysqli_affected_rows($dbc)==1)
                        {
                            ?>
                            <script>
                                alert("thêm thành công");
                                window.location="admin-loaitin.php";
                            </script>
                        <?php
                        }
                        else
                        {
                            echo "<script>";
                            echo 'alert("Thêm không thành công")';
                            echo "</script>";
                        }
                    }
                }
                ?>
                <?php
                if(isset($message))
                {
                    echo $message;
                }
                ?>
                    <div class="card-header border-bottom">
                        <h5 class="text-info">Thêm lớp học</h5>
                        <form action="" method="post">
                            <div class="form-group">
                                <label style="display:block">Thể Loại</label>
                                <select class="form-control" name="theloaicha">
                                    <option value="0">Vui Lòng Chọn Thể Loại</option>
                                    <?php selectCtrl(); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tên Thể Loại</label>
                                <input class="form-control" name="txtTenTheLoai" placeholder="Vui lòng nhập tên thể loại" value = "<?php if(isset($_POST['txtTenTheLoai'])) {echo $_POST['txtTenTheLoai'];} ?>">
                                <?php
                                if(isset($errors) && in_array('txtTenTheLoai',$errors))
                                {
                                    echo "<p class='text-danger'>Bạn chưa nhập tên thể loại</p>";
                                }
                                ?>
                            </div>
                            <button type="submit" name="xacnhanthem" class="btn btn-info">Thêm Thông Tin</button>
                        </form>
                    </div>
                    <?php
                }
                ?>
                <!-- End thêm loại tin -->
                <!-- Danh sach loại tin -->
                <div class="card-header border-bottom">
                    <form action="" method="get" class="row">
                        <div class="col-md-12">
                            <h5 class="text-info">Danh sách lớp học</h5>
                        </div>
                        <div class="col-md-2">
                            <button id="btn-show-add-nien-khoa" type="button" name="them" data-toggle="modal" data-target="#Modal_NIEN_KHOA" class="btn btn-success">Thêm mới niên khóa</button>
                        </div>
                        <div class="col-md-2">
                            <button id="btn-show-add" type="button" name="them" data-toggle="modal" data-target="#myModal" class="btn btn-info">Thêm mới lớp học</button>
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-2 text-right" style="padding-right: 0;padding-top: 7px">Niên khóa</div>
                        <div class="col-md-2">
                            <form id="form-bo-loc" action="admin-lop.php" method="get">
                                <select name="loc_nien_khoa" id="" class="form-control">
                                    <?php foreach ($results_nien_khoa as $item):?>
                                        <?php if($nien_khoa != 0) :?>
                                            <option <?php if($nien_khoa == $item['ten_nien_khoa']) echo "selected";?>
                                                    data-nam-ket-thuc="<?php echo $item['nam_ket_thuc'];?>"
                                                    value="<?php echo $item['ten_nien_khoa']?>"><?php echo $item['ten_nien_khoa']?>
                                            </option>
                                        <?php else:?>
                                            <option <?php if($nien_khoa_hien_tai == $item['ten_nien_khoa']) echo "selected"?>
                                                    data-nam-ket-thuc="<?php echo $item['nam_ket_thuc'];?>"
                                                    value="<?php echo $item['ten_nien_khoa']?>"><?php echo $item['ten_nien_khoa']?>
                                            </option>
                                        <?php endif;?>

                                    <?php endforeach;?>
                                </select>
                                <button id="btn-bo-loc" type="submit" class="hidden"></button>
                            </form>
                        </div>
                    </form>

                    <!-- Modal -->
                    <div id="myModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Thông tin lớp học</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <input id="id_chi_tiet_lop_hoc" type="hidden" value="">
                                    <div class="form-group">
                                        <label style="display:block">Tên lớp <span class="dot-required">*</span></label>
                                        <input name="ten_lop" onkeyup="check_ten_lop(this)" maxlength="255" type="text" class="form-control">
                                        <small style="display: none" class="error-message">Tên lớp này đã tồn tại</small>
                                        <small style="display: none" class="error-message e-1">Tên lớp có độ từ 5-255 ký tự</small>
                                    </div>
                                    <div class="form-group">
                                        <label style="display:block">Khối <span class="dot-required">*</span></label>
                                        <select name="select_lop_hoc" id="" class="form-control">
<!--                                            <option value="0">Chọn loại lớp học</option>-->
                                            <?php foreach ($results_lop_hoc as $item):?>
                                                <option value="<?php echo $item['id']?>"><?php echo $item['ten_lop']?></option>
                                            <?php endforeach;?>
                                        </select>
                                        <small style="display: none" class="error-message e-2"><i>Vui lòng chọn khối</i></small>
                                    </div>
                                    <div class="form-group">
                                        <label style="display:block">Niên khóa <span class="dot-required">*</span></label>
                                        <select name="select_nien_khoa" id="" class="form-control">
<!--                                            <option value="0">Chọn Niên khóa</option>-->
                                            <?php foreach ($results_nien_khoa as $item):?>
                                                <option value="<?php echo $item['id']?>"><?php echo $item['ten_nien_khoa']?></option>
                                            <?php endforeach;?>
                                        </select>
                                        <small style="display: none" class="error-message e-3"><i>Vui lòng niên khóa</i></small>
                                    </div>
                                    <div class="form-group">
                                        <label style="display:block">Nhân viên <span class="dot-required">*</span></label>
                                        <select multiple class="form-control select-nhannien-add w-100" name="nhanvien[]" onChange="">
                                            <?php foreach ($results_nhan_vien_them_moi as $item): ?>
                                                <option value="<?php echo $item['id'] ?>">
                                                    <?php echo $item['ho_ten'] ?>
                                                </option>
                                            <?php endforeach;?>
                                        </select>
                                        <select multiple class="form-control select-nhannien-edit w-100" name="nhanvien[]" onChange="">
                                            <?php foreach ($results_nhan_vien_cap_nhat as $item): ?>
                                                <option value="<?php echo $item['id'] ?>">
                                                    <?php echo $item['ho_ten'] ?>
                                                </option>
                                            <?php endforeach;?>
                                        </select>
                                        <small style="display: none" class="error-message e-4"><i>Số lượng bắt buộc từ 2-3 nhân viên</i></small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button id="btn-save" onclick="submit_lop_hoc()" type="button" class="btn btn-success"><i class="glyphicon glyphicon-floppy-saved"></i> Lưu lại</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                                </div>
                            </div>

                        </div>
                    </div>


                    <!-- ================= Modal NIEN KHÓA ============== -->
                    <div id="Modal_NIEN_KHOA" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Thông tin niên khóa</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <input id="id_chi_tiet_lop_hoc" type="hidden" value="">
                                    <form action="" method="post">
                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <label style="display:block">Năm bắt đầu <span class="dot-required">*</span></label>
                                                <input name="date_start" onkeyup="check_ten_lop(this)" maxlength="255" type="text" class="form-control date-start">
                                                <script type="text/javascript">
                                                    $(document).ready(function () {
                                                        $('.date-start').datepicker({
                                                            format: "yyyy",
                                                            viewMode: "years",
                                                            minViewMode: "years",
                                                            autoclose: true
                                                        }).on("change", function () {
                                                            var date = new Date($(this).val());
                                                            date.setFullYear(date.getFullYear() + 1);

                                                            $('.date-end').datepicker('setDate', date);
                                                        });
                                                    });
                                                </script>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label style="display:block">Năm kết thúc <span class="dot-required">*</span></label>
                                                <input name="date_end" onkeyup="check_ten_lop(this)" maxlength="255" type="text" class="form-control date-end">
                                                <small style="display: none" class="error-message">Tên lớp này đã tồn tại</small>
                                                <script type="text/javascript">
                                                    $(document).ready(function () {
                                                        $('.date-end').datepicker({
                                                            format: "yyyy",
                                                            viewMode: "years",
                                                            minViewMode: "years"
                                                        }).on("change", function () {
                                                            var date = new Date($(this).val());
                                                            date.setFullYear(date.getFullYear() - 1);

                                                            $('.date-start').datepicker('setDate', date);
                                                            $('.date-end').Close();
                                                        });
                                                    });
                                                </script>
                                            </div>

                                            <div class="form-group col-md-4" style="padding-left: 0">
                                                <label style="color: transparent">aaaaaa</label>
                                                <div class="w-100">
                                                    <button id="btn-save" onclick="insert_nien_khoa()" type="button" class="btn btn-success w-75" style="float: right">Thêm mới</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="text-center">Danh sách niên khóa</h5>
                                        </div>
                                        <div class="col-md-12" style="max-height: 360px; overflow-y: auto;">
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" style="width: 50px">TT</th>
                                                    <th class="text-center">Niên khóa</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($results_nien_khoa as $index => $item):?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $index += 1;?></td>
                                                        <td class="text-center"><?php echo $item['ten_nien_khoa']?></td>
                                                    </tr>
                                                    <?php endforeach;?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card-body p-0 text-center" style="margin: 30px 0">
                    <!--                    copy cai nay -->
                    <div class="row" style="padding: 5px 20px;">
                        <div class="col-md-12">
                            <table id="tripRevenue" class="table display w-100 hover cell-border compact stripe">
                                <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên lớp</th>
                                    <th>Niên khóa</th>
                                    <th>Số lượng giáo viên</th>
                                    <th>Số lượng bé</th>
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
                <!-- End danh sách loại tin -->
            </div>
        </div>
    </div>
    <!-- End Default Light Table -->

    <input type="hidden" id="flag_insert_update" value="1">


<!-- ============================ MODAL DANH SACH BE TRONG LOP ====================================-->
    <div id="Modal_DS_BE" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="background-color: #ddd;">
                    <h4 class="modal-title">Danh sách bé trong lớp</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" style="padding: 0 !important;">
                    <div class="tien-ich"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <button id="btn-chuyen-lop" data-toggle="modal" data-target="#modal-chuyen-lop" class="btn btn-info" style="display: none">Chuyển lớp</button>
                        </div>

                        <!-- Modal -->
                        <div id="modal-chuyen-lop" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Chuyển lớp cho bé</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">Lớp học hiện tại</label>
                                                    <select name="lop_hoc_hien_tai" id="" class="form-control">
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Niên khóa mới</label>
                                                    <select name="nien_khoa_chuyen_lop" id="" class="form-control">
                                                    <?php foreach ($results_nien_khoa as $item):?>
                                                        <?php if($nien_khoa != 0) :?>
                                                            <option <?php if($nien_khoa == $item['ten_nien_khoa']) echo "selected";?>
                                                                    data-nam-ket-thuc="<?php echo $item['nam_ket_thuc'];?>"
                                                                    value="<?php echo $item['id']?>"><?php echo $item['ten_nien_khoa']?>
                                                            </option>
                                                        <?php else:?>
                                                            <option <?php if($nien_khoa_hien_tai == $item['ten_nien_khoa']) echo "selected"?>
                                                                    data-nam-ket-thuc="<?php echo $item['nam_ket_thuc'];?>"
                                                                    value="<?php echo $item['id']?>"
                                                            >
                                                                <?php echo $item['ten_nien_khoa']?>
                                                            </option>
                                                        <?php endif;?>

                                                    <?php endforeach;?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Lớp học mới</label>
                                                    <select name="lop_hoc_moi" id="" class="form-control"></select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button onclick="chuyen_lop_cho_be()" class="btn btn-success">Chuyển lớp</button>
                                        <button id="btn-close-chuyen-lop" type="button" class="btn btn-default">Đóng</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <table id="example" class="table table-bordered" style="width: 100% !important;">
                        <thead>
                        <tr>
                            <th>STT</th>
                            <th>Họ và tên</th>
                            <th>Ngày sinh</th>
                            <th>Giới tính</th>
                            <th>SĐT cha</th>
                            <th>SĐT mẹ</th>
                            <th><input name="select_all" value="1" type="checkbox"></th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer" style="background-color: #ddd;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>

        </div>
    </div>
<!-- ============================ END MODAL DANH SACH BE TRONG LOP ================================-->

</div>
<!-- End page content-->

<script>
    var rows_selected = [];
    $(document).ready(function () {
        $( '.select-nhannien-add' ).select2( {
            placeholder: {
                id: '',
                text: 'Vui lòng chọn nhân viên'
            },
            maximumSelectionLength: 3
        } );

        $( '.select-nhannien-edit' ).select2( {
            placeholder: {
                id: '',
                text: 'Vui lòng chọn nhân viên'
            },
            maximumSelectionLength: 3
        } );

        $('select[name="loc_nien_khoa"]').change(function () {
            $('#btn-bo-loc').click();
        });

       $('#btn-show-add').click(function () {
           $('#flag_insert_update').val(1); //bật cờ báo là đang ở form thêm mới lớp học
           // gán gia trị text về null để thêm mới
           $('#id_chi_tiet_lop_hoc').val("");
           $('input[name="ten_lop"]').val("");
           $('select[name="select_nien_khoa"]').val("");
           $('select[name="select_lop_hoc"]').val("");

           $('.select-nhannien-add').next(".select2-container").show();
           $('.select-nhannien-edit').next(".select2-container").hide();

           $('.select-nhannien-add').val("").trigger('change');
       });

        var tb = $('#example').DataTable({
            language: {
                "lengthMenu": "Hiển thị _MENU_ bé/ trang",
                "zeroRecords": "Không tìm thấy kết quả",
                "info": "Hiển thị trang _PAGE_ của _PAGES_ trang",
                "infoEmpty": "Không có dữ liệu",
                "infoFiltered": "(Được lọc từ _MAX_ bé)",
                "search": "Tìm kiếm",
                "paginate": {
                    "previous": "Trở về",
                    "next": "Tiếp"
                },
                select: {
                    rows: {
                        _: "Bạn đã chọn %d bé",
                        0: "Click vào dòng để chọn bé",
                        1: "Có 1 dòng được chọn"
                    }
                }
            },
            data: null,
            columnDefs: [
                { targets: 0, data: null, orderable: false, width: '30px' },
                { targets: 1, className: 'dt-body-left' },
                { targets: 2, className: 'dt-body-center', orderable: false },
                { targets: 3, className: 'dt-body-center', orderable: false },
                { targets: 4, className: 'dt-body-center', orderable: false },
                { targets: 5, className: 'dt-body-center', orderable: false},
                {
                    targets: 6,
                    orderable: false,
                    data:   null,
                    width: "80px",
                    "defaultContent": '',
                    render: function ( data, type, row ) {
                        if ( type === 'display' ) {
                            return '<input type="checkbox" class="editor-active">';
                        }
                        return data;
                    },
                    className: "dt-body-center"
                },
            ],
            columns: [
                { data: null, width: "30px" },
                { data: 'ten' },
                { data: 'ngaysinh' },
                { data: 'gioitinh', width: "100px" },
                { data: 'sdtcha', width: "130px" },
                { data: 'sdtcha', width: "130px" },
                { data: null, width: "30px" },
            ],
            order: [[ 0, 'asc' ]]

        });
        // PHẦN THỨ TỰ TABLE
        tb.on( 'order.dt search.dt', function () {
            tb.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();

        $('#example').on('click', 'tbody td, thead th:first-child', function(e){
            $(this).parent().find('input[type="checkbox"]').trigger('click');
        });

        $('#example tbody').on('click', 'input[type="checkbox"]', function(e){
            var $row = $(this).closest('tr');

            // Get row data
            var data = tb.row($row).data();

            var ddd = tb.row( $(this).parents('tr') ).data();

            // Get row ID
            var rowId = ddd.be_id;

            // Determine whether row ID is in the list of selected row IDs
            var index = $.inArray(rowId, rows_selected);

            // If checkbox is checked and row ID is not in list of selected row IDs
            if(this.checked && index === -1){
                rows_selected.push(rowId);

                // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
            } else if (!this.checked && index !== -1){
                rows_selected.splice(index, 1);
            }

            if(this.checked){
                $row.addClass('selected');
            } else {
                $row.removeClass('selected');
            }

            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(tb);

            // Prevent click event from propagating to parent
            e.stopPropagation();
        });

        function updateDataTableSelectAllCtrl(table){
            var $table             = table.table().node();
            var $chkbox_all        = $('tbody input[type="checkbox"]', $table);
            var $chkbox_checked    = $('tbody input[type="checkbox"]:checked', $table);
            var chkbox_select_all  = $('thead input[name="select_all"]', $table).get(0);

            // If none of the checkboxes are checked
            if($chkbox_checked.length === 0){
                chkbox_select_all.checked = false;
                if('indeterminate' in chkbox_select_all){
                    chkbox_select_all.indeterminate = false;
                }
                $('#btn-chuyen-lop').hide();
                // If all of the checkboxes are checked
            } else if ($chkbox_checked.length === $chkbox_all.length){
                chkbox_select_all.checked = true;
                if('indeterminate' in chkbox_select_all){
                    chkbox_select_all.indeterminate = false;
                }
                $('#btn-chuyen-lop').show();
                // If some of the checkboxes are checked
            } else {
                chkbox_select_all.checked = true;
                if('indeterminate' in chkbox_select_all){
                    chkbox_select_all.indeterminate = true;
                }
                $('#btn-chuyen-lop').show();
            }
        }


        // Handle click on "Select all" control
        $('#example thead input[name="select_all"]', tb.table().container()).on('click', function(e){
            if(this.checked){
                $('#example tbody input[type="checkbox"]:not(:checked)').trigger('click');
            } else {
                $('#example tbody input[type="checkbox"]:checked').trigger('click');
            }

            // Prevent click event from propagating to parent
            e.stopPropagation();
        });

        // Handle table draw event
        tb.on('draw', function(){
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(tb);
        });

        // click chuyen lop
        $('#btn-chuyen-lop').click(function () {
            $('#modal-chuyen-lop').modal().show();
            getDataNienKhoa();
        });


        $.ajax({
            type: "GET",
            url: 'admin-xuly-lop.php?load_list_lop=1&loc_nien_khoa=' + $('select[name="loc_nien_khoa"]').val(),
            success: function (result) {
                var data = JSON.parse(result);
                table_lop = $('#tripRevenue').DataTable({
                    language: {
                        "lengthMenu": "Hiển thị _MENU_ lớp/ trang",
                        "zeroRecords": "Không tìm thấy kết quả",
                        "info": "Hiển thị trang _PAGE_ của _PAGES_ trang",
                        "infoEmpty": "Không có dữ liệu",
                        "infoFiltered": "(Được lọc từ _MAX_ lớp)",
                        "search": "Tìm kiếm",
                        "paginate": {
                            "previous": "Trở về",
                            "next": "Tiếp"
                        }
                    },
                    data: data,
                    columnDefs: [
                        { targets: 0,  "orderable": false, data: null },
                        { targets: 1, className: 'dt-body-left' },
                        { targets: 2, orderable: false,className: 'dt-body-center' },
                        { targets: 3, orderable: false, className: 'dt-body-center' },
                        {
                            targets: 4,
                            orderable: false,
                            data: null,
                            defaultContent: '<a class="show" data-action="3" style="cursor: pointer" title="Xem danh sách lớp"><span></span> <i class="glyphicon glyphicon-list-alt"></i></a> '
                        },
                        {
                            targets: 5,
                            orderable: false,
                            data: null,
                            defaultContent: '<a class="edit" data-action="1" style="cursor: pointer" title="Cập nhật lớp"><i class="material-icons action-icon">edit</i></a> ' +
                                '<a data-action="2" style="cursor: pointer" title="Xóa lớp"><i class="material-icons action-icon">delete_outline</i></a>'
                        }
                    ],
                    columns: [
                        { width: "30px" },
                        { data: 'mo_ta' },
                        { data: 'ten_nien_khoa',},
                        { data: 'sl_nhan_vien',},
                        { data: 'null',},
                        { width: "80px" }
                    ],
                    rowCallback: function ( row, data ) {
                        // Set the checked state of the checkbox in the table
                        $('span', row).html(data.sl_be);
                    }
                });

                // PHẦN THỨ TỰ TABLE
                table_lop.on( 'order.dt search.dt', function () {
                    table_lop.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                        cell.innerHTML = i+1;
                    } );
                } ).draw();


                table_lop.on( 'click', 'a', function () {
                    var data = table_lop.row( $(this).parents('tr') ).data();

                    if($(this).data('action') == 1) {
                        // window.location.href = "admin-lop-sua.php?id=" + data.bang_cap_id;
                        show_form_edit(data.id)
                        // $('#myModal').modal('show');
                    }
                    else if ($(this).data('action') == 2){
                        delete_lop_hoc(data.id)
                    }
                    else if ($(this).data('action') == 3){
                        show_list_be(data.id, data.mo_ta, data.nien_khoa_id)
                    }
                });
            }
        });

        function show_list_be(id_lop, ten_lop, nien_khoa) {
            get_danh_sach_be_theo_lop(id_lop);

            $('select[name="lop_hoc_hien_tai"]').html('<option value="'+ id_lop +'" >'+ ten_lop +'</option>');
            get_data_lop_hoc_theo_nien_khoa(nien_khoa, id_lop);
        }

        $('.btn-list-be').click(function () {
            var id_lop = $(this).data('id');
            show_list_be(id_lop);
        });

        function get_danh_sach_be_theo_lop(id_lop) {
            $.ajax({
                type: "POST",
                url: 'admin-xuly-lop.php?load_list_be=1&id_lop=' + id_lop,
                success: function (result) {
                    var data = JSON.parse(result);
                    if (data.length > 0)
                    {
                        var tb = $('#example').dataTable();
                        tb.dataTable().fnClearTable();
                        tb.dataTable().fnAddData(data);
                        $('#Modal_DS_BE').modal('show');
                    }
                    else alert('Lớp học này chưa có bé!');
                }
            });
        }


        $('select[name="nien_khoa_chuyen_lop"]').change(function () {
            getDataNienKhoa();
        });

        // close modal chuyen lop
        $('#btn-close-chuyen-lop').click(function () {
            $('#modal-chuyen-lop').modal().hide();
            $('.modal-backdrop').hide();
        });
    });
    // END DOCUMENT READY
    
    function check_ten_lop(item) {
        $.get( "admin-xuly-lop.php?ten_lop=" + $(item).val() + "&check_lop=1", function( data ) {
            if(data == "1"){
                $('.erro-ten-lop').css('display','block');
                $('#btn-save').attr('disabled','disabled');
            }
            else {
                $('.erro-ten-lop').css('display','none');
                $('#btn-save').removeAttr('disabled');
            }
        });
    }

    function insert_lop_hoc() {
        // kiểm tra dữ liệu
        if (validate_form() == -1) {
            return;
        } else {
            data = {
                'add': 1,
                'ten_lop': $('input[name="ten_lop"]').val(),
                'id_nien_khoa': $('select[name="select_nien_khoa"]').val(),
                'id_lop': $('select[name="select_lop_hoc"]').val(),
                'arr_nhan_vien': $('.select-nhannien-add').val()
            }

            $.ajax({
                type: "POST",
                url: 'admin-xuly-lop.php',
                data: data,
                success: function (result) {
                    if (result == "1") {
                        alert("Thêm lớp học thành công!");
                    } else alert("Lỗi không thêm được lớp học");
                    location.reload();
                }
            });
        }
    }

    function delete_lop_hoc(id) {
        if(confirm("Bạn có chắc chắn muốn xóa lớp học vừa chọn?")) {
            data = {
                'delete': 1,
                'id_chi_tiet_lop_hoc': id
            };
            $.ajax({
                type: "POST",
                url: 'admin-xuly-lop.php',
                data: data,
                success : function (result){
                    console.log(result);
                    if (result == "1") alert("Lớp học vừa chọn đã được xóa!");
                    else if(result == "-2") alert("Lớp học này đang có bé! vui lòng xóa thông tin bé trong lớp học này!");
                    else alert("Lỗi không xóa được!");
                    location.reload();
                }
            });
        }
    }

    function show_form_edit(id) {
        $('#flag_insert_update').val(2); //bật cờ báo là đang ở form cập nhật lớp học
        $('.select-nhannien-add').next(".select2-container").hide();
        $('.select-nhannien-edit').next(".select2-container").show();
        // load thông tin của một lớp học
        data = {
            'load_info_item': 1,
            'id_chi_tiet_lop_hoc': id
        }
        $.ajax({
            type: "POST",
            url: 'admin-xuly-lop.php',
            data: data,
            success : function (result){
                item = JSON.parse(result);
                $('#id_chi_tiet_lop_hoc').val(item.id);
                $('input[name="ten_lop"]').val(item.mo_ta);
                $('select[name="select_nien_khoa"]').val(item.nien_khoa_id);
                $('select[name="select_lop_hoc"]').val(item.lop_hoc_id);
                $('.select-nhannien-edit').val(item.nv).trigger('change');
            }
        });
        $('#myModal').modal().show();
    }

    function edit_lop_hoc(id) {
        // kiểm tra dữ liệu
        console.log(validate_form());
        if (validate_form() == -1) {
            return;
        } else {
            data = {
                edit: 1,
                ten_lop: $('input[name="ten_lop"]').val(),
                id_nien_khoa: $('select[name="select_nien_khoa"]').val(),
                id_lop: $('select[name="select_lop_hoc"]').val(),
                id_chi_tiet_lop: $('#id_chi_tiet_lop_hoc').val(),
                arr_nhan_vien: $('.select-nhannien-edit').val()
            };

            $.ajax({
                type: "POST",
                url: 'admin-xuly-lop.php',
                data: data,
                success: function (result) {
                    if (result == "1") {
                        alert("Cập nhật thông tin lớp học thành công!");
                    } else alert("Lỗi không cập nhật được lớp học");
                    location.reload();
                }
            });
        }
    }

    function submit_lop_hoc() {
        type = $('#flag_insert_update').val();

        if(type == 1) {
            insert_lop_hoc();
        }
        else{
            id_chi_tiet_lop_hoc = $('#id_chi_tiet_lop').val();
            edit_lop_hoc(id_chi_tiet_lop_hoc);
        }
    }

    function validate_form() {
        ten_lop = new String($('input[name="ten_lop"]').val());
        id_nien_khoa = parseInt($('select[name="select_nien_khoa"]').val());
        id_lop = parseInt($('select[name="select_lop_hoc"]').val());

        type = $('#flag_insert_update').val();
        if(type == 1) arr_nhan_vien = $('.select-nhannien-add').val();
        else arr_nhan_vien = $('.select-nhannien-edit').val();

        if(ten_lop.length < 5 || ten_lop.length > 255) { $('.e-1').show(); return -1; }
        else $('.e-1').hide();

        if(!id_lop) { $('.e-2').show(); return -1; }
        else $('.e-2').hide();

        if(!id_nien_khoa) { $('.e-3').show(); return -1; }
        else $('.e-3').hide();

        if(arr_nhan_vien.length < 2) { $('.e-4').show(); return -1; }
        else $('.e-4').hide();

        return 1;
    }

    function insert_nien_khoa() {
        var date_start = $('.date-start').val();
        var date_end   = $('.date-end').val();
        if(!date_start || !date_end) {
            alert("Vui lòng nhập đủ thông tin để thêm niên khóa");
            return;
        }

        $.ajax({
            type: "POST",
            url: 'admin-xuly-lop.php',
            data: { add_nien_khoa: 1, date_start: $('.date-start').val(), date_end: $('.date-end').val() },
            success: function (result) {
                if (result == "1") alert("Thêm mới niên khóa thành công!");
                else if(result == "-2") alert("Năm bắt đầu phải lớn hơn năm kết thúc")
                else if (result == "-3") alert("Niên khóa này đã tồn tại");
                else alert("Lỗi không không thêm được niên khóa");
                location.reload();
            }
        });

    }


    function get_data_lop_hoc_theo_nien_khoa(id_nien_khoa, id_lop_old) {
        $.ajax({
            type: "POST",
            url: 'admin-be-xuly.php',
            data: { 'get_data_lop_hoc' : 1, 'id_nien_khoa': id_nien_khoa },
            success : function (result){
                var data = JSON.parse(result);
                var str = "";
                if(data.length > 0) {
                    data.forEach(function (item) {
                        str += '<optgroup label="'+ item.ten_nien_khoa +'">';
                        if(item.data_lop.length > 0){
                            item.data_lop.foreach(function (lop) {
                                str += '<option data-khoi="'+ lop.lop_hoc_id +'" value="'+ lop.id +'">'+ lop.mo_ta +'</option>'
                            });
                        }

                        str += '</optgroup>';
                    });
                    $('select[name="lop_hoc_moi"]').html(str);
                }
                else{
                    $('select[name="lop_hoc_moi"]').html('<option data-khoi="0" value="0">Chưa có lớp</option>');
                }

            }
        });
        $('select[name="loc_lop_hoc"]').removeAttr('disabled');
    }

    function chuyen_lop_cho_be() {
        $.ajax({
            type: "POST",
            url: 'admin-xuly-lop.php',
            data: { 'chuyen_lop' : 1, 'lop': $('select[name="lop_hoc_moi"').val(), arr_be: rows_selected },
            success : function (result){
                if (result == "1") {
                    alert('Chuyển lớp thành công');
                    location.reload();
                }
                else alert('Lỗi không chuyển được lớp');
            }
        });
    }
    
    function getDataNienKhoa() {
        $.ajax({
            type: "POST",
            url: 'admin-xuly-lop.php',
            data: { 'nien_khoa_lop_hoc' : 1, 'nien_khoa_id': $('select[name="nien_khoa_chuyen_lop"]').val() },
            success : function (result){
                var data = JSON.parse(result);
                var str = null;

                if(data.length > 0) {
                    data.forEach(function (item) {
                        str += '<optgroup label="'+ item.ten_khoi +'">';
                        var data_lop = item.data_lop;
                        if(data_lop.length > 0){
                            data_lop.forEach(function (lop) {
                                var lop_hien_tai = $('select[name="lop_hoc_hien_tai"]').val();
                                if(lop_hien_tai == lop.id){
                                    str += '<option disabled data-khoi="'+ lop.lop_hoc_id +'" value="'+ lop.id +'">'+ lop.mo_ta +'</option>'
                                }
                                else str += '<option data-khoi="'+ lop.lop_hoc_id +'" value="'+ lop.id +'">'+ lop.mo_ta +'</option>'
                            });
                        }

                        str += '</optgroup>';
                    });
                    $('select[name="lop_hoc_moi"]').html(str);
                }
                else{
                    $('select[name="lop_hoc_moi"]').html('<option data-khoi="0" value="0">Chưa có lớp</option>');
                }
            }
        });
    }
</script>

<!-- Footer-->
<?php include "admin-footer.php";?>
<!-- End footer