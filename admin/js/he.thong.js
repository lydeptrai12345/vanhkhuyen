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

    $('#show-modal-nhom-nguoi-dung').click(function () {
        $('#modal-nhom-nguoi-dung').toggle(true);
    });

    $('#btn-dong-nhom').click(function () {
        $('#modal-nhom-nguoi-dung').toggle(false);
    });


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

    $('#btn-show-add-nien-khoa').click(function () {
        set_select_nhan_vien(1);
        $('input[name="ten_nguoi_dung"]').removeAttr('disabled');
        $('input[name="mat_khau"]').val('');
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
            url: 'admin-he-thong-xu-ly.php?danh_sach_nhom_nguoi_dung=1',
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

                    get_phan_quyen_theo_nhom_nguoi_dung($(this).data('id'));
                })
            }
        });
    }

    $('[data-toggle="toggle"]').change(function(){
        $(this).parents().next('.hide').toggle();
    });
    get_danh_sach_chuc_nang_con_theo_cha(0);
    function get_danh_sach_chuc_nang_con_theo_cha(id) {
        $.ajax({
            type: "GET",
            url: 'admin-he-thong-xu-ly.php?danh_sach_nhom_chuc_nang_con_theo_cha=1&id=' + id,
            success : function (result){
                var data = JSON.parse(result);
                console.log(data);
                var str = '';

                if(data.length > 0){
                    data.forEach(function (value) {
                        if(value.nhom_cha == 0){
                            if(value.nhom_con.length > 0) {
                                str += '<tr><td colspan="6"><b style="font-weight: bold">'+ value.ten_nhom +'</b></td></td></tr>';
                                value.nhom_con.forEach(function (item) {
                                    str += '<tr id="'+ item.id +'" class="chuc-nang">\n' +
                                        '                                                        <td style="padding-left: 10px">- '+ item.ten_nhom +'</td>\n' +
                                        '                                                        <td class="text-center"><input class="all" type="checkbox"></td>\n' +
                                        '                                                        <td class="text-center"><input class="cb xem" type="checkbox"></td>\n' +
                                        '                                                        <td class="text-center"><input class="cb them" type="checkbox"></td>\n' +
                                        '                                                        <td class="text-center"><input class="cb sua" type="checkbox"></td>\n' +
                                        '                                                        <td class="text-center"><input class="cb xoa" type="checkbox"></td>\n' +
                                        '                                                    </tr>';
                                })
                            }
                            else{
                                str += '<tr id="'+ value.id +'" class="chuc-nang">\n' +
                                    '                                                        <td style="font-weight: bold">'+ value.ten_nhom +'</td>\n' +
                                    '                                                        <td class="text-center"><input class="all" type="checkbox"></td>\n' +
                                    '                                                        <td class="text-center"><input class="cb xem" type="checkbox"></td>\n' +
                                    '                                                        <td class="text-center"><input class="cb them" type="checkbox"></td>\n' +
                                    '                                                        <td class="text-center"><input class="cb sua" type="checkbox"></td>\n' +
                                    '                                                        <td class="text-center"><input class="cb xoa" type="checkbox"></td>\n' +
                                    '                                                    </tr>';
                            }
                        }
                    });
                }
                else{
                    str = '<tr><td colspan="6" class="text-center"><b>Chưa có chức năng </b></td></td></tr>'
                }
                $('#table_chuc_nang tbody').html(str);

                $('.all').on('click', function () {
                    var parent = $(this).closest('tr');
                    if ($(this).is(':checked')) {
                        parent.find('input').prop('checked', true);
                    }
                    else parent.find('input').prop('checked', false);
                });

                $('.cb').on('click',function () {
                    var e = $(this).closest('tr');
                    // debugger;
                    var all = e.find('.all');
                    var xem = e.find('.xem').is(':checked');
                    var them = e.find('.them').is(':checked');
                    var sua = e.find('.sua').is(':checked');
                    var xoa = e.find('.xoa').is(':checked');
                    if (xem && them && sua && xoa) {
                        all.prop('checked', true);
                    }else{
                        all.prop('checked', false);
                    }
                });
            }
        });
    }

    get_danh_sach_chuc_nang_cha();
    var table_nhom = {};

    function get_danh_sach_nhom_nguoi_dung() {
        $.ajax({
            type: "GET",
            url: 'admin-he-thong-xu-ly.php?danh_sach_nhom_nguoi_dung=1',
            success : function (result){
                var data = JSON.parse(result);


                table_nhom = $('#nhom-nguoi').DataTable({
                    language: {
                        "lengthMenu": "Hiển thị _MENU_ nhóm/ trang",
                        "zeroRecords": "Không tìm thấy kết quả",
                        "info": "Hiển thị trang _PAGE_ của _PAGES_ trang",
                        "infoEmpty": "Không có dữ liệu",
                        "infoFiltered": "(Được lọc từ _MAX_ nhóm)",
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
                        {
                            targets: 2,
                            orderable: false,
                            data: null,
                            defaultContent: '<a class="edit" data-action="1" style="cursor: pointer" title="Cập nhật người dùng"><i class="material-icons action-icon">edit</i></a> ' +
                                '<a data-action="2" style="cursor: pointer" title="Xóa người dùng"><i class="material-icons action-icon">delete_outline</i></a>'
                        }
                    ],
                    columns: [
                        { width: "30px" },
                        { data: 'ten_nhom' },
                        { "width": "60px" },
                    ],
                    order: [[ 1, 'asc' ]]

                });

                // PHẦN THỨ TỰ TABLE
                table_nhom.on( 'order.dt search.dt', function () {
                    table_nhom.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                        cell.innerHTML = i+1;
                    } );
                } ).draw();

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
                $('.dataTables_filter label input').val('');
                $('input[type=search]').val('');
                table_lop = $('#tripRevenue').DataTable({
                    language: {
                        "lengthMenu": "Hiển thị _MENU_ người dùng/ trang",
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
                    searching: false,
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
                            defaultContent: '<a class="edit" data-action="1" style="cursor: pointer" title="Cập nhật người dùng"><i class="material-icons action-icon">edit</i></a> ' +
                                '<a data-action="2" style="cursor: pointer" title="Xóa người dùng"><i class="material-icons action-icon">delete_outline</i></a>'
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
                        get_tai_khoan_nguoi_dung(data.id);
                    }
                    else if ($(this).data('action') == 2){
                        delete_nguyen_lieu(data.id);
                    }
                    else if ($(this).data('action') == 3){
                        show_list_be(data.id, data.mo_ta, data.nien_khoa_id)
                    }
                });

                table_lop.on( 'change', 'input.editor-active', function () {
                    var data = table_lop.row( $(this).parents('tr') ).data();
                    if(data.trang_thai == 1) msg = 'khóa';
                    else msg = 'kích hoạt';

                    if(confirm('Bạn có chắc chắn muốn '+ msg +' người dùng vừa chọn?')) {
                        if(data.trang_thai == 1) type = 0;
                        else type = 1;
                        kich_hoat_nguoi_dung(data.id, type)
                    }
                } );
            }
        });
    }

    function get_danh_sach_nhan_vien_chua_co_tai_khoan(type) {
        var url = "";
        if(type == 1) {
            url = "admin-he-thong-xu-ly.php?danh_sach_nhan_vien_chua_co_tai_khoan=1"
        }
        else {
            url = "admin-he-thong-xu-ly.php?danh_sach_all_nhan_vien=1"
        }
        $.ajax({
            type: "GET",
            url: url,
            success : function (result){
                var data = JSON.parse(result);
                var str = '';
                if(data.length > 0) {
                    data.forEach(function (item) {
                        str += '<option value="'+ item.id +'">'+ item.ho_ten +'</option>';
                    });
                }else { str += '<option value="0">Không có nhân viên chưa có tài khoản</option>'; }

                if(type == 1) $('#nhan_vien_id').html(str);
                else $('#nhan_vien_id_edit').html(str);
            }
        });
    }
    get_danh_sach_nhan_vien_chua_co_tai_khoan(1);
    get_danh_sach_nhan_vien_chua_co_tai_khoan(2);

    set_select_nhan_vien(1);

    function set_select_nhan_vien(type) {
        if(type == 1){
            $('#nhan_vien_add').show();
            $('#nhan_vien_edit').hide();
        }
        else{
            $('#nhan_vien_add').hide();
            $('#nhan_vien_edit').show();
        }
    }

    function insert_nguoi_dung() {
        var ten_nguoi_dung = $('input[name="ten_nguoi_dung"]').val();
        var mat_khau = $('input[name="mat_khau"]').val();
        var nhan_vien_id = $('#nhan_vien_id').val();
        var nhom_nguoi_dung_id = $('#nhom_nguoi_dung_id').val();
        $('#nguoi_dung_id').val(0);

        if(ten_nguoi_dung.length < 6 || ten_nguoi_dung.length > 12){
            $('#err_max_ten_nguoi_dung').show();
            return;
        }
        else $('#err_max_ten_nguoi_dung').hide();

        if(mat_khau.length < 6 || mat_khau.length > 12){
            $('#err_max_mat_khau').show();
            return;
        }else $('#err_max_mat_khau').hide();

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
                var data = JSON.parse(result)[0];

                $('input[name="ten_nguoi_dung"]').val(data.ten_nguoi_dung);
                $('input[name="mat_khau"]').val('');
                $('#nhan_vien_id_edit').val(data.nhan_vien_id);
                $('#nhom_nguoi_dung_id').val(data.nhom_nguoi_dung_id);
                $('#nguoi_dung_id').val(data.id);

                $('input[name="ten_nguoi_dung"]').attr('disabled','disabled');
                $('#nhan_vien_id_edit').attr('disabled','disabled');
                set_select_nhan_vien(2);
            }
        });
    }

    function update_nguoi_dung() {
        var ten_nguoi_dung = $('input[name="ten_nguoi_dung"]').val();
        var mat_khau = $('input[name="mat_khau"]').val();
        var nhan_vien_id = $('#nhan_vien_id').val();
        var nhom_nguoi_dung_id = $('#nhom_nguoi_dung_id').val();
        var id = $('#nguoi_dung_id').val();

        if(ten_nguoi_dung.length < 6 || ten_nguoi_dung.length > 12){
            $('#err_max_ten_nguoi_dung').show();
            return;
        }
        else $('#err_max_ten_nguoi_dung').hide();

        if(mat_khau.length < 6 || mat_khau.length > 12){
            $('#err_max_mat_khau').show();
            return;
        }else $('#err_max_mat_khau').hide();

        var data = {
            ten_nguoi_dung: ten_nguoi_dung,
            mat_khau: mat_khau,
            nhan_vien_id: nhan_vien_id,
            nhom_nguoi_dung_id: nhom_nguoi_dung_id,
            id: id
        };

        $.ajax({
            type: "POST",
            url: 'admin-he-thong-xu-ly.php',
            data: { 'edit_nguoi_dung' : 1, data: data },
            success : function (result){
                console.log(result);

                if(result == "1"){
                    alert('Cập nhật người dùng thành công!');
                    location.reload();
                }
                else if( result == "-1"){
                    alert('Lỗi không cập nhật được người dùng');
                }
                else{
                    $('#err_' + result).show();
                }
            }
        });
    }

    function kich_hoat_nguoi_dung(id, type) {
        $.ajax({
            type: "POST",
            url: 'admin-he-thong-xu-ly.php',
            data: { 'kich_hoat_nguoi_dung' : 1, id: id, type: type },
            success : function (result){
                if(type == 0) msg = "đã bị khóa";
                else msg = "đã được kích hoạt";

                if(result == "1"){
                    alert('Người dùng vừa chọn ' + msg);
                    location.reload();
                }
                else {
                    alert('Đã xảy ra lỗi');
                }
            }
        });
    }

    function delete_nguyen_lieu(id) {
        if(confirm('Bạn có chắc chắn muốn xóa người dùng vừa chọn?')) {
            $.ajax({
                type: "POST",
                url: 'admin-nguyen-lieu-xu-ly.php',
                data: { 'delete_nguyen_lieu' : 1, id: id },
                success : function (result){
                    if(result == "1"){
                        alert('Người dùng vừa chọn đã được xóa!');
                        location.reload();
                    }
                    else {
                        alert('Lỗi không xóa được người dùng vừa chọn!!!');
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
            update_nguoi_dung();

    });

    $('#btn-save-phan-quyen').click(function () {
        get_data_insert_update_phan_quyen();
    });

    function get_data_insert_update_phan_quyen() {
        var arr_checkbox = $('#table_chuc_nang tbody tr.chuc-nang');
        var data = [];
        var nhom_nguoi_dung_id = $('#list-chuc-nang-cha').find('li.active-nhom').data('id');
        arr_checkbox.each(function (idx, item) {
            $(item).find('input.all').is(':checked');
            var obj = {
                id_nhom_nguoi_dung: nhom_nguoi_dung_id,
                id_chuc_nang: $(item).attr('id'),
                allaction: Number($(item).find('input.all').is(':checked')),
                xem: Number($(item).find('input.xem').is(':checked')),
                them: Number($(item).find('input.them').is(':checked')),
                sua: Number($(item).find('input.sua').is(':checked')),
                xoa: Number($(item).find('input.xoa').is(':checked'))
            };

            data.push(obj);
        });
        save_phan_quyen(nhom_nguoi_dung_id, data);
    }

    function save_phan_quyen(nhom_nguoi_dung_id, data) {
        if (typeof nhom_nguoi_dung_id == "undefined" || nhom_nguoi_dung_id <= 0) alert('Vui lòng chọn nhóm người dùng!');
        else {
            $.ajax({
                type: "POST",
                url: 'admin-he-thong-xu-ly.php',
                data: {'add_phan_quyen': 1, id: nhom_nguoi_dung_id, data: data},
                success: function (result) {
                    if (result == "1") {
                        alert('Phân quyền thành công!');
                    }
                    else alert('Lỗi Phân quyền thất bại');
                }
            });
        }
    }

    function get_phan_quyen_theo_nhom_nguoi_dung(nhom_nguoi_dung_id) {
        $.ajax({
            type: "GET",
            url: 'admin-he-thong-xu-ly.php?get_phan_quyen_theo_nhom_nguoi_dung=1&id=' + nhom_nguoi_dung_id,
            success : function (result){
                var data = JSON.parse(result);
                var arr_checkbox = $('#table_chuc_nang tbody tr.chuc-nang');
                arr_checkbox.each(function (idx, item) {
                    if(data.length == 0){
                        $(item).find('input').prop('checked', false);
                    }
                    else{
                        if($(item).attr('id') == data[idx].id_chuc_nang){
                            $(item).find('input.all').prop('checked', (data[idx].allaction == 1) ? true : false);
                            $(item).find('input.xem').prop('checked', (data[idx].xem == 1) ? true : false);
                            $(item).find('input.them').prop('checked', (data[idx].them == 1) ? true : false);
                            $(item).find('input.sua').prop('checked', (data[idx].sua == 1) ? true : false);
                            $(item).find('input.xoa').prop('checked', (data[idx].xoa == 1) ? true : false);
                        }
                    }
                });
            }
        });
    }
    
    function add_nhom_nguoi_dung() {
        $.ajax({
            type: "POST",
            url: 'admin-he-thong-xu-ly.php',
            data: { 'add_nhom_nguoi_dung' : 1, 'ten_nhom': $('input[name="ten_nhom_nguoi_dung"]').val() },
            success : function (result){
                if(result == "1"){
                    alert('Thêm nhóm người dùng thành công!!!');
                    fill_lai_data_nhom_nguoi_dung();
                }
                else {
                    alert('Lỗi không thêm được nhóm người dùng!!!');
                }
            }
        });
    }

    $('#btn-add-nhom-nguoi-dung').click(function () {
        add_nhom_nguoi_dung();
    });

    function fill_lai_data_nhom_nguoi_dung () {
        $.ajax({
            type: "GET",
            url: 'admin-he-thong-xu-ly.php?danh_sach_nhom_nguoi_dung=1',
            success : function (result){
                $('input[name="ten_nhom_nguoi_dung"]').val('');
                $('#modal-nhom-nguoi-dung').toggle(false);
                var data = JSON.parse(result);
                var tb = $('#nhom-nguoi').dataTable();
                tb.dataTable().fnClearTable();
                if(data.length > 0) tb.dataTable().fnAddData(data);

                get_danh_sach_chuc_nang_cha();
            }
        });
    }

});