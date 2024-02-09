<?php
$host = "openshift-deployment-site-php-login-site-db-service";
$db_user = "root";
$db_password = "root";
$db_name = "logowanie";

$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

// Check connection
if ($polaczenie->connect_error) {
    die("Connection failed: " . $polaczenie->connect_error);
}
?>