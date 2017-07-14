<?php
//
// Module: sqlrequest.php (2017-07-14) G.J. Watson
//
// Purpose: common functions
//
// Date       Version Note
// ========== ======= ================================================
// 2017-07-14 v0.01   First cut of code
//

function insertRemoteRequest($remote, $id) {
    return "INSERT INTO request_history (remote, access_ident) VALUES ('".$remote."',".$id.")";
}
?>
