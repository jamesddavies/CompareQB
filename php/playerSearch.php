<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require('config.php');

	if (isset($_GET['search'])){
		$search = strtolower($_GET['search']);
	}
	if (isset($_GET['searchid'])){
		$searchid = $_GET['searchid'];
	}

	try {
		$db = new PDO($dsn,$dbuser,$dbpass,$opt);
	} catch (PDOException $e) {
		echo "Error connecting to database: " . $e->getMessage();
	}

	if (isset($search)){
		$query = $db->prepare("SELECT * FROM playerdata WHERE full_name LIKE :name");
		$query->execute([
		    "name" => "%" . $search . "%"
		]);
	} 
	if (isset($searchid)){
		$query = $db->prepare("SELECT * FROM playerdata WHERE id = :searchid");
		$query->execute([
		    "searchid" => $searchid
		]);
	}

	$count = 0;

	$surname = "surname";
	$firstname = "";

	while ($row = $query->fetch()) {
		
			$name = ucwords($row['full_name']);
			$names = explode(" ",$name);
			$firstname = $names[0];
			$surnames = array_slice($names,1);
			$surname = implode(" ",$surnames);
			$team = strtoupper($row['team']);
			$position = strtoupper($row['position']);
			$id = $row['id'];
			$number = $row['number'];
			$birthdate = $row['birthdate'];
			$college = ucwords($row['college']);
			$ht = intval($row['height']) / 12;
			$height = number_format($ht, 1, "' ", "");
			$weight = $row['weight'];
			$years = intval(date("Y")) - intval($row['drafted_year']);
			if ($years < 1){
				$exp = "Rookie";
			} else if ($years == 1){
				$exp = $years . " season";
			} else {
				$exp = $years . " seasons";
			}
			if ($row['retired'] != 0){
				$exp = (intval($row['retired']) - intval($row['drafted_year']) + 1) . " seasons";
			}
			$drafted_year = $row['drafted_year'];
			$drafted_info = $row['drafted_info'];
			$picurl = $row['picurl'];
			$count++;
		
	}



	//Format particular names

	if ($surname[0] === "M" && $surname[1] === "c"){
		$surname[2] = strtoupper($surname[2]);
	}

	if ($firstname === "Aj"){
		$firstname = "AJ";
	}

	if ($count == 0 && isset($search)){
		$message = "There doesn't seem to be an active player with the name '" . $search . "' - please try again.";
	} else if ($count == 0 && isset($search)){
		$message = "There doesn't seem to be an active player with the id '" . $searchid . "' - please try again.";
	}

	//Set colours

$colormain = "#888888";
$colorsecondary = "#EEEEEE";
$textcolor = "#121212";

