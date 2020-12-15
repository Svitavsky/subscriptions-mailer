<?php

include 'database.php';

function check_email($email)
{
    sleep(rand(1, 60));
    return rand(0, 1);
}

function send_email($email, $from, $to, $subj, $body)
{
    sleep(rand(1, 10));
    return true;
}

function check_subscription()
{
    $email = 'subscribe@example.com';
    $subj = 'Renew your subscription';
    $text = 'Subscription renewal text';

    $time = strtotime('+ 2 days');

    $query = <<<QUERY
SELECT
    emails.id,
    emails.email,
    emails.checked
FROM users
JOIN emails
ON users.email = emails.email
WHERE users.email_confirmed = TRUE
AND (emails.valid = TRUE OR emails.checked = FALSE)
AND validts = {$time}

QUERY;

    $connection = connection_open();
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            if (!$row['checked']) {
                $valid = check_email($row['email']);

                $rowQuery = <<< ROW_QUERY
UPDATE emails
SET valid = {$valid}, checked = 1
WHERE id = {$row['id']}
ROW_QUERY;

                mysqli_query($connection, $rowQuery);

                if (!$valid) {
                    continue;
                }
            }

            send_email($email, $row['email'], $row['email'], $subj, $text);
        }
    }
}

check_subscription();