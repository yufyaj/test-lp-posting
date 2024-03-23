<?php
    require 'lib/php/Logger/logger.php';

    use lib\logger as logger;

    $log = logger\Logger::getInstance();

    $type = $_GET['type'];
    $state = $_GET['state'];
    $city = $_GET['city'];
    $busu = $_GET['busu'];
    $price = $_GET['price'];
    $company = $_GET['company'];
    $name = $_GET['name'];
    $mail = $_GET['mail'];
    $privacy = $_GET['privacy'];
    $log->info("type: {$type}, state: {$state}, city: {$city}, busu: {$busu}, company: {$company}, name: {$name}, mail: {$mail}, privacy: {$privacy}");

    exit(header("Location: confirm.php?type={$type}&state={$state}&city={$city}&busu={$busu}&price={$price}&company={$company}&name={$name}&mail={$mail}&privacy={$privacy}", true, 303));
?>
