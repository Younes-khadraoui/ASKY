<?php
require_once realpath(__DIR__ . '/../vendor/autoload.php');

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(realpath(__DIR__ . '/..'));
try {
    $dotenv->load();
} catch (Exception $e) {
    echo 'Error loading .env file: ' . $e->getMessage();
    exit;
}

$host = $_ENV['DB_HOST'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];
$database = $_ENV['DB_NAME']; 

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 