if (isset($team)){
	switch ($team){
		case "ARI":
			$colormain = "#9B2743";
			$colorsecondary = "#000000";
			$textcolor = "#FDFDFD";
			break;
		case "ATL":
			$colormain = "#A6192D";
			$colorsecondary = "#000000";
			$textcolor = "#FDFDFD";
			break;
		case "BAL":
			$colormain = "#280353";
			$colorsecondary = "#000000";
			$textcolor = "#FDFDFD";
			break;
		case "BUF":
			$colormain = "#00338D";
			$colorsecondary = "#C60C30";
			$textcolor = "#FDFDFD";
			break;
		case "CAR":
			$colormain = "#0088CE";
			$colorsecondary = "#000000";
			$textcolor = "#FDFDFD";
			break;
		case "CHI":
			$colormain = "#03202F";
			$colorsecondary = "#DD4814";
			$textcolor = "#FDFDFD";
			break;
		case "CIN":
			$colormain = "#FB4F14";
			$colorsecondary = "#000000";
			$textcolor = "#FDFDFD";
			break;
		case "CLE":
			$colormain = "#FE3C00";
			$colorsecondary = "#512F2D";
			$textcolor = "#FDFDFD";
			break;
		case "DAL":
			$colormain = "#0D254C";
			$colorsecondary = "#87909B";
			$textcolor = "#FDFDFD";
			break;
		case "DEN":
			$colormain = "#FB4F14";
			$colorsecondary = "#002244";
			$textcolor = "#FDFDFD";
			break;
		case "DET":
			$colormain = "#006DB0";
			$colorsecondary = "#C5C7CF";
			$textcolor = "#FDFDFD";
			break;
		case "GB":
			$colormain = "#203731";
			$colorsecondary = "#FFB612";
			$textcolor = "#FDFDFD";
			break;
		case "HOU":
			$colormain = "#02253A";
			$colorsecondary = "#B31B34";
			$textcolor = "#FDFDFD";
			break;
		case "IND":
			$colormain = "#003B7B";
			$colorsecondary = "#FDFDFD";
			$textcolor = "#FDFDFD";
			break;
		case "JAX":
			$colormain = "#006778";
			$colorsecondary = "#9F792C";
			$textcolor = "#FDFDFD";
			break;
		case "KC":
			$colormain = "#B20032";
			$colorsecondary = "#F2C800";
			$textcolor = "#FDFDFD";
			break;
		case "LAC":
			$colormain = "#0072CE";
			$colorsecondary = "#FFB81C";
			$textcolor = "#FDFDFD";
			break;
		case "LAR":
			$colormain = "#13264B";
			$colorsecondary = "#C9AF74";
			$textcolor = "#FDFDFD";
			break;
		case "MIA":
			$colormain = "#008D97";
			$colorsecondary = "#F5811F";
			$textcolor = "#FDFDFD";
			break;
		case "MIN":
			$colormain = "#582C81";
			$colorsecondary = "#F0BF00";
			$textcolor = "#FDFDFD";
			break;
		case "NE":
			$colormain = "#0D254C";
			$colorsecondary = "#C80815";
			$textcolor = "#FDFDFD";
			break;
		case "NO":
			$colormain = "#000000";
			$colorsecondary = "#D2B887";
			$textcolor = "#FDFDFD";
			break;
		case "NYG":
			$colormain = "#192F6B";
			$colorsecondary = "#CA001A";
			$textcolor = "#FDFDFD";
			break;
		case "NYJ":
			$colormain = "#0C371D";
			$colorsecondary = "#FDFDFD";
			$textcolor = "#FDFDFD";
			break;
		case "OAK":
			$colormain = "#000000";
			$colorsecondary = "#C4C8CB";
			$textcolor = "#FDFDFD";
			break;
		case "PHI":
			$colormain = "#006369";
			$colorsecondary = "#000000";
			$textcolor = "#FDFDFD";
			break;
		case "PIT":
			$colormain = "#FFB612";
			$colorsecondary = "#000000";
			$textcolor = "#FDFDFD";
			break;
		case "SF":
			$colormain = "#AF1E2C";
			$colorsecondary = "#E6BE8A";
			$textcolor = "#FDFDFD";
			break;
		case "SEA":
			$colormain = "#002244";
			$colorsecondary = "#A5ACAF";
			$textcolor = "#FDFDFD";
			break;
		case "TB":
			$colormain = "#D60A0B";
			$colorsecondary = "#89765F";
			$textcolor = "#FDFDFD";
			break;
		case "TEN":
			$colormain = "#648FCC";
			$colorsecondary = "#0D254C";
			$textcolor = "#FDFDFD";
			break;
		case "WAS":
			$colormain = "#773141";
			$colorsecondary = "#FFB612";
			$textcolor = "#FDFDFD";
			break;
		default:
			$colormain = "#888888";
			$colorsecondary = "#EEEEEE";
			$textcolor = "#121212";
			break;
	}
}

