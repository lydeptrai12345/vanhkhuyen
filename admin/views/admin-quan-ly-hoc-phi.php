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
?>



<script>
    $( document ).ready( function () {
        $( '#heading5 .panel-heading' ).attr( 'aria-expanded', 'true' );
        $( '#collapse5' ).addClass( 'show' );
        $( '#collapse5 .list-group a:nth-child(3)' ).addClass( 'cus-active' );
    } );
</script>

<style>
    #tripRevenue {  }

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
                                                <div class="form-group">
                                                    <label for="">Niên khóa</label>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
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
        var table;
        $.ajax({
            type: "GET",
            url: 'admin-be-xuly.php?load_list_be=1&id_lop=',
            success: function (result) {
                var data = JSON.parse(result);
                table = $('#tripRevenue').DataTable({
                    language: {
                        "lengthMenu": "Hiển thị _MENU_ bé",
                        "zeroRecords": "Không tìm thấy kết quả",
                        "info": "Hiển thị trang _PAGE_ của _PAGES_",
                        "infoEmpty": "Không có dữ liệu",
                        "infoFiltered": "(filtered from _MAX_ total records)",
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
    });
</script>

<!-- Footer-->
<?php include "admin-footer.php";?>
<!-- End footer