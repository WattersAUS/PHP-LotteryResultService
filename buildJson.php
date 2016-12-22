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
//

    require("globals.php");
    require("common.php");
    require("sql.php");

    function buildJSONArrayElement($row) {
        debugMessage("Processing draw (".$row["description"].")...");
        $array["ident"]           = $row["ident"];
        $array["description"]     = $row["description"];
        $array["numbers"]         = $row["numbers"];
        $array["upper_number"]    = $row["upper_number"];
        $array["specials"]        = $row["specials"];
        $array["upper_special"]   = $row["upper_special"];
        $array["last_modified"]   = $row["last_modified"];
        $array["count_of_draws"]  = $row["count_of_draws"];
        $array["first_draw"]      = $row["first_draw"];
        $array["first_draw_date"] = $row["first_draw_date"];
        $array["last_draw"]       = $row["last_draw"];
        $array["last_draw_date"]  = $row["last_draw_date"];
        return $array;
    }

    debugMessage("Commnencing script (".basename(__FILE__).")...");
    if (!($srvr = mysql_connect($hostname, $username, $password))) {
        printf("ERROR (".mysql_errno()."): ".mysql_error());
        exit();
    }
    debugMessage("Connected to server (".$hostname.")...");

    //
    // we've connected to the server, now to the right database
    //
    if (!mysql_select_db($database, $srvr)) {
        printf("ERROR (".mysql_errno()."): ".mysql_error());
        exit();
    }
    debugMessage("Selected database (".$database.")...");

    //
    // ready to connect to the datasbe and finally start work
    //
    if (!$result = mysql_query($draws, $srvr)) {
        printf("ERROR (".mysql_errno()."): ".mysql_error());
        exit();
    }

    //
    // get the rows and process
    //
    debugMessage("Ready to process draws...");
    while ($row = mysql_fetch_array($result)) {
        $json[] = buildJSONArrayElement($row);
        debugMessage("Processed draw (".$row["description"].")...");
    }
    $outputArray["lottery"] = $json;
    $output                 = json_encode($outputArray);
    debugMessage("Completed processing results JSON (".$output.")...");

    mysql_close($srvr);
    exit();
?>
