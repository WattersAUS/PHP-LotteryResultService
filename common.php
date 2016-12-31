<?php
//
// Module: common.php (2016-12-22) G.J. Watson
//
// Purpose: common functions
//
// Date       Version Note
// ========== ======= ================================================
// 2016-12-21 v0.01   First cut of code
// 2016-12-24 v0.02   Added jsonFilename($workspace, $filename)
// 2016-12-31 v0.03   Added getGeneratedDate
//

    function debugMessage($debugMsg) {
        $now = date("Y-m-d H:i:s");
        printf("[ ".$now." ] DEBUG: ".$debugMsg."\n");
    }

    function getGeneratedDate() {
        return date("Y-m-d");
    }

    function jsonFilename($workspace, $filename) {
        return $workspace.getGeneratedDate()."_".$filename;
    }

?>
