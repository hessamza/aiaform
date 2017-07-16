jQuery(document).ready(function() {
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
