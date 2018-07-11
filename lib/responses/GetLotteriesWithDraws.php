<?php
//
//  Module: GetLotteriesWithDraws.php - G.J. Watson
//    Desc: Get all lotteries and associated draws and build array obj
// Version: 1.02
//

function getNumbers($db, $ident, $draw, $isSpecial) {
    // get the numbers
    $sql  = "SELECT number";
    $sql .= " FROM number_usage";
    $sql .= " WHERE ident = ".$ident;
    $sql .= " AND draw = ".$draw;
    $sql .= " AND is_special = ".($isSpecial == TRUE ? "TRUE" : "FALSE");
    $sql .= " ORDER BY number ASC";
    $numbers = $db->select($sql);
    $arr = [];
    while ($number = $numbers->fetch_array(MYSQLI_ASSOC)) {
        $arr[] = $number["number"];
    }
    return $arr;
}

function getNumbersForDraw($db, $ident, $draw) {
    return getNumbers($db, $ident, $draw, FALSE);
}

function getSpecialsForDraw($db, $ident, $draw) {
    return getNumbers($db, $ident, $draw, TRUE);
}

function getLotteriesWithDraws($db, $limit = 50) {
    $arr = [];
    // we're only interested in active lottery draws
    $sql  = "SELECT ident, description, draw, numbers, upper_number, numbers_tag, specials, upper_special, specials_tag, is_bonus, base_url, last_modified, end_date ";
    $sql .= " FROM lottery_draws";
    $sql .= " WHERE end_date IS NULL";
    $sql .= " ORDER BY description ASC";
    $lotteries = $db->select($sql);
    while ($lRow = $lotteries->fetch_array(MYSQLI_ASSOC)) {
        $lottery = new Lottery($lRow["ident"], 
                                $lRow["description"], 
                                $lRow["draw"], 
                                $lRow["numbers"], 
                                $lRow["upper_number"], 
                                $lRow["numbers_tag"],
                                $lRow["specials"], 
                                $lRow["upper_special"], 
                                $lRow["specials_tag"],
                                $lRow["is_bonus"],
                                $lRow["base_url"],
                                $lRow["last_modified"],
                                $lRow["end_date"]);
        // get the draws
        $sql  = "SELECT draw, draw_date, last_modified";
        $sql .= " FROM draw_history";
        $sql .= " WHERE ident = ".$lottery->getLotteryID();
        $sql .= " ORDER BY draw_date DESC";
        $sql .= " LIMIT ".$limit;
        $draws = $db->select($sql);
        while ($lDraw = $draws->fetch_array(MYSQLI_ASSOC)) {
            $draw = new Draw($lDraw["draw"], $lDraw["draw_date"], $lDraw["last_modified"]);
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