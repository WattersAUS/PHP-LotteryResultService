<?php
//
// Program: staticJSONGenerator.php (2017-02-21) G.J. Watson
//
// Purpose: build static JSON file for Lottery results
//
// Date       Version Note
// ========== ======= ====================================================
// 2017-02-21 v1.01   First cut of code
// 2017-02-21 v1.02   Include set_include_path directive
// 2017-07-11 v1.03   Include LIMIT to draws extracted
// 2017-07-14 v1.04   Access and token handling now done elsewhere not buildJSON
//                    Moved DB connection outside to calling script
//

    set_include_path("<LIB GOES HERE>");
    require_once("globals.php");
    require_once("common.php");
    require_once("buildJSON.php");

    $version  = "v1.04";
    $wrksp    = "<WRKSPACE DIR GOES HERE>";
    $cdest    = "<DEST DIR GOES HERE>";
    $filename = "lotteryresults.json";
    $debug = TRUE;

    try {
        debugMessage("Commencing ".basename(__FILE__)." ".$GLOBALS['version']."...");
        $server = new mysqli($GLOBALS['hostname'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['database']);
        if ($server->connect_errno) {
            throw new Exception("Unable to retrieve information from the database");
        }
        debugMessage("Connected to host (".$server->host_info.")...");
        //
        // build the JSON file
        //
        $output = buildJSON($server, 10);
        debugMessage("Writing JSON to file (".jsonFilename($wrksp, $filename).")...");
        if ($file = fopen(jsonFilename($wrksp, $filename), "w")) {
            fputs($file, $output);
            fclose($file);
            if (copy(jsonFilename($wrksp, $filename), $cdest.$filename)) {
                debugMessage("Copied JSON file to (".$cdest.$filename.")...");
            } else {
                printf("ERROR (9999): Failed to copy JSON file from source (".jsonFilename($wrksp, $filename).") to (".$cdest.$filename.")");
            }
        }
        $server->close();
    } catch (Exception $e) {
        debugMessage("ERROR: ".$e->getMessage());
    }
    exit();
?>
