<?php include "admin-header.php";?>
<?php include "../../inc/myconnect.php";?>
<!-- End header-->
<link rel="stylesheet" href="../styles/admin/datatables.min.css">
<script src="../js/datatables.min.js"></script>
<script>
		$( document ).ready( function () {
					$('#heading1 .panel-heading').attr('aria-expanded','true');
					$('#collapse1').addClass('show');
					$('#collapse1 .list-group a:nth-child(2)').addClass('cus-active');
		});
	</script>

<!-- Page content-->
<div class="main-content-container container-fluid px-4">
	<!-- Page Header -->
    <div class="page-header row no-gutters py-4">
        <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
            <span class="text-uppercase page-subtitle">Dashboard</span>
            <h3 class="page-title">Hoạt động</h3>
        </div>
    </div>
    <!-- End Page Header -->

	<!-- Default Light Table -->
	<div class="row">
		<div class="col">
			<div class="card card-small mb-4">
			<!-- Danh sach loại tin -->
				<div class="card-header border-bottom">
					<h5 class="text-info">Danh sách hoạt động</h5>
					<!-- <a class="btn btn-light" data-toggle="tooltip" title="Thêm tin tức" href="admin-tintuc-them.php"><i class="material-icons action-icon">add</i></a> -->
					<a class="btn btn-light" data-toggle="tooltip" title="Thêm tin tức" href="admin-hoatdong-them.php"><i class="material-icons action-icon">add</i></a>
				</div>
				<div class="card-body p-0 pb-3 text-center">
                    <div class="row" style="padding: 5px 20px;">
                        <div class="col-md-12">
                            <table id="tripRevenue" class="table display w-100 hover cell-border compact stripe">
                                <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tiêu đề</th>
                                    <th>Mô tả</th>
                                    <th>Người đăng</th>
                                    <th></th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
				</div>
				<!-- End danh sách loại tin -->
			</div>
		</div>
	</div>
    <!-- End Default Light Table -->



</div>
<!-- End page content-->

<script>
    $(document).ready(function () {
        var table;
        $.ajax({
            type: "GET",
            url: 'admin-hoatdong-xuly.php?load_list_hoatdong=1',
            success: function (result) {
                var data = JSON.parse(result);
                table = $('#tripRevenue').DataTable({
                    language: {
                        "lengthMenu": "Hiển thị _MENU_ hoạt động/ trang",
                        "zeroRecords": "Không tìm thấy kết quả",
                        "info": "Hiển thị trang _PAGE_ của _PAGES_ trang",
                        "infoEmpty": "Không có dữ liệu",
                        "infoFiltered": "(Được lọc từ _MAX_ hoạt động)",
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
                        { targets: 2, className: 'dt-body-center' },
                        { targets: 3, className: 'dt-body-center' },
                        {
                            targets: 4,
                            data: null,
                            defaultContent: '<a class="edit" data-action="1" style="cursor: pointer" title="Cập nhật hoạt động"><i class="material-icons action-icon">edit</i></a> ' +
                                '<a data-action="2" style="cursor: pointer" title="Xóa hoạt động"><i class="material-icons action-icon">delete_outline</i></a>'
                        }
                    ],
                    columns: [
                        { width: "30px" },
                        { data: 'tieu_de' },
                        { data: 'tieu_de'},
                        { data: 'ten_nguoi_dung' },
                        { width: "60px" }
                    ]
                });

                // PHẦN THỨ TỰ TABLE
                table.on( 'order.dt search.dt', function () {
                    table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                        cell.innerHTML = i+1;
                    } );
                } ).draw();


                table.on( 'click', 'a', function () {
                    var data = table.row( $(this).parents('tr') ).data();
                    console.log(data);
                    if($(this).data('action') == 1) {
                        window.location.href = "admin-hoatdong-sua.php?id=" + data.id;
                    }
                    else{
                        if(confirm("Bạn có chắc chắn muốn xóa hoạt động vừa chọn")) {
                            window.location.href = "admin-hoatdong-xoa.php?id=" + data.id;
                        }
                    }
                });
            }
        });




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
    });
</script>
<!-- Footer-->
<?php include "admin-footer.php";?>
<!-- End footer -->