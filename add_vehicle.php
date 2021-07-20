<?php
require_once 'connection.php';
header('Content-Type: application/json');
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $input = json_decode(json_encode($_POST));
    $files= $_FILES['foto'];
   if(validate($input, $files, $mysqli)){
       $fileName = generateFileName($files['name']);
       $upload = move_uploaded_file($files['tmp_name'],  __DIR__.'/photos/'.$fileName);
       if($upload){
        try{
            
            $mysqli->begin_transaction();
            $queryKendaraan = "INSERT INTO kendaraan(personel_id, nopol, merk, warna, tahun_pembuatan) VALUES (?, ?, ?, ?, ?)";
            $queryFoto = "INSERT INTO fotorandis(randis_id, foto) VALUES (?, ?)";
            $stmt1 = $mysqli->prepare($queryKendaraan);
            $stmt1->bind_param("isssi", $input->personel_id, $input->nopol, $input->merk, $input->warna, $input->tahun_pembuatan);
            $stmt1->execute();
            
            $randisId = $mysqli->insert_id;
            $stmt2 = $mysqli->prepare($queryFoto);
            $stmt2->bind_param("is", $randisId, $fileName);
            $stmt2->execute();

            $mysqli->commit();
            http_response_code(201);
            die(json_encode([
                'code' => 201,
                'data' => null,
                'message' => 'Kendaraan berhasil ditambahkan'
            ]));
           }
           catch(Exception $e){
            $mysqli->rollback();
               badResponse(400, $e->getMessage(), null);
           }
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

function validate($input, $files, $mysqli){
    $errors = [];
    if(!isset($input->nopol)){
        pushError($errors, 'nopol', 'Harap isi No Pol');
    } else {
        $stmt = $mysqli->prepare('SELECT k.nopol
        FROM kendaraan k
        WHERE k.nopol = ?
        ');
        $stmt->bind_param('s', $input->nopol);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if($result->num_rows > 0){
            pushError($errors, 'nopol', 'No Pol sudah digunakan');
        }
    }

    if(!isset($input->personel_id)){
        pushError($errors, 'personel_id', 'Harap isi Personel ID');
    } else {
        $stmt = $mysqli->prepare('SELECT p.nrp
        FROM personel p
        WHERE p.personel_id = ?
        ');
        $stmt->bind_param('s', $input->personel_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if($result->num_rows === 0){
            pushError($errors, 'personel_id', 'Personel tidak ditemukan');
        }
    }

    if(!isset($input->merk)){
        pushError($errors, 'merk', 'Harap isi merk');
    }

    if(!isset($input->warna)){
        pushError($errors, 'warna', 'Harap isi warna');
    }

    if(!isset($input->tahun_pembuatan)){
        pushError($errors, 'tahun_pembuatan', 'Harap isi tahun pembuatan');
    }
    
    if(!isset($files)){
        pushError($errors, 'foto', 'Harap isi foto');
    } else {
        $validateExtension = (($files["type"] == "image/jpeg")
        || ($files["type"] == "image/jpg")
        || ($files["type"] == "image/png"));
        $maxsize    = 1000000;
        $validateSize = $files['size'] <= $maxsize;
        if (!$validateExtension){
            pushError($errors, 'foto', $files['type']);
        } else if(!$validateSize){
            pushError($errors, 'foto', 'Ukuran foto lebih dari 1MB');
        }
    }

    if(empty($errors)){
        return true;
    } 

    badResponse(400, 'Error validation', $errors);

    
}

?>