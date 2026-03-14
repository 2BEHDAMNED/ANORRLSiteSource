<?php
	ob_start();
?>
-- Setup studio cmd bar & load core scripts

pcall(function() game:GetService("InsertService"):SetFreeModelUrl("http://arl.lambda.cam/Game/Tools/InsertAsset.ashx?type=fm&q=%s&pg=%d&rs=%d") end)
pcall(function() game:GetService("InsertService"):SetFreeDecalUrl("http://arl.lambda.cam/Game/Tools/InsertAsset.ashx?type=fd&q=%s&pg=%d&rs=%d") end)

pcall(function() game:GetService("ScriptInformationProvider"):SetAssetUrl("http://arl.lambda.cam/Asset/") end)
pcall(function() game:GetService("InsertService"):SetBaseSetsUrl("http://arl.lambda.cam/Game/Tools/InsertAsset.ashx?nsets=10&type=base") end)
pcall(function() game:GetService("InsertService"):SetUserSetsUrl("http://arl.lambda.cam/Game/Tools/InsertAsset.ashx?nsets=20&type=user&userid=%d") end)
pcall(function() game:GetService("InsertService"):SetCollectionUrl("http://arl.lambda.cam/Game/Tools/InsertAsset.ashx?sid=%d") end)
pcall(function() game:GetService("InsertService"):SetAssetUrl("http://arl.lambda.cam/Asset/?id=%d") end)
pcall(function() game:GetService("InsertService"):SetAssetVersionUrl("http://arl.lambda.cam/Asset/?assetversionid=%d") end)

pcall(function() game:GetService("SocialService"):SetFriendUrl("http://arl.lambda.cam/Game/LuaWebService/HandleSocialRequest.ashx?method=IsFriendsWith&playerid=%d&userid=%d") end)
pcall(function() game:GetService("SocialService"):SetBestFriendUrl("http://arl.lambda.cam/Game/LuaWebService/HandleSocialRequest.ashx?method=IsBestFriendsWith&playerid=%d&userid=%d") end)
pcall(function() game:GetService("SocialService"):SetGroupUrl("http://arl.lambda.cam/Game/LuaWebService/HandleSocialRequest.ashx?method=IsInGroup&playerid=%d&groupid=%d") end)
pcall(function() game:GetService("SocialService"):SetGroupRankUrl("http://arl.lambda.cam/Game/LuaWebService/HandleSocialRequest.ashx?method=GetGroupRank&playerid=%d&groupid=%d") end)
pcall(function() game:GetService("SocialService"):SetGroupRoleUrl("http://arl.lambda.cam/Game/LuaWebService/HandleSocialRequest.ashx?method=GetGroupRole&playerid=%d&groupid=%d") end)
pcall(function() game:GetService("GamePassService"):SetPlayerHasPassUrl("http://arl.lambda.cam/Game/GamePass/GamePassHandler.ashx?Action=HasPass&UserID=%d&PassID=%d") end)

pcall(function() game:GetService("MarketplaceService"):SetProductInfoUrl("https://arl.lambda.cam/marketplace/productinfo?assetId=%d") end)
pcall(function() game:GetService("MarketplaceService"):SetDevProductInfoUrl("http://arl.lambda.cam/marketplace/productDetails?productId=%d") end)
pcall(function() game:GetService("MarketplaceService"):SetPlayerOwnsAssetUrl("http://arl.lambda.cam/ownership/hasasset?userId=%d&assetId=%d") end)

pcall(function() game:GetService("ScriptContext"):AddCoreScript(2610, game:GetService("ScriptContext"), "StarterScript") end)

<?php
    function get_signature($script)
    {
        $signature = "";
        openssl_sign($script, $signature, file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/core/PrivateKey.pem"), OPENSSL_ALGO_SHA1);
        return base64_encode($signature);
    }    

    header("Content-Type: text/plain");

    $script = "\r\n" . ob_get_clean();
    $signature = get_signature($script);

    echo "%". $signature . "%" . $script;
?>