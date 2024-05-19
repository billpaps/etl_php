<?php

require 'vendor/autoload.php'; // Include Composer's autoloader
require __DIR__ . '/../bootstrap.php';

require_once 'openExchangeHistorical.php';
require_once 'AbstractPipeline.php';

function parseDate($dateString) {
    try {
        return new DateTime($dateString);
    } catch (Exception $e) {
        exit("Invalid date format: $dateString\n");
    }
}

// Check if the script is used correctly
if ($argc == 1) {
    $start_date = new DateTime('now');

} elseif ($argc == 3) {
    $start_date = parseDate($argv[1]);
    $end_date = parseDate($argv[2]);

    // Sanity check of dates logic
    if ($start_date > $end_date) {
        exit("The start date is greater than end date\n");
    }

} else {
    exit("wrong usage of script\n");
}

// Endpoint related values
$app_id = "b95b8a2a95c6459fac19324564dbc0a7";
$endpoint = "https://openexchangerates.org/api/historical";

$opts = [
    "app_id" => $app_id,
    "start_date" => $start_date,
    "end_date" => $end_date ?? null
];

$worker = new Sqlite($entityManager, 100);
$pipeline = new openExchangeHistorical($opts, $worker);

$pipeline->execute($opts);
