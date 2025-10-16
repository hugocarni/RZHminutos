<?php
$host = 'b3ehcobded3fsuwrvkbb-mysql.services.clever-cloud.com';
$db = 'b3ehcobded3fsuwrvkbb';
$user = 'uynpuinpuormozoc';
$port = 3306;
$password = 'qb3gaw0Bqol208ZEXJzJ';

$conexion = new mysqli($host, $user, $password, $db, $port);

if ($conexion->connect_error) {
    die("Connection failed: " . $conexion->connect_error);
}
