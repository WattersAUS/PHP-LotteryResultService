<?php
//
//  Module: GetLotteries.php - G.J. Watson
//    Desc: Get all lotteries
// Version: 1.00
//

function getLotteries($db) {
    $arr = [];
    $lotteries = $db->select(getAllLotterySQL());
    while ($lRow = $lotteries->fetch_array(MYSQLI_ASSOC)) {
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
        array_push($arr, $lottery->getLotteryAsArray());
    }
    return $arr;
}
?>