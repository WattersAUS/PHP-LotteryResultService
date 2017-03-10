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
//

    set_include_path("<LIB GOES HERE>");
    require("buildJSON.php");

    $version  = "v1.02";
    $wrksp    = "<WRKSPACE DIR GOES HERE>";
    $cdest    = "<DEST DIR GOES HERE>";
    $filename = "lotteryresults.json";

    $debug  = TRUE;
    $output = buildJSON();
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
