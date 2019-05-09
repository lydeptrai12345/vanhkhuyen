<?php include "admin-header.php";?>
<?php include "../../inc/myconnect.php";?>
<?php include "../../inc/myfunction.php";?>
<!-- End header-->
<link rel="stylesheet" href="../styles/admin/datatables.min.css">
<script src="../js/datatables.min.js"></script>
<script>
		$( document ).ready( function () {
					$('#heading6 .panel-heading').attr('aria-expanded','true');
					$('#collapse6').addClass('show');
					$('#collapse6 .list-group a:nth-child(4)').addClass('cus-active');
		});
	</script>
<!-- Page content-->
<div class="main-content-container container-fluid px-4">
	<!-- Page Header -->
    <div class="page-header row no-gutters py-4">
        <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
            <span class="text-uppercase page-subtitle">Dashboard</span>
            <h3 class="page-title">Phòng ban</h3>
        </div>
    </div>
    <!-- End Page Header -->

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
					if(empty($_POST['txtTenphongban']))
					{
						$errors[] = 'txtTenphongban';
					}
					else
					{
						$name = $_POST['txtTenphongban']; 
					}
					if(empty($errors))
					{
						$query = "INSERT INTO phongban(ten_phong_ban) VALUES('{$name}')";
						$results = mysqli_query($dbc, $query);
						//Kiem tra them moi thanh cong hay chua
						if(mysqli_affected_rows($dbc)==1)
                        {
                    ?>
                        	<script>
                        		alert("thêm thành công");
								window.location="admin-phongban.php";
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
					<h5 class="text-info">Thêm phòng ban</h5>
					<form action="" method="post">
						<div class="form-group">
							<label>Tên Phòng Ban</label>
							<input class="form-control" name="txtTenphongban" placeholder="Vui lòng nhập tên phòng ban" value = "<?php if(isset($_POST['txtTenphongban'])) {echo $_POST['txtTenphongban'];} ?>">
							<?php 
                                if(isset($errors) && in_array('txtTenphongban',$errors))
                                {
                                    echo "<p class='text-danger'>Bạn chưa nhập tên phòng ban</p>";
                                }
                            ?>
                        </div>
							<button type="submit" name="xacnhanthem" class="btn btn-info">Thêm Thông Tin</button>
                        <a href="admin-phongban.php" class="btn btn-warning">Quay về</a>
					</form>
				</div>
			<?php
			}
			?>
			<!-- End thêm loại tin -->
			<!-- Danh sach loại tin -->
				<div class="card-header border-bottom">
					<form action="admin-phongban.php" method="get">
						<h5 class="text-info">Danh sách phòng ban</h5>
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
                                    <th>Tên phòng ban</th>
                                    <th></th>
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
            url: 'admin-phongban-xuly.php?load_list_phongban=1',
            success: function (result) {
                var data = JSON.parse(result);
                table = $('#tripRevenue').DataTable({
                    language: {
                        "lengthMenu": "Hiển thị _MENU_ phòng ban/ trang",
                        "zeroRecords": "Không tìm thấy kết quả",
                        "info": "Hiển thị trang _PAGE_ của _PAGES_ trang",
                        "infoEmpty": "Không có dữ liệu",
                        "infoFiltered": "(Được lọc từ _MAX_ phòng ban)",
                        "search": "Tìm kiếm",
                        "paginate": {
                            "previous": "Trở về",
                            "next": "Tiếp"
                        }
                    },
                    data: data,
                    columnDefs: [
                        { targets: 1, className: 'dt-body-left' },
                        { targets: 2, data: null, defaultContent: '<a style="cursor: pointer" title="Cập nhật phòng ban"><i class="material-icons action-icon">edit</i></a>' },
                        { targets: 3, data: null, defaultContent: '<a style="cursor: pointer" title="Xóa phòng ban"><i class="material-icons action-icon">delete_outline</i></a>' },
                    ],
                    columns: [
                        { data: 'phong_ban_id' },
                        { data: 'ten_phong_ban' },
                        { "width": "50px" },
                    ]
                });

                // table = $('#tripRevenue').dataTable();
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

        $('#tripRevenue tbody').on( 'click', 'a', function () {
            var data = table.row( $(this).parents('tr') ).data();
            if($(this).data('action') == 1) {
                $.ajax( {
                    type: "GET",
                    url: "admin-phongban.php?changeStatusId=" + data.id,
                    success: function ( result ) {
                        $('.table-data').html($(result).find('.table-data').html());
                    }
                } );
            }
            else
                window.location.href = "admin-phongban-sua.php?id=" + data.id;
        } );

        $('#tripRevenue tbody').on( 'change', 'input.editor-active', function () {
            var data = table.row( $(this).parents('tr') ).data();
            if(confirm('Bạn có chắc chắn muốn cập nhật trạng thái của nhân viên vừa chọn?')) {
                $.ajax( {
                    type: "GET",
                    url: "admin-nhanvien.php?&changeStatusId=" + data.id,
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