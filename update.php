<?php
header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

$file = 'score.json';
$existingData = [];
if (file_exists($file)) {
    $existingData = json_decode(file_get_contents($file), true);
}

if (empty($data['score']['target']) && !empty($existingData['score']['target'])) {
    $data['score']['target'] = $existingData['score']['target'];
}
if (empty($data['score']['targetWickets']) && !empty($existingData['score']['targetWickets'])) {
    $data['score']['targetWickets'] = $existingData['score']['targetWickets'];
}

file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));

echo json_encode(["status" => "success", "message" => "Score updated successfully"]);
?>
