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
		$( '#collapse6 .list-group a:nth-child(3)' ).addClass( 'cus-active' );
	} );
</script>

<!-- Page content-->
<div class="main-content-container container-fluid px-4">
	<!-- Page Header -->
	<div class="page-header row no-gutters py-4">
		<div class="col-12 col-sm-4 text-center text-sm-left mb-0">
			<span class="text-uppercase page-subtitle">Dashboard</span>
			<h3 class="page-title">Chức vụ</h3>
		</div>
	</div>
	<!-- End Page Header -->

	<!-- Default Light Table -->
	<div class="row">
		<div class="col">
			<div class="card card-small mb-4">
				<?php
				//Kiểm tra ID có phải là kiểu số không
				if ( isset( $_GET[ 'id' ] ) && filter_var( $_GET[ 'id' ], FILTER_VALIDATE_INT, array( 'min_range' => 1 ) ) ) {
					$id = $_GET[ 'id' ];
				} else {
					header( 'Location: admin-congviec.php' );
					exit();
				}
				if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
					$errors = array();
					if ( empty( $_POST[ 'txtTencongviec' ] ) ) {
						$errors[] = 'txtTencongviec';
					} else {
						$tencongviec = $_POST[ 'txtTencongviec' ];
					}
					if(!is_numeric($_POST['txtPhuCap']))
					{
						$errors[] = 'txtPhuCap';
					}
					else
					{
						$phucap = $_POST['txtPhuCap']; 
						if($phucap < 0)
							$errors[] = 'txtPhuCap2';
					}		
					if ( empty( $errors ) ) {
						$query = "UPDATE congviec
                                SET 
                                    ten_cong_viec = '{$tencongviec}',
									phucap = '{$phucap}'
                                WHERE congviec_id = {$id}
                        ";
						$results = mysqli_query( $dbc, $query );
						//Kiểm tra sửa thành công hay không    
						if ( mysqli_affected_rows( $dbc ) == 1 ) {
							?>
				<script>
					alert( "Sửa thành công" );
					window.location = "admin-congviec.php";
				</script>
				<?php
				} else {
					echo "<script>";
					echo 'alert("Sửa không thành công")';
					echo "</script>";
				}
				}
				}
				$query_id = "SELECT ten_cong_viec,phucap FROM congviec WHERE congviec_id = {$id}";
				$result_id = mysqli_query( $dbc, $query_id );
				//Kiểm tra xem ID có tồn tại không
				if ( mysqli_num_rows( $result_id ) == 1 ) {
					list( $tencongviec, $phucap ) = mysqli_fetch_array( $result_id, MYSQLI_NUM );					
				} else {
					$message = "<p class='required'>ID không tồn tại</p>";
				}
				if ( isset( $message ) ) {
					echo $message;
				}
				?>
				<div class="card-header border-bottom">
					<h5 class="text-info">Sửa chức vụ</h5>
					<form action="" method="post">
						<div class="form-group">
							<label>Tên chức vụ</label>
							<input class="form-control" value="<?php if(isset($tencongviec)){ echo $tencongviec;} ?>" name="txtTencongviec" placeholder="Vui lòng nhập tên chức vụ">
							<?php 
                                if(isset($errors) && in_array('txtTencongviec',$errors))
                                {
                                    echo "<p class='text-danger'>Bạn chưa nhập tên chức vụ</p>";
                                }
                            ?>
						</div>
						<div class="form-group">
							<label>Phụ cấp</label>
							<input class="form-control" name="txtPhuCap" value="<?php if(isset($phucap)) {echo $phucap;} ?>">
							<?php 
                                if(isset($errors) && in_array('txtPhuCap',$errors))
                                {
                                    echo "<p class='text-danger'>Phụ cấp chức vụ không hợp lệ</p>";
                                }
								if(isset($errors) && in_array('txtPhuCap2',$errors))
                                {
                                    echo "<p class='text-danger'>Hệ số phụ cấp phải lớn hơn 0</p>";
                                }
                            ?>
						</div>
						<button type="submit" name="xacnhansua" class="btn btn-info">Lưu</button>
                        <a href="admin-congviec.php" class="btn btn-warning">Quay về</a>
					</form>
				</div>
				<!-- End sua phòng ban -->
				<!-- Danh sach phòng ban -->
				<div class="card-header border-bottom">
					<form action="admin-phongban.php" method="get">
						<h5 class="text-info">Danh sách chức vụ</h5>
                        <button type="submit" name="them" class="btn btn-light"><i class="material-icons action-icon">add</i></button>
					</form>
				</div>

                    <div class="card-body p-0 pb-3 text-center">
                        <div class="row" style="padding: 5px 20px;">
                            <div class="col-md-12">
                                <table id="tripRevenue" class="table display w-100 hover cell-border compact stripe">
                                    <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên chức vụ</th>
                                        <th>Phụ cấp</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>



				</div>
				<!-- End danh sách phòng ban -->
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
            url: 'admin-congviec-xuly.php?load_list_congviec=1',
            success: function (result) {
                var data = JSON.parse(result);
                table = $('#tripRevenue').DataTable({
                    language: {
                        "lengthMenu": "Hiển thị _MENU_ công việc/ trang",
                        "zeroRecords": "Không tìm thấy kết quả",
                        "info": "Hiển thị trang _PAGE_ của _PAGES_ trang",
                        "infoEmpty": "Không có dữ liệu",
                        "infoFiltered": "(Được lọc từ _MAX_ công việc)",
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
                        {
                            targets: 3,
                            data: null,
                            defaultContent: '<a class="edit" data-action="1" style="cursor: pointer" title="Cập nhật chức vụ"><i class="material-icons action-icon">edit</i></a> ' +
                                '<a data-action="2" style="cursor: pointer" title="Xóa chức vụ"><i class="material-icons action-icon">delete_outline</i></a>'
                        }
                    ],
                    columns: [
                        { width: "30px" },
                        { data: 'ten_cong_viec' },
                        { data: 'phucap' , width: "100px"},
                        { "width": "60px" },
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
                        window.location.href = "admin-congviec-sua.php?id=" + data.congviec_id;
                    }
                    else{
                        if(confirm("Bạn có chắc chắn muốn xóa chức vụ vừa chọn")) {
                            window.location.href = "admin-congviec -xoa.php?id=" + data.congviec_id;
                        }
                    }
                });
            }
        });
        // table = $('#tripRevenue').dataTable();



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
                    url: "admin-congviec.php?changeStatusId=" + data.id,
                    success: function ( result ) {
                        $('.table-data').html($(result).find('.table-data').html());
                    }
                } );
            }
            else
                window.location.href = "admin-congviec-sua.php?id=" + data.id;
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