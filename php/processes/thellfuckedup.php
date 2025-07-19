<?php

# "Live database - OverseerDev"

$live = mysqli_connect("localhost", "thellfuckedup", "hereallydid", "OverseerDev");

# "Backup Database - OverseerBak"

$backup = mysqli_connect("localhost", "thellfuckedup2", "hereallydid", "OverseerBak");

$liveRows = mysqli_query($live, "SELECT * FROM `users`;");
$backupRows = mysqli_query($backup, "SELECT * FROM `users`;");
$n = 1;
while ($liveRow = mysqli_fetch_assoc($liveRows)) {
    if ($liveRow['password'] == '0') {
        $restoreID = $liveRow['ID'];
        $backupQuery = mysqli_query($backup, "SELECT * FROM `users` WHERE `id` = '$restoreID';");
        $backupRow = mysqli_fetch_array($backupQuery);
        $restorePassword = $backupRow['password'];
        mysqli_query($live, "UPDATE `users` SET `password` = '$restorePassword' WHERE `id` = '$restoreID';");
        echo $n.'. Restored '.$liveRow['username'].'\'s password.<br>';
        $n++;
    }

}
echo 'Restored '.$n.' passwords. Don\'t fuck up next time.';
