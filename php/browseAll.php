<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require('config.php');

	if ($_SERVER['REQUEST_METHOD'] != 'GET'){
		die("Request method not accepted. Try a GET request instead.");
	}

	try {
		$db = new PDO($dsn,$dbuser,$dbpass,$opt);
	} catch (PDOException $e) {
		echo "Error connecting to database: " . $e->getMessage();
	}

	$query = $db->prepare("SELECT * FROM playerdata");
	$query->execute();

    $playerArray = [];
    $count = 0;
    $playerArray['players'] = [];

	while ($row = $query->fetch()) {

            $player = [];
		
			$name = ucwords($row['full_name']);
			$names = explode(" ",$name);
			$player['firstname'] = $names[0];
			$surnames = array_slice($names,1);
            $player['surname'] = implode(" ",$surnames);
            $player['team'] = strtoupper($row['team']);
			$player['position'] = strtoupper($row['position']);
			$player['id'] = $row['id'];
			$player['number'] = $row['number'];
			$player['birthdate'] = $row['birthdate'];
			$player['college'] = ucwords($row['college']);
			$ht = intval($row['height']) / 12;
			$player['height'] = number_format($ht, 1, "' ", "");
			$player['weight'] = $row['weight'];
			$player['drafted_year'] = $row['drafted_year'];
			$player['drafted_info'] = $row['drafted_info'];
			$player['picurl'] = $row['picurl'];
            $count++;

            //Set colours

$colormain = "#888888";
$colorsecondary = "#EEEEEE";
$textcolor = "#121212";

if (isset($player['team'])){
	switch ($player['team']){
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

            $player['colorMain'] = $colormain;
            $player['colorSecondary'] = $colorsecondary;
            $player['textColor'] = $textcolor;

            //Format particular names

	        if ($player['surname'][0] === "M" && $player['surname'][1] === "c"){
		        $player['surname'][2] = strtoupper($player['surname'][2]);
	        }

	        if ($player['firstname'] === "Aj"){
		        $player['firstname'] = "AJ";
	        }
            
            array_push($playerArray['players'],$player);
    }

    $playerArray['count'] = $count;

    $sname = [];
    foreach ($playerArray['players'] as $key => $row){
        $sname[$key] = $row['surname'];
    }
    array_multisort($sname, SORT_ASC, $playerArray['players']);

    echo json_encode($playerArray);
    
?>