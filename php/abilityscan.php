<?php

require_once "header.php";
$message = "";
$class = $charrow['class'];
$aspect = $charrow['aspect'];
$abilities = $charrow['abilities'];
$currentrung = $charrow['echeladder'];
$abilityresult = mysqli_query($connection, "SELECT `id`,`name` FROM `abilities` WHERE
`abilities`.`class` IN ('$class', 'All') AND `abilities`.`aspect` IN ('$aspect', 'All') AND `abilities`.`rungreq` BETWEEN 1 AND $currentrung;");
//NOTE - No need to check for god tiers here. They'll be listed as requiring a rung of "1025" and have a god tier requirement instead.
if ($abilityresult != false) {
    while ($row = mysqli_fetch_array($abilityresult)) {
        if (strpos("|" . $charrow['abilities'], "|" . $row['id'] . "|") === false) {
            $abilities .= intval($row['id']) . "|"; //Add this ability to the ability string
            $message .= $charrow['name'] . " gains a new ability: $row[Name]!<br />";
        }
    }
} else {
    $message = "No new abilities available.<br />";
}
if ($message == "") {
    $message = "No new abilities available.<br />";
}
mysqli_query($connection, "UPDATE `characters` SET `abilities` = '$abilities' WHERE `characters`.`id` = $charrow[ID] LIMIT 1;");
echo $message;
strifeInit($charrow);
require_once "footer.php";
