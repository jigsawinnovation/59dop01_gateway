$(document).ready(function(){
    auto_setformat();
});
function auto_setformat(){
    $.mask.definitions['h'] = "[1-9]";
    $(".phone").mask("+66 h99999999",{allow:true});
    $(".date").mask("99/99/9999");
    $(".numberZipcode").mask("99999");
    $('.numeric').number( true, 2 );//$('#numberic').number( true, 2,'.','' )
    $('.numberonly').number( true ,0,'','');
    $('.input_idcard').mask("9-9999-99999-99-9");
}