jQuery(document).ready(function() {
console.log($('#appbundle_contract_contractDate').val())
var data=$('#appbundle_contract_contractDate').val();
    var arr = data.split('-');
    /**
     * Default
     * */
    $('#appbundle_contract_contractDate').persianDatepicker({
        altField: '#defaultAlt',
        altFormat: "YYYY MM DD ",
        format: 'DD-MM-YYYY',
    });
    $( "#appbundle_contract_contractDate" ).pDatepicker("setDate",[parseInt(arr[2]),parseInt(arr[1]),parseInt(arr[0]),11,11] );
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
