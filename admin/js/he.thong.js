Number.prototype.format = function(n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
};
function click_list(){
    $('.item-nhom-chuc-nang').removeClass('active-nhom');
    $(this).addClass('active-nhom');
}
$(document).ready(function () {
    $( "#tabs" ).tabs({ active: '#tabs-1' });


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

    function get_danh_sach_chuc_nang_cha() {
        $.ajax({
            type: "GET",
            url: 'admin-he-thong-xu-ly.php?danh_sach_nhom_chuc_nang=1',
            success : function (result){
                var data = JSON.parse(result);
                var str = '';
                data.forEach(function (item) {
                    str += '<li onclick="click_list()" class="list-group-item item-nhom-chuc-nang" data-id="'+ item.id +'">'+ item.ten_nhom +'</li>';
                });

                $('#list-chuc-nang-cha').html(str);

                $('.item-nhom-chuc-nang').on('click', function () {
                    $('.item-nhom-chuc-nang').removeClass('active-nhom');
                    $(this).addClass('active-nhom');

                    get_danh_sach_chuc_nang_con_theo_cha($(this).data('id'));
                })
            }
        });
    }

    function get_danh_sach_chuc_nang_con_theo_cha(id) {
        $.ajax({
            type: "GET",
            url: 'admin-he-thong-xu-ly.php?danh_sach_nhom_chuc_nang_con_theo_cha=1&id=' + id,
            success : function (result){
                var data = JSON.parse(result);
                var str = '';
                if(data.length > 0){
                    data.forEach(function (item) {
                        str += '<tr id="'+ item.id +'">\n' +
                            '                                                        <td>'+ item.ten_nhom +'</td>\n' +
                            '                                                        <td class="text-center"><input class="all" type="checkbox"></td>\n' +
                            '                                                        <td class="text-center"><input class="xem" type="checkbox"></td>\n' +
                            '                                                        <td class="text-center"><input class="them" type="checkbox"></td>\n' +
                            '                                                        <td class="text-center"><input class="sua" type="checkbox"></td>\n' +
                            '                                                        <td class="text-center"><input class="xoa" type="checkbox"></td>\n' +
                            '                                                    </tr>';
                    });
                }
                else{
                    str = '<tr><td colspan="6" class="text-center"><b>Chức năng này chưa có chức năng con</b></td></td></tr>'
                }
                $('#table_chuc_nang tbody').html(str);

                $('.all').on('click', function () {
                    var parent = $(this).closest('tr');
                    if ($(this).is(':checked')) {
                        parent.find('input').prop('checked', true);
                    }
                    else parent.find('input').prop('checked', false);
                });
            }
        });
    }

    get_danh_sach_chuc_nang_cha();

    function get_danh_sach_nhom_nguoi_dung() {
        $.ajax({
            type: "GET",
            url: 'admin-he-thong-xu-ly.php?danh_sach_nhom_nguoi_dung=1',
            success : function (result){
                var data = JSON.parse(result);
                var str = '';
                if(data.length > 0) {
                    data.forEach(function (item) {
                        str += '<option value="'+ item.id +'">'+ item.ten_nhom +'</option>';
                    });
                }else { str += '<option value="0">Chưa có nhóm người dùng</option>'; }
                $('#nhom_nguoi_dung_id').html(str);
            }
        });
    }
    get_danh_sach_nhom_nguoi_dung();

    function get_danh_sach_nguyen_lieu() {
        $.ajax({
            type: "GET",
            url: 'admin-he-thong-xu-ly.php?danh_sach_tai_khoan=1',
            success: function (result) {
                var data = JSON.parse(result);
                console.log(data)
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
                        {
                            targets: 3,
                            orderable: false,
                            data: null,
                            defaultContent: ''
                        },
                        {
                            targets: 4,
                            orderable: false,
                            data: null,
                            defaultContent: '<a class="edit" data-action="1" style="cursor: pointer" title="Cập nhật nguyên liệu"><i class="material-icons action-icon">edit</i></a> ' +
                                '<a data-action="2" style="cursor: pointer" title="Xóa nguyên liệu"><i class="material-icons action-icon">delete_outline</i></a>'
                        }
                    ],
                    columns: [
                        { width: "30px" },
                        { data: 'ten_nguoi_dung'},
                        { data: 'ten_nhom', width: '180px' },
                        {
                            data:   "trang_thai",
                            width: "80px",
                            render: function ( data, type, row ) {
                                if ( type === 'display' ) {
                                    return '<input type="checkbox" class="editor-active">';
                                }
                                return data;
                            },
                            className: "dt-body-center"
                        },
                        { width: "60px" },
                    ],
                    order: [[ 1, 'asc' ]],
                    rowCallback: function ( row, data ) {
                        $('input.editor-active', row).prop( 'checked', data.trang_thai == 1 );
                    },

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

    function get_danh_sach_nhan_vien_chua_co_tai_khoan() {
        $.ajax({
            type: "GET",
            url: 'admin-he-thong-xu-ly.php?danh_sach_nhan_vien_chua_co_tai_khoan=1',
            success : function (result){
                var data = JSON.parse(result);
                var str = '';
                if(data.length > 0) {
                    data.forEach(function (item) {
                        str += '<option value="'+ item.id +'">'+ item.ho_ten +'</option>';
                    });
                }else { str += '<option value="0">Không có nhân viên chưa có tài khoản</option>'; }
                $('#nhan_vien_id').html(str);
            }
        });
    }
    get_danh_sach_nhan_vien_chua_co_tai_khoan();

    function insert_nguoi_dung() {
        var ten_nguoi_dung = $('input[name="ten_nguoi_dung"]').val();
        var mat_khau = $('input[name="mat_khau"]').val();
        var nhan_vien_id = $('#nhan_vien_id').val();
        var nhom_nguoi_dung_id = $('#nhom_nguoi_dung_id').val();
        $('#nguoi_dung_id').val(0);

        var data = {
            ten_nguoi_dung: ten_nguoi_dung,
            mat_khau: mat_khau,
            nhan_vien_id: nhan_vien_id,
            nhom_nguoi_dung_id: nhom_nguoi_dung_id,
        };
        $.ajax({
            type: "POST",
            url: 'admin-he-thong-xu-ly.php',
            data: { 'add_nguoi_dung' : 1, data: data },
            success : function (result){
                if(result == "1"){
                    alert('Thêm người dùng thành công!');
                    location.reload();
                }
                else if( result == "-1"){
                    alert('Lỗi không thêm được người dùng');
                }
                else{
                    $('#err_' + result).show();
                }
            }
        });
    }

    function get_tai_khoan_nguoi_dung(id) {
        $.ajax({
            type: "GET",
            url: 'admin-he-thong-xu-ly.php?get_nguoi_dung_id=1&id=' + id,
            success : function (result){
                var data = JSON.parse(result);
                $('input[name="ten_nguoi_dung"]').val(data.ten_nguyen_lieu);
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

    $('#btn-save_nguoi_dung').click(function () {
        if($('#nguoi_dung_id').val() == 0)
            insert_nguoi_dung();
        else
            update_nguyen_lieu();

    });

});