<?php
//
// Module: checkAccess.php (2017-07-13) G.J. Watson
//
// Purpose: Return JSON string containing Lottery results
//
// Date       Version Note
// ========== ======= ====================================================
// 2017-07-14 v1.00   First cut of code
// 2017-10-10 v1.01   Added support for access limits / time
//

    require_once("common.php");
    require_once("sqlaccess.php");

    $version = "v1.01";

    function checkAccess($db, $token) {
        if (empty($token)) {
            return ACCESSTOKENMISSING;
        }
        try {
            if (!$access = $db->query(getAccessSQL().setToken($token).setActiveOnly())) {
                throw new Exception("Unable to retrieve access information");
            }
            if ($row = $access->fetch_array(MYSQLI_ASSOC)) {
                $status   = $row['ident'];
                $requests = $row['requests_per_period'];
                $period   = $row['time_period'];
                $message  = "Access token (".$token.") found, ";
                //
                // if requests_per_period == 0 on db, user allowed unlimited requests
                //
                if ($requests == 0) {
                    $message .= "account has unlimited access as requests set to 0...";
                    debugMessage($message);
                } else {
                    $message .= $requests." reqs allowed in ".$period." minutes, ";
                    if (!$req = $db->query(getRequestsMadeSQL($token, $period))) {
                        throw new Exception("Unable to retrieve requests information");
                    }
                    if ($row = $req->fetch_array(MYSQLI_ASSOC)) {
                        $made     = $row['reqs'];
                        $message .= "used ".$made." reqs...";
                        debugMessage($message);
                        if ($made >= $requests) {
                            $status = TOOMANYREQUESTS;
                        }
                    }
                }
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
