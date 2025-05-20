<?php

require_once __DIR__ . '/crest/crest.php';

$response = CRest::call('user.get', [
    'SELECT' => ['ID', 'NAME', 'LAST_NAME'],
]);

header('Content-Type: application/json');

if (!empty($response['result'])) {
    return json_encode($response['result']);
} else {
    return json_encode([]);
}
