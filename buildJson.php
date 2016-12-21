<?php
//
// buildJson.php - details of draws / numbers downloaded
//
    require("globals.php");
    require("common.php");
    require("sql.php");

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
    while ($row = mysql_fetch_array($result)) {
        debugMessage("Processing draw (".$row["description"].")...");
    }

    mysql_close($srvr);
    exit();
?>
