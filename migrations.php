<?php

require_once 'database.php';

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
    id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255),
    email VARCHAR(255),
    validts BIGINT,
    email_confirmed BOOLEAN
)
QUERY;

    run_query($connection, $query, 'users');
}

function create_emails_table($connection)
{
    $query = <<<QUERY
CREATE TABLE IF NOT EXISTS emails(
    id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255),
    checked BOOLEAN,
    valid BOOLEAN
)
QUERY;

    run_query($connection, $query, 'emails');
}

function migrate_all($connection)
{
    create_users_table($connection);
    create_emails_table($connection);
}

function index_users_table($connection) {
    $queryEmail = <<<QUERY
CREATE INDEX email
ON users(email)
QUERY;

    $queryConfirmed = <<<QUERY
CREATE INDEX email_confirmed
ON users(email_confirmed) DESC
QUERY;

    mysqli_query($connection, $queryEmail);
    mysqli_query($connection, $queryConfirmed);
}

function index_emails_table($connection) {
    $query = <<<QUERY
CREATE INDEX email
ON emails(email)
QUERY;

    mysqli_query($connection, $query);
}

function index_all($connection) {
    index_users_table($connection);
    index_emails_table($connection);
}