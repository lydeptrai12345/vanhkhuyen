Number.prototype.format = function(n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
};


$(document).ready(function () {

    $('.date_nguyen_lieu').datepicker({
        format: "mm-yyyy",
        viewMode: "months",
        minViewMode: "months",
        autoclose: true
    });

    $('.date_nguyen_lieu').datepicker("update", new Date());

    $('.date_nguyen_lieu').change(function () {
        fill_lai_data();
    });

    function fill_lai_data() {
        $.ajax({
            type: "GET",
            url: 'admin-nguyen-lieu-xu-ly.php?danh_sach_nguyen_lieu=1&date=01-' + $('.date_nguyen_lieu').val(),
            success : function (result){
                var data = JSON.parse(result);
                var tb = $('#tripRevenue').dataTable();
                tb.dataTable().fnClearTable();
                if(data.length > 0) tb.dataTable().fnAddData(data);
            }
        });
    }

    function get_danh_sach_nguyen_lieu() {
        $.ajax({
            type: "GET",
            url: 'admin-nguyen-lieu-xu-ly.php?danh_sach_nguyen_lieu=1&date=01-' + $('.date_nguyen_lieu').val(),
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
                        { targets: 3, className: 'dt-body-right' },
                        { targets: 4, className: 'dt-body-right' },
                        { targets: 5, className: 'dt-body-right' },
                        {
                            targets: 7,
                            orderable: false,
                            data: null,
                            defaultContent: '<a class="edit" data-action="1" style="cursor: pointer" title="Cập nhật nguyên liệu"><i class="material-icons action-icon">edit</i></a> ' +
                                '<a data-action="2" style="cursor: pointer" title="Xóa nguyên liệu"><i class="material-icons action-icon">delete_outline</i></a>'
                        }
                    ],
                    columns: [
                        { width: "30px" },
                        { data: 'ten_nguyen_lieu', width: '180px' },
                        { data: 'dvt'},
                        { data: 'so_luong', render: $.fn.dataTable.render.number( ',', '.', 1, '' )},
                        { data: 'gia_tien', render: $.fn.dataTable.render.number( ',', '.', 0, '' )},
                        { data: 'thanh_tien', render: $.fn.dataTable.render.number( ',', '.', 0, '' )},
                        { data: "ngay_nhap" },
                        { width: "50px" },
                    ],
                    "footerCallback": function ( row, data, start, end, display ) {
                        var api = this.api(), data;

                        // Remove the formatting to get integer data for summation
                        var intVal = function ( i ) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '')*1 :
                                typeof i === 'number' ?
                                    i : 0;
                        };

                        // Total over all pages
                        total = api
                            .column( 5 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        // Total over this page
                        pageTotal = api
                            .column( 5, { page: 'current'} )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        // $sum = pageTotal.format();

                        // Update footer
                        $( api.column( 5 ).footer() ).html(
                            'Tổng: ' + pageTotal.format() +' ('+ total.format() +' tổng cộng)'
                        );
                    }
                });

                // PHẦN THỨ TỰ TABLE
                table_lop.on( 'order.dt search.dt', function () {
                    table_lop.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                        cell.innerHTML = i+1;
                    } );
                } ).draw();


                table_lop.on( 'click', 'a', function () {
                    var data = table_lop.row( $(this).parents('tr') ).data();
                    if($(this).data('action') == 1) {
                        $('#myModal').modal('show');
                        get_nguyen_lieu(data.id);
                    }
                    else if ($(this).data('action') == 2){
                        delete_nguyen_lieu(data.id);
                    }
                    else if ($(this).data('action') == 3){
                        show_list_be(data.id, data.mo_ta, data.nien_khoa_id)
                    }
                });
            }
        });
    }

    function insert_nguyen_lieu() {
        var ten_nguyen_lieu = $('input[name="ten_nguyen_lieu"]').val();
        var gia_tien = $('input[name="gia_tien"]').val();
        var so_luong = $('input[name="so_luong"]').val();
        var dvt = $('input[name="dvt"]').val();
        var nhan_vien_id = $('#nguoi_dung').val();
        $('#nguyen_lieu_id').val(0);

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

    function get_nguyen_lieu(id) {
        $.ajax({
            type: "GET",
            url: 'admin-nguyen-lieu-xu-ly.php?get_nguyen_lieu=1&id=' + id,
            success : function (result){
                var data = JSON.parse(result);
                $('input[name="ten_nguyen_lieu"]').val(data.ten_nguyen_lieu);
                $('input[name="gia_tien"]').val(data.gia_tien);
                $('input[name="so_luong"]').val(data.so_luong);
                $('input[name="dvt"]').val(data.dvt);
                $('#nguyen_lieu_id').val(data.id);
            }
        });
    }

    function update_nguyen_lieu() {
        var ten_nguyen_lieu = $('input[name="ten_nguyen_lieu"]').val();
        var gia_tien = $('input[name="gia_tien"]').val();
        var so_luong = $('input[name="so_luong"]').val();
        var dvt = $('input[name="dvt"]').val();
        var nhan_vien_id = $('#nguoi_dung').val();
        var id = $('#nguyen_lieu_id').val();

        var data = {
            ten_nguyen_lieu: ten_nguyen_lieu,
            gia_tien: gia_tien,
            so_luong: so_luong,
            dvt: dvt,
            nhan_vien_id: nhan_vien_id,
            id: id
        };

        $.ajax({
            type: "POST",
            url: 'admin-nguyen-lieu-xu-ly.php',
            data: { 'edit_nguyen_lieu' : 1, data: data },
            success : function (result){
                console.log(result);

                if(result == "1"){
                    alert('Cập nhật nguyên liệu thành công!');
                    location.reload();
                }
                else if( result == "-1"){
                    alert('Lỗi không cập nhật được nguyên liệu');
                }
                else{
                    $('#err_' + result).show();
                }
            }
        });
    }

    function delete_nguyen_lieu(id) {
        if(confirm('Bạn có chắc chắn muốn xóa nguyên liệu vừa chọn?')) {
            $.ajax({
                type: "POST",
                url: 'admin-nguyen-lieu-xu-ly.php',
                data: { 'delete_nguyen_lieu' : 1, id: id },
                success : function (result){
                    if(result == "1"){
                        alert('Nguyên liệu vừa chọn đã được xóa!');
                        location.reload();
                    }
                    else {
                        alert('Lỗi không xóa được nguyên liệu vừa chọn!!!');
                    }
                }
            });
        }
    }

    get_danh_sach_nguyen_lieu();

    $('#btn-save').click(function () {
        if($('#nguyen_lieu_id').val() == 0)
            insert_nguyen_lieu();
        else
            update_nguyen_lieu();

    });

});