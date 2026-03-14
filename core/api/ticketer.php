<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/assetutils.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";

	$user = UserUtils::RetrieveUser();


	function getRandomString(int $length = 25): string {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		
		for ($i = 0; $i < $length; $i++) {
			$index = rand(0, strlen($characters) - 1);
			$randomString .= $characters[$index];
		}

		return $randomString;
	}

	function getAnActiveServer(int $placeID): array|null {
		include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

		$stmt_getactiveservers = $con->prepare("SELECT * FROM `active_servers` WHERE `server_placeid` = ? AND `server_playercount` != `server_maxcount` AND `server_teamcreate` = 0");
		$stmt_getactiveservers->bind_param("i", $placeID);
		$stmt_getactiveservers->execute();

		$result_getactiveservers = $stmt_getactiveservers->get_result();

		if($result_getactiveservers->num_rows != 0) {
			return $result_getactiveservers->fetch_assoc();
		}

		return null;
	}

	function isUserInAGame(int $userID): bool {
		include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

		$stmt_getsessiondetails = $con->prepare("SELECT * FROM `active_players` WHERE `session_playerid` = ?");
		$stmt_getsessiondetails->bind_param("i", $userID);
		$stmt_getsessiondetails->execute();

		$result_getsessiondetails = $stmt_getsessiondetails->get_result();

		return $result_getsessiondetails->num_rows != 0;
	}

	function getServerDetails(string $serverID): array|null {
		include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

		$stmt_getsessiondetails = $con->prepare("SELECT * FROM `active_servers` WHERE `server_id` = ? AND `server_teamcreate` = 0");
		$stmt_getsessiondetails->bind_param("s", $serverID);
		$stmt_getsessiondetails->execute();

		$result_getsessiondetails = $stmt_getsessiondetails->get_result();

		if($result_getsessiondetails->num_rows != 0) {
			return $result_getsessiondetails->fetch_assoc();
		}

		return null;
	}

	function getActiveServersCount(int $placeID, bool $teamcreate = false): bool {
		include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

		$stmt_teamcreate = $teamcreate ? 1 : 0;

		$stmt_getactiveservers = $con->prepare("SELECT * FROM `active_servers` WHERE `server_placeid` = ? AND `server_playercount` != `server_maxcount` AND `server_teamcreate` = ?");
		$stmt_getactiveservers->bind_param("ii", $placeID, $stmt_teamcreate);
		$stmt_getactiveservers->execute();

		$result_getactiveservers = $stmt_getactiveservers->get_result();

		return $result_getactiveservers->num_rows;
	}

	function updatePlaceOfSession(string $sessionID, string $placeID, bool $teamcreate = false): array|null {
		include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

		$stmt_teamcreate = $teamcreate ? 1 : 0;

		$stmt_getsessiondetails = $con->prepare("UPDATE `active_players` SET `session_serverid` = ? WHERE `session_id` = ? AND `session_teamcreate` = ?");
		$stmt_getsessiondetails->bind_param("ssi", $placeID, $sessionID, $stmt_teamcreate);
		$stmt_getsessiondetails->execute();

		return null;
	}

	function httpGetJson(string $url, array $headers = [], int $timeout = 10): ?array {
		//echo $url;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$res = curl_exec($ch);
		$errno = curl_errno($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($errno || $res == false || $res == '') return null;
		$decoded = json_decode($res, true);
		if(str_contains($res, "true")) {
			$decoded["result"] = true;
		}
		if (json_last_error() !== JSON_ERROR_NONE) return null;
		return $decoded;
	}

	function findAndStartOtherGame(string $year, Place|null $place = null, User|null $user = null) {

		$settings = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/core/settings.env", true);
		$rcc_settings = $settings['renderer'];

		$access = $settings['asset']['ACCESSKEY'];
		$rcc_ip = $rcc_settings['RCCGAMEIP'];
		//$rcc_ip = "192.168.0.220";

		if($place != null && $user != null) {
			$playerID = $user->id;
			if(isUserInAGame($user->id)) {
				include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
				$stmt_deletesession = $con->prepare("DELETE FROM `active_players` WHERE `session_playerid` = ?");
				$stmt_deletesession->bind_param("i", $playerID);
				$stmt_deletesession->execute();
			}

			$server = getAnActiveServer($place->id);

			if($server != null) {
				$serverID = $server['server_id'];
			} else {
				$serverID = strval($place->id);
			}
			$sessionID = getRandomString(25);
			
			include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
			$stmt_createnewsession = $con->prepare("INSERT INTO `active_players`(`session_id`, `session_serverid`, `session_playerid`, `session_status`) VALUES (?,?,?,0)");
			$stmt_createnewsession->bind_param("ssi", $sessionID, $serverID, $playerID);
			$stmt_createnewsession->execute();

			$dont_load = false;
			if(getActiveServersCount($place->id) == 0) {
				try {
					$serverid = getRandomString(11);
					$placeId = $place->id;
					$port = rand(40000, 49999);
					$strPort = strval($port);
					$jobId = md5(rand());
					$json = httpGetJson("http://$rcc_ip:64209/$year/StartServer?id=$placeId&serverId=$serverid&maxPlayerCount=12&gamePort=$port&jobId=$jobId");

					if($json != null && $json['result']) {
						include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
						$stmt_createnewserver = $con->prepare("INSERT INTO `active_servers`(`server_id`, `server_jobid`, `server_placeid`, `server_maxcount`, `server_port`, `server_year`, `server_pid`) VALUES (?,?,?,?,?,?,0)");
						$stmt_createnewserver->bind_param("ssiiss", $serverid, $jobId, $placeId, $place->server_size, $strPort, $year);
						$stmt_createnewserver->execute();

						updatePlaceOfSession($sessionID, $serverid);
					} else {
						$dont_load = true;
					}
					

				} catch(SoapFault $e) {
					include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
					$stmt_createnewserver = $con->prepare("DELETE FROM `active_players` WHERE `session_id` = ?;");
					$stmt_createnewserver->bind_param("s", $sessionID);
					$stmt_createnewserver->execute();

					return null;
				}
			} else {
				$server_data = getAnActiveServer($place->id);

				if($server_data != null) {
					$serverid = $server_data['server_id'];
				} else {
					$dont_load = true;
				}
			}

			if(!$dont_load) {
				return ["serverID"=>$serverid, "sessionID" => $sessionID];
			}

			return null;
		}
	}

	if($user != null) {
		if(isset($_POST['editID'])) {
			$place = Place::FromID(intval($_POST['editID']));

			if($place != null && ($user->id == $place->creator->id || !$place->copylocked || ($place->teamcreate_enabled && $place->IsCloudEditor($user)) || $user->IsAdmin())) {
				$placeID = $place->id;
				if($place->year == AssetYear::Y2013) {
					$clientticket = base64_encode($user->security_key);
					die("anorrl-2013-studio:1+script:http%3A%2F%2Farl.lambda.cam%2Fgame%2Fedit.ashx?placeId=$placeID+placeid:$placeID+launchmode:edit+gameinfo:$clientticket");	
				}

				if($place->year == AssetYear::Y2016) {
					$clientticket = base64_encode(string: $user->security_key);
					die("anorrl-studio-lambda:1+script:http%3A%2F%2Farl.lambda.cam%2Fgame%2Fedit.ashx?placeId=$placeID+placeid:$placeID+launchmode:edit+gameinfo:$clientticket");	
				}
			} else {
				if($place == null) {
					die("Invalid place!");
				} else {
					die("This place is not available for editing!");
				}
			}
		} else {
			if(isset($_POST['placeID'])) {

				$place = Place::FromID(intval($_POST['placeID']));

				if($place != null) {
					$playerID = $user->id;
					
					
					$placeID = $place->id;
					if($place->year == AssetYear::Y2016) {
						if(isUserInAGame($user->id)) {
							include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
							$stmt_createnewsession = $con->prepare("DELETE FROM `active_players` WHERE `session_playerid` = ?");
							$stmt_createnewsession->bind_param("i", $playerID);
							$stmt_createnewsession->execute();
						}

						$server = getAnActiveServer($place->id);

						if($server != null) {
							$serverID = $server['server_id'];
						} else {
							$serverID = strval($place->id);
						}
						$sessionID = getRandomString();
						
						include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
						$stmt_createnewsession = $con->prepare("INSERT INTO `active_players`(`session_id`, `session_serverid`, `session_playerid`, `session_status`) VALUES (?,?,?,0)");
						$stmt_createnewsession->bind_param("ssi", $sessionID, $serverID, $playerID);
						$stmt_createnewsession->execute();
						die("anorrl-player:1+placelauncherurl:http%3A%2F%2Farl.lambda.cam%2Fgame%2FPlaceLauncher.ashx?sessionID=$sessionID+placeid:$placeID+launchmode:play+gameinfo:0");
					} elseif($place->year == AssetYear::Y2013) {
						$joinData = findAndStartOtherGame("2013", $place, $user);
						
						if($joinData != null) {
							$serverID = $joinData['serverID'];
							$sessionID = $joinData['sessionID'];
							//http://arl.lambda.cam/game/join.ashx?serverToken=$serverid&sessionToken=$sessionID&server=$fakeahserver
							die("anorrl-2013-player:1+placelauncherurl:http%3A%2F%2Farl.lambda.cam%2Fgame%2F2013%2Fjoin.ashx?sessionToken=$sessionID&serverToken=$serverID&server=86.20.118.158+placeid:$placeID+launchmode:play+gameinfo:0");
						} else {
							die("server failed to create....");
						}
					} else {
						die("Uhm something weird happened i think...");
					}
					

				}

			} else if(isset($_POST['serverID'])) {

				$server_details = getServerDetails($_POST['serverID']);

				if($server_details != null) {
					$place = Place::FromID(intval($server_details['server_placeid']));

					if($place != null) {

						$playerID = $user->id;
						
						
						$placeID = $place->id;
						if($place->year == AssetYear::Y2016) {
							if(isUserInAGame($user->id)) {
								include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
								$stmt_createnewsession = $con->prepare("DELETE FROM `active_players` WHERE `session_playerid` = ?");
								$stmt_createnewsession->bind_param("i", $playerID);
								$stmt_createnewsession->execute();
							}

							$server = getAnActiveServer($place->id);

							if($server != null) {
								$serverID = $server['server_id'];
							} else {
								$serverID = strval($place->id);
							}
							$sessionID = getRandomString();
							
							include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";
							$stmt_createnewsession = $con->prepare("INSERT INTO `active_players`(`session_id`, `session_serverid`, `session_playerid`, `session_status`) VALUES (?,?,?,0)");
							$stmt_createnewsession->bind_param("ssi", $sessionID, $serverID, $playerID);
							$stmt_createnewsession->execute();
							die("anorrl-player:1+placelauncherurl:http%3A%2F%2Farl.lambda.cam%2Fgame%2FPlaceLauncher.ashx?sessionID=$sessionID+placeid:$placeID+launchmode:play+gameinfo:0");
						} elseif($place->year == AssetYear::Y2013) {
							$joinData = findAndStartOtherGame("2013", $place, $user);
							
							if($joinData != null) {
								$serverID = $joinData['serverID'];
								$sessionID = $joinData['sessionID'];
								//http://arl.lambda.cam/game/join.ashx?serverToken=$serverid&sessionToken=$sessionID&server=$fakeahserver
								die("anorrl-2013-player:1+placelauncherurl:http%3A%2F%2Farl.lambda.cam%2Fgame%2F2013%2Fjoin.ashx?sessionToken=$sessionID&serverToken=$serverID&server=86.20.118.158+placeid:$placeID+launchmode:play+gameinfo:0");
							} else {
								die("server failed to create....");
							}
							//
						}

					}
				}
				
			}
		}
		
	}
	