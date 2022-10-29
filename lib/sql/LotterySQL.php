<?php
//
//  Module: LotterySQL.php - G.J. Watson
//    Desc: Common SQL Statements used for Lottery DB
// Version: 1.10
//

// Lottery

function getBasicLotteryFields() {
    $sql  = " l.ident AS l_ident, l.description AS l_description, l.draw AS l_draw, l.numbers AS l_numbers, l.upper_number AS l_upper_number";
    $sql .= ", l.numbers_tag AS l_number_tag, l.specials AS l_specials, l.upper_special AS l_upper_special, l.specials_tag AS l_specials_tag";
    $sql .= ", l.is_bonus AS l_is_bonus, l.base_url AS l_base_url, l.last_modified AS l_last_modified, l.end_date AS l_end_date ";
    return $sql;
}

function getBasicLotterySQL() {
    $sql  = "SELECT ";
    $sql .= getBasicLotteryFields();
    $sql .= " FROM lottery_draws l";
    return $sql;
}

function getAllActiveLotterySQL() {
    $sql  = getBasicLotterySQL();
    $sql .= " WHERE l.end_date IS NULL";
    $sql .= " ORDER BY l.description ASC";
    return $sql;
}

function getAllLotterySQL() {
    $sql  = getBasicLotterySQL();
    $sql .= " ORDER BY l.description ASC";
    return $sql;
}

function getLotteryFromIDSQL($ident) {
    $sql  = getBasicLotterySQL();
    $sql .= " WHERE l.ident = ".$ident;
    return $sql;
}

// Draw History

function getBasicDrawHistoryFields() {
    $sql  = " d.ident AS dh_ident, d.draw as dh_draw, d.draw_date AS dh_draw_date, d.last_modified AS dh_last_modified ";
    return $sql;
}

function getBasicDrawHistorySQL() {
    $sql  = "SELECT ";
    $sql .= getBasicDrawHistoryFields();
    $sql .= " FROM draw_history d";
    return $sql;
}

function getDrawHistoryForLotteryLimitedSQL($ident, $limit) {
    $sql  = getBasicDrawHistorySQL();
    $sql .= " WHERE d.ident = ".$ident;
    $sql .= " ORDER BY draw_date DESC";
    $sql .= " LIMIT ".$limit;
    return $sql;
}

// Numbers

function getBasicNumberFields() {
    $sql  = " n.ident AS n_ident, n.draw as n_draw, n.number AS n_number, n.is_special AS n_is_special ";
    return $sql;
}

function getBasicNumberSQL() {
    $sql  = "SELECT ";
    $sql .= getBasicNumberFields();
    $sql .= " FROM number_usage n";
    return $sql;
}

function getNumberSQL($ident, $draw, $isSpecial) {
    $sql  = getBasicNumberSQL();
    $sql .= " WHERE ident = ".$ident;
    $sql .= " AND draw = ".$draw;
    $sql .= " AND is_special = ".($isSpecial == TRUE ? "TRUE" : "FALSE");
    $sql .= " ORDER BY number ASC";
    return $sql;
}
?>