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
//
    $version = "v1.04";
    require("globals.php");
    require("common.php");
    require("sql.php");

    function buildNumbersArray($lotteryRow, $historyRow) {
        debugMessage("Process numbers usage for (".$lotteryRow["description"]."), draw (".$historyRow["draw"]."), date (".$historyRow["draw_date"].")...");
        if (!$numbersUsage = mysql_query(getNumbersSQL($lotteryRow["ident"], $historyRow["draw"]))) {
            printf("ERROR (".mysql_errno()."): ".mysql_error());
            exit();
        }
        //
        // get the draw history rows and process
        //
        $numberArray = array();
        while ($numberRow = mysql_fetch_array($numbersUsage)) {
            array_push($numberArray, $numberRow["number"]);
        }
        return $numberArray;
    }
    function buildSpecialsArray($lotteryRow, $historyRow) {
        debugMessage("Process specials usage for (".$lotteryRow["description"]."), draw (".$historyRow["draw"]."), date (".$historyRow["draw_date"].")...");
        if (!$specialsUsage = mysql_query(getSpecialsSQL($lotteryRow["ident"], $historyRow["draw"]))) {
            printf("ERROR (".mysql_errno()."): ".mysql_error());
            exit();
        }
        //
        // get the draw history rows and process
        //
        $specialArray = array();
        while ($specialRow = mysql_fetch_array($specialsUsage)) {
            array_push($specialArray, $specialRow["number"]);
        }
        return $specialArray;
    }
    function buildDrawsArray($row) {
        debugMessage("Process draw history for (".$row["description"].")...");
        if (!$lotteryHistory = mysql_query(getDrawHistorySQL($row["ident"]))) {
            printf("ERROR (".mysql_errno()."): ".mysql_error());
            exit();
        }
        //
        // get the draw history rows and process
        //
        $historyArray = "";
        while ($historyRow = mysql_fetch_array($lotteryHistory)) {
            $historyInfo["draw"] = $historyRow["draw"];
            $historyInfo["date"] = $historyRow["draw_date"];
            $historyInfo["nos"]  = buildNumbersArray($row, $historyRow);
            $historyInfo["spc"]  = buildSpecialsArray($row, $historyRow);
            $historyArray[]      = $historyInfo;
        }
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
    if (!($server = mysql_connect($hostname, $username, $password))) {
        printf("ERROR (".mysql_errno()."): ".mysql_error());
        exit();
    }
    debugMessage("Server (".$hostname.")...");
    //
    // we've connected to the server, now to the right database
    //
    if (!mysql_select_db($database, $server)) {
        printf("ERROR (".mysql_errno()."): ".mysql_error());
        exit();
    }
    debugMessage("Database (".$database.")...");
    //
    // connect to the database and get to work!
    //
    if (!$lotteryDraws = mysql_query(getLotteryDrawSQL(), $server)) {
        printf("ERROR (".mysql_errno()."): ".mysql_error());
        exit();
    }
    //
    // iterate through 'draws'
    //
    debugMessage("Commencing to process draws...");
    while ($lotteryRow = mysql_fetch_array($lotteryDraws)) {
        $json[] = buildJSONContents($lotteryRow, $server);
        debugMessage("Draw (".$lotteryRow["description"].") processed...");
    }
    mysql_close($server);
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
