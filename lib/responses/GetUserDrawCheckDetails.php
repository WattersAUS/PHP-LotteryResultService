<?php
//
//  Module: GetUserDrawCheckDetails.php - G.J. Watson
//    Desc: Get lotteries and associated latest draw and build array obj to pass back
// Version: 1.00
//

function GetUserDrawCheckDetails($db) {
    $arr = [];
    $lastid = -1;
    $lastdr = -1;
    $user = "";
    $draw = "";
    $latest = $db->select(getLatestUserLotteryCheckDrawsFields());
    while ($cRow = $latest->fetch_array(MYSQLI_ASSOC)) {
        if ($lastid <> $cRow["cu_ident"]) {
            if ($lastid <> -1) {
                $user->addCheckDraw($draw);
                array_push($arr, $user->getCheckUserAsArray());
            }
            $user = new CheckUser(
                $cRow["cu_ident"], 
                $cRow["cu_name"], 
                $cRow["cu_email"], 
                $cRow["cu_is_active"], 
                $cRow["cu_last_modified"]
            );
            $lastid = $cRow["cu_ident"];
            $lastdr = -1;
        }
        if ($lastdr <> $cRow["cd_ident"]) {
            if ($lastdr <> -1) {
                $user->addCheckDraw($draw);
            }
            $draw = new CheckDraw(
                $cRow["cd_ident"], 
                $cRow["l_description"], 
                $cRow["cd_is_active"], 
                $cRow["cd_last_modified"]
            );
        }
        if ($cRow["cn_is_special"] == 1) {
            $draw->addSpecial($cRow["cn_number"]);
        } else {
            $draw->addNumber($cRow["cn_number"]);
        }
    }
    if ($lastid <> -1) {
        $user->addCheckDraw($draw);
        array_push($arr, $user->getCheckUserAsArray());
    }
    return $arr;
}
?>