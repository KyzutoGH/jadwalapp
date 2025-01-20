<?php
class JadwalModel {
    private $db;
    private $table = 'jadwal_sekolah';

    public function __construct($database) {
        $this->db = $database;
    }

    // Create
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                (nama_sekolah, jenis_sekolah, alamat, tanggal_dies, 
                nomor_telepon, sosmed_facebook, sosmed_instagram, sosmed_tiktok)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($query);
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($this->db->error));
        }
        
        $stmt->bind_param(
            "ssssssss",
            $data['nama_sekolah'],
            $data['jenis_sekolah'],
            $data['alamat'],
            $data['tanggal_dies'],
            $data['nomor_telepon'],
            $data['sosmed_facebook'],
            $data['sosmed_instagram'],
            $data['sosmed_tiktok']
        );
        
        return $stmt->execute();
    }

    // Read
    public function read($id = null) {
        if ($id) {
            $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
            $stmt = $this->db->prepare($query);
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($this->db->error));
            }
            
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc(); // Use get_result() for MySQLi
        } else {
            // Fetch all records
            $query = "SELECT * FROM " . $this->table;
            $result = $this->db->query($query);
            return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        }
    }

    // Update
    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " SET
                nama_sekolah = ?, jenis_sekolah = ?, alamat = ?,
                tanggal_dies = ?, nomor_telepon = ?, sosmed_facebook = ?,
                sosmed_instagram = ?, sosmed_tiktok = ?
                WHERE id = ?";
        
        $stmt = $this->db->prepare($query);
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($this->db->error));
        }
        
        $stmt->bind_param(
            "ssssssssi",
            $data['nama_sekolah'],
            $data['jenis_sekolah'],
            $data['alamat'],
            $data['tanggal_dies'],
            $data['nomor_telepon'],
            $data['sosmed_facebook'],
            $data['sosmed_instagram'],
            $data['sosmed_tiktok'],
            $id
        );
        
        return $stmt->execute();
    }

    // Delete
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->db->prepare($query);
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($this->db->error));
        }
        
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