$db = null;
	
	try {
		$db = new PDO($dsn,$dbuser,$dbpass,$opt);
	} catch (PDOException $e) {
		echo "Error connecting to database: " . $e->getMessage();
	}

	$filter = "";

	$queryStr = $_SERVER['QUERY_STRING'];

	parse_str($queryStr,$parsedStr);

	$oppArr = [];
	$gameArr = [];
	$buffer = " ";

	if (isset($parsedStr['opp'])){
	 	if ($parsedStr['opp'][0] != ""){
			foreach ($parsedStr['opp'] as $opp){
				$oppArr[] = "(opp = '" . $opp . "')";
			}
		}
	}

	if (isset($parsedStr['game'])){
		if ($parsedStr['game'][0] != ""){
			foreach ($parsedStr['game'] as $gm){
				$y = substr($gm, 0, 4);
				$g = substr($gm, 5);
				$gameArr[] = "(week = '" . $g . "' AND year = '" . $y . "')";
			}
		}
	}

	if (count($oppArr) > 0 && count($gameArr) > 0){
		$buffer = " OR ";
	}

	if (count($oppArr) > 0 || count($gameArr) > 0){
		$filter .= " AND (" . implode(" OR ",$oppArr) . $buffer . implode(" OR ",$gameArr) . ")";
	} 

	//echo "SELECT * FROM regstats WHERE id = " . $id . $filter;

	$count = 0;
	$yardsplus300 = 0;
	$yardsplus200 = 0;
	$yardsplus100 = 0;
	$yardsunder100 = 0;
	$passyds = 0;
	$passtd = 0;
	$passcmp = 0;
	$passatt = 0;
	$passper = 0;
	$passint = 0;
	$rate = 0;
	$gamesplayed = 0;
	$gamesstarted = 0;
	$ya = 0;
	$aya = 0;
	$sk = 0;
	$skyds = 0;
	$years = [];
	$yearsyds = [];
	$opp = [];

	foreach($db->query("SELECT * FROM regstats WHERE id = " . $id . $filter) as $row) {

				$count++;

				//Get general variables
			
				$passyds += $row['yds'];

				$passtd += $row['td'];

				$passcmp += $row['cmp'];

				$passatt += $row['att'];

				$passint += $row['int'];

				$rate += $row['rate'];

				$ya += $row['y/a'];

				$aya += $row['ay/a'];

				$sk += $row['sk'];

				$skyds += $row['skyds'];

				//Count games started

				if ($row['GS'] == "*"){
					$gamesstarted++;
				}

				//Count games played

				if ($row['att'] > 0){
					$gamesplayed++;
				}

				//Get number of games by yards

				if ($row['yds'] >= 300){
					$yardsplus300++;
				} else if ($row['yds'] >= 200 && $row['yds'] < 300){
					$yardsplus200++;
				} else if ($row['yds'] >= 100 && $row['yds'] < 200){
					$yardsplus100++;
				} else if ($row['yds'] <= 100){
					$yardsunder100++;
				}

				//Get array of years in league within search

				if ($row['year'] != in_array($row['year'], $years)){
					$years[] = $row['year'];
					sort($years, SORT_NATURAL);
					//Check for/fill in empty years
					for ($j = 1; $j < count($years); $j++){
						if (($years[$j] - $years[$j-1]) != 1){
							$extraYear = $years[$j-1] + 1;
							array_splice($years,$j,0,$extraYear);
						}
					}
				}

				//Declare variables for stat in each year
				foreach($years as $yr){
					if (!isset(${"yds".$yr})){
						${"yds".$yr} = 0;
					}
					if (!isset(${"tds".$yr})){
						${"tds".$yr} = 0;
					}
					if (!isset(${"ints".$yr})){
						${"ints".$yr} = 0;
					}
					if (!isset(${"starts".$yr})){
						${"starts".$yr} = 0;
					}
					if (!isset(${"played".$yr})){
						${"played".$yr} = 0;
					}
					if (!isset(${"attempts".$yr})){
						${"attempts".$yr} = 0;
					}
					if (!isset(${"comps".$yr})){
						${"comps".$yr} = 0;
					}
					if (!isset(${"sacks".$yr})){
						${"sacks".$yr} = 0;
					}
					if (!isset(${"sackyards".$yr})){
						${"sackyards".$yr} = 0;
					}
				}
				
				//Stats by year
				foreach($years as $yr){
					if ($row['year'] == $yr){
						${"yds".$yr} += $row['yds'];
						${"tds".$yr} += $row['td'];
						${"ints".$yr} += $row['int'];
						${"played".$yr} += 1;
						${"attempts".$yr} += $row['att'];
						${"comps".$yr} += $row['cmp'];
						${"sacks".$yr} += $row['sk'];
						${"sackyards".$yr} += $row['skyds'];
						if ($row['GS'] == "*"){
							${"starts".$yr} += 1;
						}
					}
				}

		}

	//Passer rating formula

	$rateVar1 = (($passcmp/$passatt) - 0.3) / 0.2;
	$rateVar2 = (($passyds/$passatt) - 3) / 4;
	$rateVar3 = ($passtd/$passatt) / 0.05;
	$rateVar4 = (0.095 - ($passint/$passatt)) / 0.04;

	$rateVar = $rateVar1 + $rateVar2 + $rateVar3 + $rateVar4;

	$passrating = ($rateVar * 100) / 6;

	$passrating = number_format((float)$passrating, 1, ".", "");

	//Formatting

	if ($count != 0){ 

	$passper = (($passcmp / $passatt) * 100);
	$passper = number_format((float)$passper, 2, ".", "");

	if (strlen($passyds) > 4){
		$passyds = substr_replace($passyds,",",2,0);
	}

	$seasons = count($years);

	$missedgames = ($seasons * 16) - $count;

	$missedperc = $missedgames/($seasons * 16) * 100;
	$missedperc = number_format((float)$missedperc, 2, ".", "");

	$ya = number_format((float)($ya / $count), 1, ".", "");

	$aya = number_format((float)($aya / $count), 1, ".", "");

	}


	foreach ($years as $yr){
		$yearsyds[] = ${"yds".$yr};
		$yearstds[] = ${"tds".$yr};
		$yearsints[] = ${"ints".$yr};
		$yearsstarts[] = ${"starts".$yr};
		$yearsgamesplayed[] = ${"played".$yr};
		$attempts[] = ${"attempts".$yr};
		$yearscomps[] = ${"comps".$yr};
		$yearssacks[] = ${"sacks".$yr};
		$yearssackyards[] = ${"sackyards".$yr};
	}

	$yardsPerAtt = [];
	$yearscompperc = [];
	$yearsTDperc = [];
	$yearsYardsPerSack = [];

	//Avg by year

	foreach ($yearsyds as $i => $yearyds){
		//Yards per Attempt
		if ($attempts[$i]){
			$yardsPerAtt[$i] = number_format($yearyds / $attempts[$i],1);
		} else {
			$yardsPerAtt[$i] = 0;
		}
		//Completion %
		if ($attempts[$i]){
			$yearscompperc[$i] = number_format(($yearscomps[$i] / $attempts[$i]) * 100,1);
		} else {
			$yearscompperc[$i] = 0;
		}
		//TD %
		if ($attempts[$i]){
			$yearsTDperc[$i] = number_format(($yearstds[$i] / $attempts[$i]) * 100,1);
		} else {
			$yearsTDperc[$i] = 0;
		}
		// Yards per sack
		if ($yearssacks[$i] > 0){
			$yearsYardsPerSack[$i] = number_format($yearssackyards[$i] / $yearssacks[$i],1);
		} else {
			$yearsYardsPerSack[$i] = 0;
		}
	}

