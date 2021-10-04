$(function() {
    var password  = '#js-password';
    var passcheck = '#js-passcheck';
	
    changeInputtype(password, passcheck);
});


function changeInputtype(password, passcheck) {
    $(passcheck).change(function() {       
        if ($(this).prop('checked')) {
            $(password).attr('type','text');
        } else {
            $(password).attr('type','password');
        }
    });
}