<?php

// 1. HÀM CŨ: Lấy tất cả dữ liệu (Dùng cho việc Xuất Excel/PDF cần lấy hết)
function getReportData($conn, $thang, $nam) {
    $sql = "
        SELECT 
            nv.hoten,
            l.thang,
            l.nam,
            l.luongcoban,
            l.tongphucap,
            l.tongthuong,
            l.tongphat,
            l.thue,
            l.tongluong
        FROM luongs l
        JOIN nhan_viens nv ON l.id_nhanvien = nv.id_nhanvien
        WHERE l.thang = ? AND l.nam = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $thang, $nam);
    $stmt->execute();
    return $stmt->get_result();
}

// 2. HÀM MỚI: Đếm tổng số bản ghi (Để tính số trang)
function countTotalReportData($conn, $thang, $nam) {
    $sql = "SELECT COUNT(*) as total FROM luongs WHERE thang = ? AND nam = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $thang, $nam);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total'];
}

// 3. HÀM MỚI: Lấy dữ liệu có phân trang (Dùng cho hiển thị web)
function getReportDataPaginated($conn, $thang, $nam, $limit, $offset) {
    $sql = "
        SELECT 
            nv.hoten,
            l.thang,
            l.nam,
            l.luongcoban,
            l.tongphucap,
            l.tongthuong,
            l.tongphat,
            l.thue,
            l.tongluong
        FROM luongs l
        JOIN nhan_viens nv ON l.id_nhanvien = nv.id_nhanvien
        WHERE l.thang = ? AND l.nam = ?
        LIMIT ? OFFSET ?
    ";
    
    // Bind 4 tham số: thang (int), nam (int), limit (int), offset (int)
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $thang, $nam, $limit, $offset);
    $stmt->execute();
    return $stmt->get_result();
}
?>