<?php
    require 'lib/php/Logger/logger.php';
    $pattern = $_SERVER['DOCUMENT_ROOT'] . '/model/InputItem/*.php';
    foreach ( glob( $pattern ) as $filename )
    {
        require_once $filename;
    }

    use lib\logger as logger;

    $log = logger\Logger::getInstance();

    $response = new InputItemResponse();
    try {
        $type = $_GET['type'];
        $type = new Type($type);
    } catch (Exception $err) {
        $response->setErrMsg($err->getMessage());
    }
    try{
        $state = $_GET['state'];
        $state = new State($state);
    } catch (Exception $err) {
        $response->setErrMsg($err->getMessage());
    }
    try{
        $city = $_GET['city'];
        $city = new City($city);
    } catch (Exception $err) {
        $response->setErrMsg($err->getMessage());
    }
    try{
        $busu = $_GET['busu'];
        $busu = new Busu($busu);
    } catch (Exception $err) {
        $response->setErrMsg($err->getMessage());
    }
    try{
        $cost = $_GET['cost'];
        $cost = new Cost($cost);
    } catch (Exception $err) {
        $response->setErrMsg($err->getMessage());
    }
    try{
        $company = $_GET['company'];
        $company = new Company($company);
    } catch (Exception $err) {
        $response->setErrMsg($err->getMessage());
    }
    try{
        $name = $_GET['name'];
        $name = new Name($name);
    } catch (Exception $err) {
        $response->setErrMsg($err->getMessage());
    }
    try{
        $mail = $_GET['mail'];
        $mail = new Mail($mail);
    } catch (Exception $err) {
        $response->setErrMsg($err->getMessage());
    }
    try{
        $privacy = $_GET['privacy'];
        $privacy = new Privacy($privacy);
    } catch (Exception $err) {
        $response->setErrMsg($err->getMessage());
    }
    
    $log->info("type: {$type->getValue()}, state: {$state}, city: {$city}, busu: {$busu}, company: {$company}, name: {$name}, mail: {$mail}, privacy: {$privacy}");

    exit(header("Location: confirm.php?type={$type->getValue()}&state={$state}&city={$city}&busu={$busu}&cost={$cost}&company={$company}&name={$name}&mail={$mail}&privacy={$privacy}", true, 303));
?>
