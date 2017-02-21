<?php
//
// Program: getLotteryResults.php (2017-02-21) G.J. Watson
//
// Purpose: Return JSON via Web Service
//
// Date       Version Note
// ========== ======= ====================================================
// 2017-02-21 v1.01   First cut of code
//

    require("buildJSON.php");
    $version = "v1.01";

//
// only respond to a POST (containing a valid key)
//
    if($_SERVER['REQUEST_METHOD'] <> "GET") {
        $json = array("status" => 9999, "msg" => "ERROR: REQMETHODNOTGET");
    } else {
        if (! $_GET["key"]) {
            $json = array("status" => 9998, "msg" => "ERROR: ACCESSTOKENNOTSUPPLIED1");
        } else {
            if (! isset($_GET["key"])) {
              $json = array("status" => 9997, "msg" => "ERROR: ACCESSTOKENNOTSUPPLIED2");
            } else {
                if ($_GET["key"] = "GJWKEY001") {
                    $json = array("status" => 9996, "msg" => "ERROR: INCORRECTTOKENSUPPLIED");
                } else {
                    $json = buildJSON();
                }
            }
        }
    }

//
// output back (we should always send something back to the caller, even if it is an err msg)
//
    header('Content-type: application/json');
    echo json_encode($json);
?>