<?php
//
// Program: buildJson.php (2016-12-22) G.J. Watson
//
// Purpose: build static JSON file for Lottery results
//
// Date       Version Note
// ========== ======= ====================================================
// 2016-12-21 v0.01   First cut of code
// 2016-12-22 v0.02   Start JSON decode of results from DB (valid o/p)
// 2016-12-24 v0.03   Completed 1st cut JSON generation
// 2016-12-31 v0.04   Added JSON output to second file
// 2016-12-31 v1.00   Released version
// 2016-12-31 v1.01   Added generated 'date' to JSON
// 2016-12-31 v1.02   Added script version and removed some summary info
// 2017-02-03 v1.03   Edit tags in JSON to reduce payload size
// 2017-02-15 v1.04   Use JSON_NUMERIC_CHECK to force integer non quoting
//                    Also use integer array for numbers/specials not a hash
// 2017-02-21 v1.05   Rewrite mySQL code to utilise mysqli as mysql deprecated
// 2017-02-21 v1.06   Incorrect parameter used in calls to buildDigitArray
//
    $version = "v1.06";

    require("globals.php");
    require("common.php");
    require("sql.php");

    function setSpecial($isSpecial) {
        return $isSpecial == TRUE ? 'specials' : 'numbers';
    }

    function buildDigitArray($lotteryRow, $historyRow, $isSpecial) {
        debugMessage("Process ".setSpecial($isSpecial)." usage for (".$lotteryRow["description"]."), draw (".$historyRow["draw"]."), date (".$historyRow["draw_date"].")...");
        if (!$usage = $server->query(getDigitSQL($lotteryRow["ident"], $historyRow["draw"], $isSpecial))) {
            printf("ERROR (".$server->connect_errno."): ".$server->connect_error);
            exit();
        }
        //
        // get the draw history rows and process
        //
        $array = array();
        while ($row = $usage->fetch_array()) {
            array_push($array, $row["number"]);
        }
        $usage->free();
        return $array;
    }

    function buildDrawsArray($row) {
        debugMessage("Process draw history for (".$row["description"].")...");
        if (!$lotteryHistory = $server->query(getDrawHistorySQL($row["ident"]))) {
            printf("ERROR (".$server->connect_errno."): ".$server->connect_error);
            exit();
        }
        //
        // get the draw history rows and process
        //
        $historyArray = "";
        while ($historyRow = $lotteryHistory->fetch_array()) {
            $historyInfo["draw"] = $historyRow["draw"];
            $historyInfo["date"] = $historyRow["draw_date"];
            $historyInfo["nos"]  = buildDigitArray($row, $historyRow, FALSE);
            $historyInfo["spc"]  = buildDigitArray($row, $historyRow, TRUE);
            $historyArray[]      = $historyInfo;
        }
        $lotteryHistory->free();
        return $historyArray;
    }

    function buildJSONContents($row) {
        debugMessage("Process summary info for (".$row["description"].")...");
        $drawInfo["id"]        = $row["ident"];
        $drawInfo["desc"]      = $row["description"];
        $drawInfo["nos"]       = $row["numbers"];
        $drawInfo["upper_nos"] = $row["upper_number"];
        $drawInfo["spc"]       = $row["specials"];
        $drawInfo["upper_spc"] = $row["upper_special"];
        $drawInfo["modified"]  = $row["last_modified"];
        $drawInfo["draws"]     = buildDrawsArray($row);
        return $drawInfo;
    }

    debugMessage("Starting ".basename(__FILE__)." ".$version."...");

    $server = new mysqli($hostname, $username, $password, $database);
    if ($server->connect_errno) {
        printf("ERROR (".$server->connect_errno."): ".$server->connect_error);
    }
    debugMessage("Connected to host (".$server->host_info.")...");

    //
    // connect to the database and get to work!
    //
    if (!$lotteryDraws = $server->query(getLotteryDrawSQL())) {
        printf("ERROR (".$server->connect_errno."): ".$server->connect_error);
        exit();
    }
    //
    // iterate through 'draws'
    //
    debugMessage("Commencing to process games...");
    while ($lotteryRow = $lotteryDraws->fetch_array(MYSQLI_ASSOC)) {
        $json[] = buildJSONContents($lotteryRow, $server);
        debugMessage("Draw (".$lotteryRow["description"].") processed...");
    }
    $server->close();
    //
    // format as JSON and save out to a file
    //
    $outputArray["version"]   = $version;
    $outputArray["generated"] = getGeneratedDate();
    $outputArray["lottery"]   = $json;
    $output                   = json_encode($outputArray, JSON_NUMERIC_CHECK);
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
    exit();
?>
