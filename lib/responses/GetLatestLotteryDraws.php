<?php
//
//  Module: GetLatestLotteryDraws.php - G.J. Watson
//    Desc: Get lotteries and associated latest draw and build array obj to pass back
// Version: 1.01
//

function GetLatestLotteryDraws($db) {
    $arr = [];
    $lastid = -1;
    $lottery = "";
    $draw = "";
    $latest = $db->select(getLatestDrawsSQL());
    while ($lRow = $latest->fetch_array(MYSQLI_ASSOC)) {
        if ($lastid <> $lRow["l_ident"]) {
            if ($lastid <> -1) {
                $lottery->addDraw($draw);
                array_push($arr, $lottery->getLotteryAsArray());
            }
            $lottery = new Lottery($lRow["l_ident"], 
                $lRow["l_description"], 
                $lRow["l_draw"], 
                $lRow["l_numbers"], 
                $lRow["l_upper_number"], 
                $lRow["l_numbers_tag"],
                $lRow["l_specials"], 
                $lRow["l_upper_special"], 
                $lRow["l_specials_tag"],
                $lRow["l_is_bonus"],
                $lRow["l_base_url"],
                $lRow["l_last_modified"],
                $lRow["l_end_date"]);
            $draw = new Draw($lRow["dh_draw"],
                $lRow["dh_draw_date"],
                $lRow["dh_last_modified"]);

            $lastid = $lRow["l_ident"];
        }
        if ($lRow["n_is_special"] == 1) {
            $draw->addSpecial($lRow["n_number"]);
        } else {
            $draw->addNumber($lRow["n_number"]);
        }
    }
    if ($lastid <> -1) {
        $lottery->addDraw($draw);
        array_push($arr, $lottery->getLotteryAsArray());
    }
    return $arr;
}
?>