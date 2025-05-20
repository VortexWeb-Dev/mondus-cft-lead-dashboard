<?php

header('Content-Type: application/json');

require_once __DIR__ . '/crest/crest.php';
require_once __DIR__ . '/config.php';
$agents = require __DIR__ . '/agents.php';
$agents = json_decode($agents, true);

$page = $_GET['page'] ?? 1;

// Prepare agent name map
$agentNames = [];
foreach ($agents as $agent) {
    $id = $agent['ID'];
    $name = trim(($agent['NAME'] ?? '') . ' ' . ($agent['LAST_NAME'] ?? ''));
    $agentNames[$id] = $name ?: "Unnamed ($id)";
}

// Prepare stage and source mappings
$stageInfoMap = [];
foreach (STAGES as $stage) {
    $stageInfoMap[$stage['ID']] = [
        'name' => $stage['NAME'],
        'color' => $stage['COLOR'],
    ];
}

$sourceNameMap = [];
foreach (SOURCES as $source) {
    $sourceNameMap[$source['ID']] = $source['NAME'];
}

// Fetch leads
$response = CRest::call('crm.item.list', [
    'entityTypeId' => 1036,
    'select' => ['id', 'stageId', 'sourceId', 'createdTime', 'assignedById', 'ufCrm3_1746081053233'],
    'start' => ($page - 1) * 50,
]);

$items = $response['result']['items'] ?? [];

$grouped = [];

foreach ($items as $item) {
    $sourceId = $item['sourceId'] ?? 'UNKNOWN_SOURCE';
    $stageId = $item['stageId'] ?? 'UNKNOWN_STAGE';
    $assignedById = $item['assignedById'] ?? 'UNASSIGNED';

    $agentName = $agentNames[$assignedById] ?? "Unassigned ($assignedById)";
    $stageName = $stageInfoMap[$stageId]['name'] ?? $stageId;
    $stageColor = $stageInfoMap[$stageId]['color'] ?? '#999';
    $sourceName = $sourceNameMap[$sourceId] ?? $sourceId;

    $item['agentName'] = $agentName;
    $item['stageName'] = $stageName;
    $item['stageColor'] = $stageColor;
    $item['sourceName'] = $sourceName;
    $item['campaignName'] = $item['ufCrm3_1746081053233'] ?? 'Unknown Campaign';

    $grouped[$sourceName][$stageName][$agentName][] = $item;
}

// Optional: Add counts per group (can be used on frontend)
$finalOutput = [];
foreach ($grouped as $source => $stages) {
    foreach ($stages as $stage => $agents) {
        foreach ($agents as $agent => $items) {
            $finalOutput[$source][$stage][$agent] = [
                'count' => count($items),
                'items' => $items
            ];
        }
    }
}

echo json_encode($finalOutput, JSON_PRETTY_PRINT);
