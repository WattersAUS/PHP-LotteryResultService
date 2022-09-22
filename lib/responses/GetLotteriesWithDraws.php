<?php
//
//  Module: GetLotteriesWithDraws.php - G.J. Watson
//    Desc: Get all lotteries and associated draws and build array obj
// Version: 1.10
//

function getNumbers($db, $ident, $draw, $isSpecial) {
    $numbers = $db->select(getNumberSQL($ident, $draw, $isSpecial));
    $arr = [];
    while ($number = $numbers->fetch_array(MYSQLI_ASSOC)) {
        $arr[] = $number["n_number"];
    }
    return $arr;
}

function getNumbersForDraw($db, $ident, $draw) {
    return getNumbers($db, $ident, $draw, FALSE);
}

function getSpecialsForDraw($db, $ident, $draw) {
    return getNumbers($db, $ident, $draw, TRUE);
}

function getLotteriesWithDraws($db, $limit = 99999) {
    $arr = [];
    $lotteries = $db->select(getAllActiveLotterySQL());
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
        // get the draws
        $draws = $db->select(getDrawHistoryForLotteryLimitedSQL($lottery->getLotteryID(), $limit));
        while ($lDraw = $draws->fetch_array(MYSQLI_ASSOC)) {
            $draw = new Draw($lDraw["dh_draw"], $lDraw["dh_draw_date"], $lDraw["dh_last_modified"]);
            foreach (getNumbersForDraw($db, $lottery->getLotteryID(), $draw->getDrawID()) as $number) {
                $draw->addNumber($number);
            }
            foreach (getSpecialsForDraw($db, $lottery->getLotteryID(), $draw->getDrawID()) as $special) {
                $draw->addSpecial($special);
            }
            $lottery->addDraw($draw);
        }
        array_push($arr, $lottery->getLotteryAsArray());
    }
    return $arr;
}
?>