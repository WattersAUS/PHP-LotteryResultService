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
// 2017-07-13 v1.05   Now using tokens for access
//                    Initial DB open now done here
//

    set_include_path("<LIB GOES HERE>");

    require_once("globals.php");
    require_once("constants.php");
    require_once("common.php");
    require_once("checkAccess.php");
    require_once("buildJSON.php");
    require_once("logRequest.php");

    function processServiceRequest($token, $draws) {
        $debug = TRUE;
        try {
            debugMessage("Commencing ".basename(__FILE__)." ".$GLOBALS['version']."...");
            $server = new mysqli($GLOBALS['hostname'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['database']);
            if ($server->connect_errno) {
                throw new Exception("Unable to retrieve information from the database");
            }
            debugMessage("Connected to host (".$server->host_info.")...");
            $id = checkAccess($server, $token);
            if ($id < 0) {
                $json = json_encode(array("status" => $id, "msg" => serviceErrorMessage($id)), JSON_NUMERIC_CHECK);
            } else {
                $drawLimit = 50;
                if (isset($draws)) {
                    $drawLimit = $draws;
                }
                $json = buildJSON($server, $drawLimit);
                logRequest($server, $_SERVER['REMOTE_ADDR'], $id);
            }
            $server->close();
        } catch (Exception $e) {
            $json = json_encode(array("status" => 10000, "msg" => "DBERROR: ".$e->getMessage()), JSON_NUMERIC_CHECK);
        }
        return $json;
    }

    if($_SERVER['REQUEST_METHOD'] <> "GET") {
        $json = json_encode(array("status" => REAQMETHODERROR,    "msg" => serviceErrorMessage(REAQMETHODERROR)), JSON_NUMERIC_CHECK);
    } else {
        if (! $_GET["token"] || empty($_GET["token"])) {
            $json = json_encode(array("status" => ACCESSTOKENMISSING, "msg" => serviceErrorMessage(ACCESSTOKENMISSING)), JSON_NUMERIC_CHECK);
        } else {
            $json = processServiceRequest($_GET["token"], $_GET["draws"]);
        }
    }

    header('Content-type: application/json;charset=utf-8');
    echo $json;
?>
