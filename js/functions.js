//Show filters pane

	function toggleFiltersPane(){
		if ($(".filters").hasClass("shown")){
			$(".filters").removeClass("shown");
			$(".button-drop img").attr("src","./img/caret-down.png");
		} else {
			$(".filters").addClass("shown");
			$(".button-drop img").attr("src","./img/caret-up.png");
		}
	}

//Get siblings of an element

	function siblings(div){
		var siblingsList = [].slice.call(div.parentNode.children).filter(function(v) { return v !== div });
		return siblingsList;
	}

//Change colour of filters button & show appropriate filters

	function changeFilters(){
		$(this).siblings().removeClass("active");
		$(this).addClass("active");

		if ($(".games").hasClass("active")){
			$(".games").removeClass("active");
			$(".opp").addClass("active");
		} else if (!$(".games").hasClass("active")){
			$(".opp").removeClass("active");
			$(".games").addClass("active");
		}
	}

//Check checkbox when clicking on checkbox container

	function checkboxContainerClick(){
		if (!this.children[0].checked == true){
			this.children[0].checked = true;
		} else {
			this.children[0].checked = false;
		}
	}

//Check checkbox on click

	function checkboxClick(){
		if (!this.checked == true){
			this.checked = true;
		} else {
			this.checked = false;
		}
	}

//Select all games when 'Year' checkbox is ticked

	function selectAllGames(){
		var boxes = document.querySelectorAll('.year-games > input');
		var thisYear = this.value;
		for (var i = 0; i < boxes.length; i++){
			var year = boxes[i].value.indexOf(thisYear);
			if (year == 0 && this.checked){
				boxes[i].checked = true;
			} else if (year == 0 && !this.checked){
				boxes[i].checked = false;
			}
		}
	}

//Select all teams when Conference checkbox is ticked

	function selectAllTeamsConf(){
		var divboxes = this.parentNode.querySelectorAll('.divisions > li > input');
		var teamboxes = this.parentNode.querySelectorAll('.division > li > input')
		for (var i = 0; i < divboxes.length; i++){
			if (this.checked){
				divboxes[i].checked = true;
			} else if (!this.checked){
				divboxes[i].checked = false;
			}
		}
		for (var j = 0; j < teamboxes.length; j++){
			if (this.checked){
				teamboxes[j].checked = true;
			} else if (!this.checked){
				teamboxes[j].checked = false;
			}
		}
	}

//Select all teams when Division checkbox is ticked

	function selectAllTeamsDiv(){
		var teamboxes = siblings(this)[0].querySelectorAll('.division > li > input');
		for (var i = 0; i < teamboxes.length; i++){
			if (this.checked){
				teamboxes[i].checked = true;
			} else if (!this.checked){
				teamboxes[i].checked = false;
			}
		}
	}

//Clear all checkboxes

	function clearAll(){
		var boxes = document.querySelectorAll('input');
		for (var i = 0; i < boxes.length; i++){
			boxes[i].checked = false;
		}
	}

//Check scroll position, show stats container

	function showStats(){
		if (stats.getBoundingClientRect().top < (window.innerHeight - 200)){ 
			stats.classList.add("show");
		}
	}

//Reduce surname size if covering picture

	function surnameReduce(){
		if (surname[0].getBoundingClientRect().right > 200 && window.innerWidth < 700){
			surname[0].style.fontSize = "1.4rem";
		}
	}