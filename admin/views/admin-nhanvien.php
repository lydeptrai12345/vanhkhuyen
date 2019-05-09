<?php include "admin-header.php";?>
<?php include "../../inc/myconnect.php";?>
<?php include "../../inc/myfunction.php";?>
<!-- End header-->
<link rel="stylesheet" href="../styles/admin/datatables.min.css">
<script src="../js/datatables.min.js"></script>
<script>
	$( document ).ready( function () {
		$( '#heading6 .panel-heading' ).attr( 'aria-expanded', 'true' );
		$( '#collapse6' ).addClass( 'show' );
		$( '#collapse6 .list-group a:nth-child(1)' ).addClass( 'cus-active' );
	} );
</script>
<?php 
//Kiểm tra ID có phải là kiểu số không, filter_var kiem tra có thuộc tính trim sẽ loại bỏ khoảng trắng
if(isset($_GET['changeStatusId']) && filter_var($_GET['changeStatusId'],FILTER_VALIDATE_INT,array('min_range'=>1)))
{
	$query_nd = "UPDATE nhanvien SET trangthai = !trangthai WHERE id = {$_GET['changeStatusId']}";
    $results_nd= mysqli_query($dbc, $query_nd);
}
?>
<!-- Page content-->
<div class="main-content-container container-fluid px-4">
	<!-- Page Header -->
	<!-- Page Header -->
	<div class="page-header row no-gutters py-4">
		<div class="col-12 col-sm-4 text-center text-sm-left mb-0">
			<span class="text-uppercase page-subtitle">Dashboard</span>
			<h3 class="page-title">Nhân viên</h3>
		</div>
	</div>
	<!-- End Page Header -->

	<!-- Default Light Table -->
	<div class="row">
		<div class="col">
			<div class="card card-small mb-4">
				<!-- Danh sach loại tin -->
				<div class="card-header border-bottom">
					<h5 class="text-info">Danh sách Nhân viên</h5>
					<!-- <a class="btn btn-light" data-toggle="tooltip" title="Thêm Nhân viên" href="admin-nhanvien-them.php"><i class="material-icons action-icon">add</i></a> -->
					<a class="btn btn-light" data-toggle="tooltip" title="Thêm Nhân viên" href="admin-nhanvien-them.php"><i class="material-icons action-icon">add</i></a>
				</div>
				<div class="card-body p-0 pb-3 text-center table-data">
                    <!--                    copy cai nay -->
                    <div class="row" style="padding: 5px 20px;">
                        <div class="col-md-12">
                            <table id="tripRevenue" class="table display w-100 hover cell-border compact stripe">
                                <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Họ và tên</th>
                                    <th>Giới tính</th>
                                    <th>Chức vụ</th>
                                    <th>Phòng ban</th>
                                    <th>Email</th>
                                    <th>Email</th>
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
				<!-- End danh sách loại tin -->
			</div>
		</div>
	</div>
</div>

<script>
    $(document).ready(function () {
        var table;
        $.ajax({
            type: "GET",
            url: 'admin-nhanvien-xuly.php?load_list_nhanvien=1',
            success: function (result) {
                var data = JSON.parse(result);
                table = $('#tripRevenue').DataTable({
                    language: {
                        "lengthMenu": "Hiển thị _MENU_ nhân viên",
                        "zeroRecords": "Không tìm thấy kết quả",
                        "info": "Hiển thị trang _PAGE_ của _PAGES_",
                        "infoEmpty": "Không có dữ liệu",
                        "infoFiltered": "(Được lọc từ _MAX_ nhân viên)",
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
                        { targets: 7, data: null, defaultContent: '<a><i class="material-icons action-icon">edit</i></a>' },
                    ],
                    columns: [
                        { data: 'id' },
                        { data: 'ho_ten' },
                        { data: 'gioi_tinh' },
                        { data: 'ten_cong_viec' },
                        { data: 'ten_phong_ban' },
                        { data: 'email' },
                        { data: '' },
                    ]
                });

                // table = $('#tripRevenue').dataTable();
            }
        });

        function format ( d ) {
            // `d` is the original data object for the row
            return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
                '<tr>'+
                '<td><img class="img-be" src="../images/hinhbe/'+ d.hinhbe +'"> </img>:</td>'+
                '<td>'+d.name+'</td>'+
                '</tr>'+
                '<tr>'+
                '<td>Extension number:</td>'+
                '<td>'+d.extn+'</td>'+
                '</tr>'+
                '<tr>'+
                '<td>Extra info:</td>'+
                '<td>And any further details here (images etc)...</td>'+
                '</tr>'+
                '</table>';
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
            if($(this).data('action') == 1) {
                $.ajax( {
                    type: "GET",
                    url: "admin-nhanvien.php?changeStatusId=" + data.id,
                    success: function ( result ) {
                        $('.table-data').html($(result).find('.table-data').html());
                    }
                } );
            }
            else
                window.location.href = "admin-nhanvien-xem.php?id=" + data.id;
        } );
    });
</script>


<!-- Footer-->
<?php include "admin-footer.php";?>
<!-- End footer