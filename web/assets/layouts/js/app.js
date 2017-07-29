jQuery(document).ready(function() {
    $('#contractTimeFrom').persianDatepicker({
        altFormat: "YYYY MM DD ",
        formatDate: 'DD-MM-YYYY',
    });
    $('#contractTimeTo').persianDatepicker({
        altFormat: "YYYY MM DD ",
        formatDate: 'YYYY-MM-DD',
    });

    Table = $('#example').dataTable({
        "data": [],
        "columns": [{
            "title": "id",'data': 'id'
        }, {
            "title": "نام شرکت",'data': 'company_name'
        } , {
            "title": "نام کاربری",'data': 'user_name'
        }, {
            "title": "تاریخ",'data': null,
            render: function ( data, type, row ) {
                var m='unknow';
                if(data.contract_date !=null){
                    var dataCoul=data.contract_date;
                    var am=dataCoul.split('T')
                    var n=am[0];
                    var arrDate=n.split('-');
                    var jdate3 =JalaliDate.gregorianToJalali(arrDate[0],arrDate[1],arrDate[2])
                    m=jdate3[0]+'-'+jdate3[1]+'-'+jdate3[2]
                }
                return m;
            }
        },
            {
                data: null,
                className: "center",
                render: function ( data, type, row ) {
                    return '<a href="contract/'+data.id+'" class="editor_edit">Edit</a>';
                }
            }
        ]
    });

    if ($('.contractData').length > 0) {
        $.ajax({
            url: "/contracts/items",
            type: "GET",
            data: {},
            success: function (response) {
                $('.companiesBuyItems').html('');
                var items = response;
                var html = '';
                $.each(items, function (key, value) {
                    html += '<tr>'
                        + '<td> Tridentسیس </td>'
                        + '<td> Internet Explorer 4.0 </td>'
                        + '<td> Win 95+ </td>'
                        + '<td> 4 </td>'
                        + '<td> X </td>'
                        + '</tr>';
                });

                var table = $('#example').DataTable();
                table.clear().draw();
                table.rows.add(items).draw();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    }
    var jdf = new jDateFunctions();
    var pd = new persianDate();
    var m='';
    //var JDate = require('jalali');
    if($('#appbundle_contract_contractDate').val()){
        var dateStart= $('#appbundle_contract_contractDate').val();
        var arrDate=dateStart.split('-');
        var jdate3 =JalaliDate.gregorianToJalali(arrDate[2],arrDate[1],arrDate[0])
        m=jdate3[0]+'/'+jdate3[1]+'/'+jdate3[2]
        /**
         * Default
         * */
        $('#contractDateextra').persianDatepicker({
            altFormat: "YYYY MM DD ",
            formatDate: 'DD-MM-YYYY',
            selectedBefore: !0,
            selectedDate:m.toString(),
            onSelect: function () {
                $("#appbundle_contract_contractDate").val($("#contractDateextra").attr("data-gdate"))
            }
        });

    }
    else{
        $('#contractDateextra').persianDatepicker({
            altFormat: "YYYY MM DD ",
            formatDate: 'DD-MM-YYYY',
            onSelect: function () {
                $("#appbundle_contract_contractDate").val($("#contractDateextra").attr("data-gdate"))
            }
        });
    }

$('#appbundle_contract_contractPrice').change(function () {
    if($('#appbundle_contract_basePrice').val()!=''){
        var discount=$('#appbundle_contract_basePrice').val()-$('#appbundle_contract_contractPrice').val();
        if(discount>0){
            $('#appbundle_contract_discount').val(discount)
        }
        else{
            alert('مقدار مبلغ قرارداد باید بیشتر از پایه باشد');
            $('#appbundle_contract_contractPrice').val('');
        }
    }

});
    $('#appbundle_contract_basePrice').change(function () {
        if($('#appbundle_contract_contractPrice').val()!=''){
            var discount=$('#appbundle_contract_basePrice').val()-$('#appbundle_contract_contractPrice').val();
            if(discount>0){
                $('#appbundle_contract_discount').val(discount)
            }
            else{
                alert('مقدار مبلغ قرارداد باید بیشتر از پایه باشد');
                $('#appbundle_contract_contractPrice').val('');
            }
        }

    });

    var selectContract = $("select[name='appbundle_contract[contractType]'] option:selected").val();
    $('div[class*="contractI"]').hide();
});
$('.contractType').change(function () {
    var selectContract = $("select[name='appbundle_contract[contractType]'] option:selected").val();
    $('div[class*="contractI"]').hide();
    switch (selectContract){
        case '1':
            $('.contractIRecharge').show();
            break;
        case '2':
            $('.contractIRegister').show();
            break;
        case '3':
            $('.contractIPhone').show();
            break;
        case '4':
            $('.contractITelegram').show();
            break;
        case '5':
            $('.contractIDirect').show();
            break;
    }
});


$('.searchForm').submit(function (e) {
    e.preventDefault();
    var name = $("input[name='companyName']", '.searchForm').val();
    var contractTimeFrom = $("input[name='contractTimeFrom']", '.searchForm').val();
    var contractTimeTo = $("input[name='contractTimeTo']", '.searchForm').val();

    // var values = $(this).serialize();
    $.ajax({
        url: "/contracts/items",
        type: "GET",
        data: {companyName: name, contractTimeFrom: contractTimeFrom,contractTimeTo:contractTimeTo},
        success: function (response) {
            $('.companiesBuyItems').html('');
            var items = response;
            console.log(items)
            var html = '';
            $.each(items, function (key, value) {
                console.log('fsdfdsfsdfsd');
                html += '<tr>'
                    +'<td> Tridentسیس </td>'
                    +'<td> Internet Explorer 4.0 </td>'
                +'<td> Win 95+ </td>'
                +'<td> 4 </td>'
               +'<td> X </td>'
                +'</tr>';
            });

            var table = $('#example').DataTable();
            table.clear().draw();
            table.rows.add(items).draw();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });

});