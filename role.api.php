<?php
// role.api.php

header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

// Thông tin kết nối CSDL
$host = "localhost";
$user = "laraveluser";
$password = "LaravelPass123!";
$database = "cdio_db";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Kết nối database thất bại'
    ]);
    exit();
}

switch ($method) {
    case 'GET':
        // Lấy danh sách chức vụ
        $sql = "SELECT id_chucvu, tenchucvu, hesoluong FROM chuc_vus ORDER BY id_chucvu DESC";
        $result = $conn->query($sql);

        $roles = [];
        while ($row = $result->fetch_assoc()) {
            $roles[] = $row;
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Lấy danh sách chức vụ thành công',
            'data' => $roles
        ]);
        break;

    case 'POST':
        // Thêm chức vụ mới
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['tenchucvu']) || !isset($data['hesoluong'])) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'Thiếu tên chức vụ hoặc hệ số lương'
            ]);
            exit();
        }

        $tenchucvu = $conn->real_escape_string($data['tenchucvu']);
        $hesoluong = floatval($data['hesoluong']);

        $sql = "INSERT INTO chuc_vus (tenchucvu, hesoluong) VALUES ('$tenchucvu', $hesoluong)";
        if ($conn->query($sql)) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Thêm chức vụ thành công',
                'id' => $conn->insert_id
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Thêm thất bại'
            ]);
        }
        break;

    case 'PUT':
        // Cập nhật chức vụ
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['id_chucvu']) || !isset($data['tenchucvu']) || !isset($data['hesoluong'])) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'Thiếu thông tin để cập nhật'
            ]);
            exit();
        }

        $id = intval($data['id_chucvu']);
        $tenchucvu = $conn->real_escape_string($data['tenchucvu']);
        $hesoluong = floatval($data['hesoluong']);

        $sql = "UPDATE chuc_vus SET tenchucvu='$tenchucvu', hesoluong=$hesoluong WHERE id_chucvu=$id";
        if ($conn->query($sql)) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Cập nhật thành công'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Cập nhật thất bại'
            ]);
        }
        break;

case 'DELETE':
    // Xoá chức vụ
    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data['id_chucvu'])) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Thiếu ID chức vụ để xoá'
        ]);
        exit();
    }

    $id = intval($data['id_chucvu']);
    $sql = "DELETE FROM chuc_vus WHERE id_chucvu=$id";
    if ($conn->query($sql)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Xoá thành công'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Xoá thất bại'
        ]);
    }
    break;


    default:
        http_response_code(405);
        echo json_encode([
            'status' => 'error',
            'message' => 'Phương thức không hỗ trợ'
        ]);
        break;
}

$conn->close();
?>
