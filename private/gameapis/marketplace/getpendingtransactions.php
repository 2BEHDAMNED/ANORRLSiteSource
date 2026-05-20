<?php
set_content_type(ARLTYPEJSON);
$PlayerId = (int)$_GET['PlayerId'];
$PlaceId = (int)$_GET['PlaceId'];

// reference shit, maybe not the greatest
/*$receiptquery = $con->prepare("SELECT * FROM `devproduct` WHERE `PlayerId` = :PlayerId AND `placeId` = :placeId");
$receiptquery->execute(['PlayerId' => $PlayerId, 'placeId' => $PlaceId]);
$receipt = $receiptquery->fetch();
if(!is_array($receipt)){
echo "[]";
exit();
}
$receiptid = $receipt['receipt'];
$productId = $receipt['productId'];
$unitPrice = $receipt['unitPrice'];
$devproductid = $receipt['id'];
$sql = "DELETE FROM `devproduct` WHERE `id` = :devproductid";
$stmt = $con->prepare($sql);
$stmt->bindParam(':devproductid', $devproductid);
$stmt->execute();*/

// i think im doing this right but whatever fuck roblox formatting
echo json_encode([[
    "playerId" => $PlayerId,
    "placeId" => $PlaceId,
    "receipt" => strval(rand(0, 100000)),
    "actionArgs" => [
        [
            "Key" => "productId",
            "Value" => strval(rand(0, 1000000))
        ],
        [
            "Key" => "currencyTypeId",
            "Value" => strval(1)
        ],
        [
            "Key" => "unitPrice",
            "Value" => strval(0)
        ],
    ]
]])
?>