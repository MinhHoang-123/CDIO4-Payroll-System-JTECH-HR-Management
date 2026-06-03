<?php
header('Content-Type: application/json');
require_once 'connect.php';

$action = $_GET['action'] ?? '';
$data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
}

// --- Lấy danh sách phòng ban ---
if ($action === 'list') {
    $result = $conn->query("SELECT * FROM phong_bans ORDER BY id_phongban DESC");
    $departments = [];

    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Lấy danh sách thành công',
        'data' => $departments
    ]);
    exit;
}

// --- Thêm phòng ban ---
if ($action === 'add') {
    $tenphongban = trim($data['tenphongban'] ?? '');
    $mota = trim($data['mota'] ?? '');

    if (!$tenphongban) {
        echo json_encode(['status' => 'error', 'message' => 'Tên phòng ban không được để trống']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO phong_bans (tenphongban, mota) VALUES (?, ?)");
    $stmt->bind_param("ss", $tenphongban, $mota);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Thêm phòng ban thành công']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi khi thêm phòng ban']);
    }
    exit;
}

// --- Sửa phòng ban ---
if ($action === 'edit') {
    $id = intval($data['id_phongban_edit'] ?? 0); // đổi từ id_phongban sang id_phongban_edit
    $tenphongban = trim($data['tenphongban'] ?? '');
    $mota = trim($data['mota'] ?? '');

    if (!$id || !$tenphongban) {
        echo json_encode(['status' => 'error', 'message' => 'Thiếu thông tin cập nhật']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE phong_bans SET tenphongban = ?, mota = ? WHERE id_phongban = ?");
    $stmt->bind_param("ssi", $tenphongban, $mota, $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Cập nhật phòng ban thành công']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi khi cập nhật']);
    }
    exit;
}


// --- Xoá phòng ban ---
if ($action === 'delete') {
    $id = (int)($data['id_phongban'] ?? 0);

    if (!$id) {
        echo json_encode(['status' => 'error', 'message' => 'Thiếu ID để xoá']);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM phong_bans WHERE id_phongban = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Xoá phòng ban thành công']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi khi xoá']);
    }
    exit;
}

// --- Hành động không hợp lệ ---
echo json_encode(['status' => 'error', 'message' => 'Yêu cầu không hợp lệ']);
exit;
