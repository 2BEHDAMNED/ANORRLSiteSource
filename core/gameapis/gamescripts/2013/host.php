<?php ob_start(); ?>
loadfile('http://arl.lambda.cam/game/2013/gameserver.ashx')({placeID}, {port}, "http://arl.lambda.cam/", "{access}", "{jobID}")
<?php
	function get_signature($script) {
		$signature = "";
		openssl_sign($script, $signature, file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/core/PrivateKey.pem"), OPENSSL_ALGO_SHA1);
		return base64_encode($signature);
	}

	header("Content-Type: text/plain");
	require_once $_SERVER['DOCUMENT_ROOT']."/core/classes/asset.php";

	if(
		isset($_GET['placeID']) &&
		isset($_GET['port']) &&
		isset($_GET['access']) &&
		isset($_GET['jobID']))
	{
		$place = Place::FromID(intval($_GET['placeID']));
		$port = intval($_GET['port']);

		if($place != null && $place->year == AssetYear::Y2013) {
			$script = "\r\n" . ob_get_clean();
			$script = str_replace("{placeID}",$place->id     , $script);
			$script = str_replace("{port}"   ,$port          , $script);
			$script = str_replace("{access}" ,$_GET['access'], $script);
			$script = str_replace("{jobID}"  ,$_GET['jobID'] , $script);
			$signature = get_signature($script);

			echo "--rbxsig%". $signature . "%" . $script;
		}
	}
?>