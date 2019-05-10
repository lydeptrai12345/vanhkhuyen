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
					$('#collapse6 .list-group a:nth-child(2)').addClass('cus-active');
		});
	</script>
<!-- Page content-->
<div class="main-content-container container-fluid px-4">
	<!-- Page Header -->
    <div class="page-header row no-gutters py-4">
        <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
            <span class="text-uppercase page-subtitle">Dashboard</span>
            <h3 class="page-title">Bằng cấp</h3>
        </div>
    </div>
    <!-- End Page Header -->

	<!-- Default Light Table -->
	<div class="row">
		<div class="col">
			<div class="card card-small mb-4">
				<?php
            	//Kiểm tra ID có phải là kiểu số không
                if(isset($_GET['id']) && filter_var($_GET['id'],FILTER_VALIDATE_INT,array('min_range'=>1)))
                {
                    $id=$_GET['id'];
                }
                else
                {
                    header('Location: admin-bangcap.php');
                    exit();
                }
                if($_SERVER['REQUEST_METHOD']=='POST') 
                {
                    $errors=array();
                    if(empty($_POST['txtTenbangcap']))
                    {
                        $errors[]='txtTenbangcap';
                    }
                    else
                    {
                        $name = $_POST['txtTenbangcap'];
                    }
					if(!is_numeric($_POST['txtHeSo']))
					{
						$errors[] = 'txtHeSo';
					}
					else
					{
						$heso = $_POST['txtHeSo']; 
						if($heso <= 0)
							$errors[] = 'txtHeSo2';
					}
                    if(empty($errors))
                    {
                        $query="UPDATE bangcap
                                SET ten_bang_cap='{$name}',
									heso='{$heso}'
                                WHERE bang_cap_id={$id}
                        ";
                        $results=mysqli_query($dbc,$query);
                        //Kiểm tra sửa thành công hay không    
                        if(mysqli_affected_rows($dbc)==1)
                        {
                        ?>
                        	<script>
                        		alert("sửa thành công");
								window.location="admin-bangcap.php";
                        	</script>
                        <?php
                        }
                        else
                        {
                            echo "<script>";
                        	echo 'alert("Sửa không thành công")';
                        	echo "</script>";   
                        }
                    }
                }
                $query_id="SELECT ten_bang_cap,heso FROM bangcap WHERE bang_cap_id={$id}";
                $result_id=mysqli_query($dbc,$query_id);
                //Kiểm tra xem ID có tồn tại không
                if(mysqli_num_rows($result_id)==1)
                {
                    list($name,$heso)=mysqli_fetch_array($result_id,MYSQLI_NUM);
                }
                else
                {
                    $message="<p class='required'>ID không tồn tại</p>";  
                }
                if(isset($message))
                {
                    echo $message;
                }
                ?>
				<div class="card-header border-bottom">
					<h5 class="text-info">Sửa bằng cấp</h5>
					<form action="" method="post">
						<div class="form-group">
							<label>Tên Bằng Cấp</label>
							<input class="form-control" value="<?php if(isset($name)){ echo $name;} ?>" name="txtTenbangcap" placeholder="Vui lòng nhập tên bằng cấp" >
                            <?php 
                                if(isset($errors) && in_array('txtTenbangcap',$errors))
                                {
                                    echo "<p class='text-danger'>Bạn chưa nhập tên bằng cấp</p>";
                                }
                            ?>
                        </div>
						<div class="form-group">
							<label>Hệ Số</label>
							<input class="form-control" name="txtHeSo" placeholder="Vui lòng nhập hệ số" value = "<?php if(isset($heso)){ echo $heso;} ?>">
							<?php 
                                if(isset($errors) && in_array('txtHeSo',$errors))
                                {
                                    echo "<p class='text-danger'>Hệ số không hợp lệ</p>";
                                }
				 				if(isset($errors) && in_array('txtHeSo2',$errors))
                                {
                                    echo "<p class='text-danger'>Hệ số phải lớn hơn 0</p>";
                                }
                            ?>
                        </div>
							<button type="submit" name="xacnhansua" class="btn btn-info">Lưu</button>
                        <a href="admin-bangcap.php" class="btn btn-warning">Quay về</a>
					</form>
				</div>
			<!-- End sua bằng cấp -->
			<!-- Danh sach bằng cấp -->

                    <!-- Danh sach loại tin -->
                    <div class="card-header border-bottom">
                        <form action="admin-bangcap.php" method="get">
                            <h5 class="text-info">Danh sách bằng cấp</h5>
                            <button type="submit" name="them" class="btn btn-light"><i class="material-icons action-icon">add</i></button>
                        </form>
                    </div>
                <div class="card-body p-0 pb-3 text-center">
                    <div class="row" style="padding: 5px 20px;">
                        <div class="col-md-12">
                            <table id="tripRevenue" class="table display w-100 hover cell-border compact stripe">
                                <thead>
                                <tr>
                                    <th >STT</th>
                                    <th>Tên bằng cấp</th>
                                    <th>Hệ số</th>
                                    <th></th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
				<!-- End danh sách bằng cấp -->
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
            url: 'admin-bangcap-xuly.php?load_list_bangcap=1',
            success: function (result) {
                var data = JSON.parse(result);
                table = $('#tripRevenue').DataTable({
                    language: {
                        "lengthMenu": "Hiển thị _MENU_ bằng cấp/ trang",
                        "zeroRecords": "Không tìm thấy kết quả",
                        "info": "Hiển thị trang _PAGE_ của _PAGES_ trang",
                        "infoEmpty": "Không có dữ liệu",
                        "infoFiltered": "(Được lọc từ _MAX_ bằng cấp)",
                        "search": "Tìm kiếm",
                        "paginate": {
                            "previous": "Trở về",
                            "next": "Tiếp"
                        }
                    },
                    data: data,
                    columnDefs: [
                        { targets: 0, data: null,className: 'dt-body-center' },
                        { targets: 1, className: 'dt-body-center' },
                        { targets: 2, className: 'dt-body-center' },
                        {
                            targets: 3,
                            data: null,
                            defaultContent: '<a class="edit" data-action="1" style="cursor: pointer" title="Cập nhật bằng cấp"><i class="material-icons action-icon">edit</i></a> ' +
                                '<a data-action="2" style="cursor: pointer" title="Xóa bằng cấp"><i class="material-icons action-icon">delete_outline</i></a>'
                        }
                    ],
                    columns: [
                        { width: "30px" },
                        { data: 'ten_bang_cap' },
                        { data: 'heso', width: "100px" },
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
                        window.location.href = "admin-bangcap-sua.php?id=" + data.bang_cap_id;
                    }
                    else{
                        if(confirm("Bạn có chắc chắn muốn xóa bằng cấp vừa chọn")) {
                            window.location.href = "admin-bangcap-xoa.php?id=" + data.bang_cap_id;
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
<!-- End footer