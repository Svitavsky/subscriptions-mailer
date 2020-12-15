<?php

function connection_open()
{
    if(file_exists('dbconfig.php')) {
        $config = require 'dbconfig.php';
    } else {
        die('Отсутствует файл конфигурации БД - dbconfig.php, создайте его из примера - dbconfig_example.php');
    }

    check_config($config);

    $connection = mysqli_connect($config['host'], $config['user'], $config['password'], $config['database']);

    if(mysqli_connect_errno()) {
        die('Connection failed: %s\n' . mysqli_connect_error());
    }

    return $connection;
}

function connection_close($connection) {
    mysqli_close($connection);
}

function check_config($config) {
    $errorTemplate = 'Не указан параметр {param} в файле конфигурации!';
    $configExample = require_once 'dbconfig_example.php';
    $params = array_keys($configExample);

    foreach($params as $param) {
        if(!isset($config[$param])) {
            $error = str_replace('{param}', $param, $errorTemplate);
            die($error);
        }
    }
}