
<?php include "admin-header.php";?>
<?php include "../../inc/myconnect.php";?>
<?php include "../../inc/myfunction.php";?>
<link rel="stylesheet" href="../styles/admin/datatables.min.css">
<script src="../js/datatables.min.js"></script>
<script>

    $('#heading1 .panel-heading').attr('aria-expanded','true');
    $('#collapse1').addClass('show');
    $('#collapse1 .list-group a:nth-child(1)').addClass('cus-active');
</script>
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
                if(isset($_GET['them']))
                {
                    ?>
                    <!-- Thêm loại tin -->
                    <?php
                if(isset($_POST['xacnhanthem']))
                {
                    $errors = array();
                    if(empty($_POST['txtTenTheLoai']))
                    {
                        $errors[] = 'txtTenTheLoai';
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
                    $query = "INSERT INTO loaitin(ten,the_loai_cha) VALUES('{$name}',$theloaicha)";
                    $results = mysqli_query($dbc, $query);
                    //Kiem tra them moi thanh cong hay chua
                if(mysqli_affected_rows($dbc)==1)
                {
                    ?>
                    <script>
                        alert("thêm thành công");
                        window.location="admin-loaitin.php";
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
                        <h5 class="text-info">Thêm loại tin</h5>
                        <form action="" method="post">
                            <div class="form-group">
                                <label style="display:block">Thể Loại</label>
                                <select class="form-control" name="theloaicha">
                                    <option value="0">Vui Lòng Chọn Thể Loại</option>
                                    <?php selectCtrl(); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tên Thể Loại</label>
                                <input class="form-control" name="txtTenTheLoai" placeholder="Vui lòng nhập tên thể loại" value = "<?php if(isset($_POST['txtTenTheLoai'])) {echo $_POST['txtTenTheLoai'];} ?>">
                                <?php
                                if(isset($errors) && in_array('txtTenTheLoai',$errors))
                                {
                                    echo "<p class='text-danger'>Bạn chưa nhập tên thể loại</p>";
                                }
                                ?>
                            </div>
                            <button type="submit" name="xacnhanthem" class="btn btn-info">Thêm Thông Tin</button>
                            <a href="admin-loaitin.php" class="btn btn-warning">Quay về</a>
                        </form>
                    </div>
                    <?php
                }
                ?>
                <!-- End thêm loại tin -->
                <!-- Danh sach loại tin -->
                <div class="card-header border-bottom">
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
                        { data: 'the_loai_cha', },
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