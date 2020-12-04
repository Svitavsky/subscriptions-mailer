<?php

include 'database.php';

function seed($count)
{
    $connection = connection_open();
    for ($i = 0; $i < $count; $i++) {
        $name = generate_name();
        $emailName = str_replace(' ', '_', $name);
        $email = "{$emailName}@example.com";
        $confirmed = rand(0, 1);
        $expiry = rand(time() - 2629743, time() + 2629743);
        $checked = rand(0, 1);
        $valid = $checked === 1 ? rand(0, 1) : 0;

        $usersQuery = <<<QUERY
INSERT
INTO users
    (username, email, validts, email_confirmed)
VALUES (
    '{$name}',
    '{$email}',
    {$expiry},
    {$confirmed}
)
QUERY;

        $emailsQuery = <<<QUERY
INSERT
INTO emails
    (email, checked, valid)
VALUES (
    '{$email}',
    {$checked},
    {$valid}
)
QUERY;

        if (!mysqli_query($connection, $usersQuery)) {
            die(mysqli_error($connection));
        }

        if (!mysqli_query($connection, $emailsQuery)) {
            die(mysqli_error($connection));
        }
    }

    connection_close($connection);
}

function generate_name()
{
    $chars = range('a', 'z');

    $nameArray = array_rand($chars, rand(4, 10));
    $surnameArray = array_rand($chars, rand(6, 15));

    $name = '';
    foreach ($nameArray as $index) {
        $name .= $chars[$index];
    }

    $surname = '';
    foreach ($surnameArray as $index) {
        $surname .= $chars[$index];
    }

    return "{$name} {$surname}";
}

seed(1000000);