$(document).ready(function () {
  let scrollChange = 1;
  $(window).on('scroll load', function () {
    let scroll = $(window).scrollTop();
    if (scroll >= scrollChange) {
      $('header').addClass('fixed');
    } else {
      $('header').removeClass('fixed');
    }
  })
  
  $('.item-faq .top').click( function(){
    if(!$(this).parent().hasClass('open')){
      $('.item-faq .content').slideUp(200);
      $('.item-faq').removeClass('open');
      $(this).next('.content').slideDown(200);
      $(this).parent().addClass('open');
    } else{
      $(this).next('.content').slideUp(200);
      $(this).parent().removeClass('open');
    }
  });

  $('.has-level').hover(function () {
      // over
      $('body').addClass('open-menu');
      $(this).addClass('active-menu');
    }, function () {
      // out
      $('body').removeClass('open-menu');
      $(this).removeClass('active-menu'); 
    }
  );

  $('.col-menus a').hover( function(){
    let i = $(this).parent().index();
    let contents = $('.col-contents .wrap-content-menu');

    $('.col-menus a').removeClass('active');
    $('.wrap-content-menu').removeClass('active');

    $(this).addClass('active');
    $(contents[i]).addClass('active');
  })

  $('.top-btn').click(function(e) {
    e.preventDefault();
    $("html, body").animate({ scrollTop: 0 }, "slow");
    return false;
  });

  $('.btn-mobile').click( function(){
    $('body').toggleClass('open-mobile-menu');
  })

  $('.mobile-menu-wrap .has-children').append(`<div class="btn-ar-mob"><svg width="18" height="10" viewBox="0 0 18 10" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="M1 4.35C0.641015 4.35 0.35 4.64101 0.35 5C0.35 5.35899 0.641015 5.65 1 5.65L1 4.35ZM17.4596 5.45962C17.7135 5.20578 17.7135 4.79422 17.4596 4.54038L13.323 0.403805C13.0692 0.149964 12.6576 0.149964 12.4038 0.403805C12.15 0.657646 12.15 1.0692 12.4038 1.32304L16.0808 5L12.4038 8.67695C12.15 8.93079 12.15 9.34235 12.4038 9.59619C12.6576 9.85003 13.0692 9.85003 13.323 9.59619L17.4596 5.45962ZM1 5.65L17 5.65L17 4.35L1 4.35L1 5.65Z" fill="#1a1a1a"/>
  </svg>
  </div>`);

  $('.btn-ar-mob').click( function(){
    $(this).parent().toggleClass('open-mobile-item');
    $(this).siblings('ul').slideToggle(200);
  })

  Fancybox.bind("[data-fancybox]", {});

});

$(window).on('load', function(){
  var swiper = new Swiper('.swiper-reviews', {
    slidesPerView: 'auto',
    spaceBetween: 20,
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    on: {
      progress: function (swiper, progress) {
        if(progress === 1) {
          $(swiper.$el).addClass('active-last');
        } else {
          $(swiper.$el).removeClass('active-last');
        }
      },
    },
    breakpoints: {
      991: {
        spaceBetween: 60,
      },
    },
  });
})