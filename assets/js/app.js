//@codekit-prepend "../vendor/foundation/js/vendor/custom.modernizr.js"
//@codekit-prepend "../vendor/foundation/js/foundation/foundation.js"
//@codekit-prepend "../vendor/foundation/js/foundation/foundation.alerts.js"
//@codekit-prepend "../vendor/foundation/js/foundation/foundation.clearing.js"
//@codekit-prepend "../vendor/foundation/js/foundation/foundation.cookie.js"
//@codekit-prepend "../vendor/foundation/js/foundation/foundation.dropdown.js"
//@codekit-prepend "../vendor/foundation/js/foundation/foundation.forms.js"
//@codekit-prepend "../vendor/foundation/js/foundation/foundation.joyride.js"
//@codekit-prepend "../vendor/foundation/js/foundation/foundation.magellan.js"
//@codekit-prepend "../vendor/foundation/js/foundation/foundation.orbit.js"
//@codekit-prepend "../vendor/foundation/js/foundation/foundation.placeholder.js"
//@codekit-prepend "../vendor/foundation/js/foundation/foundation.reveal.js"
//@codekit-prepend "../vendor/foundation/js/foundation/foundation.section.js"
//@codekit-prepend "../vendor/foundation/js/foundation/foundation.tooltips.js"
//@codekit-prepend "../vendor/foundation/js/foundation/foundation.topbar.js"

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