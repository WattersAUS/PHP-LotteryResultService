<?php
//
// Module: sqlaccess.php (2017-07-13) G.J. Watson
//
// Purpose: common functions
//
// Date       Version Note
// ========== ======= ================================================
// 2017-07-13 v0.01   First cut of code
//

function getAccessSQL() {
    $access  = "SELECT ac.ident,ac.name,ac.token,ac.created_when,ac.last_modified,ac.end_dated";
    $access .= " FROM access ac";
    return $access;
}

function setToken($token) {
    return " WHERE ac.token = '".$token."'";
}

function setActiveOnly() {
    return " AND ac.end_dated IS NULL";
}
?>
