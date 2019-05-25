
<?php include "admin-header.php";?>
<?php include "../../inc/myconnect.php";?>
<?php include "../../inc/myfunction.php";?>
<!-- End header-->
<link rel="stylesheet" href="../styles/admin/datatables.min.css">
<script src="../js/datatables.min.js"></script>
<!-- Page content-->
<div class="main-content-container container-fluid px-4"style="margin-top:10px">
	<!-- Page Header -->

    <!-- End Page Header -->

	<!-- Default Light Table -->
	<div class="row">
		<div class="col">
			<div class="card card-small mb-4">
				<?php
            	//Kiểm tra ID có phải là kiểu số không, filter_var kiem tra có thuộc tính trim sẽ loại bỏ khoảng trắng
                if(isset($_GET['id']) && filter_var($_GET['id'],FILTER_VALIDATE_INT,array('min_range'=>1)))
                {
                    $id=$_GET['id'];
                }
                else
                {
                    header('Location: admin-loaitin.php');
                    exit();
                }
                if($_SERVER['REQUEST_METHOD']=='POST') 
                {
                    $errors=array();
                    if(empty($_POST['txtTenTheLoai']))
                    {
                        $errors[]='txtTenTheLoai';
                    }
                    else
                    {
                        $name = $_POST['txtTenTheLoai'];
                    }
                    if(empty($errors))
                    {
                        if($_POST['theloaicha']==0)
                        {
                            $theloaicha = 0;
                        }
                        else 
                        {
                            $theloaicha = $_POST['theloaicha'];
                        }
                        $query="UPDATE loaitin
                                SET ten='{$name}',
                                    the_loai_cha ={$theloaicha}
                                WHERE id={$id}
                        ";
                        $results=mysqli_query($dbc,$query);
                        //Kiểm tra sửa thành công hay không    
                        if(mysqli_affected_rows($dbc)==1)
                        {
                        ?>
                        	<script>
                        		alert("sửa thành công");
								window.location="admin-loaitin.php";
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
                $query_id="SELECT ten, the_loai_cha FROM loaitin WHERE id={$id}";
                $result_id=mysqli_query($dbc,$query_id);
                //Kiểm tra xem ID có tồn tại không
                if(mysqli_num_rows($result_id)==1)
                {
                    list($name,$theloaicha)=mysqli_fetch_array($result_id,MYSQLI_NUM);
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
					<h5 class="text-info">Sửa loại tin</h5>
					<form action="" method="post">
                        <div class="form-group">
                            <label style="display:block">Thể Loại</label>
                            <select class="form-control" name="theloaicha">
                            <option value="0">Vui Lòng Chọn Thể Loại</option>
                            <?php selectCtrl_e($theloaicha); ?>
                            </select>
                        </div>
						<div class="form-group">
							<label>Tên Thể Loại</label>
							<input class="form-control" value="<?php if(isset($name)){ echo $name;} ?>" name="txtTenTheLoai" placeholder="Vui lòng nhập tên thể loại" >
                            <?php 
                                if(isset($errors) && in_array('txtTenTheLoai',$errors))
                                {
                                    echo "<p class='text-danger'>Bạn chưa nhập tên thể loại</p>";
                                }
                            ?>
                        </div>
							<button type="submit" name="xacnhansua" class="btn btn-info">Lưu</button>
                        <a href="admin-tintuc.php" class="btn btn-warning">Quay về</a>
					</form>
				</div>
			<!-- End sua loại tin -->
			<!-- Danh sach loại tin -->
				<!--<div class="card-header border-bottom">
					<form action="admin-loaitin.php" method="get">
						<h5 class="text-info">Danh sách loại tin</h5>
                        <button type="submit" name="them" class="btn btn-defaut"><i class="material-icons action-icon">add</i></button>
					</form>
				</div>
				<div class="card-body p-0 pb-3 text-center">

                    <div class="row" style="padding: 5px 20px;">
                        <div class="col-md-12">
                            <table id="tripRevenue" class="table display w-100 hover cell-border compact stripe">
                                <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên thể loại</th>
                                    <th>Thể loại cha</th>
                                    <th></th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>-->
                    <!-- End danh sách loại tin -->
                </div>
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
            url: 'admin-loaitin-xuly.php?load_list_loaitin=1',
            success: function (result) {
                var data = JSON.parse(result);
                table = $('#tripRevenue').DataTable({
                    language: {
                        "lengthMenu": "Hiển thị _MENU_ thể loại/ trang",
                        "zeroRecords": "Không tìm thấy kết quả",
                        "info": "Hiển thị trang _PAGE_ của _PAGES_ trang",
                        "infoEmpty": "Không có dữ liệu",
                        "infoFiltered": "(Được lọc từ _MAX_ thể loại)",
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
                            defaultContent: '<a class="edit" data-action="1" style="cursor: pointer" title="Cập nhật thể loại"><i class="material-icons action-icon">edit</i></a> ' +
                                '<a data-action="2" style="cursor: pointer" title="Xóa thể loại"><i class="material-icons action-icon">delete_outline</i></a>'
                        }
                    ],
                    columns: [
                        { width: "30px" },
                        { data: 'ten' },
                        { data: 'ten_cha', },
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
                        window.location.href = "admin-loaitin-sua.php?id=" + data.id;
                    }
                    else{
                        if(confirm("Bạn có chắc chắn muốn xóa loại tin vừa chọn")) {
                            window.location.href = "admin-loaitin-xoa.php?id=" + data.id;
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