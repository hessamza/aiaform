jQuery(document).ready(function() {
    var jdf = new jDateFunctions();
    var pd = new persianDate();
    var m='';
    //var JDate = require('jalali');
    if($('#appbundle_contract_contractDate').val()){
        var dateStart= $('#appbundle_contract_contractDate').val();
        var arrDate=dateStart.split('-');
        var jdate3 =JalaliDate.gregorianToJalali(arrDate[2],arrDate[1],arrDate[0])
       console.log(jdate3.y)
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




    var selectContract = $("select[name='appbundle_contract[contractType]'] option:selected").val();
    console.log(selectContract)
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
