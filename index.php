<?php ini_set('display_errors', 1); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<!--Basic Page Needs-->
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CompareQB</title>
	<base href="/">

	<!--<meta name="description" content="">-->
	<meta name="author" content="James Davies">

	<!--IE Support-->
	<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">

	<!--Fonts-->
	<link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">

	<!--CSS-->
	<link rel="stylesheet" href="stylesheets/skeleton.css">
	<link rel="stylesheet" href="stylesheets/normalize.css">
	<link rel="stylesheet" href="stylesheets/screen.css">
	<!--Slick slider-->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.css"/>

	<!--Favicon-->
	<link rel="icon" type="image/png" href="img/helmet.png">

	<!--Manifest-->
	<link rel="manifest" href="manifest.json">

	<!--Website functions-->
	<script src="js/functions.js"></script>

	<!--jQuery-->
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="  crossorigin="anonymous"></script>
	<!--Chart.js-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
	<!--AngularJS-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.5/angular.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.5/angular-sanitize.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.5/angular-route.min.js"></script>
	<!--Slick JS-->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.js"></script>
	
</head>
<body ng-app="playerApp" class="ng-cloak">


	<div class="ng-view view"></div>

	<nav>
	<ul>
		<li class="search-link"><a><img src="img/search-blk.png"><br>Search</a></li>
		<li class="home-link"><a href="/"><img src="img/home-blk.png"><br>Home</a></li>
		<li class="compare-link"><a href="/compare"><img src="img/arrows-blk.png"><br>Compare</a></li>
	</ul>
	</nav>

	<div class="search-overlay">
		<?php include('php/search.php'); ?>
	</div>

<script src="js/player.js"></script>
<script>
$(document).ready(function(){
	$(".search-link").click(function(){
		$(".search-overlay").toggleClass("show");
		$(".search-input").eq(0).focus();
	})
	$(".link").click(function(){
		$(".search-overlay").toggleClass("show");
	})

});
</script>
</body>
</html>