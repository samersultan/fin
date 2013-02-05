//@codekit-append "bootstrap/bootstrap-affix"
//@codekit-append "bootstrap/bootstrap-alert"
//@codekit-append "bootstrap/bootstrap-carousel"
//@codekit-append "bootstrap/bootstrap-collapse"
//@codekit-append "bootstrap/bootstrap-dropdown"

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