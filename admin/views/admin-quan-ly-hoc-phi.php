<?php include "admin-header.php";?>
<?php include "../../inc/myconnect.php";?>
<?php include "../../inc/myfunction.php";?>
<!-- End header-->

<link rel="stylesheet" href="../styles/admin/datatables.min.css">
<script src="../js/datatables.min.js"></script>
<script src="../js/printThis.js"></script>

<?php
$str = "SELECT be.id, be.hinhbe, be.ten, be.ngaysinh, be.gioitinh, lophoc_chitiet.mo_ta, be.chieucao, be.cannang, be.diachi FROM be 
                                              INNER JOIN lophoc_be ON be.id = lophoc_be.be_id 
                                              INNER JOIN lophoc_chitiet ON lophoc_be.lop_hoc_chi_tiet_id = lophoc_chitiet.id 
                                              ORDER BY id DESC ";
$data_be = mysqli_query( $dbc, $str );

// lấy danh sách niên khóa
$results_nien_khoa = mysqli_query($dbc,"SELECT * FROM nienkhoa ORDER BY nam_ket_thuc DESC");

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


$data_lop_hoc = mysqli_query($dbc,"SELECT lophoc_chitiet.id, lophoc_chitiet.mo_ta, lophoc.id AS 'khoi_id' FROM lophoc_chitiet INNER JOIN lophoc ON lophoc_chitiet.lop_hoc_id = lophoc.id");

?>



<script>
    $( document ).ready( function () {
        $( '#heading5 .panel-heading' ).attr( 'aria-expanded', 'true' );
        $( '#collapse5' ).addClass( 'show' );
        $( '#collapse5 .list-group a:nth-child(3)' ).addClass( 'cus-active' );
    } );
</script>

<style>
    .error-message { color: #ff392a; }

    #advanced-search {
        margin-top: 20px;
    }

    label {
        font-weight: normal;
    }
    td.details-control {
        background: url('../images/details_open.png') no-repeat center center;
        cursor: pointer;
    }
    tr.shown td.details-control {
        background: url('../images/details_close.png') no-repeat center center;
    }

    .img-be {
        height: 100px;
        width: 50%;tinhtrangsuckhoe
    }
</style>

