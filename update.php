<?php
// Accept JSON input from fetch() POST
$data = json_decode(file_get_contents("php://input"), true);

$filePath = "/var/www/html/score.json";

// Read old data to preserve target if empty
$existingData = [];
if (file_exists($filePath)) {
    $existingData = json_decode(file_get_contents($filePath), true);
    if (!is_array($existingData)) $existingData = [];
}

// ✅ Preserve target if new target is empty
if (empty($data['target']) && !empty($existingData['target'])) {
    $data['target'] = $existingData['target'];
}

// ✅ Preserve team names if empty (avoid wiping accidentally)
if (empty($data['team1']) && !empty($existingData['team1'])) {
    $data['team1'] = $existingData['team1'];
}
if (empty($data['team2']) && !empty($existingData['team2'])) {
    $data['team2'] = $existingData['team2'];
}

// ✅ Save updated data
$jsonData = json_encode($data, JSON_PRETTY_PRINT);

if (file_put_contents($filePath, $jsonData) !== false) {
    echo "✅ Score updated successfully.";
} else {
    http_response_code(500);
    echo "❌ Failed to write score.json (check permissions)";
}
?>
