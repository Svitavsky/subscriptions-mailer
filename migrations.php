<?php

include 'database.php';

function run_query($connection, $query, $table)
{
    if (mysqli_query($connection, $query)) {
        echo "Table {$table} created successful!";
    } else {
        die("Could not create table {$table}: " . mysqli_error($connection));
    }
}

function create_users_table($connection)
{
    $query = <<<QUERY
CREATE TABLE IF NOT EXISTS users(
    username VARCHAR(255) PRIMARY KEY,
    email VARCHAR(255) UNIQUE,
    validts BIGINT,
    email_confirmed BOOLEAN,
    INDEX(email)
)
QUERY;

    run_query($connection, $query, 'users');
}

function create_emails_table($connection)
{
    $query = <<<QUERY
CREATE TABLE IF NOT EXISTS emails(
    email VARCHAR(255) PRIMARY KEY,
    checked BOOLEAN,
    valid BOOLEAN
)
QUERY;

    run_query($connection, $query, 'emails');
}

function migrate_all()
{
    $connection = connection_open();

    create_users_table($connection);
    create_emails_table($connection);

    connection_close($connection);
}

migrate_all();