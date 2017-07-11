<?php
//
// Program: getLotteryResults.php (2017-02-21) G.J. Watson
//
// Purpose: Return JSON via Web Service
//
// Date       Version Note
// ========== ======= ====================================================
// 2017-02-21 v1.01   First cut of code
// 2017-02-22 v1.02   Ensure we don't get debug messages
// 2017-05-23 v1.03   Pass remote_addr/host info into build script
// 2017-07-11 v1.04   Enable number of draws to be returned to be set in URL
//

    set_include_path("/var/sites/s/shiny-ideas.tech/lib");
    require("buildJSON.php");

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
                if ($_GET["key"] <> "GJWKEY001") {
                    $json = array("status" => 9996, "msg" => "ERROR: INCORRECTTOKENSUPPLIED");
                } else {
                    $debug = FALSE;
                    $drawLimit = 50;
                    if (isset($_GET["draws"])) {
                        $drawLimit = $_GET["draws"];
                    }
                    $json  = buildJSON($_SERVER['REMOTE_ADDR'], $drawLimit);
                }
            }
        }
    }

//
// output back (we should always send something back to the caller, even if it is an err msg)
//

    header('Content-type: application/json;charset=utf-8');
    echo $json;
?>
