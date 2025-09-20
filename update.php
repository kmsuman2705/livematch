<?php
$data = json_decode(file_get_contents("php://input"), true);
$filePath = "/var/www/html/score.json";

// Read old data to preserve existing values
$existingData = [];
if (file_exists($filePath)) {
    $existingData = json_decode(file_get_contents($filePath), true);
    if (!is_array($existingData)) $existingData = [];
}

// ✅ Preserve target if new target empty
if (empty($data['score']['target']) && !empty($existingData['score']['target'])) {
    $data['score']['target'] = $existingData['score']['target'];
}

// ✅ Preserve team names if empty
if (empty($data['score']['teamA']) && !empty($existingData['score']['teamA'])) {
    $data['score']['teamA'] = $existingData['score']['teamA'];
}
if (empty($data['score']['teamB']) && !empty($existingData['score']['teamB'])) {
    $data['score']['teamB'] = $existingData['score']['teamB'];
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