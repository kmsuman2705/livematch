<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit(0);

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['score'])) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid data"]);
    exit;
}

$file = 'score.json';
if (file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT))) {
    echo json_encode(["success" => true, "message" => "Score updated successfully"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to write file"]);
}
?>
