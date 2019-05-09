
<?php include "admin-header.php";?>
<?php include "../../inc/myconnect.php";?>
<?php include "../../inc/myfunction.php";?>
<!-- End header-->


<!-- Page content-->
<div class="main-content-container container-fluid px-4">
	<!-- Page Header -->
    <div class="page-header row no-gutters py-4">
        <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
            <span class="text-uppercase page-subtitle">Dashboard</span>
            <h3 class="page-title">Loại tin</h3>
        </div>
    </div>
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
				<div class="card-header border-bottom">
					<form action="admin-loaitin.php" method="get">
						<h5 class="text-info">Danh sách loại tin</h5>
					</form>
				</div>
				<div class="card-body p-0 pb-3 text-center">
					<table class="table mb-0">

						<thead class="bg-light">
							<tr>
								<th scope="col" class="border-0">STT</th>
								<th scope="col" class="border-0">Tên thể loại</th>
                                <th scope="col" class="border-0">Thể loại cha</th>
								<th colspan="2" scope="col" class="border-0">Thao tác</th>
							</tr>
						</thead>
						<?php
                            //đặt số bản ghi cần hiện thị
                            $limit=10;
                            //Xác định vị trí bắt đầu
                            if(isset($_GET['s']) && filter_var($_GET['s'],FILTER_VALIDATE_INT,array('min_range'=>1)))
                            {
                                $start=$_GET['s'];
                            }   
                            else
                            {
                                $start=0;
                            }   
                            if(isset($_GET['p']) && filter_var($_GET['p'],FILTER_VALIDATE_INT,array('min_range'=>1)))
                            {
                                $per_page=$_GET['p'];
                            } 
                            else
                            {
                                //Nếu p không có, thì sẽ truy vấn CSDL để tìm xem có bao nhiêu page
                                $query_pg="SELECT COUNT(id) FROM loaitin";
                                $results_pg=mysqli_query($dbc,$query_pg);
                                list($record)=mysqli_fetch_array($results_pg,MYSQLI_NUM);                       
                                //Tìm số trang bằng cách chia số dữ liệu cho số limit   
                                if($record > $limit)
                                {
                                    $per_page=ceil($record/$limit);
                                }
                                else
                                {
                                    $per_page=1;
                                }
                            }
                            $query = "SELECT * FROM loaitin ORDER BY the_loai_cha ASC LIMIT {$start},{$limit}";
                            $results = mysqli_query($dbc, $query);
                            foreach ($results as $key => $item)
                            { 
                        ?>
						<tbody>
							<tr>
								<td><?php echo ($key + 1) ?></td>
								<td><?php echo $item['ten'] ?></td>
                                <td>
                                    <?php
                                    if($item['the_loai_cha']==0)
                                    {
                                        echo "Không Có";
                                    }
                                    else
                                    {
                                        $query_id="SELECT id,ten FROM loaitin WHERE id={$item['the_loai_cha']}";
                                        $result_id=mysqli_query($dbc,$query_id);
                                        if(mysqli_num_rows($result_id)==1)
                                        {
                                            list($id,$name)=mysqli_fetch_array($result_id,MYSQLI_NUM);
                                            echo $name;
                                        }                                       
                                    }
                                    ?>
                                </td>
								<td>
									<a href="admin-loaitin-sua.php?id=<?php echo $item['id']; ?>"><i class="material-icons action-icon">edit</i></a>
								</td>
								<td>
									<a href="admin-loaitin-xoa.php?id=<?php echo $item['id']; ?>" onclick="return confirm('Bạn có thực sự muốn xóa không <?php echo $item['ten']; ?>');"><i class="material-icons action-icon">delete_outline</i></a>
								</td>
							</tr>
						</tbody>
						<?php } ?>
					</table>
                    <?php
                    echo "<nav aria-label='Page navigation example'>";
                        echo "<ul class='pagination justify-content-center'>";
                        if($per_page > 1)
                        {
                            $current_page=($start/$limit) + 1;
                            //Nếu không phải là trang đầu thì hiện thị trang trước
                            if($current_page !=1)
                            {
                                echo "<li class='page-item' class='float-left'><a class='page-link' href='admin-loaitin.php?s=".($start - $limit)."&p={$per_page}'>Trở về</a></li>";
                            }
                            //hiện thị những phần còn lại của trang
                            for ($i=1; $i <= $per_page ; $i++) 
                            { 
                                if($i != $current_page)
                                {
                                    echo "<li class='page-item'><a class='page-link' href='admin-loaitin.php?s=".($limit *($i - 1))."&p={$per_page}'>{$i}</a></li>";
                                }
                                else
                                {
                                    echo "<li class='page-item' class='active'><a class='page-link'>{$i}</a></li>";
                                }
                            }
                            //Nếu không phải trang cuối thì hiện thị nút next
                            if($current_page != $per_page)
                            {
                                echo "<li class='page-item' ><a class='page-link' href='admin-loaitin.php?s=".($start + $limit)."&p={$per_page}'>Tiếp</a></li>";  
                            }
                        }
                        echo "</ul>";
                    echo "</nav>"          
                    ?>
				</div>
				<!-- End danh sách loại tin -->
			</div>
		</div>
	</div>
    <!-- End Default Light Table -->



</div>
<!-- End page content-->


<!-- Footer-->
<?php include "admin-footer.php";?>
<!-- End footer