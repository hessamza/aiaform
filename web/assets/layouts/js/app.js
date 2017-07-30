jQuery(document).ready(function() {
    $('#contractTimeFrom').persianDatepicker({
        altFormat: "YYYY MM DD ",
        formatDate: 'YYYY-MM-DD',
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

    if($('#appbundle_contract_contractStartDate').val()){
        var dateStart= $('#appbundle_contract_contractStartDate').val();
        var arrDate=dateStart.split('-');
        var jdate3 =JalaliDate.gregorianToJalali(arrDate[2],arrDate[1],arrDate[0])
        m=jdate3[0]+'/'+jdate3[1]+'/'+jdate3[2]
        /**
         * Default
         * */
        $('#contractDateStartextra').persianDatepicker({
            altFormat: "YYYY MM DD ",
            formatDate: 'DD-MM-YYYY',
            selectedBefore: !0,
            selectedDate:m.toString(),
            onSelect: function () {
                $("#appbundle_contract_contractStartDate").val($("#contractDateStartextra").attr("data-gdate"))
            }
        });

    }
    else{
        $('#contractDateStartextra').persianDatepicker({
            altFormat: "YYYY MM DD ",
            formatDate: 'DD-MM-YYYY',
            onSelect: function () {
                $("#appbundle_contract_contractStartDate").val($("#contractDateStartextra").attr("data-gdate"))
            }
        });
    }

    if($('#appbundle_contract_contractEndDate').val()){
        var dateStart= $('#appbundle_contract_contractEndDate').val();
        var arrDate=dateStart.split('-');
        var jdate3 =JalaliDate.gregorianToJalali(arrDate[2],arrDate[1],arrDate[0])
        m=jdate3[0]+'/'+jdate3[1]+'/'+jdate3[2]
        /**
         * Default
         * */
        $('#contractDateEndextra').persianDatepicker({
            altFormat: "YYYY MM DD ",
            formatDate: 'DD-MM-YYYY',
            selectedBefore: !0,
            selectedDate:m.toString(),
            onSelect: function () {
                $("#appbundle_contract_contractEndDate").val($("#contractDateEndextra").attr("data-gdate"))
            }
        });

    }
    else{
        $('#contractDateEndextra').persianDatepicker({
            altFormat: "YYYY MM DD ",
            formatDate: 'DD-MM-YYYY',
            onSelect: function () {
                $("#appbundle_contract_contractEndDate").val($("#contractDateEndextra").attr("data-gdate"))
            }
        });
    }

$('#appbundle_contract_contractPrice').change(function () {
    if($('#appbundle_contract_basePrice').val()!=''){
        var discount=$('#appbundle_contract_basePrice').val()-$('#appbundle_contract_contractPrice').val();
        console.log(discount);
        if(discount>=0){
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
            if(discount>=0){
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
        case '6':
            $('.contractIExhibition').show();
            break;
        case '7':
            console.log('fsdfsdfsdfsdfsdfdssddsfs');
            $('.contractIAdv').show();
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


$('input[id*="appbundle_contract_serviceItems"]').change(function () {
    changeFunction();
});
$('input[id*="appbundle_contract_shareItems"]').change(function () {
    changeFunction();
});

$('#appbundle_contract_contractTime').change(function () {

    changeFunction();
});

function changeFunction(){
    var price=0
    var selectTime = $("select[name='appbundle_contract[contractTime]'] option:selected").val();
    var serviceItem1=!!($('#appbundle_contract_serviceItems_1').is(':checked'));
    var serviceItem2=!!($('#appbundle_contract_serviceItems_2').is(':checked'));
    var serviceItem3=!!($('#appbundle_contract_serviceItems_3').is(':checked'));
    var serviceItem4=!!($('#appbundle_contract_serviceItems_4').is(':checked'));
    var serviceItem5=!!($('#appbundle_contract_serviceItems_5').is(':checked'));
    var serviceItem6=!!($('#appbundle_contract_serviceItems_6').is(':checked'));
    var shareItem1=!!($('#appbundle_contract_shareItems_1').is(':checked'));
    var shareItem2=!!($('#appbundle_contract_shareItems_2').is(':checked'));
    var shareItem3=!!($('#appbundle_contract_shareItems_3').is(':checked'));
    if(selectTime==='1'){
        if(serviceItem1 &&  !serviceItem2){
            if(shareItem1 && shareItem2 && shareItem3) {
                price='5300000';
            }
            else if(shareItem1 && !shareItem2 && shareItem3){
                price='4500000';
            }
            else if(!shareItem1 && shareItem2 && shareItem3){
                price='4200000';
            }
            else if(!shareItem1 && shareItem2 && !shareItem3){
                price='3800000';
            }
            else if(!shareItem1 && !shareItem2 && shareItem3){
                price='1200000';
            }
        }
        else if(serviceItem1 && serviceItem2){
            if(shareItem1 && shareItem2 && shareItem3) {
                price='5900000';
            }
            else if(shareItem1 && !shareItem2 && shareItem3){
                price='5300000';
            }
            else if(!shareItem1 && shareItem2 && shareItem3){
                price='4900000';
            }
            else if(!shareItem1 && shareItem2 && !shareItem3){
                price='4۵00000';
            }
            else if(!shareItem1 && !shareItem2 && shareItem3){
                price='160000';
            }
        }
        else if(!serviceItem1 && !serviceItem2 && !serviceItem3 && serviceItem4 && !serviceItem6){
            price='1600000';
        }
        if(serviceItem4 && (serviceItem1 || serviceItem2 || serviceItem3 || serviceItem5 || serviceItem6)){
            price=parseInt(price)+80;
        }
        if(serviceItem3){
            price=parseInt(price)+150;
        }
        if(serviceItem6){
            price=parseInt(price)+50;
        }
    }
    if(selectTime==='2'){

        if(serviceItem1 && !serviceItem2){
            if(shareItem1 && shareItem2 && shareItem3) {
                price='6900000';
            }
            else if(shareItem1 && !shareItem2 && shareItem3){
                price='5900000';
            }
            else if(!shareItem1 && shareItem2 && shareItem3){
                price='5400000';
            }
            else if(!shareItem1 && shareItem2 && !shareItem3){
                price='4900000';
            }
            else if(!shareItem1 && !shareItem2 && shareItem3){
                price='2100000';
            }
        }
        else if(serviceItem1 && serviceItem2){
            if(shareItem1 && shareItem2 && shareItem3) {
                price='7900000';
            }
            else if(shareItem1 && !shareItem2 && shareItem3){
                price='6900000';
            }
            else if(!shareItem1 && shareItem2 && shareItem3){
                price='6400000';
            }
            else if(!shareItem1 && shareItem2 && !shareItem3){
                price='5900000';
            }
            else if(!shareItem1 && !shareItem2 && shareItem3){
                price='2900000';
            }
        }
        else if(!serviceItem1 && !serviceItem2 && !serviceItem3 && serviceItem4 && !serviceItem6){
            price='2300000';
        }
        if(serviceItem4 && (serviceItem1 || serviceItem2 || serviceItem3 || serviceItem5 || serviceItem6)){
            price=parseInt(price)+120;
        }
        if(serviceItem3){
            price=parseInt(price)+300;
        }
        if(serviceItem6){
            price=parseInt(price)+100;
        }
    }



    $('#appbundle_contract_basePrice').val(price)
}