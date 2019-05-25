Number.prototype.format = function(n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
};
$(document).ready(function () {

    $('.date_thiet_bi').datepicker({
        format: "mm-yyyy",
        viewMode: "months",
        minViewMode: "months",
        autoclose: true
    });

    $('.date_thiet_bi').datepicker("update", new Date());

    $('.date_thiet_bi').change(function () {
        fill_lai_data();
    });

    $('.ngay_san_xuat').datepicker({
        format: "dd-mm-yyyy",
        autoclose: true
    });

    $('.ngay_het_han').datepicker({
        format: "dd-mm-yyyy",
        autoclose: true
    });

    function fill_lai_data() {
        $.ajax({
            type: "GET",
            url: 'admin-quan-ly-thiet-bi-xu-ly.php?danh_sach_thiet_bi=1&date=01-' + $('.date_thiet_bi').val(),
            success : function (result){
                var data = JSON.parse(result);
                var tb = $('#tripRevenue').dataTable();
                tb.dataTable().fnClearTable();
                if(data.length > 0) tb.dataTable().fnAddData(data);
            }
        });
    }

    function get_danh_sach_thiet_bi() {
        $.ajax({
            type: "GET",
            url: 'admin-quan-ly-thiet-bi-xu-ly.php?danh_sach_thiet_bi=1&date=01-' + $('.date_thiet_bi').val(),
            success: function (result) {
                var data = JSON.parse(result);
                // console.log(data);
                table_lop = $('#tripRevenue').DataTable({
                    language: {
                        "lengthMenu": "Hiển thị _MENU_ nguyên liệu/ trang",
                        "zeroRecords": "Không tìm thấy kết quả",
                        "info": "Hiển thị trang _PAGE_ của _PAGES_ trang",
                        "infoEmpty": "Không có dữ liệu",
                        "infoFiltered": "(Được lọc từ _MAX_ nguyên liệu)",
                        "search": "Tìm kiếm",
                        "paginate": {
                            "previous": "Trở về",
                            "next": "Tiếp"
                        }
                    },
                    data: data,
                    columnDefs: [
                        { targets: 0,orderable: false, data: null },
                        { targets: 1, className: 'dt-body-left' },
                        { targets: 2, orderable: false,className: 'dt-body-center' },
                        { targets: 3, orderable: false,className: 'dt-body-right' },
                        { targets: 4, orderable: false,className: 'dt-body-right' },
                        { targets: 5, orderable: false,className: 'dt-body-right' },
                        { targets: 6, orderable: false,className: 'dt-body-right' },
                        { targets: 7, orderable: false,className: 'dt-body-right' },
                        { targets: 8, orderable: false,className: 'dt-body-right' },
                        { targets: 9, orderable: false,className: 'dt-body-right' },
                        { targets: 10, orderable: false,className: 'dt-body-right' },
                        {
                            targets: 11,
                            orderable: false,
                            data: null,
                            defaultContent: '<a class="edit" data-action="1" style="cursor: pointer" title="Cập nhật nguyên liệu"><i class="material-icons action-icon">edit</i></a> ' +
                                '<a data-action="2" style="cursor: pointer" title="Xóa nguyên liệu"><i class="material-icons action-icon">delete_outline</i></a>'
                        }
                    ],
                    columns: [
                        { width: "30px" },
                        { data: 'ten_thiet_bi', width: '180px' },
                        { data: 'ngay_nhap'},
                        { data: 'ngay_san_xuat'},
                        { data: 'bao_hanh', render: $.fn.dataTable.render.number( ',', '.', 0, '' )},
                        { data: 'so_luong', render: $.fn.dataTable.render.number( ',', '.', 0, '' )},
                        { data: "gia_tien" },
                        { data: "" },
                        { data: "thanh_ly" },
                        { data: "ghi_chu" },
                        { data: "ngay_nhap" },
                        { width: "50px" },
                    ],
                    order: [[ 1, 'asc' ]],
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
                        get_thiet_bi(data.id);
                    }
                    else if ($(this).data('action') == 2){
                        delete_thiet_bi(data.id);
                    }
                    else if ($(this).data('action') == 3){
                        show_list_be(data.id, data.mo_ta, data.nien_khoa_id)
                    }
                });
            }
        });
    }

    function insert_thiet_bi() {
        var ten_thiet_bi = $('input[name="ten_thiet_bi"]').val();
        var gia_tien = $('input[name="gia_tien"]').val();
        var so_luong = $('input[name="so_luong"]').val();
        var dvt = $('input[name="dvt"]').val();
        var ngay_san_xuat = $('input[name="ngay_san_xuat"]').val();
        var ngay_het_han = $('input[name="ngay_het_han"]').val();
        var thanh_ly = $('input[name="thanh_ly"]').val();
        var nien_khoa_id = $('select[name="nien_khoa"]').val();
        var nhan_vien_id = $('#nguoi_dung').val();
        $('#thiet_bi_id').val(0);

        var data = {
            ten_thiet_bi: ten_thiet_bi,
            gia_tien: gia_tien,
            so_luong: so_luong,
            dvt: dvt,
            ngay_san_xuat: ngay_san_xuat,
            ngay_het_han: ngay_het_han,
            thanh_ly: thanh_ly,
            nien_khoa_id: nien_khoa_id,
            nhan_vien_id: nhan_vien_id,
        };
        $.ajax({
            type: "POST",
            url: 'admin-quan-ly-thiet-bi-xu-ly.php',
            data: { 'add_thiet_bi' : 1, data: data },
            success : function (result){
                console.log(result);
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

    function get_thiet_bi(id) {
        $.ajax({
            type: "GET",
            url: 'admin-nguyen-lieu-xu-ly.php?get_thiet_bi=1&id=' + id,
            success : function (result){
                var data = JSON.parse(result);
                $('input[name="ten_thiet_bi"]').val(data.ten_thiet_bi);
                $('input[name="gia_tien"]').val(data.gia_tien);
                $('input[name="so_luong"]').val(data.so_luong);
                $('input[name="dvt"]').val(data.dvt);
                $('#thiet_bi_id').val(data.id);
            }
        });
    }

    function update_thiet_bi() {
        var ten_thiet_bi = $('input[name="ten_thiet_bi"]').val();
        var gia_tien = $('input[name="gia_tien"]').val();
        var so_luong = $('input[name="so_luong"]').val();
        var dvt = $('input[name="dvt"]').val();
        var nhan_vien_id = $('#nguoi_dung').val();
        var id = $('#thiet_bi_id').val();

        var data = {
            ten_thiet_bi: ten_thiet_bi,
            gia_tien: gia_tien,
            so_luong: so_luong,
            dvt: dvt,
            nhan_vien_id: nhan_vien_id,
            id: id
        };

        $.ajax({
            type: "POST",
            url: 'admin-nguyen-lieu-xu-ly.php',
            data: { 'edit_thiet_bi' : 1, data: data },
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

    function delete_thiet_bi(id) {
        if(confirm('Bạn có chắc chắn muốn xóa nguyên liệu vừa chọn?')) {
            $.ajax({
                type: "POST",
                url: 'admin-nguyen-lieu-xu-ly.php',
                data: { 'delete_thiet_bi' : 1, id: id },
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

    get_danh_sach_thiet_bi();

    $('#btn-save').click(function () {
        if($('#thiet_bi_id').val() == 0)
            insert_thiet_bi();
        else
            update_thiet_bi();

    });

});