//Show filters pane

	$(".button-drop").click(toggleFiltersPane);

//Hide filters pane on stats update

	$(".updateBtn").click(toggleFiltersPane);

//Check scroll position, show stats container
		
	$(window).scroll(function(){
		if ($("#stat-container").css("opacity") < 1){
			if (( $("#stat-container").offset().top - $(window).scrollTop() ) < 300){
				$("#stat-container").css("opacity","1");
			}
		}
	})

//Reduce surname size if covering picture
				
	setTimeout(function(){
		var surnameOffsetRight = ($(window).width() - ($(".surname").offset().left + $(".surname").outerWidth()));
		if (surnameOffsetRight < 200 && window.innerWidth < 700){
			$(".surname").css("font-size","1.4rem");
		}
	},140);

//Initialise filter slider

	setTimeout(function(){
	$(".slider").slick({
		arrows: false
	});
	$(".slider-prev").click(function(){
		$(".slider").slick('slickPrev');
	});
	$(".slider-next").click(function(){
		$(".slider").slick('slickNext');
	});	
	},140)

