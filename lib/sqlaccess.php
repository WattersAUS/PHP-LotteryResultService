<?php
//
// Module: sqlaccess.php (2017-07-13) G.J. Watson
//
// Purpose: common functions
//
// Date       Version Note
// ========== ======= ================================================
// 2017-07-13 v1.00   First cut of code
// 2017-10-10 v1.01   Introduce access limits / time and merged req sql
//

function getAccessSQL() {
    $access  = "SELECT ac.ident,ac.name,ac.token,ac.requests_per_period,ac.time_period,ac.created_when,ac.last_modified,ac.end_dated";
    $access .= " FROM access ac";
    return $access;
}

function insertRemoteRequest($remote, $id) {
    return "INSERT INTO request_history (remote, access_ident) VALUES ('".$remote."',".$id.")";
}

function setToken($token) {
    return " WHERE ac.token = '".$token."'";
}

function getRequestsMadeSQL($token, $minutes) {
    $req  = "SELECT COUNT(*) AS reqs FROM access ac LEFT JOIN request_history rh";
    $req .= " ON ac.ident = rh.access_ident";
    $req .= setToken($token);
    $req .= " AND rh.accessed > date_sub(now(), INTERVAL ".$minutes." MINUTE)";
    return $req;
}

function setActiveOnly() {
    return " AND ac.end_dated IS NULL";
}
?>
