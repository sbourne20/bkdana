/*
Name : vendor.js
Author : MD Creative Indonesia
Type : Main Settings Vendor
*/

(function($){
	$(function(){


		/*
		Document Event
		*/
		$(document).ready(function(){


			/* Tooltip */
			$('[data-toggle="tooltip"]').tooltip();  

			/* Counterup */
			if($('.counter').length != 0){
				$('.counter').counterUp({
	                delay: 10,
	                time: 1000
	            });
			}

			var grade_persen = $('#profil_complete').data('value');

			/* Progress Profile */
			if($('.progress-profile').length != 0){
				$('.progress-profile .progress-bar').animate({
				    width: grade_persen+'%'
				}, 2500);
			}

			/* Overflow for Panel Register*/
			// var panels = $('.form-wizard .panels').innerHeight();
	  		// var panel = $('.form-wizard .panels > #panel'+panelActive).innerHeight();
	        if($('.form-wizard .panels').length != 0){
		        $('.btn-file').on('click', function(){
			        //if(panels <= panel){
			          // console.log('height all panel: '+panels+' <= &nbsp; height per panel:'+panel);
			        //}
			        $('.form-wizard .panels').css({'overflow-y':'auto'});
			        // alert('active');
		        });
	    	}

			/* Scrolldown */
			$('.scrolldown').on('click', function(){
				$("html,body").animate({
					scrollTop: $("header").height()
				});
			});

			/* Back to top */
			$('#back-to-top').on('click', function(){
				$('html, body').animate({ scrollTop: 0 }, 500);
				return false;
			});

		}); // end of document ready


		/*
		Document Event for Keyboard Load
		*/
		$(document).keydown(function(objEvent){
		    if(objEvent.keyCode == 9) {  //tab pressed
		        objEvent.preventDefault(); // stops its action
		    }
		});
		$('#telp').keyup(function(objEvent){
			/*if((objEvent.keyCode >= 48 && objEvent.keyCode <= 57) || (objEvent.keyCode >= 96 && objEvent.keyCode <= 105) || (objEvent.keyCode == 107)  || (objEvent.keyCode == 61)){
				return true;
			}else{
				$(this).attr('placeholder', 'Format tidak diijinkan').val('').focus().blur().css({'border':'2px #dc3545 solid', 'boxShadow':'0 0 0 #DC3545'});
				return false;
			}*/
			var patt1 = new RegExp('^[+0-9]+$');
			var str = $('#telp').val();
			var result = str.match(patt1);
			if (result == null)
			{
				$(this).attr('placeholder', 'Format tidak diijinkan').val('+62').css({'border':'2px #dc3545 solid', 'boxShadow':'0 0 0 #DC3545'});
				return false;
			}else{
				$(this).css({'border':'1px #0040FF solid', 'boxShadow':'0 0 0 #819FF7'});
			}
		});


		/*
		Window Event Load
		*/
		$(window).load(function(){

		}); // end of window load


		/* Window Event Scroll */
		$(window).scroll(function(){

			if($(this).scrollTop() > 10){
				$('#back-to-top').fadeIn('slow');
			}else{
				$('#back-to-top').fadeOut('slow');
			}

		}); // end of window scroll

		/* Window Resize Envent */
		$(window).resize(function(){

		}); // end of window resize


	}); // end of function
})(jQuery); // end of jQuery name space


/*************
NATIVE JS LOAD
*************/

/* Video */
function playVideo(_idVideo, _controls){
	idVideo = '#'+_idVideo;
	controls = '#'+_controls;
	if($(idVideo).get(0).paused){
		$(idVideo).get(0).play();
		$(controls).toggleClass('fa-play-circle fa-pause-circle');
	}else{
		$(idVideo).get(0).pause();
		$(controls).toggleClass('fa-pause-circle fa-play-circle');
	}
}

var video_count = 1;
var videoPlayer = document.getElementById('source1');        
var video = document.getElementById('video1');

function run(){
    video_count++;
    if (video_count == 4) video_count = 1;
    videoPlayer.setAttribute('src','video/video'+video_count+'.mp4');       
    video.play();

}