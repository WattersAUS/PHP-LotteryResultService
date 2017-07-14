<?php
//
// Module: checkAccess.php (2017-07-13) G.J. Watson
//
// Purpose: Return JSON string containing Lottery results
//
// Date       Version Note
// ========== ======= ====================================================
// 2017-07-14 v0.01   First cut of code
//

    require_once("common.php");
    require_once("sqlaccess.php");

    $version = "v1.00";

    function checkAccess($db, $token) {
        if (empty($token)) {
            return ACCESSTOKENMISSING;
        }
        try {
            if (!$access = $db->query(getAccessSQL().setToken($token).setActiveOnly())) {
                throw new Exception("Unable to retrieve access information");
            }
            if ($row = $access->fetch_array(MYSQLI_ASSOC)) {
                debugMessage("Access token (".$token.") found...");
                $status = $row['ident'];
            } else {
                debugMessage("Supplied access token (".$token.") does not exist...");
                $status = INCORRECTTOKENSUPPLIED;
            }
        } catch (Exception $e) {
            $status = DATABASEERROR;
        }
        return $status;
    }
?>
