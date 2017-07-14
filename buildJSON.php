<?php
//
// Module: buildJSON.php (2016-12-22) G.J. Watson
//
// Purpose: Return JSON string containing Lottery results
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
// 2017-02-21 v2.01   Encapsulate JSON build in new function buildJSON()
// 2017-02-21 v2.02   Add getVersion() and bug fixes
// 2017-02-22 v2.03   Reintroduce globals usage for debug/db etc
// 2017-02-22 v2.04   Introduced new getGeneratedDateTime() to provide gen date
// 2017-03-03 v2.05   Added is_bonus (as bonus) to JSON to denote bonus numbers used
// 2017-05-23 v2.06   Added to JSON status/msg to match calling wrapper on an error
//                    Also include try / catch handling
// 2017-05-23 v2.07   Save requestor information, in case we need to block
// 2017-05-26 v2.08   Renamed sqllottery.php from sql.php
// 2017-07-11 v2.09   Introduce an ability to control the numbers of draws returned
//                    Also return 'draw_limit' in JSON response
// 2017-07-14 v2.10   Remove requestor save (handled elsewhere now)
//                    Also removed DB connection out to calling script
//

    require_once("globals.php");
    require_once("common.php");
    require_once("sqllottery.php");

    $version = "v2.10";

    function setSpecial($isSpecial) {
        return $isSpecial == TRUE ? 'specials' : 'numbers';
    }

    function buildDigitArray($db, $lotteryRow, $historyRow, $isSpecial) {
        debugMessage("Process ".setSpecial($isSpecial)." usage for (".$lotteryRow["description"]."), draw (".$historyRow["draw"]."), date (".$historyRow["draw_date"].")...");
        if (!$usage = $db->query(getDigitSQL($lotteryRow["ident"], $historyRow["draw"], $isSpecial))) {
            throw new Exception("Unable to retrieve draw number information");
        }
        //
        // get the draw history rows and process
        //
        $array = array();
        while ($row = $usage->fetch_array(MYSQLI_ASSOC)) {
            array_push($array, $row["number"]);
        }
        $usage->free();
        return $array;
    }

    function buildDrawsArray($db, $row, $drawLimit) {
        debugMessage("Process draw history for (".$row["description"].")...");
        if (!$lotteryHistory = $db->query(getDrawHistorySQL($row["ident"], $drawLimit))) {
            throw new Exception("Unable to retrieve draw information");
        }
        //
        // get the draw history rows and process
        //
        $historyArray = "";
        while ($historyRow = $lotteryHistory->fetch_array(MYSQLI_ASSOC)) {
            $historyInfo["draw"] = $historyRow["draw"];
            $historyInfo["date"] = $historyRow["draw_date"];
            $historyInfo["nos"]  = buildDigitArray($db, $row, $historyRow, FALSE);
            $historyInfo["spc"]  = buildDigitArray($db, $row, $historyRow, TRUE);
            $historyArray[]      = $historyInfo;
        }
        $lotteryHistory->free();
        return $historyArray;
    }

    function buildJSONContents($db, $row, $drawLimit) {
        $msg = "Process summary info for (".$row["description"].")";
        if (isset($drawLimit) && is_numeric($drawLimit)) {
            $msg .= ", draws limited to (".$drawLimit.")";
        }
        $msg .= "...";
        debugMessage($msg);
        $drawInfo["id"]        = $row["ident"];
        $drawInfo["desc"]      = $row["description"];
        $drawInfo["nos"]       = $row["numbers"];
        $drawInfo["upper_nos"] = $row["upper_number"];
        $drawInfo["spc"]       = $row["specials"];
        $drawInfo["upper_spc"] = $row["upper_special"];
        $drawInfo["modified"]  = $row["last_modified"];
        $drawInfo["bonus"]     = $row["is_bonus"];
        $drawInfo["draws"]     = buildDrawsArray($db, $row, $drawLimit);
        return $drawInfo;
    }

    function buildJSON($db, $drawLimit) {
        try {
            //
            // get the list of lotteries to process
            //
            if (!$lotteryDraws = $db->query(getLotteryDrawSQL())) {
                throw new Exception("Unable to retrieve lottery information");
            }
            //
            // if we've been supplied a value for the number of draws to return, validate it!
            //
            if (!isset($drawLimit) || !is_numeric($drawLimit)) {
                debugMessage("Draw Limit either not supplied, or not numeric, setting = 50...");
                $drawLimit = 50;
            }
            //
            // iterate through 'draws'
            //
            debugMessage("Commencing to process games...");
            while ($lotteryRow = $lotteryDraws->fetch_array(MYSQLI_ASSOC)) {
                $json[] = buildJSONContents($db, $lotteryRow, $drawLimit);
                debugMessage("Draw (".$lotteryRow["description"].") processed...");
            }
            //
            // format as JSON and save out to a file
            //
            $outputArray["version"]    = $GLOBALS['version'];
            $outputArray["generated"]  = getGeneratedDateTime();
            $outputArray["draw_limit"] = $drawLimit;
            $outputArray["lottery"]    = $json;
            $outputArray["msg"]        = "SUCCESS";
            $outputArray["status"]     = 0;
        } catch (Exception $e) {
            $outputArray["msg"]        = "ERROR: ".$e->getMessage();
            $outputArray["status"]     = 999;
        }
        return json_encode($outputArray, JSON_NUMERIC_CHECK);
    }
?>
