<?php include "admin-header.php";?>
<?php include "../../inc/myconnect.php";?>
<?php include "../../inc/myfunction.php";?>
<!-- End header-->
<link rel="stylesheet" href="../styles/admin/datatables.min.css">
<script src="../js/datatables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#heading1 .panel-heading').attr('aria-expanded', 'true');
        $('#collapse1').addClass('show');
        $('#collapse1 .list-group a:nth-child(3)').addClass('cus-active');
    });
	</script>
<style>
    .img-tintuc {
        height: 40px ;
        width: 50px;
    }
</style>

<?php
$data_phan_quyen = kiem_tra_quyen_nguoi_dung(9);
?>

<!-- Page content-->
<div class="main-content-container container-fluid px-4"style="margin-top:10px">
	<!-- Page Header -->

    <!-- End Page Header -->

	<!-- Default Light Table -->
	<div class="row">
		<div class="col">
			<div class="card card-small mb-4">
			<!-- Danh sach loại tin -->
				<div class="card-header border-bottom">
					<h5 class="text-info">Danh sách tin tức</h5>
                    <?php if($data_phan_quyen->them): ?>
                        <a id="btn-show-add-nien-khoa" name="them" class="btn btn-success" href="admin-tintuc-them.php">Thêm tin tức</a>
                    <?php endif; ?>

				</div>
				<div class="card-body p-0 pb-3 text-center">
                    <div class="row" style="padding: 5px 20px;">
                        <div class="col-md-12">
                            <table id="tripRevenue" class="table display w-100 hover cell-border compact stripe">
                                <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Hình</th>
                                    <th>Tên tin tức</th>
                                    <th>Loại tin</th>
                                    <th>Người đăng</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
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
    var data_quyen = <?php echo json_encode($data_phan_quyen);?>;
    var phan_quyen = {};
    if(data_quyen.allaction == 0) {
        phan_quyen = {
            them: data_quyen.them,
            sua: data_quyen.sua,
            xoa: data_quyen.xoa
        }
    }
    else{
        phan_quyen = {
            them: 1,
            sua: 1,
            xoa: 1
        }
    }
</script>

<script>
    $(document).ready(function () {
        var table;
        $.ajax({
            type: "GET",
            url: 'admin-tintuc-xuly.php?load_list_tintuc=1',
            success: function (result) {
                var data = JSON.parse(result);
                table = $('#tripRevenue').DataTable({
                    language: {
                        "lengthMenu": "Hiển thị _MENU_ tin tức/ trang",
                        "zeroRecords": "Không tìm thấy kết quả",
                        "info": "Hiển thị trang _PAGE_ của _PAGES_ trang",
                        "infoEmpty": "Không có dữ liệu",
                        "infoFiltered": "(Được lọc từ _MAX_ tin tức)",
                        "search": "Tìm kiếm",
                        "paginate": {
                            "previous": "Trở về",
                            "next": "Tiếp"
                        }
                    },
                    data: data,
                    columnDefs: [
                        { targets: 0, orderable: false,data: null },
                        { targets: 1,orderable: false, className: 'dt-body-left' },
                        { targets: 2, className: 'dt-body-left' },
                        { targets: 3, className: 'dt-body-left' },
                        { targets: 4,orderable: false, className: 'dt-body-left' },
                        {
                            targets: 5,
                            orderable: false,
                            data: null,visible: ((phan_quyen.sua == 0 && phan_quyen.xoa == 0) ? false : true),
                            defaultContent: '<a class="edit-btn '+ ((phan_quyen.sua == 0) ? 'd-none' : '') + '" data-action="1" style="cursor: pointer" title="Cập nhật tin tức"><i class="material-icons action-icon">edit</i></a> ' +
                                '<a data-action="2" class="delete-btn '+ ((phan_quyen.xoa == 0) ? 'd-none' : '') +'" style="cursor: pointer" title="Xóa tin tức"><i class="material-icons action-icon">delete_outline</i></a>'
                        }
                    ],
                    columns: [
                        { width: "30px" },
                        {
                            data:   "hinh",
                            width: "80px",
                            render: function ( data, type, row ) {
                                if ( type === 'display' ) {
                                    return '<img class="img-tintuc" />';
                                }
                                return data;
                            },
                            className: "dt-body-center"
                        },
                        { data: 'tieude', },
                        { data: 'ten', },
                        { data: 'ten_nguoi_dung',},
                        { width: "60px" }
                    ],
                    order: [[ 2, 'asc' ]],
                    rowCallback: function ( row, data ) {
                        // Set the checked state of the checkbox in the table
                        $('img.img-tintuc', row).prop( 'src', '../images/tintuc/' + data.hinh );
                    }
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
                        window.location.href = "admin-tintuc-sua.php?id=" + data.id;
                    }
                    else{
                        if(confirm("Bạn có chắc chắn muốn xóa tin tức vừa chọn")) {
                            window.location.href = "admin-tintuc-xoa.php?id=" + data.id;
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