$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id === 0) {
header('Location: myEvents.php');
exit();
}

$stmt = $conn->prepare("SELECT * FROM eventos WHERE id = :id AND organizador_id = :organizador");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->bindValue(':organizador', (int) $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$evento = $stmt->fetch();

if (!$evento) {
header('Location: myEvents.php');
exit();
}