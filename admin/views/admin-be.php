<?php include "admin-header.php";?>
<?php include "../../inc/myconnect.php";?>
<?php include "../../inc/myfunction.php";?>
<!-- End header-->
<!--copy cai nay-->
<link rel="stylesheet" href="../styles/admin/datatables.min.css">
<script src="../js/datatables.min.js"></script>
<!-- end copy cai nay-->
<!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">-->
<!--<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>-->

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

<div class="main-content-container container-fluid px-4" style="margin-top:10px">
	<div class="row">
		<div class="col">
			<div class="card card-small mb-4">
				<div class="card-header border-bottom">
					<h5 class="text-info salary-h5" style="margin: 7px 0 0 0;display: inline-block">Tìm kiếm bé</h5>
					<a href="admin-be-them.php" class="btn-custom2" style="float: right; margin-right: 20px; font-size: 14px">Thêm bé</a>
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
<!--					<div class="search-container">-->
<!--						<form action="" method="get">-->
<!--							<div class="input-search">-->
<!--								<span class="glyphicon glyphicon-search" style="font-size: 16px;"></span>-->
<!--								<input name="searchKey" class="teeetet" type="text" placeholder="Nhập tên bé" --><?php //if(isset($_GET['searchKey'])) echo 'value="'.$_GET['searchKey'].'"'?><!--/>-->
<!--							</div>-->
<!--							<button name="btnSeach" class="btn-custom2" type="submit">Tìm kiếm</button>-->
<!--						</form>-->
<!--					</div>-->

<!--                    copy cai nay -->
                    <div class="row" style="padding: 5px 20px;">
                        <div class="col-md-12">
                            <table id="tripRevenue" class="table display w-100 hover cell-border compact stripe">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Họ và tên</th>
                                    <th>Giới tính</th>
                                    <th>Ngày sinh</th>
                                    <th>Lớp</th>
                                    <th>Địa chỉ</th>
                                    <th></th>
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
    });
</script>

<!-- Footer-->
<?php include "admin-footer.php";?>
<!-- End footer