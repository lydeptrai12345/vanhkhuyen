<?php include "admin-header.php";?>
<?php include "../../inc/myconnect.php";?>
<?php include "../../inc/myfunction.php";?>
<!-- End header-->
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
					</form>
				</div>
				<div class="card-body p-0 pb-3 text-center">
					<table class="table mb-0">

						<thead class="bg-light">
							<tr>
								<th scope="col" class="border-0">STT</th>
								<th scope="col" class="border-0">Tên chức vụ</th>
								<th scope="col" class="border-0">Phụ cấp</th>
								<th colspan="2" scope="col" class="border-0">Thao tác</th>
							</tr>
						</thead>
						<?php
						//đặt số bản ghi cần hiện thị
						$limit = 10;
						//Xác định vị trí bắt đầu
						if ( isset( $_GET[ 's' ] ) && filter_var( $_GET[ 's' ], FILTER_VALIDATE_INT, array( 'min_range' => 1 ) ) ) {
							$start = $_GET[ 's' ];
						} else {
							$start = 0;
						}
						if ( isset( $_GET[ 'p' ] ) && filter_var( $_GET[ 'p' ], FILTER_VALIDATE_INT, array( 'min_range' => 1 ) ) ) {
							$per_page = $_GET[ 'p' ];
						} else {
							//Nếu p không có, thì sẽ truy vấn CSDL để tìm xem có bao nhiêu page
							$query_pg = "SELECT COUNT(congviec_id) FROM congviec";
							$results_pg = mysqli_query( $dbc, $query_pg );
							list( $record ) = mysqli_fetch_array( $results_pg, MYSQLI_NUM );
							//Tìm số trang bằng cách chia số dữ liệu cho số limit   
							if ( $record > $limit ) {
								$per_page = ceil( $record / $limit );
							} else {
								$per_page = 1;
							}
						}
						$query = "SELECT * FROM congviec ORDER BY congviec_id ASC LIMIT {$start},{$limit}";
						$results = mysqli_query( $dbc, $query );
						foreach ( $results as $key => $item ) {
							?>
						<tbody>
							<tr>
								<td>
									<?php echo ($key +$start+ 1) ?>
								</td>
								<td>
									<?php echo $item['ten_cong_viec'] ?>
								</td>
								<td>
									<?php if(strlen($item['phucap'])>3){
							for($i = strlen($item['phucap'])-3; $i>0 ;$i-=3){
								$item['phucap'] = substr($item['phucap'],0,$i).".".substr($item['phucap'],$i);
							}
							
						} echo $item['phucap'];?>
								</td>
								<td>
									<a href="admin-congviec-sua.php?id=<?php echo $item['congviec_id']; ?>"><i class="material-icons action-icon">edit</i></a>
								</td>
								<td>
									<a href="admin-congviec-xoa.php?id=<?php echo $item['congviec_id']; ?>" onclick="return confirm('Bạn có thực sự muốn xóa không <?php echo $item['ten']; ?>');"><i class="material-icons action-icon">delete_outline</i></a>
								</td>
							</tr>
						</tbody>
						<?php } ?>
					</table>
					<?php
					echo "<nav aria-label='Page navigation example'>";
					echo "<ul class='pagination justify-content-center'>";
					if ( $per_page > 1 ) {
						$current_page = ( $start / $limit ) + 1;
						//Nếu không phải là trang đầu thì hiện thị trang trước
						if ( $current_page != 1 ) {
							echo "<li class='page-item' class='float-left'><a class='page-link' href='admin-congviec.php?s=" . ( $start - $limit ) . "&p={$per_page}'>Trở về</a></li>";
						}
						//hiện thị những phần còn lại của trang
						for ( $i = 1; $i <= $per_page; $i++ ) {
							if ( $i != $current_page ) {
								echo "<li class='page-item'><a class='page-link' href='admin-congviec.php?s=" . ( $limit * ( $i - 1 ) ) . "&p={$per_page}'>{$i}</a></li>";
							} else {
								echo "<li class='page-item' class='active'><a class='page-link'>{$i}</a></li>";
							}
						}
						//Nếu không phải trang cuối thì hiện thị nút next
						if ( $current_page != $per_page ) {
							echo "<li class='page-item' ><a class='page-link' href='admin-congviec.php?s=" . ( $start + $limit ) . "&p={$per_page}'>Tiếp</a></li>";
						}
					}
					echo "</ul>";
					echo "</nav>"
					?>
				</div>
				<!-- End danh sách phòng ban -->
			</div>
		</div>
	</div>
	<!-- End Default Light Table -->



</div>
<!-- End page content-->


<!-- Footer-->
<?php include "admin-footer.php";?>
<!-- End footer