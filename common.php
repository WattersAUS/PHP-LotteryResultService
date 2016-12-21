<?php
//
// common.php - common functions live here
//

    function debugMessage($debugMsg) {
        $now = date("Y-m-d H:i:s");
        printf("[ ".$now." ] DEBUG: ".$debugMsg."\n");
    }

?>
