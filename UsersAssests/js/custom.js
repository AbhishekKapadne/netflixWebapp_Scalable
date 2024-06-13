
$(document).ready(function () {

  //navbar fixed to add class
  function navScroll() {
    var windowpos = jQuery(window).scrollTop();
    if (windowpos > 60) {
      jQuery('.navbar').addClass('navbar-scroll');
      jQuery('.navbar').addClass('navbar-light');
      jQuery('.navbar').removeClass('navbar-dark');
      jQuery('.nav-link').addClass('scroll-text');
    }
    if (windowpos < 60) {
      jQuery('.navbar').removeClass('navbar-scroll');
      jQuery('.navbar').addClass('navbar-dark');
      jQuery('.navbar').removeClass('navbar-light');
      jQuery('.nav-link').removeClass('scroll-text');
     
    }

  };
  navScroll();
  jQuery(window).scroll(function () {
    navScroll();
  });

  // banner slider
  var swiper = new Swiper('#movie-slider', {
    spaceBetween: 30,
    slidesPerView: 1,
    loop: true,
    autoplay: true,
    autoplayStopOnLast: false, // loop false also
    breakpoints: {
      1024: {
        slidesPerView: 1.5,
        centeredSlides: true,
      },
     
      767: {
        slidesPerView: 1,
        centeredSlides: false,
        spaceBetweenSlides: 10
      }
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true
    },
    autoplay: {
      delay: 2500,
      disableOnInteraction: false
    },
  });

    // slider 1
  var swiper = new Swiper('#movie-slider1', {
    loop: true,
    spaceBetween: 30,
    slidesPerGroup: 3,
    navigation: {
      nextEl: '.swiper-button-next.heading-nav',
      prevEl: '.swiper-button-prev.heading-nav',
    },
    autoplay: true,
    autoplayStopOnLast: true, // loop false also
    breakpoints: {
      1024: {
        slidesPerView: 6,
        autoplay: false,
loop: true,
      },
      900: {
        slidesPerView: 2,
        centeredSlides: true,
      },
      767: {
        slidesPerView: 3,
        spaceBetweenSlides: 10
      },
      425: {
        slidesPerView: 2.5,
        spaceBetween: 10
      },
      320: {
        slidesPerView: 2.5,
        spaceBetweenSlides: 8
      }
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true
    }
  });



  //***************************************************************************** */
  var current_fs, next_fs, previous_fs; //fieldsets
  var opacity;
  var current = 1;
  var steps = $("fieldset").length;
  
  setProgressBar(current);
  $(".action-button").click(function(){
    $(window).scrollTop(0);
  });
  $(".action-button-previous").click(function(){
    $(window).scrollTop(0);
  });
  $(".next").click(function(){
  
  current_fs = $(this).parent();
  next_fs = $(this).parent().next();
  
  
  //Add Class Active
  $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
  $("#progressbar li").eq($("fieldset").index(next_fs)).prev().addClass("filled-green");



  
  //show the next fieldset
  next_fs.show();
  //hide the current fieldset with style
  current_fs.animate({opacity: 0}, {
  step: function(now) {
  // for making fielset appear animation
  opacity = 1 - now;
  
  current_fs.css({
  'display': 'none',
  'position': 'relative'
  });
  next_fs.css({'opacity': opacity});
  },
  duration: 500
  });
  setProgressBar(++current);
  });
  
  $(".previous").click(function(){
  
  current_fs = $(this).parent();
  previous_fs = $(this).parent().prev();
  
  //Remove class active
  $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
  $("#progressbar li").eq($("fieldset").index(current_fs)).prev().removeClass("filled-green");
  
  //show the previous fieldset
  previous_fs.show();
  
  //hide the current fieldset with style
  current_fs.animate({opacity: 0}, {
  step: function(now) {
  // for making fielset appear animation
  opacity = 1 - now;
  
  current_fs.css({
  'display': 'none',
  'position': 'relative'
  });
  previous_fs.css({'opacity': opacity});
  },
  duration: 500
  });
  setProgressBar(--current);
  });
  
  function setProgressBar(curStep){
  var percent = parseFloat(100 / steps) * curStep;
  percent = percent.toFixed();
  $(".progress-bar")
  .css("width",percent+"%")
  }
  
  $(".submit").click(function(){
  return false;
  })

  //***************************************************************************** */
});