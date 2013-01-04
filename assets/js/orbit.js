$(window).load(function() {
   $(".rotator").orbit({
     animation: 'fade',                  // fade, horizontal-slide, vertical-slide, horizontal-push
     animationSpeed: 800,                // how fast animtions are
     timer: true,                        // true or false to have the timer
     resetTimerOnClick: false,           // true resets the timer instead of pausing slideshow progress
     advanceSpeed: 3200,                 // if timer is enabled, time between transitions
     pauseOnHover: true,                // if you hover pauses the slider
     startClockOnMouseOut: true,        // if clock should start on MouseOut
     startClockOnMouseOutAfter: 400,    // how long after MouseOut should the timer start again
     directionalNav: true,               // manual advancing directional navs
     captions: false,                     // do you want captions?
     captionAnimation: 'fade',           // fade, slideOpen, none
     captionAnimationSpeed: 400,         // if so how quickly should they animate in
     bullets: false,                     // true or false to activate the bullet navigation
     bulletThumbs: false,                // thumbnails for the bullets
     bulletThumbLocation: '',            // location from this file where thumbs will be
     afterSlideChange: function(){},     // empty function
     fluid: true                      // true or set a aspect ratio for content slides (ex: '4x3')
   });
});