<template>
  <Header></Header>
  <slot></slot>
  <Footer/>
</template>

<script setup>
import Header from "./Header.vue";
import Footer from "./Footer.vue";

$('.fade').slick({
  dots: false,
  arrows: false,
  infinite: true,
  speed: 500,
  fade: true,
  cssEase: 'linear',
});

$(document).ready(function () {
  /* on click header icon */
  $(document).on("click", ".header-site-nav", function(e){
    e.preventDefault();
    if($(this).parent().hasClass('active')){
      $('#site-overlay').removeClass('active');
      $(this).parent().removeClass('active');
      $('body').removeClass('locked-scroll');
    }
    else{
      $('body').addClass('locked-scroll');
      $('.header--icon').removeClass('active');
      $("#site-menu-handle").removeClass('active');
      $('.menu-mobile').removeClass('active');
      $('#site-overlay').addClass('active');
      $(this).parent().addClass('active');
    }
  });

  /* on click site menu mobile */
  $("#site-menu-handle").on("click", function(){
    if($(this).hasClass('active')){
      $(this).removeClass("active");
      $('.menu-mobile').removeClass('active');
      $('body').removeClass('locked-scroll');
    }
    else{
      $(this).addClass("active");
      $('.header--icon').removeClass('active');
      $('#site-overlay').removeClass('active');
      $('.menu-mobile').addClass('active');
      $('body').addClass('locked-scroll');
    }
  });
});

$('.qty-click .qtyplus').on('click',function(e){
  e.preventDefault();
  var input = $(this).parent('.quantity-partent').find('input');
  var currentVal = parseInt(input.val());
  if (!isNaN(currentVal)) {
    input.val(currentVal + 1);
  } else {
    input.val(1);
  }
});
$(".qty-click .qtyminus").on('click',function(e) {
  e.preventDefault();
  var input = $(this).parent('.quantity-partent').find('input');
  var currentVal = parseInt(input.val());
  if (!isNaN(currentVal) && currentVal > 1) {
    input.val(currentVal - 1);
  } else {
    input.val(1);
  }
});
</script>