<div class="main-content-container container-fluid px-4" style="margin-top:10px">
    <input id="nguoi_dung" type="hidden" value="<?php echo $_SESSION['uid']?>">
    <input id="ho_ten_nguoi_dung" type="hidden" value="<?php echo $_SESSION['ho_ten']?>">
    <div class="row">
        <div class="col">
            <div class="card card-small mb-4">
                <div class="card-header border-bottom">
                    <div class="row">
                        <div class="col-md-3">
                            <h5 class="text-info salary-h5" style="margin: 7px 0 0 0;display: inline-block">Danh sách học phí</h5>
                        </div>
                        <div class="col-md-9">
                            <button data-toggle="modal" data-target="#myModal" class="btn btn-success pull-right" style="float: right">Thêm mới học phí</button>
                        </div>

                        <!-- Modal -->
                        <div id="myModal" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Thông tin học phí</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <form action="">
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label style="display:block">Niên khóa <span class="dot-required">*</span></label>
                                                    <select name="nien_khoa" id="" class="form-control">
                                                        <?php foreach ($results_nien_khoa as $item):?>
                                                            <?php if($nien_khoa != 0) :?>
                                                                <option <?php if($nien_khoa == $item['ten_nien_khoa']) echo "selected";?>
                                                                        data-nam-ket-thuc="<?php echo $item['nam_ket_thuc'];?>"
                                                                        data-id="<?php echo $item['id'];?>"
                                                                        value="<?php echo $item['ten_nien_khoa']?>"><?php echo $item['ten_nien_khoa']?>
                                                                </option>
                                                            <?php else:?>
                                                                <option <?php if($nien_khoa_hien_tai == $item['ten_nien_khoa']) echo "selected"?>
                                                                        data-nam-ket-thuc="<?php echo $item['nam_ket_thuc'];?>"
                                                                        data-id="<?php echo $item['id'];?>"
                                                                        value="<?php echo $item['ten_nien_khoa']?>"><?php echo $item['ten_nien_khoa']?>
                                                                </option>
                                                            <?php endif;?>

                                                        <?php endforeach;?>
                                                    </select>
                                                    <small style="display: none" class="error-message e-3"><i>Vui lòng chọn niên khóa</i></small>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label style="display:block">Khối <span class="dot-required">*</span></label>
                                                    <select name="khoi" id="" class="form-control">
                                                        <!--                                            <option value="0">Chọn loại lớp học</option>-->
                                                        <?php foreach ($results_lop_hoc as $item):?>
                                                            <option value="<?php echo $item['id']?>"><?php echo $item['ten_lop']?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                    <small style="display: none" class="error-message e-2"><i>Vui lòng khối</i></small>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label for="">Học phí <span class="dot-required">*</span></label>
                                                    <input name="hoc_phi" type="text" class="form-control formatCurrency text-right" value="0">
                                                    <small style="display: none" class="error-message e-4"><i>Học phí phải lớn hơn 1000</i></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button id="btn-hoc-phi" type="button" class="btn btn-success">Lưu lại</button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0 text-center" style="margin: 30px 0">
                    <form id="form-bo-loc" action="admin-lop.php" method="get" class="col-md-12">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label class="text-left">Niên khóa</label>
                                <select name="loc_nien_khoa" id="" class="form-control">
                                    <?php foreach ($results_nien_khoa as $item):?>
                                        <?php if($nien_khoa != 0) :?>
                                            <option <?php if($nien_khoa == $item['ten_nien_khoa']) echo "selected";?>
                                                    data-id="<?php echo $item['id'];?>"
                                                    data-nam-ket-thuc="<?php echo $item['nam_ket_thuc'];?>"
                                                    value="<?php echo $item['ten_nien_khoa']?>"><?php echo $item['ten_nien_khoa']?>
                                            </option>
                                        <?php else:?>
                                            <option <?php if($nien_khoa_hien_tai == $item['ten_nien_khoa']) echo "selected"?>
                                                    data-id="<?php echo $item['id'];?>"
                                                    data-nam-ket-thuc="<?php echo $item['nam_ket_thuc'];?>"
                                                    value="<?php echo $item['ten_nien_khoa']?>"><?php echo $item['ten_nien_khoa']?>
                                            </option>
                                        <?php endif;?>

                                    <?php endforeach;?>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label class="text-left">Lớp</label>
                                <select name="loc_lop_hoc" id="" class="form-control" disabled>
                                    <option value="0">Chọn lớp học</option>
                                    <?php foreach ($data_lop_hoc as $item):?>
                                        <option data-khoi="<?php echo $item['khoi_id']?>" value="<?php echo $item['id']?>"><?php echo $item['mo_ta']?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label class="text-left">Thanh toán</label>
                                <select name="thanh_toan" id="" class="form-control">
                                    <option value="0">Chưa thanh toán</option>
                                    <option value="1">Đã thanh toán</option>
                                </select>
                            </div>
                            <button id="btn-bo-loc" type="submit" class="hidden"></button>
                        </div>
                    </form>
                    <div class="row" style="padding: 5px 20px;">
                        <div class="col-md-12">
                            <table id="tripRevenue" class="table display w-100 hover cell-border compact stripe">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>STT</th>
                                    <th>Khối</th>
                                    <th>Niên khóa</th>
                                    <th>Học phí</th>
                                    <th>Ngày tạo</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-12">
                            <table id="table-hoc-phi" class="table display w-100 hover cell-border compact stripe">
                                <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Mã số</th>
                                    <th>Họ và tên</th>
                                    <th>Lớp</th>
                                    <th>Niên khóa</th>
                                    <th>Trạng thái</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="" style="display: none">
        <div id="print-hoc-phi" class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="m-b-5 p-b-5">TRƯỜNG MẦM NON VÀNH KHUYÊN</h6>
                    <p class="m-b-5 p-b-5">Địa chỉ: sfsfsdffsdf</p>
                    <p class="m-b-5 p-b-5">Website: sfsfsdffsdf</p>
                </div>
                <div class="col-md-12" style="margin-top: 20px">
                    <h5 class="text-center">BIÊN LAI THU HỌC PHÍ</h5>
                </div>
                <div class="col-md-12" style="margin-top: 20px">
                    <p class="content-hoa-don"><b>Họ tên người nộp: </b> <span class="nguoi-nop">Trần Thị Tủn</span></p>
                    <p class="content-hoa-don"><b>Lớp: </b> <span class="ten-lop">Lớp mầm 1</span></p>
                    <p class="content-hoa-don"><b>Địa chỉ: </b><span class="dia-chi"></span></p>
                    <p class="content-hoa-don"><b>Lý do thu: </b>Đóng tiền học phí</p>
                    <p class="content-hoa-don"><b>Số tiền: </b><span class="so-tien">10,000,000</span> VNĐ</p>
                    <p class="content-hoa-don hinh-thuc"><b>Hình thức: </b>Thanh toán bằng tiền mặt</p>
                </div>
            </div>

            <ul class="footer-hoa-don">
                <li>
                    <p class="content-hoa-don ngay-thanh-toan">Ngày 13 tháng 5 năm 2019</p>
                    <p class="content-hoa-don "><b>Người thu tiền</b></p>
                    <p class="content-hoa-don ">(Ký và ghi rõ họ tên)</p>
                    <br><br><br>
                    <p class="content-hoa-don" style="margin-top: 50px">
                        <b> <span class="nguoi-thu-tien">Trần Thị Thu</span></b>
                    </p>
                </li>
                <li>
                    <div class="" style="float: right">
                        <p class="content-hoa-don text-center ngay-thanh-toan">Ngày 13 tháng 5 năm 2019</p>
                        <p class="content-hoa-don text-center"><b>Người nộp</b></p>
                        <p class="content-hoa-don text-center">(Ký và ghi rõ họ tên)</p>
                        <br><br><br>
                        <p class="content-hoa-don text-center" style="padding-top: 50px">
                            <b><span class="nguoi-nop">Trần Thị Tủn</span></b>
                        </p>
                    </div>
                </li>
            </ul>
        </div>

    </div>
