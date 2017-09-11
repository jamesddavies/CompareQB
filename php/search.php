<input class="search-input" type="text" autofocus placeholder="QB Name">
<ul class="suggestions">
		
</ul>
	
<a class="link"><button class="search-btn">Search</button></a>

<script>

	$(".search-input").keyup(function(e){

		//Go to player page on enter

		if (e.keyCode == 13){
		window.location = "#!/player/" + $(this).val().toLowerCase();
		$(".search-overlay").removeClass("show");
		}

		//Live search update

		if ($(".search-input").val().length <= 2){

			$self = $(this);

			$self.siblings(".suggestions").html("");

		} else if ($(this).val().length > 2){ 

			$self = $(this);

			$.ajax({
				url: 'php/livesearch.php?search=' + $(this).val(),
				success: function(response){
					$self.siblings(".suggestions").html(response);
				},
				error: function(response){
					console.log("Error! - " + request.status);
				}
			})

		}

	});

	$(".search-input").change(function(){
		$(".link").attr("href","#!/player/" + $(this).val().toLowerCase());
	})

	$(".suggestions").click(function(e){

		if (e.target.tagName == "LI"){
			$(this).siblings(".search-input").val(e.target.innerHTML).change();
		}

		window.location = "#!/player/" + $(this).siblings(".search-input").val().toLowerCase();
		$(".search-overlay").removeClass("show");
		
	})

</script>