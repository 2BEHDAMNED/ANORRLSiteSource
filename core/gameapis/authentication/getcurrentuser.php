<?php 
    require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	$user = UserUtils::RetrieveUser();

    if($user != null) {
        echo strval($user->id);
    } else {
        echo "1";
    }
?>