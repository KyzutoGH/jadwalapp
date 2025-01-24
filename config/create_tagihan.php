// Billing Form
$tanggal = mysqli_real_escape_string($db, $_POST['tanggal']);
$customer = mysqli_real_escape_string($db, $_POST['customer']);
$total = mysqli_real_escape_string($db, $_POST['total']);
$dp_current = mysqli_real_escape_string($db, $_POST['dp_current']);
$dp_total = mysqli_real_escape_string($db, $_POST['dp_total']);
$pelunasan = mysqli_real_escape_string($db, $_POST['pelunasan']);
$status = mysqli_real_escape_string($db, $_POST['status']);

// Query insert data
$sql = "INSERT INTO penagihan (tanggal, customer, total, dp_current, dp_total, pelunasan, status)
VALUES ('$tanggal', '$customer', '$total', '$dp_current', '$dp_total', '$pelunasan', '$status')";

// Eksekusi query
if (mysqli_query($db, $sql)) {
echo "Data penagihan berhasil ditambahkan!";
} else {
echo "Gagal menambahkan data penagihan: " . mysqli_error($db);
}