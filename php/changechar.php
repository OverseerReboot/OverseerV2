<?php

session_start();
require_once "inc/database.php";
require_once "includes/global_functions.php";
require_once "inc/accrow.php";
if (empty($_SESSION['username'])) {
    echo "Log in to be someone else.<br />";
} else {
    if (!empty($_GET['c'])) {
        if (strpos($accrow['characters'], strval($_GET['c']) . "|") !== false) {
            $_SESSION['character'] = $_GET['c'];
            $charresult = mysqli_query($connection, "SELECT * FROM `characters` WHERE `id` = " . $_SESSION['character'] . " LIMIT 1;");
            $charrow = mysqli_fetch_array($charresult);
            header('Location: /overview.php');
            if ($accrow['lastchar'] != $_SESSION['character']) {
                $accrow['lastchar'] = $_SESSION['character'];
                mysqli_query($connection, "UPDATE `users` SET lastchar = " . $_SESSION['character'] . " WHERE ID = " . $accrow['id']);
            }
        } else {
            echo "You can't be a character that doesn't belong to you!<br />";
        }
    }
}
