<?php
// update.php
header('Content-Type: application/json');

// Get raw POST data
$input = file_get_contents('php://input');

// Decode JSON safely
$data = json_decode($input, true);

if(!$data){
    echo json_encode(['success'=>false, 'message'=>'Invalid JSON received']);
    exit;
}

if(!isset($data['score'])){
    echo json_encode(['success'=>false, 'message'=>'No score data received']);
    exit;
}

$score = $data['score'];

// Ensure all required fields exist and set defaults
$score = array_merge([
    'teamA' => 'Team A',
    'teamB' => 'Team B',
    'runs' => 0,
    'wickets' => 0,
    'overs' => '0.0',
    'target' => null,
    'targetOvers' => null,
    'targetWickets' => null,
    'last6' => '',
    'batsman1' => ['name'=>'Batsman 1','runs'=>0,'balls'=>0,'fours'=>0,'sixes'=>0,'sr'=>'0.0'],
    'batsman2' => ['name'=>'Batsman 2','runs'=>0,'balls'=>0,'fours'=>0,'sixes'=>0,'sr'=>'0.0'],
    'bowler' => ['name'=>'Bowler','overs'=>'0.0','runs'=>0,'wickets'=>0,'eco'=>'0.0']
], $score);

// Save to JSON file
$file = 'score.json';
try {
    $saved = file_put_contents($file, json_encode(['score'=>$score], JSON_PRETTY_PRINT));
    if($saved === false){
        throw new Exception('Failed to save score file.');
    }
    echo json_encode(['success'=>true, 'message'=>'Score updated successfully']);
} catch(Exception $e){
    echo json_encode(['success'=>false, 'message'=>$e->getMessage()]);
}
?>
