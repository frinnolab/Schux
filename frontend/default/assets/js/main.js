(function ($) {
	"use strict";

	jQuery(document).ready(function($){


		/*----------------------------------
		   remove in class
	    ------------------------------------ */
		//  $(".dropdown").hover(function(){
	    //     var dropdownMenu = $(this).children(".dropdown-menu");
	    //     if(dropdownMenu.is(":visible")){
	    //         dropdownMenu.parent().toggleClass("open");
	    //     }
	    // });

		$('.mobile-menu').slicknav({
			prependTo: '.navbar-header',
			parentTag: 'liner',
			allowParentLinks: true,
			duplicate: true,
			label: '',
			closedSymbol: '<i class="fa fa-angle-right"></i>',
			openedSymbol: '<i class="fa fa-angle-down"></i>',
		});

		/*----------------------------------
		   sticky nav
	    ------------------------------------ */
	   	// var navBar = document.querySelector('.header-menu-area');
		// var navBarHeight = navBar.offsetTop;

		// function stickyNav() {
		// 	if(window.scrollY >= navBarHeight) {
		// 		document.body.style.paddingTop = navBar.offsetHeight + 'px';
		// 		document.body.classList.add('sticky--nav');
		// 	} else {
		// 		document.body.style.paddingTop = 0;
		// 		document.body.classList.remove('sticky--nav');
		// 	}
		// }

		// window.addEventListener('scroll', stickyNav);

		/*----------------------------------
		   	slider active js
	    ------------------------------------ */
		$('#main-slider').owlCarousel({
			items:1,
			loop: true,
			autoplay: true,
			smartSpeed: 1200,
			dots: false,
			nav:true,
			navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],

		});

		$("#main-slider").on("translate.owl.carousel", function(){
		   $(".caption h2, .caption p").removeClass("animated fadeInUp").css("opacity", "0");
		   $(".caption .btn-1").removeClass("animated fadeInDown").css("opacity", "0");
	   });

	   $("#main-slider").on("translated.owl.carousel", function(){
		   $(".caption h2, .caption p").addClass("animated fadeInUp").css("opacity", "1");
		   $(".caption .btn-1").addClass("animated fadeInDown").css("opacity", "1");
	   });

	    /*----------------------------------
	    	Venobox Gallery active js
	    ------------------------------------ */
	    $('.venobox_custom').venobox({
	        framewidth: '700px', // default: ''
	        frameheight: '500px', // default: ''
	        border: '2px', // default: '0'
	        bgcolor: '#fff', // default: '#fff'
	        titleattr: 'data-title', // default: 'title'
	        numeratio: true, // default: false
	        infinigall: true // default: false
	    });
		/*---------------------
		    Counter active js
		--------------------- */
		$('.counterup').counterUp({
	        delay: 10,
	        time: 1000
	    });


		/*--------------------------
			scrollUp
		---------------------------- */
		$(function(){
			$("#scroll").fadeOut();
			$(window).scroll(function(){
			if($(this).scrollTop() > 100){
				$("#scroll").fadeIn(500);
			}else{
				$("#scroll").fadeOut(600);
			}
			});
			$("#scroll").click(function(){
				$("html,body").animate({scrollTop:0},800);
				return false;
			});
		});
	});




})(jQuery);
