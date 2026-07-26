<?php
$mysqli = new mysqli("localhost", "root", "", "mumma's_care");
if ($mysqli->connect_error) {
    echo 'ERROR: ' . $mysqli->connect_error;
} else {
    echo 'OK';
    $mysqli->close();
}
