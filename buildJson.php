<?php
//
// Program: buildJson.php (2016-12-22) G.J. Watson
//
// Purpose: build static JSON file for Lottery results
//
// Date       Version Note
// ========== ======= ================================================
// 2016-12-21 v0.01   First cut of code
// 2016-12-22 v0.02   Start JSON decode of results from DB (valid o/p)
// 2016-12-24 v0.03   Completed 1st cut JSON generation
// 2016-12-31 v0.04   Added JSON output to second file
// 2016-12-31 v1.00   Released version
// 2016-12-31 v1.01   Added generated 'date' to JSON
//

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
        $numberArray = "";
        while ($numberRow = mysql_fetch_array($numbersUsage)) {
            $numberInfo["value"] = $numberRow["number"];
            $numberArray[]       = $numberInfo;
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
        $specialArray = "";
        while ($specialRow = mysql_fetch_array($specialsUsage)) {
            $specialInfo["value"] = $specialRow["number"];
            $specialArray[]       = $specialInfo;
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
            $historyInfo["draw"]      = $historyRow["draw"];
            $historyInfo["draw_date"] = $historyRow["draw_date"];
            $historyInfo["numbers"]   = buildNumbersArray($row, $historyRow);
            $historyInfo["specials"]  = buildSpecialsArray($row, $historyRow);
            $historyArray[]           = $historyInfo;
        }
        return $historyArray;
    }

    function buildJSONContents($row) {
        debugMessage("Process summary info for (".$row["description"].")...");
        $drawInfo["ident"]           = $row["ident"];
        $drawInfo["description"]     = $row["description"];
        $drawInfo["numbers"]         = $row["numbers"];
        $drawInfo["upper_number"]    = $row["upper_number"];
        $drawInfo["specials"]        = $row["specials"];
        $drawInfo["upper_special"]   = $row["upper_special"];
        $drawInfo["last_modified"]   = $row["last_modified"];
        $drawInfo["count_of_draws"]  = $row["count_of_draws"];
        $drawInfo["first_draw"]      = $row["first_draw"];
        $drawInfo["first_draw_date"] = $row["first_draw_date"];
        $drawInfo["last_draw"]       = $row["last_draw"];
        $drawInfo["last_draw_date"]  = $row["last_draw_date"];
        $drawInfo["draws"]           = buildDrawsArray($row);
        return $drawInfo;
    }

    debugMessage("Starting (".basename(__FILE__).")...");
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
    $outputArray["date"]    = getGeneratedDate();
    $outputArray["lottery"] = $json;
    $output                 = json_encode($outputArray);
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
