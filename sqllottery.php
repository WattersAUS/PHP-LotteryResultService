<?php
//
// Module: sql.php (2016-12-22) G.J. Watson
//
// Purpose: common functions
//
// Date       Version Note
// ========== ======= ================================================
// 2016-12-21 v0.01   First cut of code
// 2016-12-24 v0.02   Added statments for history, numbers,specials
// 2017-02-21 v0.03   New routine getDigitSQL, use parameter for no/spc
// 2017-02-21 v0.04   Bug fixes - set correct parameters in getDigitSQL
// 2017-03-05 v0.05   Add is_bonus to lottery SQL
// 2017-05-23 v0.06   Add insert SQL for remote logging table
// 2017-05-26 v0.07   Renamed to sqllottery.php to prevent clash
//

function getLotteryDrawSQL() {
    $draws  = "SELECT ld.ident,ld.description,ld.numbers,ld.upper_number,ld.specials,ld.upper_special,ld.is_bonus,ld.last_modified,";
    $draws .= " COUNT(*) AS count_of_draws,";
    $draws .= " MIN(dh.draw) AS first_draw,";
    $draws .= " MIN(dh.last_modified) AS first_draw_date,";
    $draws .= " MAX(dh.draw) AS last_draw,";
    $draws .= " MAX(dh.last_modified) AS last_draw_date";
    $draws .= " FROM lottery_draws ld, draw_history dh";
    $draws .= " WHERE ld.ident = dh.ident";
    $draws .= " AND ld.end_date IS NULL GROUP BY ld.ident";
    return $draws;
}

function getDrawHistorySQL($ident) {
    return "SELECT draw, draw_date, last_modified FROM draw_history WHERE ident = ".$ident." order by draw DESC LIMIT 50";
}

function getDigitSQL($ident, $draw, $isSpecial) {
    return "SELECT number FROM number_usage WHERE ident = ".$ident." AND draw = ".$draw." AND is_special IS ".($isSpecial == TRUE ? 'TRUE' : 'FALSE');
}

function insertRemoteRequest($remote) {
    return "INSERT INTO request_history (remote) VALUES ('".$remote."')";
}
?>
