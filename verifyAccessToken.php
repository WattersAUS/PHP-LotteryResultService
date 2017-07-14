<?php
//
// Program: verifyAccessToken.php (2017-07-14) G.J. Watson
//
// Purpose: Return JSON via Web Service
//
// Date       Version Note
// ========== ======= ====================================================
// 2017-07-14 v1.01   First cut of code
//

    set_include_path("<LIB GOES HERE>");
    require_once("constants.php");
    require_once("globals.php");
    require_once("common.php");
    require_once("checkAccess.php");

    $debug = TRUE;
    try {
        debugMessage("Commencing ".basename(__FILE__)." ".$GLOBALS['version']."...");
        $server = new mysqli($GLOBALS['hostname'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['database']);
        if ($server->connect_errno) {
            throw new Exception("Unable to retrieve information from the database");
        }
        debugMessage("Connected to host (".$server->host_info.")...");
        $id = checkAccess($server, $argv[1]);
        if ($id > 0) {
            $json = json_encode(array("status" => 0, "msg" => "SUCCESS: TOKENACCEPTED"), JSON_NUMERIC_CHECK);
        } else {
            $json = json_encode(array("status" => $id, "msg" => serviceErrorMessage($id)), JSON_NUMERIC_CHECK);
        }
        $server->close();
        debugMessage($json);
    } catch (Exception $e) {
        debugMessage("ERROR: ".$e->getMessage());
    }
?>