$db = null;

//Get ALL games and ALL years in league

	try {
		$db = new PDO($dsn,$dbuser,$dbpass,$opt);
	} catch (PDOException $e) {
		echo "Error connecting to database: " . $e->getMessage();
	}

	$queryAll = $db->prepare("SELECT * FROM regstats WHERE id = :id");
	$queryAll->execute([
	    "id" => $id
	]);

	$games = [];
	$allYears = [];

	while ($row = $queryAll->fetch()) {

		//Array of games played - year, week, opposition
			$games[] = [
				"year" => $row['year'],
				"week" => $row['week'],
				"opp" => $row['opp'],
			];

		//Get array of all years in league

			if ($row['year'] != in_array($row['year'], $allYears)){
				$allYears[] = $row['year'];
				sort($allYears, SORT_NATURAL);
			}

	}

$db = null;

//Create conf/div/teams object to create menu

$league = [
	"NFC" => [
		"NFCN" => ["CHI", "DET", "GB", "MIN"],
		"NFCS" => ["ATL", "CAR", "NO", "TB"],
		"NFCE" => ["DAL", "NYG", "PHI", "WAS"],
		"NFCW" => ["ARI", "LAR", "SEA", "SF"]
	],
	"AFC" => [
		"AFCN" => ["BAL", "CIN", "CLE", "PIT"],
		"AFCS" => ["HOU", "IND", "JAX", "TEN"],
		"AFCE" => ["BUF", "MIA", "NE", "NYJ"],
		"AFCW" => ["DEN", "KC", "LAC", "OAK"]
	]
];

