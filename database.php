<?php

function connection_open()
{
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $databaseName = 'mailer';
    $connection = mysqli_connect($host, $user, $password, $databaseName);

    if(mysqli_connect_errno()) {
        die('Connection failed: %s\n' . mysqli_connect_error());
    }

    return $connection;
}

function connection_close($connection) {
    mysqli_close($connection);
}