<?php

// database Connection variables
define('HOST', 'localhost'); // Database host name ex. localhost
define('USER', 'root'); // Database user. ex. root ( if your on local server)
define('PASSWORD', ''); // user password  (if password is not set for user then keep it empty )
define('DATABASE', 'phpprojectandreidatabase'); // Database Database name

function ConnectionDatabase ()
{
    try {
        $dbh = new PDO('mysql:host='.HOST.';dbname='.DATABASE.'', USER, PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbh;
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}
?>