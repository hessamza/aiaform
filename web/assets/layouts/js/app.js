jQuery(document).ready(function() {

    $('#contractTimeFrom').persianDatepicker({
        altFormat: "YYYY MM DD ",
        formatDate: 'YYYY-MM-DD'
    });
    $('#contractTimeTo').persianDatepicker({
        altFormat: "YYYY MM DD ",
        formatDate: 'YYYY-MM-DD'
    });

    if($('input[id*="appbundle_contract_haveExtraContractPrice"]').prop('checked')) {
        $('.extraPriceShow').show();
    } else {
        $('.extraPriceShow').hide();
    }
    $('input[id*="appbundle_contract_haveExtraContractPrice"]').change(function () {
        if(this.checked){
            $('.extraPriceShow').show();

        }else{
            $('input[id*="appbundle_contract_extraContractPrice"]').val('');
            $('textarea[id*="appbundle_contract_extraDescription"]').val('');
            $('.extraPriceShow').hide();
        }
    });

    if($('input[id*="appbundle_contract_items"]').prop('checked')) {
        $('.extraItemShow').show();
    } else {
        $('.extraItemShow').hide();
    }
    $('input[id*="appbundle_contract_items"]').change(function () {
        if(this.checked){
            $('.extraItemShow').show();

        }else{
            $('input[id*="appbundle_contract_posts_"]').prop('checked', false);
            $('textarea[id*="appbundle_contract_itemDescription"]').val('');
            $('.extraItemShow').hide();
        }
    });

    $( "#appbundle_contract_extraContractPrice" ).blur(function() {
        if($.isNumeric($("#appbundle_contract_extraContractPrice").val()) && $("#appbundle_contract_extraContractPrice").val()) {
            var extraVal = parseInt($("#appbundle_contract_extraContractPrice").val());
            if ($("#appbundle_contract_contractPrice").val()) {
                var priceContract = parseInt($("#appbundle_contract_contractPrice").val());
            } else {
                var priceContract = 0;
            }
            $("#appbundle_contract_contractPrice").val(extraVal + priceContract);
        }
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
            },
            {
                data: null,
                className: "center",
                render: function ( data, type, row ) {
                    var mg ='';
                    mg=data.id;
                    return '<a href="#" onclick="callpreFactore('+data.id+')">پیش فاکتور</a>';
                }
            },
        ]
    });
    TableItems = $('#exampleItems').dataTable({
        "data": [],
        "columns": [{
            "title": "id",'data': 'id'
        }, {
            "title": "نام شرکت",'data': 'company_name'
        } , {
            "title": "نام کاربری",'data': 'user_name'
        }, {
            "title": "نوع ارسال",'data': null,
            render: function ( data, type, row ) {
                var m='';
                    $.each(data.posts,function(index,item){
                        if(index!=0){
                            m=m+'/'+item['name'];
                        }else{
                            m=item['name'];
                        }

                    })
                return m;
            }
        }, {
            "title": "کارشناس",'data': null,
            render: function ( data, type, row ) {
                if(data.owner !=null){
                    return data.owner.username;
                }else{
                    return '';
                }
            }
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
        }, {
            "title": "وضعبت ارسال",'data': null,
            render: function ( data, type, row ) {
               return 'نا مشخص';
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
    if ($('.itemData').length > 0) {
        $.ajax({
            url: "/items/list",
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

                var table = $('#exampleItems').DataTable();
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
        if(discount>=0){
            $('#appbundle_contract_discount').val(discount)
        }
        else{
            alert('چون مبلغ قرارداد از مبلغ پایه بیشتر است لطفن قسمت سایر خدمات را پر کنید');
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
            $('.contractIAdv').show();
            break;
    }
});


$('.searchForm').submit(function (e) {
    e.preventDefault();
    var name = $("input[name='companyName']", '.searchForm').val();
    var contractTimeFrom = $("input[name='contractTimeFrom']", '.searchForm').val();
    var contractTimeTo = $("input[name='contractTimeTo']", '.searchForm').val();


    if(contractTimeFrom){
        var arrDateFrom=contractTimeFrom.split('-');
        var jdate3 =JalaliDate.jalaliToGregorian(arrDateFrom[0],arrDateFrom[1],arrDateFrom[2])

        jcontractTimeFrom=jdate3[0]+'/'+jdate3[1]+'/'+jdate3[2]
    }else{
        jcontractTimeFrom='';
    }

    if(contractTimeTo){
        var arrDateTo=contractTimeTo.split('-');
        var jdate3To =JalaliDate.jalaliToGregorian(arrDateTo[0],arrDateTo[1],arrDateTo[2])
        jcontractTimeTo=jdate3To[0]+'/'+jdate3To[1]+'/'+jdate3To[2]
    }else{
        jcontractTimeTo='';
    }



    // var values = $(this).serialize();
    $.ajax({
        url: "/contracts/items",
        type: "GET",
        data: {companyName: name, contractTimeFrom: jcontractTimeFrom,contractTimeTo:jcontractTimeTo},
        success: function (response) {
            $('.companiesBuyItems').html('');
            var items = response;
            var html = '';
            $.each(items, function (key, value) {
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
$('.searchItemForm').submit(function (e) {
    e.preventDefault();
    var name = $("input[name='companyName']", '.searchItemForm').val();
    var contractTimeFrom = $("input[name='contractTimeFrom']", '.searchItemForm').val();
    var contractTimeTo = $("input[name='contractTimeTo']", '.searchItemForm').val();
    if(contractTimeFrom){
        var arrDateFrom=contractTimeFrom.split('-');
        var jdate3 =JalaliDate.jalaliToGregorian(arrDateFrom[0],arrDateFrom[1],arrDateFrom[2])

        jcontractTimeFrom=jdate3[0]+'/'+jdate3[1]+'/'+jdate3[2]
    }else{
        jcontractTimeFrom='';
    }

    if(contractTimeTo){
        var arrDateTo=contractTimeTo.split('-');
        var jdate3To =JalaliDate.jalaliToGregorian(arrDateTo[0],arrDateTo[1],arrDateTo[2])
        jcontractTimeTo=jdate3To[0]+'/'+jdate3To[1]+'/'+jdate3To[2]
    }else{
        jcontractTimeTo='';
    }

    // var values = $(this).serialize();
    $.ajax({
        url: "/items/list",
        type: "GET",
        data: {companyName: name, contractTimeFrom: jcontractTimeFrom,contractTimeTo:jcontractTimeTo},
        success: function (response) {
            $('.companiesBuyItems').html('');
            var items = response;
            var html = '';
            $.each(items, function (key, value) {
                html += '<tr>'
                    +'<td> Tridentسیس </td>'
                    +'<td> Internet Explorer 4.0 </td>'
                +'<td> Win 95+ </td>'
                +'<td> 4 </td>'
               +'<td> X </td>'
                +'</tr>';
            });

            var table = $('#exampleItems').DataTable();
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
$('#appbundle_contract_separate').change(function () {
    changeFunction();
});
function changeFunction(){
    var shareString='';
    var serviceString='';
    var price=0
    var servicePrice=0
    var selectTime = $("select[name='appbundle_contract[contractTime]'] option:selected").val();
    var separate = $("select[name='appbundle_contract[separate]'] option:selected").val();
    var serviceItem1=!!($('#appbundle_contract_serviceItems_1').is(':checked'));
    var serviceItem2=!!($('#appbundle_contract_serviceItems_2').is(':checked'));
    var serviceItem3=!!($('#appbundle_contract_serviceItems_3').is(':checked'));
    var serviceItem4=!!($('#appbundle_contract_serviceItems_4').is(':checked'));
    var serviceItem5=!!($('#appbundle_contract_serviceItems_5').is(':checked'));
    var serviceItem6=!!($('#appbundle_contract_serviceItems_6').is(':checked'));
    var serviceItem7=!!($('#appbundle_contract_serviceItems_7').is(':checked'));
    var shareItem1=!!($('#appbundle_contract_shareItems_1').is(':checked'));
    var shareItem2=!!($('#appbundle_contract_shareItems_2').is(':checked'));
    var shareItem3=!!($('#appbundle_contract_shareItems_3').is(':checked'));
    var shareItem4=!!($('#appbundle_contract_shareItems_4').is(':checked'));
    if(selectTime==='1'){
        if(serviceItem1 &&  !serviceItem2){
            serviceString='سایت, ';
            if(shareItem1 && shareItem2 && shareItem3) {
                shareString='مناقصه , استعلام , مزایده, ';
                price='5300000';
            }
            else if(shareItem1 && !shareItem2 && shareItem3){
                shareString='مناقصه , استعلام ';
                price='4500000';
            }
            else if(!shareItem1 && shareItem2 && shareItem3){
                shareString=' استعلام , مزایده, ';
                price='4200000';
            }
            else if(!shareItem1 && shareItem2 && !shareItem3){
                shareString='مزایده,';
                price='3800000';
            }
            else if(!shareItem1 && !shareItem2 && shareItem3){
                shareString=' استعلام, ';
                price='1200000';
            }
            servicePrice=price;
        }
        else if(serviceItem1 && serviceItem2){
            serviceString='سایت , ایمیل,';
            if(shareItem1 && shareItem2 && shareItem3) {
                shareString='مناقصه , استعلام , مزایده,';
                price='5900000';
            }
            else if(shareItem1 && !shareItem2 && shareItem3){
                shareString='مناقصه , استعلام, ';
                price='5300000';
            }
            else if(!shareItem1 && shareItem2 && shareItem3){
                shareString=' استعلام , مزایده,';
                price='4900000';
            }
            else if(!shareItem1 && shareItem2 && !shareItem3){
                shareString='مزایده,';
                price='4۵00000';
            }
            else if(!shareItem1 && !shareItem2 && shareItem3){
                shareString=' استعلام, ';
                price='160000';
            }
            servicePrice=price;
        }
        else if(!serviceItem1 && !serviceItem2 && !serviceItem3 && serviceItem4 && !serviceItem6||serviceItem7){
            serviceString='فقط سرویس تلگرام,';
            price='1600000';
        }
        if(serviceItem4 && (serviceItem1 || serviceItem2 || serviceItem3 || serviceItem5 || serviceItem6||serviceItem7)){
            price=parseInt(price)+80000;
        }
        if(serviceItem3){
            price=parseInt(price)+150000;
        }
        if(serviceItem7){
            price=parseInt(price)+270000;
        }
        if(serviceItem6){
            price=parseInt(price)+50000;
        }

    }
    if(selectTime==='2'){
        if(serviceItem1 && !serviceItem2){
            serviceString='سایت, ';
            if(shareItem1 && shareItem2 && shareItem3) {
                shareString='مناقصه , استعلام , مزایده, ';
                price='6900000';
            }
            else if(shareItem1 && !shareItem2 && shareItem3){
                shareString='مناقصه , استعلام, ';
                price='5900000';
            }
            else if(!shareItem1 && shareItem2 && shareItem3){
                shareString=' استعلام , مزایده, ';
                price='5400000';
            }
            else if(!shareItem1 && shareItem2 && !shareItem3){
                shareString='مزایده, ';
                price='4900000';
            }
            else if(!shareItem1 && !shareItem2 && shareItem3){
                shareString=' استعلام, ';
                price='2100000';
            }
            servicePrice=price;
        }
        else if(serviceItem1 && serviceItem2){
            serviceString='سایت , ایمیل, ';
            if(shareItem1 && shareItem2 && shareItem3) {
                shareString='مناقصه , استعلام , مزایده, ';
                price='7900000';
            }
            else if(shareItem1 && !shareItem2 && shareItem3){
                shareString='مناقصه , استعلام, ';
                price='6900000';
            }
            else if(!shareItem1 && shareItem2 && shareItem3){
                shareString=' استعلام , مزایده, ';
                price='6400000';
            }
            else if(!shareItem1 && shareItem2 && !shareItem3){
                shareString='مزایده, ';
                price='5900000';
            }
            else if(!shareItem1 && !shareItem2 && shareItem3){
                shareString=' استعلام, ';
                price='2900000';
            }
            servicePrice=price;
        }
        else if(!serviceItem1 && !serviceItem2 && !serviceItem3 && serviceItem4 && !serviceItem6 && !serviceItem7){
            serviceString='فقط سرویس تلگرام';
            price='2300000';
        }
        if(serviceItem4 && (serviceItem1 || serviceItem2 || serviceItem3 || serviceItem5 || serviceItem6 || serviceItem7)){
            price=parseInt(price)+120000;
        }
        if(serviceItem3){
            price=parseInt(price)+300000;
        }
        if(serviceItem7){
            price=parseInt(price)+540000;
        }
        if(serviceItem6){

            price=parseInt(price)+100000;
        }
    }


    if(separate==='2'){
        serviceString=serviceString+'استانی,';
        price=Math.round(parseInt(price)-(parseInt(price)*3)/10);
    }
    else if(separate==='3'){
        serviceString=serviceString+'تخصصی,';
        price=Math.round(parseInt(price)-(parseInt(price)*2)/10);
    }
    else if(separate==='4'){
        serviceString=serviceString+'استانی تخصصی,';
        price=Math.round(parseInt(price)-(parseInt(price)*4)/10);
    }
    else if(separate==='1'){
        serviceString=serviceString+'سراسری,';
    }
    if(shareItem4){
        shareString=shareString+'تک آگهی,';
        price=parseInt(price)+500000;
        servicePrice=parseInt(servicePrice)+50000;
    }
    $('#appbundle_contract_basePrice').val(price)

    $('.shareString').val(shareString);
    $('.serviceString').val(serviceString);
    $('.servicePrice').val(servicePrice);



}