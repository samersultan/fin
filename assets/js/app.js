//@codekit-append "bootstrap/bootstrap-affix"
//@codekit-append "bootstrap/bootstrap-alert"
//@codekit-append "bootstrap/bootstrap-carousel"
//@codekit-append "bootstrap/bootstrap-collapse"
//@codekit-append "bootstrap/bootstrap-dropdown"
//@codekit-append "bootstrap/bootstrap-modal"
//@codekit-append "bootstrap/bootstrap-transition"

// Sticky Nav
jQuery(document).scroll(function(){
    var elem = jQuery('.navbar.sticky');
    if (!elem.attr('data-top')) {
        if (elem.hasClass('navbar-fixed-top'))
            return;
         var offset = elem.offset()
        elem.attr('data-top', offset.top);
    }
    if (elem.attr('data-top') - elem.outerHeight() <= jQuery(this).scrollTop())
        elem.addClass('navbar-fixed-top');
    else
        elem.removeClass('navbar-fixed-top');
});

// Disabled Submit
jQuery(document).ready(function() {
	jQuery('#commentform #submit').attr('disabled', true);
	jQuery('#commentform input, #commentform textarea').on('input', function(e) {
		if(jQuery('#commentform input:invalid, #commentform textarea:invalid').length <= 0) {
			jQuery('#commentform #submit').removeAttr('disabled');
		}else {
			jQuery('#commentform #submit').attr('disabled', true);
		}
	});
});