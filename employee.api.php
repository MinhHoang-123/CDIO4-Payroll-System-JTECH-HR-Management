<?php
include 'connect.php';
header("Content-Type: application/json; charset=UTF-8");

// Hàm lấy JSON từ frontend
function getJsonInput() {
    return json_decode(file_get_contents("php://input"), true);
}

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : (getJsonInput()['action'] ?? '');

/* ===========================
   1. LẤY DANH SÁCH NHÂN VIÊN (CÓ PHÂN TRANG & LỌC)
   =========================== */
if ($method === 'GET' || $action === 'read') {
    
    // 1.1. Thiết lập phân trang
    $limit = 10; // Số dòng mỗi trang
    $page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) $page = 1;
    $offset = ($page - 1) * $limit;

    // 1.2. Xây dựng câu điều kiện WHERE (Dùng chung cho cả Đếm và Lấy dữ liệu)
    $whereClause = "WHERE 1=1";

    // Lọc Phòng ban
    if (isset($_GET['id_phongban']) && $_GET['id_phongban'] !== '') {
        $id_pb = intval($_GET['id_phongban']);
        $whereClause .= " AND n.id_phongban = $id_pb";
    }

    // Lọc Chức vụ
    if (isset($_GET['id_chucvu']) && $_GET['id_chucvu'] !== '') {
        $id_cv = intval($_GET['id_chucvu']);
        $whereClause .= " AND n.id_chucvu = $id_cv";
    }

    // 1.3. Query 1: Đếm tổng số bản ghi (Để tính total_pages)
    // Lưu ý: Chỉ cần đếm từ bảng nhan_viens (alias n)
    $sqlCount = "SELECT COUNT(*) as total FROM nhan_viens n $whereClause";
    $resultCount = $conn->query($sqlCount);
    $rowCount = $resultCount->fetch_assoc();
    $totalRecords = $rowCount['total'];
    $totalPages = ceil($totalRecords / $limit);

    // 1.4. Query 2: Lấy dữ liệu chi tiết (Có LIMIT và OFFSET)
    $sql = "SELECT n.*, p.tenphongban, c.tenchucvu 
            FROM nhan_viens n 
            LEFT JOIN phong_bans p ON n.id_phongban = p.id_phongban
            LEFT JOIN chuc_vus c ON n.id_chucvu = c.id_chucvu
            $whereClause
            ORDER BY n.id_nhanvien DESC
            LIMIT $limit OFFSET $offset";

    $result = $conn->query($sql);
    $data = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    // 1.5. Trả về JSON cấu trúc mới (Bao gồm Data và Metadata phân trang)
    echo json_encode([
        'data'          => $data,
        'total_pages'   => $totalPages,
        'current_page'  => $page,
        'total_records' => $totalRecords
    ]);
    exit;
}


// ===================================================================
// Lấy dữ liệu JSON cho các method POST
$inputData = getJsonInput();
$actionPost = $inputData['action'] ?? '';

/* ===========================
   2. THÊM NHÂN VIÊN
   =========================== */
if ($method === 'POST' && $actionPost === 'create') {
    $hoten      = $conn->real_escape_string($inputData['hoten']);
    $ngaysinh   = $conn->real_escape_string($inputData['ngaysinh']);
    $gioitinh   = $conn->real_escape_string($inputData['gioitinh']);
    $email      = $conn->real_escape_string($inputData['email']);
    $sdt        = $conn->real_escape_string($inputData['sdt']);
    $diachi     = $conn->real_escape_string($inputData['diachi']);
    $ngayvaolam = $conn->real_escape_string($inputData['ngayvaolam']);
    $id_phongban= intval($inputData['id_phongban']);
    $id_chucvu  = intval($inputData['id_chucvu']);
    $trangthai  = intval($inputData['trangthai']);
    $matkhau    = password_hash($inputData['matkhau'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO nhan_viens (hoten, ngaysinh, gioitinh, email, sdt, diachi, ngayvaolam, id_phongban, id_chucvu, trangthai, matkhau)
            VALUES ('$hoten', '$ngaysinh', '$gioitinh', '$email', '$sdt', '$diachi', '$ngayvaolam', $id_phongban, $id_chucvu, $trangthai, '$matkhau')";

    if ($conn->query($sql)) echo json_encode(["status"=>"success","message"=>"Thêm thành công"]);
    else echo json_encode(["status"=>"error","message"=>$conn->error]);
    exit;
}


/* ===========================
   3. CẬP NHẬT NHÂN VIÊN
   =========================== */
if ($method === 'POST' && $actionPost === 'update') {

    $id         = intval($inputData['id_nhanvien']);
    $hoten      = $conn->real_escape_string($inputData['hoten']);
    $ngaysinh   = $conn->real_escape_string($inputData['ngaysinh']);
    $gioitinh   = $conn->real_escape_string($inputData['gioitinh']);
    $email      = $conn->real_escape_string($inputData['email']);
    $sdt        = $conn->real_escape_string($inputData['sdt']);
    $diachi     = $conn->real_escape_string($inputData['diachi']);
    $ngayvaolam = $conn->real_escape_string($inputData['ngayvaolam']);
    $id_phongban= intval($inputData['id_phongban']);
    $id_chucvu  = intval($inputData['id_chucvu']);
    $trangthai  = intval($inputData['trangthai'] ?? 1);

    if (!empty($inputData['matkhau'])) {
        $matkhau = password_hash($inputData['matkhau'], PASSWORD_DEFAULT);
        $sql = "UPDATE nhan_viens SET hoten='$hoten', ngaysinh='$ngaysinh', gioitinh='$gioitinh',
                email='$email', sdt='$sdt', diachi='$diachi', ngayvaolam='$ngayvaolam',
                id_phongban=$id_phongban, id_chucvu=$id_chucvu, trangthai=$trangthai,
                matkhau='$matkhau'
                WHERE id_nhanvien=$id";
    } else {
        $sql = "UPDATE nhan_viens SET hoten='$hoten', ngaysinh='$ngaysinh', gioitinh='$gioitinh',
                email='$email', sdt='$sdt', diachi='$diachi', ngayvaolam='$ngayvaolam',
                id_phongban=$id_phongban, id_chucvu=$id_chucvu, trangthai=$trangthai
                WHERE id_nhanvien=$id";
    }

    if ($conn->query($sql)) echo json_encode(["status"=>"success","message"=>"Cập nhật thành công"]);
    else echo json_encode(["status"=>"error","message"=>$conn->error]);
    exit;
}


/* ===========================
   4. XOÁ NHÂN VIÊN
   =========================== */
if ($method === 'POST' && $actionPost === 'delete') {
    $id = intval($inputData['id_nhanvien']);
    if ($conn->query("DELETE FROM nhan_viens WHERE id_nhanvien=$id"))
         echo json_encode(["status"=>"success","message"=>"Xoá thành công"]);
    else echo json_encode(["status"=>"error","message"=>$conn->error]);
    exit;
}


// ===========================
http_response_code(405);
echo json_encode(["status"=>"error","message"=>"Hành động không hợp lệ"]);
$conn->close();
?>