<?php
//
// Module: logRequest.php (2017-07-14) G.J. Watson
//
// Purpose: Log the User Request into the request_history
//
// Date       Version Note
// ========== ======= ====================================================
// 2017-07-14 v0.01   First cut of code
//

    require_once("common.php");
    require_once("sqlaccess.php");

    function logRequest($db, $remote_addr, $id) {
        try {
            if ($db->query(insertRemoteRequest($remote_addr, $id)) != TRUE) {
                throw new Exception("Unable to log request");
            }
        } catch (Exception $e) {
        }
        return;
    }
?>
