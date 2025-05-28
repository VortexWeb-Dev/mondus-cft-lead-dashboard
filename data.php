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

// Stage mapping for processing
$stageMap = [
    "Assigned" => "assigned",
    "Connected" => "contacted",
    "Qualified Leads" => "qualified",
    "Demo" => "demo",
    "Success" => "id",
    "New Lead" => "remaining"
];

// Fetch leads
$response = CRest::call('crm.item.list', [
    'entityTypeId' => 1036,
    'select' => ['id', 'stageId', 'sourceId', 'createdTime', 'assignedById', 'ufCrm3_1746081053233'],
    'start' => ($page - 1) * 50,
]);

$items = $response['result']['items'] ?? [];

// Initialize processed data structure
$processedData = [];

// Function to initialize agent data
function initAgent($agentName)
{
    return [
        'name' => $agentName,
        'branch' => "",
        'campaigns' => [],
        'paid' => [
            'assigned' => 0,
            'contacted' => 0,
            'qualified' => 0,
            'demo' => 0,
            'id' => 0,
            'remaining' => 0
        ],
        'other' => [
            'assigned' => 0,
            'contacted' => 0,
            'qualified' => 0,
            'demo' => 0,
            'id' => 0,
            'remaining' => 0
        ],
        'total' => [
            'assigned' => 0,
            'contacted' => 0,
            'qualified' => 0,
            'demo' => 0,
            'id' => 0,
            'remaining' => 0
        ],
        'ziwo' => [
            'outbound' => 0,
            'answered' => 0,
            'paid' => 0
        ]
    ];
}

// Function to determine branch from campaigns
function determineBranchFromCampaigns($campaigns)
{
    if (empty($campaigns)) {
        return "UNKNOWN";
    }

    $locationSet = [];

    foreach ($campaigns as $campaign) {
        if ($campaign !== "Unknown Campaign") {
            $parts = explode(" ", $campaign);
            if (!empty($parts)) {
                $location = end($parts);
                $locationSet[$location] = true;
            }
        }
    }

    if (!empty($locationSet)) {
        return implode(", ", array_keys($locationSet));
    }

    return "OTHER";
}

// Group and process items
$grouped = [];
foreach ($items as $item) {
    $sourceId = $item['sourceId'] ?? 'UNKNOWN_SOURCE';
    $stageId = $item['stageId'] ?? 'UNKNOWN_STAGE';
    $assignedById = $item['assignedById'] ?? 'UNASSIGNED';

    $agentName = $agentNames[$assignedById] ?? "Unassigned ($assignedById)";
    $stageName = $stageInfoMap[$stageId]['name'] ?? $stageId;
    $stageColor = $stageInfoMap[$stageId]['color'] ?? '#999';
    $sourceName = $sourceNameMap[$sourceId] ?? $sourceId;
    $campaignName = $item['ufCrm3_1746081053233'] ?? 'Unknown Campaign';

    $item['agentName'] = $agentName;
    $item['stageName'] = $stageName;
    $item['stageColor'] = $stageColor;
    $item['sourceName'] = $sourceName;
    $item['campaignName'] = $campaignName;

    $grouped[$sourceName][$stageName][$agentName][] = $item;
}

// Process the grouped data into the final structure
function processSection($sectionData, $category, &$processedData, $stageMap)
{
    foreach ($sectionData as $stageName => $agents) {
        $mappedField = $stageMap[$stageName] ?? null;
        if (!$mappedField) continue;

        foreach ($agents as $agentName => $items) {
            if (!isset($processedData[$agentName])) {
                $processedData[$agentName] = initAgent($agentName);
            }

            $agent = &$processedData[$agentName];
            $count = count($items);

            $agent[$category][$mappedField] += $count;
            $agent['total'][$mappedField] += $count;

            // Collect campaigns
            foreach ($items as $item) {
                if ($item['campaignName'] && !in_array($item['campaignName'], $agent['campaigns'])) {
                    $agent['campaigns'][] = $item['campaignName'];
                }
            }
        }
    }
}

// Process different sections
if (isset($grouped['Call'])) {
    processSection($grouped['Call'], 'other', $processedData, $stageMap);
}
if (isset($grouped['Meta Sheet'])) {
    processSection($grouped['Meta Sheet'], 'paid', $processedData, $stageMap);
}

// Set branches for all agents
foreach ($processedData as &$agent) {
    $agent['branch'] = determineBranchFromCampaigns($agent['campaigns']);
}

// Convert to indexed array and sort by name
$finalProcessedData = array_values($processedData);
usort($finalProcessedData, function ($a, $b) {
    return strcmp($a['name'], $b['name']);
});

// Calculate totals
$totals = [
    'paid' => [
        'assigned' => 0,
        'contacted' => 0,
        'qualified' => 0,
        'demo' => 0,
        'id' => 0,
        'remaining' => 0
    ],
    'other' => [
        'assigned' => 0,
        'contacted' => 0,
        'qualified' => 0,
        'demo' => 0,
        'id' => 0,
        'remaining' => 0
    ],
    'total' => [
        'assigned' => 0,
        'contacted' => 0,
        'qualified' => 0,
        'demo' => 0,
        'id' => 0,
        'remaining' => 0
    ],
    'ziwo' => [
        'outbound' => 0,
        'answered' => 0,
        'paid' => 0
    ]
];

foreach ($finalProcessedData as $agent) {
    foreach (['paid', 'other', 'total', 'ziwo'] as $category) {
        foreach ($totals[$category] as $metric => $value) {
            $totals[$category][$metric] += $agent[$category][$metric];
        }
    }
}

// Prepare filter options
$agents = [];
$branches = [];

foreach ($finalProcessedData as $agent) {
    $agentFirstName = strtolower(explode(' ', $agent['name'])[0]);
    $agents[$agentFirstName] = true;

    if ($agent['branch']) {
        $branchList = explode(',', $agent['branch']);
        foreach ($branchList as $branch) {
            $trimmedBranch = trim(strtolower($branch));
            if ($trimmedBranch) {
                $branches[$trimmedBranch] = true;
            }
        }
    }
}

// Final response
$response = [
    'agents' => $finalProcessedData,
    'totals' => $totals,
    'filterOptions' => [
        'agents' => array_keys($agents),
        'branches' => array_keys($branches)
    ],
    'rawData' => $grouped // Keep for debugging if needed
];

echo json_encode($response, JSON_PRETTY_PRINT);
