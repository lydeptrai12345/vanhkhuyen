<?php include "admin-header.php";?>
<?php include "../../inc/myconnect.php";?>
<?php include "../../inc/myfunction.php";?>
<!-- End header-->

<link rel="stylesheet" href="../styles/admin/datatables.min.css">
<script src="../js/datatables.min.js"></script>

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

                    <div class="row" style="padding: 5px 20px;">
                        <div class="col-md-12">
                            <table id="tripRevenue" class="table display w-100 hover cell-border compact stripe">
                                <thead>
                                <tr>
                                    <th></th>
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
                    </div>
                </div>
            </div>
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
            url: 'admin-be-xuly.php?load_list_be=1',
            success: function (result) {
                var data = JSON.parse(result);
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
                        { targets: 1, className: 'dt-body-left' },
                        { targets: 4, className: 'dt-body-left' },
                        { targets: 5, className: 'dt-body-left' },
                        { targets: 6, data: null, defaultContent: '<a><i class="material-icons action-icon">edit</i></a>' },
                    ],
                    columns: [
                        {
                            "className":      'details-control',
                            "orderable":      false,
                            "data":           null,
                            "defaultContent": '',
                            "width": "30px"
                        },
                        { data: 'ten' },
                        { data: 'gioitinh' },
                        { data: 'ngaysinh' },
                        { data: 'mo_ta' },
                        { data: 'diachi' },
                        { "width": "50px" },
                    ]
                });

                // table = $('#tripRevenue').dataTable();
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

            // type = $('#flag_insert_update').val();
            // if(type == 1) arr_nhan_vien = $('.select-nhannien-add').val();
            // else arr_nhan_vien = $('.select-nhannien-edit').val();

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
    });
</script>

<!-- Footer-->
<?php include "admin-footer.php";?>
<!-- End footer