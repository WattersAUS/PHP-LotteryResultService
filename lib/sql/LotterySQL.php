<?php
//
//  Module: LotterySQL.php - G.J. Watson
//    Desc: Common SQL Statements used for Lottery DB
// Version: 1.15
//

// Lottery

function getBasicLotteryFields() {
    $sql = " l.ident AS l_ident, l.description AS l_description, l.draw AS l_draw, l.numbers AS l_numbers, l.upper_number AS l_upper_number";
    $sql .= ", l.numbers_tag AS l_number_tag, l.specials AS l_specials, l.upper_special AS l_upper_special, l.specials_tag AS l_specials_tag";
    $sql .= ", l.is_bonus AS l_is_bonus, l.base_url AS l_base_url, l.last_modified AS l_last_modified, l.end_date AS l_end_date ";
    return $sql;
}

function getBasicLotterySQL() {
    $sql = "SELECT ";
    $sql .= getBasicLotteryFields();
    $sql .= " FROM lottery_draws l";
    return $sql;
}

function getAllActiveLotterySQL() {
    $sql = getBasicLotterySQL();
    $sql .= " WHERE l.end_date IS NULL";
    $sql .= " ORDER BY l.description ASC";
    return $sql;
}

function getAllLotterySQL() {
    $sql = getBasicLotterySQL();
    $sql .= " ORDER BY l.description ASC";
    return $sql;
}

function getLotteryFromIDSQL($ident) {
    $sql = getBasicLotterySQL();
    $sql .= " WHERE l.ident = ".$ident;
    return $sql;
}

// Draw History

function getBasicDrawHistoryFields() {
    $sql = " d.ident AS dh_ident, d.draw as dh_draw, d.draw_date AS dh_draw_date, d.last_modified AS dh_last_modified ";
    return $sql;
}

function getBasicDrawHistorySQL() {
    $sql = "SELECT ";
    $sql .= getBasicDrawHistoryFields();
    $sql .= " FROM draw_history d";
    return $sql;
}

function getDrawHistoryForLotteryLimitedSQL($ident, $limit) {
    $sql = getBasicDrawHistorySQL();
    $sql .= " WHERE d.ident = ".$ident;
    $sql .= " ORDER BY draw_date DESC";
    $sql .= " LIMIT ".$limit;
    return $sql;
}

// Numbers

function getBasicNumberFields() {
    $sql = " n.ident AS n_ident, n.draw as n_draw, n.number AS n_number, n.is_special AS n_is_special ";
    return $sql;
}

function getBasicNumberSQL() {
    $sql = "SELECT ";
    $sql .= getBasicNumberFields();
    $sql .= " FROM number_usage n";
    return $sql;
}

function getNumberSQL($ident, $draw, $isSpecial) {
    $sql = getBasicNumberSQL();
    $sql .= " WHERE ident = ".$ident;
    $sql .= " AND draw = ".$draw;
    $sql .= " AND is_special = ".($isSpecial == TRUE ? "TRUE" : "FALSE");
    $sql .= " ORDER BY number ASC";
    return $sql;
}

function getLatestDrawLotterySQL() {
    $sql = "SELECT ";
    $sql .= getBasicLotteryFields();
    $sql .= ",";
    $sql .= getBasicDrawHistoryFields();
    $sql .= ",";
    $sql .= getBasicNumberFields();
    $sql .= " FROM lottery_draws l";
    $sql .= " LEFT JOIN draw_history d ON l.ident = d.ident";
    $sql .= " INNER JOIN number_usage n on d.ident = n.ident AND d.draw = n.draw";
    $sql .= " WHERE l.draw = d.draw";
    return $sql;
}

function getLatestDrawSQL($ident) {
    $sql = getLatestDrawLotterySQL();
    $sql .= " AND l.ident = ".$ident;
    $sql .= " ORDER BY l.ident ASC, is_special ASC, number ASC";
    return $sql;
}

function getLatestDrawsSQL() {
    $sql = getLatestDrawLotterySQL();
    $sql .= " ORDER BY l.ident ASC, is_special ASC, number ASC";
    return $sql;
}

// check users

function getBasicCheckUserFields() {
    $sql = " cu.ident AS cu_ident, cu.name as cu_name, cu.email AS cu_email, cu.is_active AS cu_is_active, cu.last_modified AS cu_last_modified ";
    return $sql;
}

function getBasicCheckUserSQL() {
    $sql = "SELECT ";
    $sql .= getBasicCheckUserFields();
    $sql .= " FROM check_user cu";
    return $sql;
}

// check draws

function getBasicCheckDrawsFields() {
    $sql = " cd.ident AS cd_ident, cd.user_ident as cd_user_ident, cd.lottery_ident AS cd_lottery_ident, cd.is_active AS cd_is_active, cd.last_modified AS cd_last_modified ";
    return $sql;
}

function getBasicCheckDrawsSQL() {
    $sql = "SELECT ";
    $sql .= getBasicCheckDrawsFields();
    $sql .= " FROM check_draws cd";
    return $sql;
}

// check numbers

function getBasicCheckNumbersFields() {
    $sql = " cn.check_ident AS cn_check_ident, cn.number as cn_number, cn.is_special AS cn_is_special ";
    return $sql;
}

function getBasicCheckNumbersSQL() {
    $sql = "SELECT ";
    $sql .= getBasicCheckNumbersFields();
    $sql .= " FROM check_numbers cn";
    return $sql;
}

// get latest lottery / user / check number info

function getLatestUserLotteryCheckDrawsFields() {
    $sql = "SELECT ";
    $sql .= getBasicLotteryFields();
    $sql .= ",";
    $sql .= getBasicCheckUserFields();
    $sql .= ",";
    $sql .= getBasicCheckDrawsFields();
    $sql .= ",";
    $sql .= getBasicCheckNumbersFields();
    $sql .= " FROM check_user cu";
    $sql .= " LEFT JOIN check_draws cd ON cu.ident = cd.user_ident";
    $sql .= " LEFT JOIN lottery_draws l ON cd.lottery_ident = l.ident";
    $sql .= " LEFT JOIN check_numbers cn ON cd.ident = cn.check_ident";
    $sql .= " WHERE cu.is_active = TRUE AND cd.is_active = TRUE";
    $sql .= " ORDER BY cu.ident, l.ident, cd.ident, cn.is_special, cn.number";
    return $sql;
}

?>