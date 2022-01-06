<?php

error_reporting(E_ALL ^ E_WARNING);

require_once __DIR__ . '/../vendor/autoload.php';
include('functions.php');

$arguments = Functions::readInput($argv);

$environment = $arguments[1];
$featureFlag = $arguments[2];
$fileName = $arguments[3];
$proj_key = 'default';

//get all users in environment
$limit = 50; // int | The number of elements to return per page

$functions = new Functions();

try {
    do {
        $result = $functions->getUsers($proj_key, $environment, $limit, isset($lastUser) ? $lastUser : null);
        usleep(45000);
        $lastUser = $functions->getLastUser($result);

        $userList[] = $functions->listUsers($result['items']);

        $totalCount = (int) $result['totalCount'];

        if (!isset($numItems)) {
            $numItems = count($result['items']);
        } else {
            $numItems += count($result['items']);
        }
    } while ($numItems < $totalCount);
} catch (Exception $e) {
    echo 'Exception when calling UsersApi->getUsers: ', $e->getMessage(), PHP_EOL;
}

//loop through each user and check the status of their flag

try {
    foreach ($userList as $userarray) {
        foreach ($userarray as $user) {
            usleep(75000);
            $result = $functions->getUserFlagSetting($proj_key, $environment, $user, $featureFlag);
            $userflagarray[] = [$user, $result];
        }
    }
} catch (Exception $e) {
    echo 'Exception when calling UserSettingsApi->getUserFlagSetting: ', $e->getMessage(), PHP_EOL;
}


//write to output file
$file = fopen($fileName, "w") or die("Unable to open file!");
fputcsv($file, ["Environment", $environment]);
fputcsv($file, ["User", $featureFlag]);
foreach ($userflagarray as $user) {
    fputcsv($file, $user);
}
fclose($file);
