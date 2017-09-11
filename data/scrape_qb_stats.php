<?php
	
	require('../php/config.php');

	require('qblinks.php');

	require('simple_html_dom.php');

foreach ($qblinks as $qb => $link){ 

	$html = file_get_html("" . $link . "");

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

//Get Player ID

	try {
		$db = new PDO($dsn,$dbuser,$dbpass,$opt);
	} catch (PDOException $e) {
		echo "Error connecting to database: " . $e->getMessage();
	}

	$query = $db->prepare("SELECT * FROM playerdata WHERE `full_name` = :qb");
	$query->execute([
		"qb" => $qb
	]);

	while ($row = $query->fetch()){
		$id = $row['id'];
	}

	for ($i = 0; $i < ($rownum - 1); $i++){
		$array = [];
		//$string = "";

			$array['id'] = $id;

			$yearid = $html->find('td[data-stat=year_id]');
			if (isset($yearid[$i])){ 
				//$string .= $yearid[$i]->innertext . ",";
				$array['year'] = $yearid[$i]->innertext;
			} else {
				//$string .= "0,";
				$array['year'] = 0;
			}
			$game_date = $html->find('td[data-stat=game_date]');
			if (isset($game_date[$i])){ 
				//$string .= $game_date[$i]->find('a')[0]->innertext . ",";
				$array['date'] = $game_date[$i]->find('a')[0]->innertext;
			} else {
				//$string .= "0,";
				$array['date'] = 0;
			}
			$gamenum = $html->find('td[data-stat=game_num]');
			if (isset($gamenum[$i])){ 
				//$string .= $gamenum[$i]->innertext . ",";
				$array['week'] = $gamenum[$i]->innertext;
			} else {
				//$string .= "0,";
				$array['week'] = 0;
			}
			$age = $html->find('td[data-stat=age]');
			if (isset($age[$i])){ 
				//$string .= $age[$i]->innertext . ",";
				$array['age'] = $age[$i]->innertext;
			} else {
				//$string .= "0,";
				$array['age'] = 0;
			}
			$team = $html->find('td[data-stat=team]');
			if (isset($team[$i]) && $team[$i]->find('a')[0]->innertext == "NWE"){ 
				//$string .= $team[$i]->find('a')[0]->innertext . ",";
				$array['team'] = "NE";
			} else if (isset($team[$i])){
				$array['team'] = $team[$i]->find('a')[0]->innertext;
			} else {
				//$string .= "0,";
				$array['team'] = 0;
			}
			$game_location = $html->find('td[data-stat=game_location]');
			if (isset($game_location[$i])){ 
				//$string .= $game_location[$i]->innertext . ",";
				$array['@'] = $game_location[$i]->innertext;
			} else {
				//$string .= "0,";
				$array['@'] = 0;
			}
			$opp = $html->find('td[data-stat=opp]');
			if (isset($opp[$i]) && $opp[$i]->find('a')[0]->innertext == "NWE"){ 
				//$string .= $opp[$i]->find('a')[0]->innertext . ",";
				$array['opp'] = "NE";
			} else if (isset($opp[$i])){
				$array['opp'] = $opp[$i]->find('a')[0]->innertext;
			} else {
				//$string .= "0,";
				$array['opp'] = 0;
			}
			$game_result = $html->find('td[data-stat=game_result]');
			if (isset($game_result[$i])){ 
				//$string .= $game_result[$i]->find('a')[0]->innertext . ",";
				$array['result'] = $game_result[$i]->find('a')[0]->innertext;
			} else {
				//$string .= "0,";
				$array['result'] = 0;
			}
			$gs = $html->find('td[data-stat=gs]');
			if (isset($gs[$i])){ 
				//$string .= $gs[$i]->innertext . ",";
				$array['GS'] = $gs[$i]->innertext;
			} else {
				//$string .= "0,";
				$array['GS'] = 0;
			}
			$pass_cmp = $html->find('td[data-stat=pass_cmp]');
			if (isset($pass_cmp[$i])){ 
				//$string .= $pass_cmp[$i]->innertext . ",";
				$array['cmp'] = $pass_cmp[$i]->innertext;
			} else {
				//$string .= "0,";
				$array['cmp'] = 0;
			}
			$pass_att = $html->find('td[data-stat=pass_att]');
			if (isset($pass_att[$i])){ 
				//$string .= $pass_att[$i]->innertext . ",";
				$array['att'] = $pass_att[$i]->innertext;
			} else {
				//$string .= "0,";
				$array['att'] = 0;
			}
			$pass_cmp_perc = $html->find('td[data-stat=pass_cmp_perc]');
			if (isset($pass_cmp_perc[$i])){ 
				//$string .= $pass_cmp_perc[$i]->innertext . ",";
				$array['cmp%'] = $pass_cmp_perc[$i]->innertext;
			} else {
				//$string .= "0,";
				$array['cmp%'] = 0;
			}
			$pass_yds = $html->find('td[data-stat=pass_yds]');
			if (isset($pass_yds[$i])){ 
				//$string .= $pass_yds[$i]->innertext . ",";
				$array['yds'] = $pass_yds[$i]->innertext;
			} else {
				//$string .= "0,";
				$array['yds'] = 0;
			}
			$pass_td = $html->find('td[data-stat=pass_td]');
			if (isset($pass_td[$i])){ 
				//$string .= $pass_td[$i]->innertext . ",";
				$array['td'] = $pass_td[$i]->innertext;
			} else {
				//$string .= "0,";
				$array['td'] = 0;
			}
			$pass_int = $html->find('td[data-stat=pass_int]');
			if (isset($pass_int[$i])){ 
				//$string .= $pass_int[$i]->innertext . ",";
				$array['int'] = $pass_int[$i]->innertext;
			} else {
				//$string .= "0,";
				$array['int'] = 0;
			}
			$pass_rating = $html->find('td[data-stat=pass_rating]');
			if (isset($pass_rating[$i])){ 
				//$string .= $pass_rating[$i]->innertext . ",";
				$array['rate'] = $pass_rating[$i]->innertext;
			} else {
				//$string .= "0,";
				$array['rate'] = 0;
			}
			$pass_sacked = $html->find('td[data-stat=pass_sacked]');
			if (isset($pass_sacked[$i])){ 
				//$string .= $pass_sacked[$i]->innertext . ",";
				$array['sk'] = $pass_sacked[$i]->innertext;
			} else {
				//$string .= "0,";
				$array['sk'] = 0;
			}
			$pass_sacked_yds = $html->find('td[data-stat=pass_sacked_yds]');
			if (isset($pass_sacked_yds[$i])){ 
				//$string .= $pass_sacked_yds[$i]->innertext . ",";
				$array['skyds'] = $pass_sacked_yds[$i]->innertext;
			} else {
				//$string .= "0,";
				$array['skyds'] = 0;
			}
			$pass_yds_per_att = $html->find('td[data-stat=pass_yds_per_att]');
			if (isset($pass_yds_per_att[$i])){ 
				//$string .= $pass_yds_per_att[$i]->innertext . ",";
				$array['y/a'] = $pass_yds_per_att[$i]->innertext;
			} else {
				//$string .= "0,";
				$array['y/a'] = 0;
			}
			$pass_adj_yds_per_att = $html->find('td[data-stat=pass_adj_yds_per_att]');
			if (isset($pass_adj_yds_per_att[$i])){ 
				//$string .= $pass_adj_yds_per_att[$i]->innertext . ",";
				$array['ay/a'] = $pass_adj_yds_per_att[$i]->innertext;
			} else {
				//$string .= "0,";
				$array['ay/a'] = 0;
			}
			$rush_att = $html->find('td[data-stat=rush_att]');
			if (isset($rush_att[$i])){ 
				//$string .= $rush_att[$i]->innertext . ",";
				$array['attru'] = $rush_att[$i]->innertext;
			} else {
				//$string .= "0,";
				$array['attru'] = 0;
			}
			$rush_yds = $html->find('td[data-stat=rush_yds]');
			if (isset($rush_yds[$i])){ 
				//$string .= $rush_yds[$i]->innertext . ",";
				$array['ydsru'] = $rush_yds[$i]->innertext;
			} else {
				//$string .= "0,";
				$array['ydsru'] = 0;
			}
			$rush_yds_per_att = $html->find('td[data-stat=rush_yds_per_att]');
			if (isset($rush_yds_per_att[$i])){ 
				//$string .= $rush_yds_per_att[$i]->innertext . ",";
				$array['y/aru'] = $rush_yds_per_att[$i]->innertext;
			} else {
				//$string .= "0,";
				$array['y/aru'] = 0;
			}
			$rush_td = $html->find('td[data-stat=rush_td]');
			if (isset($rush_td[$i])){ 
				//$string .= $rush_td[$i]->innertext . ",";
				$array['tdru'] = $rush_td[$i]->innertext;
			} else {
				//$string .= "0,";
				$array['tdru'] = 0;
			}
			$targets = $html->find('td[data-stat=targets]');
			if (isset($targets[$i])){ 
				//$string .= $targets[$i]->innertext . ",";
				$array['tgtre'] = $targets[$i]->innertext;
			} else {
				//$string .= "0,";
				$array['tgtre'] = 0;
			}
			$rec = $html->find('td[data-stat=rec]');
			if (isset($rec[$i])){ 
				//$string .= $rec[$i]->innertext . ",";
				$array['recre'] = $rec[$i]->innertext;
			} else {
				//$string .= "0,";
				$array['recre'] = 0;
			}
			$rec_yds = $html->find('td[data-stat=rec_yds]');
			if (isset($rec_yds[$i])){ 
				//$string .= $rec_yds[$i]->innertext . ",";
				$array['ydsre'] = $rec_yds[$i]->innertext;
			} else {
				//$string .= "0,";
				$array['ydsre'] = 0;
			}
			$rec_yds_per_rec = $html->find('td[data-stat=rec_yds_per_rec]');
			if (isset($rec_yds_per_rec[$i])){ 
				//$string .= $rec_yds_per_rec[$i]->innertext . ",";
				$array['y/rre'] = $rec_yds_per_rec[$i]->innertext;
			} else {
				//$string .= "0,";
				$array['y/rre'] = 0;
			}
			$rec_td = $html->find('td[data-stat=rec_td]');
			if (isset($rec_td[$i])){ 
				//$string .= $rec_td[$i]->innertext . ",";
				$array['tdre'] = $rec_td[$i]->innertext;
			} else {
				//$string .= "0,";
				$array['tdre'] = 0;
			}
			$punt = $html->find('td[data-stat=punt]');
			if (isset($punt[$i])){ 
				//$string .= $punt[$i]->innertext . ",";
				$array['pnt'] = $punt[$i]->innertext;
			} else {
				//$string .= "0,";
				$array['pnt'] = 0;
			}
			$punt_yds = $html->find('td[data-stat=punt_yds]');
			if (isset($punt_yds[$i])){ 
				//$string .= $punt_yds[$i]->innertext . ",";
				$array['ydspnt'] = $punt_yds[$i]->innertext;
			} else {
				//$string .= "0";
				$array['ydspnt'] = 0;
			} 

		$count++;
		//$output = rtrim($string,',');
		//$output = str_replace("NWE", "NE", $output);
		//echo $output . "<br>";
		//array_push($array,$output);
		//array_push($arrays,$array);
		//echo $array['year'];
		//var_dump($array);

		//$ins = $db->query("INSERT INTO regstats VALUES (" . implode("', '", $array) . ")");

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
			echo "ID: " . $array['id'] . " - Row Number: " . $count . " - Success!<br>";
		} else {
			echo "ID: " . $array['id'] . " - Row Number: " . $count . " - Failed.<br>";
		}
	}
}

?>