</div>

<script>
    $(document).ready(function () {
        String.prototype.replaceAll = function(search, replacement) {
            var target = this;
            return target.replace(new RegExp(search, 'g'), replacement);
        };

        var table;
        $.ajax({
            type: "GET",
            url: 'admin-quan-ly-hoc-phi-xu-ly.php?load_list_hoc_phi=1',
            success: function (result) {
                var data = JSON.parse(result);
                console.log(data);
                table = $('#tripRevenue').DataTable({
                    language: {
                        "lengthMenu": "Hiển thị _MENU_ bé",
                        "zeroRecords": "Không tìm thấy kết quả",
                        "info": "Hiển thị trang _PAGE_ của _PAGES_",
                        "infoEmpty": "Không có dữ liệu",
                        "infoFiltered": "(Được lọc từ _MAX_ bé)",
                        "search": "Tìm kiếm",
                        "paginate": {
                            "previous": "Trở về",
                            "next": "Tiếp"
                        }
                    },
                    data: data,
                    columnDefs: [
                        { targets: 0, data: null },
                        { targets: 1, className: 'dt-body-center' },
                        { targets: 2, className: 'dt-body-left' },
                        { targets: 4, className: 'dt-body-right' },
                        { targets: 6, data: null, defaultContent: '<a><i class="material-icons action-icon">edit</i></a>' },
                    ],
                    columns: [
                        {
                            className:      'details-control',
                            orderable:      false,
                            data:           null,
                            defaultContent: '',
                            width: "30px"
                        },
                        { data: null, width: '30px' },
                        { data: 'ten_lop' },
                        { data: 'ten_nien_khoa', width: '130px' },
                        { data: 'so_tien' },
                        { data: 'ngay_tao' },
                        { width: '50px' },
                    ],
                    order: [[ 1, 'asc' ]],
                });

                // PHẦN THỨ TỰ TABLE
                table.on( 'order.dt search.dt', function () {
                    table.column(1, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                        cell.innerHTML = i+1;
                    } );
                } ).draw();
            }
        });

        function format ( d ) {
            var str = '<div class="row">\n' +
                '    <div class="col-md-3">\n' +
                '        <img class="img-be" src="../images/hinhbe/'+ d.hinhbe +'" alt="">\n' +
                '    </div>\n' +
                '    <div class="col-md-9">\n' +
                '        <h6>asdsaddsd</h6>\n' +
                '        <p>dasdsadsadsad adasdasdasd asdsa dsa das dsa</p>\n' +
                '    </div>\n' +
                '</div>';
            return str;
        }


        $('#tripRevenue tbody').on('click', 'td.details-control', function () {
            console.log(table)
            var tr = $(this).closest('tr');
            var row = table.row(tr);

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                // Open this row
                row.child( format(row.data()) ).show();
                tr.addClass('shown');
            }
        } );

        $('#tripRevenue tbody').on( 'click', 'a', function () {
            var data = table.row( $(this).parents('tr') ).data();
            console.log(data);
        } );

        function validate_hoc_phi (nien_khoa, khoi, hoc_phi) {

            var hoc_phi = parseFloat(replaceAll(hoc_phi));

            if(!khoi) { $('.e-2').show(); return -1; }
            else $('.e-2').hide();

            if(!nien_khoa) { $('.e-3').show(); return -1; }
            else $('.e-3').hide();

            if(hoc_phi < 1000) { $('.e-4').show(); return -1; }
            else $('.e-4').hide();

            return 1;
        }

        //
        $('#btn-hoc-phi').click(function () {
            var nien_khoa = $('select[name="nien_khoa"]').children('option:selected').data('id');
            var khoi = $('select[name="khoi"]').val();
            var hoc_phi = $('input[name="hoc_phi"]').val();
            if (validate_hoc_phi(nien_khoa, khoi, hoc_phi) == 1) {
                $.ajax({
                    type: "POST",
                    url: 'admin-quan-ly-hoc-phi-xu-ly.php',
                    data: { add: 1, nien_khoa: nien_khoa, khoi: khoi, hoc_phi: hoc_phi },
                    success : function (result){
                        console.log(result);
                        if (result == "1") {
                            alert("Thêm học phí thành công!");
                            location.reload();
                        }
                        else if(result == "-2") alert("Thông tin chưa đúng!");
                        else if(result == "-3") alert("Học phí đã tồn tại niên khóa và khối vừa chọn!");
                        else alert("Lỗi không thêm được học phí!");
                    }
                });
            }
        });

        function replaceAll (value) {
            if(value == 0 || value == null || value == null) return 0;
            var argWs = value.toString();
            for (var intI=0; intI < argWs.length; intI++){
                argWs = argWs.replace(",","");
                argWs = argWs.replace(".","");
            }
            return argWs;
        }

        $('select[name="loc_nien_khoa"]').change(function () {
            get_data_lop_hoc_theo_nien_khoa($(this).val());
        });

        function get_data_lop_hoc_theo_nien_khoa(id_nien_khoa) {
            $.ajax({
                type: "POST",
                url: 'admin-be-xuly.php',
                data: { 'get_data_lop_hoc' : 1, 'id_nien_khoa': id_nien_khoa },
                success : function (result){
                    var data = JSON.parse(result);
                    var str = "";
                    if(data.length > 0) {
                        data.forEach(function (item) {
                            str += '<option data-khoi="'+ item.khoi_id +'" value="'+ item.id +'">'+ item.mo_ta +'</option>'
                        });
                        $('select[name="loc_lop_hoc"]').html(str);
                    }
                    else{
                        $('select[name="loc_lop_hoc"]').html('<option data-khoi="0" value="0">Chưa có lớp</option>');
                    }

                }
            });
            $('select[name="loc_lop_hoc"]').removeAttr('disabled');
        }



        var table_hp;
        $.ajax({
            type: "GET",
            url: 'admin-quan-ly-hoc-phi-xu-ly.php?danh_sach_hoc_phi=1&loc_nien_khoa=' + $('select[name="loc_nien_khoa"]').find('option:selected').data('id'),
            success: function (result) {
                var data = JSON.parse(result);
                console.log(data);
                table_hp = $('#table-hoc-phi').DataTable({
                    language: {
                        "lengthMenu": "Hiển thị _MENU_ bé",
                        "zeroRecords": "Không tìm thấy kết quả",
                        "info": "Hiển thị trang _PAGE_ của _PAGES_",
                        "infoEmpty": "Không có dữ liệu",
                        "infoFiltered": "(Được lọc từ _MAX_ bé)",
                        "search": "Tìm kiếm",
                        "paginate": {
                            "previous": "Trở về",
                            "next": "Tiếp"
                        }
                    },
                    data: data,
                    columnDefs: [
                        { targets: 0, data: null },
                        { targets: 1, className: 'dt-body-center' },
                        { targets: 2, className: 'dt-body-left' },
                        { targets: 4, className: 'dt-body-right' },
                        // { targets: 5, className: 'dt-body-right' },
                        { targets: 6, data: null, defaultContent: '<a style="cursor: pointer" class="print-old"><i class="material-icons action-icon">print</i></a>' },
                    ],
                    columns: [
                        { width: '30px' },
                        { data: 'be_id', width: '60px' },
                        { data: 'ten' },
                        { data: 'mo_ta', width: '130px' },
                        { data: 'ten_nien_khoa' },
                        {
                            data:   "trangthai",
                            width: "80px",
                            render: function ( data, type, row ) {
                                if ( type === 'display' ) {
                                    return '<input type="checkbox" class="editor-active">';
                                }
                                return data;
                            },
                            className: "dt-body-center"
                        },
                        { data: null, width: '50px' }
                    ],
                    order: [[ 1, 'asc' ]],
                    rowCallback: function ( row, data ) {
                        if(data.trangthai == 1){
                            $('input.editor-active', row).attr('disabled', 'disabled');
                        }
                        $('input.editor-active', row).prop( 'checked', data.trangthai == 1 );
                    }
                });

                // PHẦN THỨ TỰ TABLE
                table_hp.on( 'order.dt search.dt', function () {
                    table_hp.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                        cell.innerHTML = i+1;
                    } );
                } ).draw();
            }
        });

        $('#table-hoc-phi tbody').on( 'change', 'input.editor-active', function () {
            var da = table_hp.row( $(this).parents('tr') ).data();
            // console.log(da)
            if(confirm('Bạn có chắc chắn muốn cập nhật trạng thái của nhân viên vừa chọn?')) {
                $.ajax( {
                    type: "POST",
                    url: "admin-quan-ly-hoc-phi-xu-ly.php",
                    data: {
                        dong_tien: 1,
                        so_tien: da.hoc_phi,
                        be_id: da.be_id,
                        lop_hoc_chi_tiet: da.lop_hoc_chi_tiet_id,
                        nhan_vien: $('#nguoi_dung').val(),

                    },
                    success: function ( result ) {
                        $('.nguoi-nop').html(da.ten);
                        $('.ten-lop').html(da.mo_ta);
                        $('.dia-chi').html(da.diachi);
                        $('.so-tien').html(da.hoc_phi);
                        $('.nguoi-thu-tien').html($('#ho_ten_nguoi_dung').val());
                        if(result == "1")
                        {
                            da.ngay_thanh_toan = new Date();
                            print_hoa_don(2, da)
                        }
                        else alert("That bai")
                    }
                } );
            }
        } );
        //print-old
        $('#table-hoc-phi tbody').on( 'click', 'a.print-old', function () {
            var da = table_hp.row( $(this).parents('tr') ).data();
            console.log('aaaaa')
            print_hoa_don(1, da);
        });
        
        
        //In hóa đơn
        function print_hoa_don(type = 1, data) {
            $('.nguoi-nop').html(data.ten);
            $('.ten-lop').html(data.mo_ta);
            $('.dia-chi').html(data.diachi);
            $('.so-tien').html(data.hoc_phi);
            $('.nguoi-thu-tien').html($('#ho_ten_nguoi_dung').val());

            var date = new Date(data.ngay_thanh_toan);
            var day = date.getDate();
            var month = date.getMonth() + 1;
            var year = date.getFullYear();
            var ngay = 'Ngày ' + day + ' tháng ' + month + ' năm ' + year;

            $('.ngay-thanh-toan').html(ngay);

            $('#print-hoc-phi').printThis({
                importCSS: false,
                loadCSS: [ "../css/bootstrap.min.css", "../admin/css/print-hoa-don.css"],
            });
        }
        
    });
</script>

<!-- Footer-->
<?php include "admin-footer.php";?>
<!-- End footer