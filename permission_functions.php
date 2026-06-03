<?php
// file: permission_functions.php
/**
 * Kiểm tra quyền của người dùng cho một trang và hành động cụ thể.
 *
 * @param int $user_id ID của người dùng (nhân viên) đang đăng nhập.
 * @param string $page Tên file trang (ví dụ: 'employee.php').
 * @param string $action Hành động (ví dụ: 'can_view', 'can_add', 'can_edit', 'can_delete').
 * @param mysqli $conn Đối tượng kết nối MySQL.
 * @return bool Trả về true nếu có quyền, false nếu không.
 */
function checkPermission($user_id, $page, $action, $conn) {
    // 1. Chuẩn bị câu truy vấn: Lấy cột quyền (can_view, can_add,...) cho người dùng và trang.
    // LƯU Ý: $action cần phải khớp với tên cột trong bảng 'permissions'
    $sql = "SELECT {$action} FROM permissions WHERE user_id = ? AND page = ?";

    // 2. Chuẩn bị và thực thi statement
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        // Xử lý lỗi chuẩn bị SQL (có thể ghi log)
        error_log("Prepare failed: " . $conn->error);
        return false;
    }
    
    // 3. Liên kết tham số và thực thi
    $stmt->bind_param("is", $user_id, $page);
    $stmt->execute();
    
    // 4. Lấy kết quả
    $result = $stmt->get_result();
    
    // 5. Kiểm tra và trả về kết quả
    if ($row = $result->fetch_assoc()) {
        // Nếu giá trị cột quyền là 1 (true) thì có quyền
        return (bool)$row[$action];
    }
    
    // Mặc định, nếu không tìm thấy phân quyền nào, coi như không có quyền
    return false;
}
?>