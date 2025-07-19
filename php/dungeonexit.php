<?php

$pagetitle = "Dungeon";
$headericon = "/images/header/compass.png";
require_once "header.php";
if (empty($_SESSION['character'])) {
    echo "Select a character to go dungeon diving.<br />";
} elseif ($charrow['dungeon'] == 0) {
    echo "You are not currently in a dungeon.<br />";
    // } elseif ($charrow['dungeonrow'] != 0 || $charrow['dungeoncol'] != 0) {
    //	echo "You are not at the dungeon entrance! You can't exit it right now.<br />";
} else {
    echo "You exit the dungeon.<br />";
    mysqli_query($connection, "UPDATE `characters` SET dungeon = 0 WHERE `characters`.`id` = " . $charrow['id'] . " LIMIT 1;");
}
require_once "footer.php";
