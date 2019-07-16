#!/usr/bin/php
<?php

use Aws\CostExplorer\CostExplorerClient;

require_once __DIR__ ."/vendor/autoload.php";

$date = new DateTimeImmutable();


$client = new CostExplorerClient(
    [
        'region' => 'us-east-1',
        'version' => '2017-10-25'
    ]

);
$result = $client->getCostAndUsage(
    [
        'GroupBy' => [
            [
            'Key' => 'SERVICE',
            'Type' => 'DIMENSION'
            ]
        ],
        'TimePeriod' => [
            'End' => $date->modify('-1 days')->format('Y-m-d'),
            'Start' => $date->modify('-2 days')->format('Y-m-d')
        ],
        'Granularity' => 'DAILY',
        'Metrics' => ['BlendedCost']
    ]

);

echo $date->modify('-2 day')->format('Y-m-d') ,"\n";
$cost =0;
foreach ( $result->search('ResultsByTime[].Groups[].{Keys: Keys[0],Cost: Metrics.BlendedCost.Amount}') as $item) {
    echo $item['Keys'].",".$item['Cost']."\n";
    $cost += (float)$item['Cost'];
}

echo "Total Cost,$cost","\n";
