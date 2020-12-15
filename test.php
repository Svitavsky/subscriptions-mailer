<?php

require_once 'migrations.php';
require_once 'seeder.php';
require_once 'script.php';

$connection = connection_open();

migrate_all($connection);
seed($connection, 1000000);
index_all($connection);

connection_close($connection);

check_subscription();