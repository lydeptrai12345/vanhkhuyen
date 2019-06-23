<?php include "admin-header.php";?>
<?php include "../../inc/myconnect.php";?>
<?php include "../../inc/myfunction.php";?>
<!-- End header-->
<!--copy cai nay-->

<link rel="stylesheet" href="../styles/admin/datatables.min.css">
<script src="../js/datatables.min.js"></script>

<link rel="stylesheet" href="../../library/datepicker/bootstrap-datepicker.css">
<script src="../../library/datepicker/bootstrap-datepicker.js"></script>


<?php
    $data_phan_quyen = kiem_tra_quyen_nguoi_dung(12);
    $str = "SELECT be.id, be.hinhbe, be.ten, be.ngaysinh, be.gioitinh, lophoc_chitiet.mo_ta, be.chieucao, be.cannang, be.diachi FROM be 
                                              INNER JOIN lophoc_be ON be.id = lophoc_be.be_id 
                                              INNER JOIN lophoc_chitiet ON lophoc_be.lop_hoc_chi_tiet_id = lophoc_chitiet.id 
                                              GROUP BY be.id ORDER BY id DESC ";
$data_be = mysqli_query( $dbc, $str );
?>
<script>
	$( document ).ready( function () {

		$( '#heading5 .panel-heading' ).attr( 'aria-expanded', 'true' );
		$( '#collapse5' ).addClass( 'show' );
		$( '#collapse5 .list-group a:nth-child(1)' ).addClass( 'cus-active' );
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
<?php
//Kiểm tra ID có phải là kiểu số không, filter_var kiem tra có thuộc tính trim sẽ loại bỏ khoảng trắng
if(isset($_GET['changeStatusId']) && filter_var($_GET['changeStatusId'],FILTER_VALIDATE_INT,array('min_range'=>1)))
{
    $query_nd = "UPDATE be SET trangthai = !trangthai WHERE id = {$_GET['changeStatusId']}";
    $results_nd= mysqli_query($dbc, $query_nd);
}
?>
<div class="main-content-container container-fluid px-4" style="margin-top:10px">
	<div class="row">
		<div class="col">
			<div class="card card-small mb-4">
				<div class="card-header border-bottom">
					<h5 class="text-info salary-h5" style="margin: 7px 0 0 0;display: inline-block">Thông Tin Bé</h5>
                    <?php if($data_phan_quyen->them): ?>
					<a href="admin-be-them.php" class="btn-custom2" style="float: right; margin-right: 20px; font-size: 14px">Thêm bé</a>
                    <?php endif;?>
				</div>
				<style>
					.salary-table {
						width: 96%;
						margin: 0 auto 1.5rem auto;
						border: 1px solid #eaeaea;
						border-radius: 8px;
						border-spacing: 0;
						border-collapse: unset;
						overflow: hidden;
					}
					.chitietbe{
                        margin-bottom: 4px !important;
                    }
					.salary-table td.textStr {
						text-align: left;
					}
					
					.salary-table td.numberStr {
						text-align: right;
					}
					
					.salary-table tr:not(.salary-title):nth-child(even) {
						background-color: #eaeaea;
					}
					
					.salary-table tr td,
					.salary-table tr th {
						vertical-align: middle;
					}
					
					.salary-table th {
						background-color: #66b1cc;
						color: white;
					}
					
					.salary-title th:not(:last-child) {
						border-right: 1px solid #ffffff38 !important;
					}
					
					.salary-title:nth-child(2) th {
						border-top: 1px solid #ffffff38 !important;
					}
					
					.filter-month-year .left-filter .form-control {
						width: 7%;
						min-width: 70px;
					}
					
					.filter-month-year .left-filter label {
						margin: auto 8px auto 30px;
					}
					
					.btn-custom {
						background: -webkit-linear-gradient(left, #66a7cf 5%, #66bcca);
						color: white !important;
						font-size: 16px;
						padding: 9px 40px;
						display: inline-block;
						cursor: pointer;
					}
					
					.btn-custom:hover {
						color: skyblue !important;
						background: white;
						border: 2px solid skyblue;
						padding: 7px 38px;
					}
					
					.btn-custom2 {
						background: -webkit-linear-gradient(left, #66a7cf 5%, #66bcca);
						color: white !important;
						font-size: 18px;
						padding: 8px 35px;
						display: inline-block;
						cursor: pointer;
						outline: none !important;
					}
					
					.btn-custom2:hover {
						color: skyblue !important;
						background: white;
						border: 2px solid skyblue;
						padding: 6px 33px;
					}
					
					#editSalaryModal table tr td {
						vertical-align: middle;
						font-size: 11pt;
					}
					
					#editSalaryModal table tr td:first-child {
						padding: 5px 20px 5px 5px;
					}
					
					#editSalaryModal table tr td label {
						color: black;
						margin-bottom: 0;
					}
					
					.search-container {
						width: 70%;
						margin: 0 auto;
						border: 4px solid skyblue;
						border-radius: 20px;
						padding: 40px 50px;
					}
					
					.input-search input {
						outline: none;
						font-size: 15pt;
						padding: 5px 10px;
						color: darkgray;
					}
					
					.input-search input::placeholder {
						color: darkgray;
					}
					
					.input-search input:focus {
						color: black;
					}
					
					.input-search {
						border: 1px dotted skyblue;
						display: inline-block;
						padding: 3px 15px;
						margin-right: 6%;
					}
					
					.input-search:focus-within {
						border: 2px solid skyblue;
						padding: 2px 14px;
					}
					
					.input-search i {
						font-size: 13pt;
					}
				</style>
				<div class="card-body p-0 text-center" style="margin: 30px 0">

<!-- copy cai nay -->
                    <div class="row" style="padding: 5px 20px;">
                        <div class="col-md-12">
                            <table id="tripRevenue" class="table display w-100 hover cell-border compact stripe">
                                <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Họ và tên</th>
                                    <th>Giới tính</th>
                                    <th>Ngày sinh</th>
                                    <th>Lớp</th>
                                    <th>Địa chỉ</th>
                                    <th >Trạng thái</th>
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
			</div>
		</div>
	</div>
</div>

<script>
    var data_quyen = <?php echo json_encode($data_phan_quyen);?>;
    var phan_quyen = {};
    if(data_quyen.allaction == 0) {
        phan_quyen = {
            them: data_quyen.them,
            sua: data_quyen.sua,
            xoa: data_quyen.xoa
        }
    }
    else{
        phan_quyen = {
            them: 1,
            sua: 1,
            xoa: 1
        }
    }
</script>

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
                        "lengthMenu": "Hiển thị _MENU_ bé/ trang",
                        "zeroRecords": "Không tìm thấy kết quả",
                        "info": "Hiển thị trang _PAGE_ của _PAGES_ trang",
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
                        { targets: 1, className: 'dt-body-left'},
                        { targets: 2, orderable: false,className: 'dt-body-left' },
                        { targets: 3, orderable: false,className: 'dt-body-left' },
                        { targets: 4, orderable: false,className: 'dt-body-left' },
                        { targets: 5, orderable: false,className: 'dt-body-left' },
                        { targets: 6, orderable: false,className: 'dt-body-left' },
                        {
                            targets: 7,
                            orderable: false,
                            data: null,
                            visible: ((phan_quyen.sua == 0 && phan_quyen.xoa == 0) ? false : true),
                            defaultContent: '<a class="edit-btn '+ ((phan_quyen.sua == 0) ? 'd-none' : '') + '" data-action="1" style="cursor: pointer" title="Cập nhật bé"><i class="material-icons action-icon">edit</i></a>'
                        }
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
                        { data: '' },
                    ],
                    order: [[ 1, 'asc' ]],
                    rowCallback: function ( row, data ) {
                        // Set the checked state of the checkbox in the table
                        $('input.editor-active', row).prop( 'checked', data.trangthai == 1 );
                    }
                });


                // table = $('#tripRevenue').dataTable();
            }
        });

        function format ( d ) {
            var str = '<div class="row">\n' +
                '                    <div class="col-md-3">\n' +
                '                        <img class="img-be" src="../images/hinhbe/'+ d.hinhbe +'" alt="">\n' +
                '                    </div>\n' +
                '                    <div class="col-md-9">\n' +
                '                        <p class="text-left chitietbe">Cân nặng: '+ d.cannang +'kg - Chiều cao: '+ d.chieucao +'cm</p>\n' +
                '                        <p class="text-left chitietbe">Tình trạng sức khỏe: '+ d.tinhtrangsuckhoe +' - Bệnh bẩm sinh: '+ d.benhbamsinh +' </p>\n' +
                '                        <p class="text-left chitietbe">Tên cha: '+ d.tencha +' - SĐT cha: '+ d.sdtcha +'</p>\n' +
                '                        <p class="text-left chitietbe">Tên mẹ: '+ d.tenme +' - SĐT mẹ: '+ d.sdtme +'</p>\n' +
                '                    </div>\n' +
                '                </div>'
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
            window.location.href = "admin-be-sua.php?id=" + data.be_id;
            // if($(this).data('action') == "1") {
            //     $.ajax( {
            //         type: "GET",
            //         url: "admin-be.php?changeStatusId=" + data.be_id,
            //         success: function ( result ) {
            //             $('.table-data').html($(result).find('.table-data').html());
            //         }
            //     } );
            // }
            // else
            //     window.location.href = "admin-be-sua.php?id=" + data.be_id;
        } );

        // $('#tripRevenue tbody').on( 'click', 'a', function () {
        //     var data = table.row( $(this).parents('tr') ).data();
        //     console.log(data);
        // } );
        $('#tripRevenue tbody').on( 'change', 'input.editor-active', function () {
            var data = table.row( $(this).parents('tr') ).data();
            if(confirm('Bạn có chắc chắn muốn cập nhật trạng thái của bé vừa chọn?')) {
                $.ajax( {
                    type: "GET",
                    url: "admin-be.php?&changeStatusId=" + data.be_id,
                    success: function ( result ) {
                        window.location.reload();
                    }
                } );
            }
        } );
    });

</script>

<!-- Footer-->
<?php include "admin-footer.php";?>
<!-- End footer