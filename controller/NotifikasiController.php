<?php
class NotifikasiController {
    private $serverKey;
    private $db;

    public function __construct($database) {
        $this->serverKey = FIREBASE_SERVER_KEY;
        $this->db = $database;
    }

    public function saveToken() {
        $data = json_decode(file_get_contents('php://input'), true);
        $token = $data['token'];
        
        $query = "INSERT INTO fcm_tokens (token) VALUES (?) 
                 ON DUPLICATE KEY UPDATE last_updated = CURRENT_TIMESTAMP";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$token]);
    }

    public function sendNotification($tokens, $title, $body) {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = [
            'registration_ids' => $tokens,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ]
        ];

        $headers = [
            'Authorization: key=' . $this->serverKey,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        
        $result = curl_exec($ch);
        curl_close($ch);
        
        return $result;
    }
}

?>