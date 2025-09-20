<?php
// update.php
header('Content-Type: application/json');

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['score'])) {
    echo json_encode(['message'=>'Invalid data']);
    exit;
}

$score = $data['score'];

// File path
$file = 'score.json';

// Load existing data
if(file_exists($file)){
    $currentData = json_decode(file_get_contents($file), true);
} else {
    $currentData = ['score'=>[
        'teamA'=>'Team A','teamB'=>'Team B','runs'=>0,'wickets'=>0,'overs'=>'0.0',
        'last6'=>'','batsman1'=>[],'batsman2'=>[],'bowler'=>[],'target'=>null,'targetWickets'=>null,'targetOvers'=>null
    ]];
}

// Update data
$currentData['score'] = $score;

// Calculate last6 balls and keep max 6
$last6 = explode(" ", $score['last6']);
if(count($last6)>6){
    $last6 = array_slice($last6,-6);
}
$currentData['score']['last6'] = implode(" ",$last6);

// Update overs from legal balls
$oversParts = explode(".", $score['overs']);
$legalOvers = intval($oversParts[0]);
$balls = intval($oversParts[1]??0);
$currentData['score']['overs'] = $legalOvers.".".$balls;

// Recalculate batsmen SR
foreach(['batsman1','batsman2'] as $b){
    $r = intval($score[$b]['runs']??0);
    $bals = intval($score[$b]['balls']??0);
    $currentData['score'][$b]['sr'] = $bals>0?round(($r/$bals)*100,1):0.0;
}

// Calculate CRR
$totalOvers = $legalOvers + ($balls/6);
$currentData['score']['crr'] = $totalOvers>0?round($score['runs']/$totalOvers,2):0.0;

// Calculate RRR if target exists
if(isset($score['target']) && $score['targetOvers']){
    $runsLeft = $score['target'] - $score['runs'] + 1; // +1 for chasing
    $totalBalls = $score['targetOvers']*6;
    $ballsBowled = $legalOvers*6 + $balls;
    $ballsLeft = $totalBalls - $ballsBowled;
    $currentData['score']['rrr'] = $ballsLeft>0?round($runsLeft/( $ballsLeft/6 ),2):0.0;
} else {
    $currentData['score']['rrr'] = null;
}

// Automatic strike swap on over
if($balls==0 && $balls!=intval($oversParts[1]??0)){
    // Swap batsmen if new over started
    $tmp = $currentData['score']['batsman1'];
    $currentData['score']['batsman1'] = $currentData['score']['batsman2'];
    $currentData['score']['batsman2'] = $tmp;
}

// Save back to file
file_put_contents($file,json_encode($currentData,JSON_PRETTY_PRINT));

echo json_encode(['message'=>'Score updated successfully']);
?>