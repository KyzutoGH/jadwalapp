<?php
include 'koneksi.php';

// Initialize response array
$response = array('success' => false, 'stock' => 0);

// Check if ID parameter exists
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Query to get current stock
    $query = "SELECT stock FROM jaket WHERE id_jaket = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $response['success'] = true;
        $response['stock'] = $row['stock'];
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>