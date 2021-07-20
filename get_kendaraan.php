<?php
require_once 'connection.php';
header('Content-Type: application/json');
if($_SERVER['REQUEST_METHOD'] === 'GET'){
        $stmt = $mysqli->prepare('SELECT k.randis_id,
        k.nopol,
        k.merk,
        k.warna,
        k.tahun_pembuatan,
        fr.foto AS `foto_randis`,
        p.personel_id,
        p.nama,
        p.nrp,
        p.pkt,
        p.jabatan,
        p.satker,
        p.no_telp,
        p.alamat
        FROM kendaraan k
        LEFT JOIN personel p
        ON k.personel_id = p.personel_id
        LEFT JOIN fotorandis fr
        ON k.randis_id = fr.randis_id
        ORDER BY k.nopol
        ');
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        
    
        $data = [];
         /* now you can fetch the results into an array - NICE */
        while ($row = $result->fetch_assoc()) {
            $kendaraan = [
                'randis_id' => $row['randis_id'],
                'nopol' => $row['nopol'],
                'merk' => $row['merk'],
                'warna' => $row['warna'],
                'tahun_pembuatan' => $row['tahun_pembuatan'],
                'foto' => $photoPath.$row['foto_randis'], 
                'personel' => [                
                    'personel_id' => $row['personel_id'], 
                    'nama' => $row['nama'], 
                    'nrp' => $row['nrp'], 
                    'pkt' => $row['pkt'], 
                    'jabatan' => $row['jabatan'], 
                    'satker' => $row['satker'], 
                    'no_telp' => $row['no_telp'], 
                    'alamat' => $row['alamat']
                ]
                ];
            array_push($data, $kendaraan);
        }
        http_response_code(200);
        die(json_encode([
            'code' => 200,
            'data' => [
                'kendaraan' => $data
            ],
            'message' => 'Successfully get kendaraan'
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