<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	$user = UserUtils::RetrieveUser();

    if($user != null) {
        echo "http://arl.lambda.cam/Login/Negotiate.ashx?suggest=".base64_encode($user->security_key);
    } else {
        die(http_response_code(401));
    }
?>