<?php 
include 'includes/session.php';

$building = isset($_GET['building']) ? $_GET['building'] : '';

// Prepare SQL based on the building
if ($building === 'main_building') {
    $sql = "SELECT DISTINCT room FROM scheduless WHERE room LIKE '%M-RM%' OR room = 'COMLAB 1'";
} elseif ($building === 'arroyo_building') {
    $sql = "SELECT DISTINCT room FROM scheduless WHERE room LIKE '%A-RM%' OR room = 'COMLAB 2'";
} elseif ($building === 'tawi_tawi_building') {
    $sql = "SELECT DISTINCT room FROM scheduless WHERE room LIKE '%E-RM%'";
} else {
    $sql = "SELECT DISTINCT room FROM scheduless"; // Default: all rooms
}

// Execute the query
$stmt = $conn->query($sql);
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Generate the list of rooms
foreach ($rooms as $room) {
    echo '<a href="#" class="list-group-item list-group-item-action" data-room="' . htmlspecialchars($room['room']) . '">' . htmlspecialchars($room['room']) . '</a>';
}
?>
