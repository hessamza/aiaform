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
        aaSorting: [[0, 'desc']],
        "columns": [{
            "title": "id",'data': 'id'
        }, {
            "title": "نام شرکت",'data': 'company_name'
        } , {
            "title": "نام کاربری",'data': 'user_name'
        }, {
            "title": "تاریخ",'data': null,
            render: function ( data, type, row ) {
                console.log(data)
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
                    return '<a href="#" onclick="callSignFactore('+data.id+')">فاكتور با امضاء</a>';
                }
            },
            {
                data: null,
                className: "center",
                render: function ( data, type, row ) {
                    var mg ='';
                    mg=data.id;
                    return '<a href="#" onclick="callFactore('+data.id+')"> فاکتور بدون امضا</a>';
                }
            },            {
                data: null,
                className: "center",
                render: function ( data, type, row ) {
                    var mg ='';
                    mg=data.id;
                    return '<button class="dsbuttonAccept" style="margin-right: 10px">ضمانت نامه</button> ';

                }
            },
        ]
    });

    perTable = $('#preExample').DataTable({
        "data": [],
        aaSorting: [[0, 'desc']],
        "columns": [{
            "title": "id",'data': 'id'
        }, {
            "title": "نام شرکت",'data': 'company_name'
        } , {
            "title": "نام کاربری",'data': 'user_name',width:100
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
                    return '<a href="#" onclick="callSignpreFactore('+data.id+')">پيش فاكتور با امضاء</a>';
                }
            },
            {
                data: null,
                className: "center",
                render: function ( data, type, row ) {
                    var mg ='';
                    mg=data.id;
                    return '<a href="#" onclick="callpreFactore('+data.id+')">پیش فاکتور بدون امضا</a>';
                }
            },            {
                data: null,
                className: "center",
                render: function ( data, type, row ) {
                    var mg ='';
                    mg=data.id;
                    return '<button class="buttonAccept" style="margin-right: 10px">تایید قرار داد</button> ';

                }
            },
        ]
    });


    TableItems = $('#exampleItems').DataTable({
        "data": [],
        aaSorting: [[0, 'desc']],
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
            width:80,
            render: function ( data, type, row ) {
                var cookieValue = $.cookie("RoleCookie");
                if(cookieValue==='ROLE_SECRETARY') {
                    if(data.item_send){
                        return '<input class="nameSend"  type="checkbox" checked  ><button class="buttonItemDes" style="margin-right: 10px">ارسال</button> ';
                    }else{
                        return '<input class="nameSend"  type="checkbox" ><button class="buttonItemDes" style="margin-right: 10px">ارسال</button> ';
                    }
                }
                else{
                    if(data.item_send){
                        return '<input class="nameSend" disabled type="checkbox" checked  > ';
                    }else{
                        return '<input class="nameSend" disabled type="checkbox"  >';
                    }
                }

           }
        },
            {
                "title": "ادیت توضیحات",
                data: null,
                width:200,
                className: "center",
                render: function ( data, type, row ) {
                    var cookieValue = $.cookie("RoleCookie");
                    if(cookieValue==='ROLE_SECRETARY') {
                        return '<input type="text"  class="nameInput"  style="width: 145px" value="' + data.item_description_sec + '" size="10"/><button class="buttonItemSend" style="margin-right: 10px">ارسال</button> ';
                    }
                    else{
                        var ss= data.item_description_sec
                       return  '<label>'+ss+'</label>'
                    }
                }
            },
        ]
    });
    $('#preExample tbody').on( 'click', '.buttonAccept', function () {
        var dataItem =perTable.row( $(this).parents('tr') ).data();
        console.log(dataItem);
        $.post("contract/pre/accept/"+dataItem['id'],
            {
                itemDescriptionSec: dataItem['id'],
            },
            function(data, status){
                location.reload();
            });
    } );
    $('#exampleItems tbody').on( 'click', '.buttonItemSend', function () {
        var data =$(this).closest('tr').find('.nameInput').val();
        var dataItem =TableItems.row( $(this).parents('tr') ).data();
        $.post("listItem/"+dataItem['id'],
            {
                itemDescriptionSec: data,
            },
            function(data, status){
                location.reload();
            });
    } );

    $('#exampleItems tbody').on( 'click', '.buttonItemDes', function () {
        var data =$(this).closest('tr').find('.nameSend').is(':checked');
        var dataItem =TableItems.row( $(this).parents('tr') ).data();
        $.post("listItem/send/"+dataItem['id'],
            {
                itemSend: 1,
            },
            function(data, status){
                //location.reload();
            });
    } );


    if ($('.contractData').length > 0) {
        $.ajax({
            url: "/contracts/items",
            type: "GET",
            data: {},
            success: function (response) {
                $('.companiesBuyItems').html('');
                var items = response;
                console.log(items)
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
                console.log(items);
                table.clear().draw();
                table.rows.add(items).draw();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    }
    if ($('.contractPreData').length > 0) {
        $.ajax({
            url: "/contracts/items/pre",
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

                var table = $('#preExample').DataTable();
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
    $("#contractDateEndextra").on("change",function (){
        var dateStart= $("#contractDateEndextra").val();
        var arrDate=dateStart.split('-');
        var jdate3 =JalaliDate.jalaliToGregorian(arrDate[2],arrDate[1],arrDate[0])
        m=jdate3[2]+'-'+jdate3[1]+'-'+jdate3[0];
        $("#appbundle_contract_contractEndDate").val(m);

    })
    $("#contractDateStartextra").on("change",function (){
        var dateStart= $("#contractDateStartextra").val();
        var arrDate=dateStart.split('-');
        var jdate3 =JalaliDate.jalaliToGregorian(arrDate[2],arrDate[1],arrDate[0])
        m=jdate3[2]+'-'+jdate3[1]+'-'+jdate3[0];
        $("#appbundle_contract_contractStartDate").val(m);

    })
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
$('input[id*="appbundle_contract_advItems"]').change(function () {
    changeFunction();
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

    var advItem1=!!($('#appbundle_contract_advItems_1').is(':checked'));
    var advItem2=!!($('#appbundle_contract_advItems_2').is(':checked'));
    var advItem3=!!($('#appbundle_contract_advItems_3').is(':checked'));
    var advItem4=!!($('#appbundle_contract_advItems_4').is(':checked'));

    var serviceItem3=!!($('#appbundle_contract_serviceItems_3').is(':checked'));
    var serviceItem4=!!($('#appbundle_contract_serviceItems_4').is(':checked'));
    var serviceItem5=!!($('#appbundle_contract_serviceItems_5').is(':checked'));
    var serviceItem6=!!($('#appbundle_contract_serviceItems_6').is(':checked'));
    var serviceItem7=!!($('#appbundle_contract_serviceItems_7').is(':checked'));

    var shareItem1=!!($('#appbundle_contract_shareItems_1').is(':checked'));
    var shareItem2=!!($('#appbundle_contract_shareItems_2').is(':checked'));

    if(selectTime==='1'){
        if(shareItem1 &&  !shareItem2){
            serviceString='سایت, ';
            if(advItem1 && advItem2 && advItem3) {
                shareString='مناقصه , استعلام , مزایده, ';
                price='530000';
            }
            else if(advItem1 && !advItem2 && advItem3){
                shareString='مناقصه , استعلام ';
                price='450000';
            }
            else if(!advItem1 && advItem2 && advItem3){
                shareString=' استعلام , مزایده, ';
                price='420000';
            }
            else if(!advItem1 && advItem2 && !advItem3){
                shareString='مزایده,';
                price='380000';
            }
            else if(!advItem1 && !advItem2 && advItem3){
                shareString=' استعلام, ';
                price='120000';
            }
            servicePrice=price;
        }
        else if(shareItem1 && shareItem2){
            serviceString='سایت , ایمیل,';
            if(advItem1 && advItem2 && advItem3) {
                shareString='مناقصه , استعلام , مزایده,';
                price='590000';
            }
            else if(advItem1 && !advItem2 && advItem3){
                shareString='مناقصه , استعلام, ';
                price='530000';
            }
            else if(!advItem1 && advItem2 && advItem3){
                shareString=' استعلام , مزایده,';
                price='490000';
            }
            else if(!advItem1 && advItem2 && !advItem3){
                shareString='مزایده,';
                price='450000';
            }
            else if(!advItem1 && !advItem2 && advItem3){
                shareString=' استعلام, ';
                price='160000';
            }
            servicePrice=price;
        }
        else if(!shareItem1 && !shareItem2 && !serviceItem3 && serviceItem4 && !serviceItem6||serviceItem7){
            serviceString='فقط سرویس تلگرام,';
            price='160000';
        }
        if(serviceItem4 && ( shareItem2 || shareItem1)){

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
        if(shareItem1 &&  !shareItem2){
            serviceString='سایت, ';
            if(advItem1 && advItem2 && advItem3) {
                shareString='مناقصه , استعلام , مزایده, ';
                price='690000';
            }
            else if(advItem1 && !advItem2 && advItem3){
                shareString='مناقصه , استعلام, ';
                price='590000';
            }
            else if(!advItem1 && advItem2 && advItem3){
                shareString=' استعلام , مزایده, ';
                price='540000';
            }
            else if(!advItem1 && advItem2 && !advItem3){
                shareString='مزایده, ';
                price='490000';
            }
            else if(!advItem1 && !advItem2 && advItem3){
                shareString=' استعلام, ';
                price='210000';
            }
            servicePrice=price;
        }
        else if(shareItem1 && shareItem2){
            serviceString='سایت , ایمیل, ';
            if(advItem1 && advItem2 && advItem3) {
                shareString='مناقصه , استعلام , مزایده, ';
                price='790000';
            }
            else if(advItem1 && !advItem2 && advItem3){
                shareString='مناقصه , استعلام, ';
                price='690000';
            }
            else if(!advItem1 && advItem2 && advItem3){
                shareString=' استعلام , مزایده, ';
                price='640000';
            }
            else if(!advItem1 && advItem2 && !advItem3){
                shareString='مزایده, ';
                price='590000';
            }
            else if(!advItem1 && !advItem2 && advItem3){
                shareString=' استعلام, ';
                price='290000';
            }
            servicePrice=price;
        }
        else if(!shareItem1 && !shareItem2 && !serviceItem3 && serviceItem4 && !serviceItem6||serviceItem7){
            serviceString='فقط سرویس تلگرام,';
            price='230000';
        }
        if(serviceItem4 && ( shareItem2 || shareItem1)){
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
    if(advItem4){
        shareString=shareString+'تک آگهی,';
        price=parseInt(price)+500000;
        servicePrice=parseInt(servicePrice)+50000;
    }
    $('#appbundle_contract_basePrice').val(price)

    $('.shareString').val(shareString);
    $('.serviceString').val(serviceString);
    $('.servicePrice').val(servicePrice);



}