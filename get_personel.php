<?php
require_once 'connection.php';
header('Content-Type: application/json');
if($_SERVER['REQUEST_METHOD'] === 'GET'){
        $stmt = $mysqli->prepare('SELECT 
        p.personel_id,
        p.nama,
        p.nrp,
        p.pkt,
        p.jabatan,
        p.satker,
        p.no_telp,
        p.alamat
        FROM personel p
        ORDER BY p.nrp 
        ');
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        
    
        $data = [];
         /* now you can fetch the results into an array - NICE */
        while ($row = $result->fetch_assoc()) {
            $personel = [              
                    'personel_id' => $row['personel_id'], 
                    'nama' => $row['nama'], 
                    'nrp' => $row['nrp'], 
                    'pkt' => $row['pkt'], 
                    'jabatan' => $row['jabatan'], 
                    'satker' => $row['satker'], 
                    'no_telp' => $row['no_telp'], 
                    'alamat' => $row['alamat']
            
                ];
            array_push($data, $personel);
        }
        http_response_code(200);
        die(json_encode([
            'code' => 200,
            'data' => [
                'personel' => $data
            ],
            'message' => 'Successfully get personel'
        ]));
    
} else {
    http_response_code(400);
    die(json_encode([
        'code' => 400,
        'data' => null,
        'message' => 'Request GET not found'
    ]));
}


?>