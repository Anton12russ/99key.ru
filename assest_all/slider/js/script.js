
   	 $(document).ready(function() {
	
       
			
			
	$('#imageGallery').lightSlider({
        gallery:true,
        item:1,
        loop:true,
        thumbItem:5,
		    speed: 500, //ms'

           // auto: true,
            pauseOnHover: true,
            loop: true,
            slideEndAnimation: true,
            pause: 2000,
        slideMargin:0,
        enableDrag: false,
        currentPagerPosition:'left',
        onSliderLoad: function(el) {
            el.lightGallery({
                selector: '#imageGallery .lslide'
            });
        }   
    });  
		});
		
