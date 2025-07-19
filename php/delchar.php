<?php

session_start();
$dbtype = "PDO";
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/database.php';
require($_SERVER['DOCUMENT_ROOT'] . '/includes/global_functions.php');

// Check if the user is logged in.
if (empty($_SESSION['userid'])) {
    exit("You must be logged in to do that!");
}

// Check that the character ID is specified
if (!isset($_GET['c'])) {
    exit("You need to specify a character.");
}

// Grab the user's account line
$accquery = $db->prepare("SELECT ID,username,characters FROM `users` WHERE ID = :userid");
$accquery->bindParam(':userid', $_SESSION['userid']);
$accquery->execute();
if ($accquery->rowcount() != 1) {
    exit("Sorry, your account is fucked royally.");
}
$accrow = $accquery->fetch();
unset($accquery);

// Grab the character's line
$charquery = $db->prepare("SELECT ID,name,owner,session,server,client FROM `characters` WHERE ID = :charid");
$charquery->bindParam(':charid', $_GET['c']);
$charquery->execute();
if ($charquery->rowcount() != 1) {
    exit("The specified character could not be found.");
}
$charrow = $charquery->fetch();
unset($charquery);

// Friends don't let friends delete other friends characters!
if ($charrow['owner'] != $accrow['id']) {
    exit("You cannot delete a character that is not yours!");
}

// Check that it has been confirmed.
if (!isset($_GET['confirm'])) {
    echo('u gon delete '.$charrow['name']."<br>\n");
    echo('<a href="?c='.$charrow['id'].'&confirm">click here to confirm</a>');
    exit();
}

// Delete the character's line
$chardelete = $db->prepare("DELETE FROM `characters` WHERE ID = :charid");
$chardelete->bindParam(':charid', $_GET['c']);
$chardelete->execute();
unset($chardelete);

// Delete the character's strifers
$strifedelete = $db->prepare("DELETE FROM `strifers` WHERE owner = :charid");
$strifedelete->bindParam(':charid', $_GET['c']);
$strifedelete->execute();
unset($strifedelete);

// Get the session's data, modify it to remove the user, and put it back
$sessionquery = $db->prepare("SELECT ID,members FROM `sessions` WHERE ID = :sessionid");
$sessionquery->bindParam(':sessionid', $charrow['session']);
$sessionquery->execute();
if ($sessionquery->rowcount() != 1) {
    exit("Unable to locate associated session.");
}
$sessionrow = $sessionquery->fetch();
unset($sessionquery);
$sessmembers = explode('|', $sessionrow['members']);
unset($sessmembers[array_search($charrow['id'], $sessmembers)]);
$sessionrow['members'] = implode('|', $sessmembers);
$sessionquery = $db->prepare("UPDATE `sessions` SET members = :members WHERE ID = :sessionid");
$sessionquery->bindParam(':sessionid', $charrow['session']);
$sessionquery->bindParam(':members', $sessionrow['members']);
$sessionquery->execute();
unset($sessionquery);

// Get the session's data, modify it to remove the user, and put it back
$userchars = explode('|', $accrow['characters']);
unset($userchars[array_search($charrow['id'], $userchars)]);
$accrow['characters'] = implode('|', $userchars);
$userquery = $db->prepare("UPDATE `users` SET characters = :characters WHERE ID = :userid");
$userquery->bindParam(':userid', $accrow['id']);
$userquery->bindParam(':characters', $accrow['characters']);
$userquery->execute();
unset($userquery);

if (($charrow['server'] != 0) && ($charrow['server'] != $charrow['id'])) {
    $serverreset = $db->prepare("UPDATE `characters` SET client = 0 WHERE ID = :serverid");
    $serverreset->bindParam(':serverid', $charrow['server']);
    $serverreset->execute();
    unset($serverreset);
}

if (($charrow['client'] != 0) && ($charrow['client'] != $charrow['id'])) {
    $clientreset = $db->prepare("UPDATE `characters` SET server = 0 WHERE ID = :clientid");
    $clientreset->bindParam(':clientid', $charrow['client']);
    $clientreset->execute();
    unset($clientreset);
}

$_SESSION['loginmsg'] = "Character ".$charrow['name']." successfully deleted.";

echo 'Character successfully deleted.<script>
setTimeout(function() {window.location = "/";}, 3000);
</script>';
