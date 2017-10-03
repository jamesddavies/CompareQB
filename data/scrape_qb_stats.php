<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

	require('../php/altconfig.php');

	require('qblinks.php');

	require('simple_html_dom.php');

	try {
		$db = new PDO($dsn,$dbuser,$dbpass,$opt);
	} catch (PDOException $e) {
		echo "Error connecting to database: " . $e->getMessage();
	}

	function getRows($link,$html){
		
		//$rows is the number of rows in the regular season stats table on the page - $rownum will become the number of rows we want data from
		$rows = $html->find('table#stats')[0]->find('tr');
		$num = count($rows);
		$rownum = 0;
		$count = 0;
		
		for ($k = 0; $k < $num; $k++){
			if ($rows[$k]->find('td[data-stat=year_id]')){
				$rownum++;
			}
		}

		return $rownum;
	}

	function getPlayerID($qb,$db){

		$query = $db->prepare("SELECT * FROM playerdata WHERE `full_name` = :qb");
		$query->execute([
			"qb" => $qb
		]);

		while ($row = $query->fetch()){
			$id = $row['id'];
		}

		return $id;
	}

	function createArray($id,$html,$db,$i,$name){
			$array = [];
	
			$array['id'] = $id;
	
			$yearid = $html->find('td[data-stat=year_id]');
			if (isset($yearid[$i])){ 
				$array['year'] = $yearid[$i]->innertext;
			} else {
				$array['year'] = 0;
			}
			$game_date = $html->find('td[data-stat=game_date]');
			if (isset($game_date[$i])){ 
				$array['date'] = $game_date[$i]->find('a')[0]->innertext;
			} else {
				$array['date'] = 0;
			}
			$gamenum = $html->find('td[data-stat=game_num]');
			if (isset($gamenum[$i])){ 
				$array['week'] = $gamenum[$i]->innertext;
			} else {
				$array['week'] = 0;
			}
			$age = $html->find('td[data-stat=age]');
			if (isset($age[$i])){ 
				$array['age'] = $age[$i]->innertext;
			} else {
				$array['age'] = 0;
			}
			$team = $html->find('td[data-stat=team]');
			if (isset($team[$i])){
				$array['team'] = $team[$i]->find('a')[0]->innertext;
			} else {
				$array['team'] = 0;
			}
			$game_location = $html->find('td[data-stat=game_location]');
			if (isset($game_location[$i])){ 
				$array['@'] = $game_location[$i]->innertext;
			} else {
				$array['@'] = 0;
			}
			$opp = $html->find('td[data-stat=opp]');
			if (isset($opp[$i]) && $opp[$i]->find('a')[0]->innertext == "NWE"){ 
				$array['opp'] = "NE";
			} else if (isset($opp[$i])){
				$array['opp'] = $opp[$i]->find('a')[0]->innertext;
			} else {
				$array['opp'] = 0;
			}
			$game_result = $html->find('td[data-stat=game_result]');
			if (isset($game_result[$i])){ 
				$array['result'] = $game_result[$i]->find('a')[0]->innertext;
			} else {
				$array['result'] = 0;
			}
			$gs = $html->find('td[data-stat=gs]');
			if (isset($gs[$i])){ 
				$array['GS'] = $gs[$i]->innertext;
			} else {
				$array['GS'] = 0;
			}
			$pass_cmp = $html->find('td[data-stat=pass_cmp]');
			if (isset($pass_cmp[$i])){ 
				$array['cmp'] = $pass_cmp[$i]->innertext;
			} else {
				$array['cmp'] = 0;
			}
			$pass_att = $html->find('td[data-stat=pass_att]');
			if (isset($pass_att[$i])){ 
				$array['att'] = $pass_att[$i]->innertext;
			} else {
				$array['att'] = 0;
			}
			$pass_cmp_perc = $html->find('td[data-stat=pass_cmp_perc]');
			if (isset($pass_cmp_perc[$i])){ 
				$array['cmp%'] = $pass_cmp_perc[$i]->innertext;
			} else {
				$array['cmp%'] = 0;
			}
			$pass_yds = $html->find('td[data-stat=pass_yds]');
			if (isset($pass_yds[$i])){ 
				$array['yds'] = $pass_yds[$i]->innertext;
			} else {
				$array['yds'] = 0;
			}
			$pass_td = $html->find('td[data-stat=pass_td]');
			if (isset($pass_td[$i])){ 
				$array['td'] = $pass_td[$i]->innertext;
			} else {
				$array['td'] = 0;
			}
			$pass_int = $html->find('td[data-stat=pass_int]');
			if (isset($pass_int[$i])){ 
				$array['int'] = $pass_int[$i]->innertext;
			} else {
				$array['int'] = 0;
			}
			$pass_rating = $html->find('td[data-stat=pass_rating]');
			if (isset($pass_rating[$i])){ 
				$array['rate'] = $pass_rating[$i]->innertext;
			} else {
				$array['rate'] = 0;
			}
			$pass_sacked = $html->find('td[data-stat=pass_sacked]');
			if (isset($pass_sacked[$i])){ 
				$array['sk'] = $pass_sacked[$i]->innertext;
			} else {
				$array['sk'] = 0;
			}
			$pass_sacked_yds = $html->find('td[data-stat=pass_sacked_yds]');
			if (isset($pass_sacked_yds[$i])){ 
				$array['skyds'] = $pass_sacked_yds[$i]->innertext;
			} else {
				$array['skyds'] = 0;
			}
			$pass_yds_per_att = $html->find('td[data-stat=pass_yds_per_att]');
			if (isset($pass_yds_per_att[$i])){ 
				$array['y/a'] = $pass_yds_per_att[$i]->innertext;
			} else {
				$array['y/a'] = 0;
			}
			$pass_adj_yds_per_att = $html->find('td[data-stat=pass_adj_yds_per_att]');
			if (isset($pass_adj_yds_per_att[$i])){ 
				$array['ay/a'] = $pass_adj_yds_per_att[$i]->innertext;
			} else {
				$array['ay/a'] = 0;
			}
			$rush_att = $html->find('td[data-stat=rush_att]');
			if (isset($rush_att[$i])){ 
				$array['attru'] = $rush_att[$i]->innertext;
			} else {
				$array['attru'] = 0;
			}
			$rush_yds = $html->find('td[data-stat=rush_yds]');
			if (isset($rush_yds[$i])){
				$array['ydsru'] = $rush_yds[$i]->innertext;
			} else {
				$array['ydsru'] = 0;
			}
			$rush_yds_per_att = $html->find('td[data-stat=rush_yds_per_att]');
			if (isset($rush_yds_per_att[$i])){ 
				$array['y/aru'] = $rush_yds_per_att[$i]->innertext;
			} else {
				$array['y/aru'] = 0;
			}
			$rush_td = $html->find('td[data-stat=rush_td]');
			if (isset($rush_td[$i])){ 
				$array['tdru'] = $rush_td[$i]->innertext;
			} else {
				$array['tdru'] = 0;
			}
			$targets = $html->find('td[data-stat=targets]');
			if (isset($targets[$i])){ 
				$array['tgtre'] = $targets[$i]->innertext;
			} else {
				$array['tgtre'] = 0;
			}
			$rec = $html->find('td[data-stat=rec]');
			if (isset($rec[$i])){ 
				$array['recre'] = $rec[$i]->innertext;
			} else {
				$array['recre'] = 0;
			}
			$rec_yds = $html->find('td[data-stat=rec_yds]');
			if (isset($rec_yds[$i])){ 
				$array['ydsre'] = $rec_yds[$i]->innertext;
			} else {
				$array['ydsre'] = 0;
			}
			$rec_yds_per_rec = $html->find('td[data-stat=rec_yds_per_rec]');
			if (isset($rec_yds_per_rec[$i])){ 
				$array['y/rre'] = $rec_yds_per_rec[$i]->innertext;
			} else {
				$array['y/rre'] = 0;
			}
			$rec_td = $html->find('td[data-stat=rec_td]');
			if (isset($rec_td[$i])){ 
				$array['tdre'] = $rec_td[$i]->innertext;
			} else {
				$array['tdre'] = 0;
			}
			$punt = $html->find('td[data-stat=punt]');
			if (isset($punt[$i])){ 
				$array['pnt'] = $punt[$i]->innertext;
			} else {
				$array['pnt'] = 0;
			}
			$punt_yds = $html->find('td[data-stat=punt_yds]');
			if (isset($punt_yds[$i])){ 
				$array['ydspnt'] = $punt_yds[$i]->innertext;
			} else {
				$array['ydspnt'] = 0;
			}

			$array = formatArray($array);

			$notInDB = checkRow($array,$db);

			if ($notInDB){
				return putRow($array,$db,$i+1,$name);
			} else {
				return "Row already in DB.<br>";
			}	
	}

	function checkRow($array,$db){
		$check = $db->prepare("SELECT * FROM regstats WHERE `id` = :id AND `date` = :dateVar");
		$check->execute([
			"id" => $array['id'],
			"dateVar" => $array['date']
		]);
		$count = $check->rowCount();

		if ($count < 1){
			return true;
		} else {
			return false;
		}
	}

	function formatArray($array){

		$formatTeams = [
			"TAM" => "TB",
			"NOR" => "NO",
			"SDG" => "LAC",
			"STL" => "LAR",
			"NWE" => "NE",
			"GNB" => "GB",
			"SFO" => "SF",
			"KAN" => "KC",
			"RAI" => "OAK",
			"RAM" => "LAR",
			"PHO" => "ARI"
		];

		foreach($formatTeams as $old => $new){
			if ($array['team'] == $old){
				$array['team'] = $new;
			}
			if ($array['opp'] == $old){
				$array['opp'] = $new;
			}
		}

		if ($array['year'] < 1997 && $array['team'] == "HOU"){
			$array['year'] == "TEN";
		}

		return $array;
	}

	function putRow($array,$db,$count,$name){
		$ins = $db->prepare("INSERT INTO regstats (
			`id`,
			`year`,
			`date`,
			`week`,
			`age`,
			`team`,
			`@`,
			`opp`,
			`GS`,
			`cmp`,
			`att`,
			`cmp%`,
			`yds`,
			`td`,
			`int`,
			`rate`,
			`sk`,
			`skyds`,
			`y/a`,
			`ay/a`,
			`attru`,
			`ydsru`,
			`y/aru`,
			`tdru`,
			`tgtre`,
			`recre`,
			`ydsre`,
			`y/rre`,
			`tdre`,
			`pnt`,
			`ydspnt`,
			`result`
			) VALUES (
			:id,
			:year,
			:dateVar,
			:week,
			:age,
			:team,
			:at,
			:opp,
			:GS,
			:cmp,
			:att,
			:cmpperc,
			:yds,
			:td,
			:intVar,
			:rate,
			:sk,
			:skyds,
			:y_a,
			:ay_a,
			:attru,
			:ydsru,
			:y_aru,
			:tdru,
			:tgtre,
			:recre,
			:ydsre,
			:y_rre,
			:tdre,
			:pnt,
			:ydspnt,
			:result
			)");

		$ins->execute([
			"id" => $array['id'],
			"year" => $array['year'],
			"dateVar" => $array['date'],
			"week" => $array['week'],
			"age" => $array['age'],
			"team" => $array['team'],
			"at" => $array['@'],
			"opp" => $array['opp'],
			"GS" => $array['GS'],
			"cmp" => $array['cmp'],
			"att" => $array['att'],
			"cmpperc" => $array['cmp%'],
			"yds" => $array['yds'],
			"td" => $array['td'],
			"intVar" => $array['int'],
			"rate" => $array['rate'],
			"sk" => $array['sk'],
			"skyds" => $array['skyds'],
			"y_a" => $array['y/a'],
			"ay_a" => $array['ay/a'],
			"attru" => $array['attru'],
			"ydsru" => $array['ydsru'],
			"y_aru" => $array['y/aru'],
			"tdru" => $array['tdru'],
			"tgtre" => $array['tgtre'],
			"recre" => $array['recre'],
			"ydsre" => $array['ydsre'],
			"y_rre" => $array['y/rre'],
			"tdre" => $array['tdre'],
			"pnt" => $array['pnt'],
			"ydspnt" => $array['ydspnt'],
			"result" => $array['result']
		]);

		if ($ins){
			return "ID: " . $array['id'] . " - " . $name . " - Row Number: " . $count . " - Success!<br>";
		} else {
			return "ID: " . $array['id'] . " - " . $name . " - Row Number: " . $count . " - Failed.<br>";
		}
	}
	
foreach ($qblinks as $qb => $link){

	$html = file_get_html("" . $link . "");
	$rownum = getRows($link,$html);
	$id = getPlayerID($qb,$db);
	for ($i = 0; $i < ($rownum - 1); $i++){
		$result = createArray($id,$html,$db,$i,$qb);
		echo $result;
	}
		
}

?>