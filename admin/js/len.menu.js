Number.prototype.format = function(n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
};
$(document).ready(function () {
    var arr_day_of_week = ["Thứ Hai", "Thứ Ba", "Thứ Tư", "Thứ Năm", "Thứ Sáu"];

    $('.date_menu').datepicker({
        format: "mm-yyyy",
        viewMode: "months",
        minViewMode: "months",
        autoclose: true
    });

    $('.date_menu_create_update').datepicker({
        format: "mm-yyyy",
        viewMode: "months",
        minViewMode: "months",
        autoclose: true
    });



    $('.date_menu').datepicker("update", new Date());
    $('.date_menu_create_update').datepicker("update", new Date());

    $('.date_menu').change(function () {
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

    // $('.btn-toggle-table').click(function () {
    //     $('#week-' + $(this).data('table')).toggle('slow', function () {
    //         $('.btn-' + $(this).data('table')).text('aaa');
    //         // $(this).attr('data-btntext','aaaaa');
    //         // $('.btn-' + $(this).data('table')).text('Hiển thị')
    //         // console.log($(this).data('text', 'aaaa'));
    //     });
    //     $('.btn-' + $(this).data('table')).text('aaa');
    //     $('#aaaa').data('text','aaa');
    //     // $('.btn-' + $(this).data('table')).html()
    //     //
    //     if($(this).text() == 'Ẩn bớt') {
    //         $(this).html('Hiển thị');
    //     }
    //     else {
    //         $(this).html('Ẩn bớt');
    //     }
    // });

    $('#an-1').click(function () {
        var e = $(this);
        $('#week-' + e.data('table')).toggle('slow');
        if(e.text() == 'Ẩn bớt') {
            e.text('Hiển thị');
        }
        else{
            e.text('Ẩn bớt');
        }
    });

    $('#an-2').click(function () {
        var e = $(this);
        $('#week-' + e.data('table')).toggle('slow');
        if(e.text() == 'Ẩn bớt') {
            e.text('Hiển thị');
        }
        else{
            e.text('Ẩn bớt');
        }
    });

    $('#an-3').click(function () {
        var e = $(this);
        $('#week-' + e.data('table')).toggle('slow');
        if(e.text() == 'Ẩn bớt') {
            e.text('Hiển thị');
        }
        else{
            e.text('Ẩn bớt');
        }
    });

    $('#an-4').click(function () {
        var e = $(this);
        $('#week-' + e.data('table')).toggle('slow');
        if(e.text() == 'Ẩn bớt') {
            e.text('Hiển thị');
        }
        else{
            e.text('Ẩn bớt');
        }
    });



    function fill_lai_data() {
        $.ajax({
            type: "GET",
            url: 'admin-quan-ly-thiet-bi-xu-ly.php?danh_sach_menu=1&date=01-01-' + $('.date_menu').val(),
            success : function (result){
                var data = JSON.parse(result);
                var tb = $('#tripRevenue').dataTable();
                tb.dataTable().fnClearTable();
                if(data.length > 0) tb.dataTable().fnAddData(data);
            }
        });
    }

    function get_danh_sach_menu() {
        $.ajax({
            type: "GET",
            url: 'admin-len-menu-xuly.php?get_all_menu=1&date=01-' + $('.date_menu').val(),
            success: function (result) {
                var data = JSON.parse(result);
                console.log(data);
                table_lop = $('#tripRevenue').DataTable({
                    language: {
                        "lengthMenu": "Hiển thị _MENU_ menu/ trang",
                        "zeroRecords": "Không tìm thấy kết quả",
                        "info": "Hiển thị trang _PAGE_ của _PAGES_ trang",
                        "infoEmpty": "Không có dữ liệu",
                        "infoFiltered": "(Được lọc từ _MAX_ menu)",
                        "search": "Tìm kiếm",
                        "paginate": {
                            "previous": "Trở về",
                            "next": "Tiếp"
                        }
                    },
                    data: data,
                    columnDefs: [
                        { targets: 0, orderable: false, className: 'dt-body-center', data: null },
                        {
                            targets: 1,
                            orderable: true,
                            className: 'dt-body-left',
                            defaultContent: '<label class="trang_thai_thanh_ly"></label>'
                        },
                        {
                            targets: 2,
                            orderable: false,
                            className: 'dt-body-center',
                            defaultContent: '<label class="thang_menu"></label>'
                        },
                        {
                            targets: 3,
                            orderable: false,
                            data: null,visible: ((phan_quyen.sua == 0 && phan_quyen.xoa == 0) ? false : true),
                            defaultContent: '<a class="edit-btn '+ ((phan_quyen.sua == 0) ? 'd-none' : '') + '" data-action="1" style="cursor: pointer" title="Cập nhật menu"><i class="material-icons action-icon">edit</i></a> ' +
                                '<a data-action="2" class="delete-btn '+ ((phan_quyen.xoa == 0) ? 'd-none' : '') +'" style="cursor: pointer" title="Xóa menu"><i class="material-icons action-icon">delete_outline</i></a>'
                        }
                    ],
                    columns: [
                        { data: null, width: "30px" },
                        { data: null },
                        { data: null, width: '120px'},
                        { width: "70px" },
                    ],
                    order: [[ 1, 'asc' ]],
                    rowCallback: function ( row, data ) {
                        var str = [];
                        if(data.t2) str.push(data.t2);
                        if(data.t3) str.push(data.t3);
                        if(data.t4) str.push(data.t4);
                        if(data.t5) str.push(data.t5);
                        if(data.t6) str.push(data.t6);
                        if(data.t7) str.push(data.t7);
                        $('label.trang_thai_thanh_ly', row).html(str.join(", "));
                        var date = data.ngay_tao.slice(3);
                        $('label.thang_menu', row).html(date);
                    },
                });

                // PHẦN THỨ TỰ TABLE
                table_lop.on( 'order.dt search.dt', function () {
                    table_lop.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
                } ).draw();


                table_lop.on( 'click', 'a', function () {
                    var data = table_lop.row( $(this).parents('tr') ).data();
                    if($(this).data('action') == 1) {
                        console.log('ffffff');
                        $('#myModal').modal('show');
                        get_menu_theo_thang(data.ngay_tao);
                        $('#menu_id').val(data.ngay_tao);
                        $('.date_menu_create_update').val(data.ngay_tao.slice(3)); // gán ngày tạo vào datepicker

                        $('#btn-save').attr('data-typebtn', '2');
                    }
                    else if ($(this).data('action') == 2){
                        delete_menu(data.ngay_tao);
                    }
                });
            }
        });
    }

    function insert_menu(data_month, type=null) {
        var data = { 'data': (data_month), 'add_menu' : 1, 'date': $('.date_menu_create_update').val() };
        var check = [];
        $.ajax({
            type: "GET",
            url: 'admin-len-menu-xuly.php?check_menu_thang=1&date=01-' + $('.date_menu_create_update').val(),
            success : function (da){
                check = JSON.parse(da);
                if(check.length > 0) {
                    if(type == null) type = 'Menu đã được lên trong tháng này'
                    alert(type);
                }
                else{
                    add_menu_menu(data);
                }
            }
        });
    }

    function add_menu_menu(data) {
        $.ajax({
            type: "POST",
            url: 'admin-len-menu-xuly.php',
            data: data,
            dataType: 'jsonp',
            success : function (result){
                alert('Vo day roi')
            }
        }).done(function() {
            alert( "Lên menu thành công" );
        });

        alert( "Lên menu thành công" );
        location.reload();
    }

    function check_menu_theo_thang(date) {
        var data = [];
        $.ajax({
            type: "GET",
            url: 'admin-len-menu-xuly.php?get_menu_theo_thang=1&date=' + date,
            success : function (result){
                data = JSON.parse(result);
                return data;
            }
        });
        // return data;
    }

    function get_menu_theo_thang(date) {
        $.ajax({
            type: "GET",
            url: 'admin-len-menu-xuly.php?get_menu_theo_thang=1&date=' + date,
            success : function (result){
                var data = JSON.parse(result);
                render_data_table(data);
            }
        });
    }

    function update_menu() {
        $.ajax({
            type: "POST",
            url: 'admin-len-menu-xuly.php',
            data: { 'delete_menu' : 1, date: $('#menu_id').val() },
            success : function (result){
                var data = get_info_menu();
                insert_menu(data, 'Cập nhật menu thành công');
            }
        });
    }

    function delete_menu(id) {
        if(confirm('Bạn có chắc chắn muốn xóa menu vừa chọn?')) {
            $.ajax({
                type: "POST",
                url: 'admin-len-menu-xuly.php',
                data: { 'delete_menu' : 1, date: id },
                success : function (result){
                    if(result == "1"){
                        alert('Menu vừa chọn đã được xóa!');
                        location.reload();
                    }
                    else {
                        alert('Lỗi không xóa được menu vừa chọn!!!');
                    }
                }
            });
        }
    }

    get_danh_sach_menu();

    $('#btn-show-add-nien-khoa').click(function () {
        $('textarea').val('');
        $('#btn-save').attr('data-typebtn', 1);
    });

    $('#btn-save').click(async function () {
        var data = await get_info_menu();
        if($(this).data('typebtn') == '2')
            update_menu(data);
        else
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
                    '                                                    <td class="center-cell">Chiều</td>\n' +
                    '                                                    <td>\n' +
                    '                                                        <textarea name="" id="" rows="1" class="form-control form-control-sm content-menu"></textarea>\n' +
                    '                                                    </td>\n' +
                    '                                                </tr>';
            }
            $('#week-' + i + ' tbody').html(str);
        }
    }

    function render_data_table(data) {
        var i = 1;
        $.each(data, function (key, item) {
            $('#week-' + i + ' tbody tr').each(function (idx, value) {
                $(this).find('textarea').val(item[idx]);
            });
            i++;
        })
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