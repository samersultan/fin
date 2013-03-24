//@codekit-prepend "modernizr.min.js"
//@codekit-prepend "foundation/foundation.js"
//@codekit-prepend "foundation/foundation.alerts.js"
//@codekit-prepend "foundation/foundation.clearing.js"
//@codekit-prepend "foundation/foundation.cookie.js"
//@codekit-prepend "foundation/foundation.dropdown.js"
//@codekit-prepend "foundation/foundation.forms.js"
//@codekit-prepend "foundation/foundation.joyride.js"
//@codekit-prepend "foundation/foundation.magellan.js"
//@codekit-prepend "foundation/foundation.orbit.js"
//@codekit-prepend "foundation/foundation.placeholder.js"
//@codekit-prepend "foundation/foundation.reveal.js"
//@codekit-prepend "foundation/foundation.section.js"
//@codekit-prepend "foundation/foundation.tooltips.js"
//@codekit-prepend "foundation/foundation.topbar.js"

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

jQuery(document).foundation('reveal',{
  animation: 'fadeAndPop',
  animationSpeed: 250,
  closeOnBackgroundClick: true,
  dismissModalClass: 'close-reveal-modal',
  bgClass: 'reveal-modal-bg',
  open: function(){},
  opened: function(){},
  close: function(){},
  closed: function(){},
  bg : $('.reveal-modal-bg'),
  css : {
    open : {
      'opacity': 0,
      'visibility': 'visible',
      'display' : 'block'
    },
    close : {
      'opacity': 1,
      'visibility': 'hidden',
      'display': 'none'
    }
  }
});

jQuery(document).foundation(); // Don't log errors

//jQuery(document).foundation(function (response) { // log errors
  //console.log(response.errors);
//});