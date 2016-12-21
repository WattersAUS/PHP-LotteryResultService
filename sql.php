<?php
//
// sql.php - statements executed live here
//

$draws = "SELECT ld.ident,ld.description,ld.numbers,ld.upper_number,ld.specials,ld.upper_special,ld.last_modified,";
$draws .= " COUNT(*) AS count_of_draws,";
$draws .= " MIN(dh.draw) AS first_draw,";
$draws .= " MIN(dh.last_modified) AS first_draw_date,";
$draws .= " MAX(dh.draw) AS last_draw,";
$draws .= " MAX(dh.last_modified) AS last_draw_date";
$draws .= " FROM lottery_draws ld, draw_history dh";
$draws .= " WHERE ld.ident = dh.ident";
$draws .= " AND ld.end_date IS NULL GROUP BY ld.ident";

?>