//Format plaintext summary of search

$searchDescription = "Player Stats";
if (count($gameArr) === 0 && count($oppArr) === 0){
	$searchDescription = "Career Stats";
}
/*$textYears = [];
foreach($gameArr as $gm){
	if (preg_match('/\d{4}/',$gm,$match)){
		if (!in_array($match[0], $textYears)){
			$textYears[] = $match[0];
		}
	}
}

if (count($textYears) > 0){
	$searchDescription .= "for ";
	foreach($textYears as $num => $tYear){
		if ($num < (count($textYears) - 1)){
			$searchDescription .= $tYear . ", ";
		} else {
			$searchDescription .= $tYear . " ";
		}
	}
}*/

//JSON Response

$player = [
	"info" => [
		"firstname" => $firstname,
		"surname" => $surname,
		"team" => $team,
		"id" => $id,
		"number" => $number,
		"birthdate" => $birthdate,
		"college" => $college,
		"height" => $height,
		"weight" => $weight,
		"exp" => $exp,
		"draftedYear" => $drafted_year,
		"draftedInfo" => $drafted_info,
		"img" => $picurl,
		"colors" => [
			"colorMain" => $colormain,
			"colorSecondary" => $colorsecondary,
			"textColor" => $textcolor
		]
	],
	"stats" => [
		"pass" => [
			"yds" => $passyds,
			"td" => $passtd,
			"cmp" => $passcmp,
			"att" => $passatt,
			"passPercent" => $passper,
			"int" => $passint,
			"passerRating" => $passrating,
			"yardsAttempt" => $ya,
			"adjYardsAttempt" => $aya,
			"sacks" => $sk,
			"sackYards" => $skyds,
			"gamesStarted" => $gamesstarted,
			"gamesPlayed" => $gamesplayed,
			"gamesMissed" => $missedgames,
			"gamesMissedPercent" => $missedperc,
			"plus300" => $yardsplus300,
			"plus200" => $yardsplus200,
			"plus100" => $yardsplus100,
			"under100" => $yardsunder100,
			"years" => $years,
			"yardsByYear" => $yearsyds,
			"tdsByYear" => $yearstds,
			"intsByYear" => $yearsints,
			"startsByYear" => $yearsstarts,
			"gamesPlayedByYear" => $yearsgamesplayed,
			"yardsAttByYear" => $yardsPerAtt,
			"attsByYear" => $attempts,
			"completionsByYear" => $yearscomps,
			"comppercByYear" => $yearscompperc,
			"TDpercByYear" => $yearsTDperc,
			"sacksByYear" => $yearssacks,
			"sackYardsByYear" => $yearssackyards,
			"yardsPerSackByYear" => $yearsYardsPerSack,
			"seasons" => $seasons
		],
		"games" => $games,
		"allYears" => $allYears
	],
	"league" => $league,
	"prevSearch" => [
		"games" => $gameArr,
		"opp" => $oppArr,
		"text" => $searchDescription
	]
];

echo json_encode($player);

?>