<?php
require_once 'connection.php';
header('Content-Type: application/json');
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON);

   if(validate($input, $mysqli)){
       try{
        $query = "INSERT INTO personel(nrp, nama, pkt, jabatan, satker, no_telp, alamat) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("sssssss", $input->nrp, $input->nama, $input->pkt, $input->jabatan, $input->satker, $input->no_telp, $input->alamat);
        $stmt->execute();
        
        http_response_code(201);
        die(json_encode([
            'code' => 201,
            'data' => null,
            'message' => 'Personel berhasil ditambahkan'
        ]));
       }
       catch(Exception $e){
           badResponse(400, $e->getMessage(), null);
       }
    }    
} else {
    http_response_code(404);
    die(json_encode([
        'code' => 404,
        'data' => null,
        'message' => 'Request POST not found'
    ]));
}

function validate($input, $mysqli){
    $errors = [];
    if(!isset($input->nrp)){
        pushError($errors, 'nrp', 'Harap isi NRP');
    } else {
        $stmt = $mysqli->prepare('SELECT p.nrp
        FROM personel p
        WHERE p.nrp = ?
        ');
        $stmt->bind_param('s', $input->nrp);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if($result->num_rows > 0){
            pushError($errors, 'nrp', 'NRP sudah digunakan');
        }
    }

    if(!isset($input->nama)){
        pushError($errors, 'nama', 'Harap isi nama');
    }

    if(!isset($input->pkt)){
        pushError($errors, 'pkt', 'Harap isi pangkat');
    }

    if(!isset($input->jabatan)){
        pushError($errors, 'jabatan', 'Harap isi jabatan');
    }

    if(!isset($input->satker)){
        pushError($errors, 'satker', 'Harap isi satuan kerja');
    }

    if(!isset($input->no_telp)){
        pushError($errors, 'no_telp', 'Harap isi nomor telepon');
    } else {
        $stmt = $mysqli->prepare('SELECT p.no_telp
        FROM personel p
        WHERE p.no_telp = ?
        ');
        $stmt->bind_param('s', $input->no_telp);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if($result->num_rows > 0){
            pushError($errors, 'no_telp', 'Nomor telepon sudah digunakan');
        }
    }
    
    if(!isset($input->alamat)){
        pushError($errors, 'alamat', 'Harap isi alamat');
    }

    if(empty($errors)){
        return true;
    } 

    badResponse(400, 'Error validation', $errors);

    
}

?>