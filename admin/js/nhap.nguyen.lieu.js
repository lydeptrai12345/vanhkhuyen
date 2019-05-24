
$(document).ready(function () {

    function get_danh_sach_nguyen_lieu() {
        $.ajax({
            type: "GET",
            url: 'admin-nguyen-lieu-xu-ly.php?danh_sach_nguyen_lieu=1',
            success: function (result) {
                var data = JSON.parse(result);
                // console.log(data);
                table_lop = $('#tripRevenue').DataTable({
                    language: {
                        "lengthMenu": "Hiển thị _MENU_ lớp/ trang",
                        "zeroRecords": "Không tìm thấy kết quả",
                        "info": "Hiển thị trang _PAGE_ của _PAGES_ trang",
                        "infoEmpty": "Không có dữ liệu",
                        "infoFiltered": "(Được lọc từ _MAX_ lớp)",
                        "search": "Tìm kiếm",
                        "paginate": {
                            "previous": "Trở về",
                            "next": "Tiếp"
                        }
                    },
                    data: data,
                    columnDefs: [
                        { targets: 0, data: null },
                        { targets: 1, className: 'dt-body-left' },
                        { targets: 2, className: 'dt-body-center' },
                        { targets: 3, className: 'dt-body-center' },
                        {
                            targets: 7,
                            data: null,
                            defaultContent: '<a class="edit" data-action="1" style="cursor: pointer" title="Cập nhật bằng cấp"><i class="material-icons action-icon">edit</i></a> ' +
                                '<a data-action="2" style="cursor: pointer" title="Xóa bằng cấp"><i class="material-icons action-icon">delete_outline</i></a>'
                        }
                    ],
                    columns: [
                        { width: "30px" },
                        { data: 'ten_nguyen_lieu', width: '100px' },
                        { data: 'dvt'},
                        { data: 'so_luong',},
                        { data: 'gia_tien'},
                        { data: 'thanh_tien'},
                        { data: "ngay_nhap" },
                        { width: "50px" },
                    ]
                });

                // PHẦN THỨ TỰ TABLE
                table_lop.on( 'order.dt search.dt', function () {
                    table_lop.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                        cell.innerHTML = i+1;
                    } );
                } ).draw();


                // table_lop.on( 'click', 'a', function () {
                //     var data = table_lop.row( $(this).parents('tr') ).data();
                //
                //     if($(this).data('action') == 1) {
                //         // window.location.href = "admin-lop-sua.php?id=" + data.bang_cap_id;
                //         show_form_edit(data.id)
                //         // $('#myModal').modal('show');
                //     }
                //     else if ($(this).data('action') == 2){
                //         delete_lop_hoc(data.id)
                //     }
                //     else if ($(this).data('action') == 3){
                //         show_list_be(data.id, data.mo_ta, data.nien_khoa_id)
                //     }
                // });
            }
        });
    }

    function insert_nguyen_lieu() {
        var ten_nguyen_lieu = $('input[name="ten_nguyen_lieu"]').val();
        var gia_tien = $('input[name="gia_tien"]').val();
        var so_luong = $('input[name="so_luong"]').val();
        var dvt = $('input[name="dvt"]').val();
        var nhan_vien_id = $('#nguoi_dung').val();

        var data = {
            ten_nguyen_lieu: ten_nguyen_lieu,
            gia_tien: gia_tien,
            so_luong: so_luong,
            dvt: dvt,
            nhan_vien_id: nhan_vien_id,
        };
        $.ajax({
            type: "POST",
            url: 'admin-nguyen-lieu-xu-ly.php',
            data: { 'add_nguyen_lieu' : 1, data: data },
            success : function (result){
                if(result == "1"){
                    alert('Thêm nguyên liệu thành công!');
                    location.reload();
                }
                else if( result == "-1"){
                    alert('Lỗi không thêm được nguyên liệu');
                }
                else{
                    $('#err_' + result).show();
                }
            }
        });
    }

    get_danh_sach_nguyen_lieu();

    $('#btn-save').click(function () {
        insert_nguyen_lieu();
    });

});