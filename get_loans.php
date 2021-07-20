<?php
require_once 'connection.php';
header('Content-Type: application/json');
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(!empty($_POST['member_id'])){
        $member_id = $_POST['member_id'];
        $stmt = $mysqli->prepare('SELECT * FROM loan where member_id = ?');
        $stmt->bind_param('i', $member_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
    
        $data = [];
         /* now you can fetch the results into an array - NICE */
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }
        http_response_code(200);
        die(json_encode([
            'code' => 200,
            'data' => [
                'loans' => $data
            ],
            'message' => 'Successfully get loan'
        ]));
    } else {
        http_response_code(400);
        die(json_encode([
            'code' => 400,
            'data' => null,
            'message' => 'Missing member_id form-data'
        ]));
    }
} else {
    http_response_code(400);
    die(json_encode([
        'code' => 400,
        'data' => null,
        'message' => 'Request GET not found'
    ]));
}


?>