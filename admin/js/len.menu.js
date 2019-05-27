Number.prototype.format = function(n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
};
$(document).ready(function () {
    var arr_day_of_week = ["Thứ Hai", "Thứ Ba", "Thứ Tư", "Thứ Năm", "Thứ Sáu"];

    $('.date_thiet_bi').datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
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

    $('.btn-toggle-table').click(function () {
        $('#week-' + $(this).data('table')).toggle('slow', function () {
            $('.btn-' + $(this).data('table')).text('aaa');
            // $(this).attr('data-btntext','aaaaa');
            // $('.btn-' + $(this).data('table')).text('Hiển thị')
            // console.log($(this).data('text', 'aaaa'));
        });
        $('.btn-' + $(this).data('table')).text('aaa');
        $('#aaaa').data('text','aaa');
        // $('.btn-' + $(this).data('table')).html()
        //
        if($(this).text() == 'Ẩn bớt') {
            $(this).html('Hiển thị');
        }
        else {
            $(this).html('Ẩn bớt');
        }
    });



    function fill_lai_data() {
        $.ajax({
            type: "GET",
            url: 'admin-quan-ly-thiet-bi-xu-ly.php?danh_sach_thiet_bi=1&date=01-01-' + $('.date_thiet_bi').val(),
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
            url: 'admin-quan-ly-thiet-bi-xu-ly.php?danh_sach_thiet_bi=1&date=01-01-' + $('.date_thiet_bi').val(),
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
                        { targets: 0, orderable: false, className: 'dt-body-center', data: null },
                        { targets: 1, orderable: true, className: 'dt-body-left' },
                        { targets: 2, orderable: true, className: 'dt-body-center' },
                        { targets: 3, orderable: true, className: 'dt-body-right' },
                        { targets: 4, orderable: true, className: 'dt-body-right' },
                        { targets: 5, orderable: true, className: 'dt-body-right' },
                        { targets: 6, orderable: false, data: null,
                            defaultContent: '<label class="trang_thai_thanh_ly"></label>'
                        },
                        {
                            targets: 7,
                            orderable: false,
                            data: null,
                            defaultContent: '<a class="edit-btn" data-action="1" style="cursor: pointer" title="Cập nhật thiết bị"><i class="material-icons action-icon">edit</i></a> ' +
                                '<a data-action="2" class="delete-btn" style="cursor: pointer" title="Xóa thiết bị"><i class="material-icons action-icon">delete_outline</i></a>'
                        }
                    ],
                    columns: [
                        { data: null, width: "30px" },
                        { data: 'ten_thiet_bi', width: '180px' },
                        { data: 'ngay_nhap'},
                        { data: 'so_luong', render: $.fn.dataTable.render.number( ',', '.', 0, '' )},
                        { data: 'gia_tien', render: $.fn.dataTable.render.number( ',', '.', 0, '' )},
                        { data: "thanh_tien", render: $.fn.dataTable.render.number( ',', '.', 0, '' )},
                        { data: null },
                        { width: "70px" },
                    ],
                    order: [[ 2, 'desc' ]],
                    rowCallback: function ( row, data ) {
                        if(data.thanh_ly == 1)
                            $('label.trang_thai_thanh_ly', row).html('Đã thanh lý');
                        else
                            $('label.trang_thai_thanh_ly', row).html('Chưa thanh lý');
                    },
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

    function insert_menu(data_month) {
        console.log(data_month);
        var data = { 'data': (data_month), 'add_menu' : 1 };
        $.ajax({
            type: "POST",
            url: 'admin-len-menu-xuly.php',
            data: data,
            dataType: 'jsonp',
            success : function (result){
                console.log(result);
            }
        });
    }

    function get_thiet_bi(id) {
        $.ajax({
            type: "GET",
            url: 'admin-quan-ly-thiet-bi-xu-ly.php?get_thiet_bi=1&id=' + id,
            success : function (result){
                var data = JSON.parse(result);
                $('input[name="ten_thiet_bi"]').val(data.ten_thiet_bi);

                var gia_tien = Number(data.gia_tien)
                $('input[name="gia_tien"]').val(gia_tien.format());
                $('input[name="so_luong"]').val(data.so_luong);
                $('input[name="dvt"]').val(data.dvt);

                $('.ngay_san_xuat').datepicker('setDate', new Date(data.ngay_san_xuat));
                $('.ngay_het_han').datepicker('setDate', new Date(data.ngay_het_han));
                $('.group-thanh-ly').show();
                $('select[name="thanh_ly"]').val(data.thanh_ly);
                $('select[name="nien_khoa"]').val(data.nien_khoa_id);

                $('#thiet_bi_id').val(data.id);
            }
        });
    }

    function update_thiet_bi() {
        var ten_thiet_bi = $('input[name="ten_thiet_bi"]').val();
        var gia_tien = $('input[name="gia_tien"]').val();
        var so_luong = $('input[name="so_luong"]').val();
        var dvt = $('input[name="dvt"]').val();
        var ngay_san_xuat = $('input[name="ngay_san_xuat"]').val();
        var ngay_het_han = $('input[name="ngay_het_han"]').val();
        var thanh_ly = $('select[name="thanh_ly"]').val();
        var nien_khoa_id = $('select[name="nien_khoa"]').val();
        var nhan_vien_id = $('#nguoi_dung').val();
        var id = $('#thiet_bi_id').val();

        var data = {
            id: id,
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
            data: { 'edit_thiet_bi' : 1, data: data },
            success : function (result){
                console.log(result);

                if(result == "1"){
                    alert('Cập nhật nguyên liệu thành công!');
                    fill_lai_data();
                    $('#myModal').modal('hide');
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
                url: 'admin-quan-ly-thiet-bi-xu-ly.php',
                data: { 'delete_thiet_bi' : 1, id: id },
                success : function (result){
                    if(result == "1"){
                        alert('Nguyên liệu vừa chọn đã được xóa!');
                        fill_lai_data();
                    }
                    else {
                        alert('Lỗi không xóa được nguyên liệu vừa chọn!!!');
                    }
                }
            });
        }
    }

    get_danh_sach_thiet_bi();

    $('btn-show-add-nien-khoa').click(function () {
        $('group-thanh-ly').hide();
    });

    $('#btn-save').click(async function () {
        var data = await get_info_menu();
         insert_menu(data);
    });


    $("input[data-type='currency']").on({
        keyup: function() {
            formatCurrency($(this));
        },
        blur: function() {
            formatCurrency($(this), "blur");
        }
    });


    function formatNumber(n) {
        // format number 1000000 to 1,234,567
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    }


    function formatCurrency(input, blur) {
        // appends $ to value, validates decimal side
        // and puts cursor back in right position.

        // get input value
        var input_val = input.val();

        // don't validate empty input
        if (input_val === "") { return; }

        // original length
        var original_len = input_val.length;

        // initial caret position
        var caret_pos = input.prop("selectionStart");

        // check for decimal
        if (input_val.indexOf(".") >= 0) {

            // get position of first decimal
            // this prevents multiple decimals from
            // being entered
            var decimal_pos = input_val.indexOf(".");

            // split number by decimal point
            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring(decimal_pos);

            // add commas to left side of number
            left_side = formatNumber(left_side);

            // validate right side
            right_side = formatNumber(right_side);

            // On blur make sure 2 numbers after decimal
            if (blur === "blur") {
                right_side += "00";
            }

            // Limit decimal to only 2 digits
            right_side = right_side.substring(0, 2);

            // join number by .
            input_val = left_side + "." + right_side;

        } else {
            // no decimal entered
            // add commas to number
            // remove all non-digits
            input_val = formatNumber(input_val);
            // input_val = "$" + input_val;

            // final formatting
            if (blur === "blur") {
                // input_val += ".00";
            }
        }

        // send updated string to input
        input.val(input_val);

        // put caret back in the right position
        var updated_len = input_val.length;
        caret_pos = updated_len - original_len + caret_pos;
        input[0].setSelectionRange(caret_pos, caret_pos);
    }

    $('.content-menu').keydown(function () {
        var el = this;
        setTimeout(function(){
            el.style.cssText = 'height:auto; padding:0';
            // for box-sizing other than "content-box" use:
            // el.style.cssText = '-moz-box-sizing:content-box';
            el.style.cssText = 'height:' + (el.scrollHeight + 16) + 'px';
        },0);
    });

    function render_table() {
        for (var i = 1; i <= 4; i++) {
            var str = null;
            for (var j = 0; j < arr_day_of_week.length; j++) {
                str += '<tr class="thu-'+ (j+2) +'">\n' +
                    '                                                    <td rowspan="3" class="center-cell">'+ arr_day_of_week[j] +'</td>\n' +
                    '                                                    <td class="center-cell">Sáng</td>\n' +
                    '                                                    <td>\n' +
                    '                                                        <textarea name="" id="" rows="1" class="form-control form-control-sm content-menu"></textarea>\n' +
                    '                                                    </td>\n' +
                    '                                                </tr>\n' +
                    '                                                <tr class="thu-'+ (j+2) +'">\n' +
                    '                                                    <td class="center-cell">Trưa</td>\n' +
                    '                                                    <td>\n' +
                    '                                                        <textarea name="" id="" rows="1" class="form-control form-control-sm content-menu"></textarea>\n' +
                    '                                                    </td>\n' +
                    '                                                </tr>\n' +
                    '                                                <tr class="thu-'+ (j+2) +'">\n' +
                    '                                                    <td class="center-cell">Tối</td>\n' +
                    '                                                    <td>\n' +
                    '                                                        <textarea name="" id="" rows="1" class="form-control form-control-sm content-menu"></textarea>\n' +
                    '                                                    </td>\n' +
                    '                                                </tr>';
            }
            $('#week-' + i + ' tbody').html(str);
        }
    }
    
    function get_info_menu() {
        var week_of_month = {};
        for (var i = 1; i <= 4; i++) {
            $('#week-' + i + ' tbody tr').each(function () {
                var day_of_week = {};
                for (var j = 0; j < arr_day_of_week.length; j++) {
                    var buoi = [];
                    $('#week-' + i + ' tbody tr.thu-' + (j+2)).each(function () {
                        buoi.push($(this).find('textarea').val());
                    });
                    day_of_week['thu_' + (j+2)] = JSON.stringify(buoi);
                    buoi = [];
                }
                week_of_month['week_' + i] = (day_of_week);
            });
        }
        return week_of_month;
    }

    render_table();
});