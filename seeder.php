<?php

include 'database.php';

function seed($count)
{
    $start = microtime(true);

    $chunk = 1000;
    if ($count < $chunk) {
        $chunk = $count;
    }

    $connection = connection_open();

    toggle_keys($connection, 'users', false);
    toggle_keys($connection, 'emails', false);

    for ($i = 0; $i < $count / $chunk; $i++) {
        $users = [];
        $emails = [];
        for ($j = 0; $j < $chunk; $j++) {
            $name = generate_name();
            $emailName = str_replace(' ', '_', $name);
            $email = "{$emailName}@example.com";
            $confirmed = rand(0, 1);
            $expiry = rand(time() - 2629743, time() + 2629743);
            $checked = rand(0, 1);
            $valid = $checked === 1 ? rand(0, 1) : 0;

            $users[] = "('{$name}','{$email}',{$expiry},{$confirmed})";
            $emails[] = "('{$email}',{$checked},{$valid})";
        }

        $usersList = implode(',', $users);
        $usersQuery = <<<QUERY
INSERT
INTO users
    (username, email, validts, email_confirmed)
VALUES
    {$usersList}
QUERY;

        $emailsList = implode(',', $emails);
        $emailsQuery = <<<QUERY
INSERT
INTO emails
    (email, checked, valid)
VALUES
    {$emailsList}
QUERY;

        if (!mysqli_query($connection, $usersQuery)) {
            die(mysqli_error($connection));
        }

        if (!mysqli_query($connection, $emailsQuery)) {
            die(mysqli_error($connection));
        }
    }

    toggle_keys($connection, 'users', true);
    toggle_keys($connection, 'emails', true);

    connection_close($connection);

    $end = microtime(true);
    $time = round($end - $start, 4);
    echo "Inserted {$count} rows. It took {$time} seconds";
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

function toggle_keys($connection, $table, $state)
{
    $action = $state ? 'ENABLE' : 'DISABLE';
    $query = <<<QUERY
ALTER TABLE {$table} {$action} KEYS;
QUERY;

    if (!mysqli_query($connection, $query)) {
        die("Unable to {$action} keys for table {$table}!");
    }
}

seed(1000000);