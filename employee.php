<?php
session_start();
include 'connect.php';
include 'permission_functions.php';

$page_name = 'employee.php';

// 1. KIỂM TRA ĐĂNG NHẬP
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// 2. KIỂM TRA QUYỀN
if (!checkPermission($user_id, $page_name, 'can_view', $conn)) {
    echo "<!DOCTYPE html><html lang='vi'><head><meta charset='UTF-8'><title>Lỗi</title>
    <style>body { font-family: Arial; text-align: center; padding: 50px; } h1 { color: red; }</style></head>
    <body><h1>LỖI TRUY CẬP</h1><p>Bạn không có quyền xem trang này!</p><a href='index.php'>Quay về Trang chủ</a></body></html>";
    exit();
}

$can_add = checkPermission($user_id, $page_name, 'can_add', $conn);
$can_delete = checkPermission($user_id, $page_name, 'can_delete', $conn);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quản lý nhân viên</title>
<style>
/* =========================================
   GIAO DIỆN FERRARI & BỐ CỤC CHUNG
   ========================================= */
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Arial', sans-serif; display: flex; flex-direction: column; min-height: 100vh; background: #1c1c1c; color: #fff; }
.header { background: linear-gradient(90deg, #ff0000, #900000); color: #fff; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 8px rgba(0,0,0,0.5); }
.header h1 { font-size: 28px; font-weight: bold; display: flex; align-items: center; color: #fff; }
.header .logout-btn { background: #ff0000; border: none; padding: 10px 16px; cursor: pointer; border-radius: 6px; color: #fff; font-weight: bold; transition: 0.3s; text-decoration: none; }
.header .logout-btn:hover { background: #ffcc00; color: #900000; transform: scale(1.05); }
.container { display: flex; flex: 1; }
.sidebar { width: 220px; padding: 20px; background: #900000; border-right: 2px solid #ff0000; }
.sidebar h2 { margin-bottom: 20px; color: #ffcc00; border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 10px; }
.sidebar a { display: block; color: #fff; text-decoration: none; margin: 12px 0; font-weight: bold; padding: 6px 12px; border-radius: 6px; transition: 0.3s; }
.sidebar a:hover { background: #ff0000; color: #fff; }
.sidebar a.active { background: #ffcc00; color: #900000; padding-left: 15px; border-left: 5px solid #fff; box-shadow: 0 0 10px rgba(255, 204, 0, 0.5); }
.main-content { flex: 1; padding: 20px; background: #1c1c1c; overflow: auto; }

/* Filter & Form */
.filter-container { background: #333; padding: 15px; border-radius: 8px; margin-bottom: 20px; display: flex; gap: 15px; align-items: center; border-left: 5px solid #ffcc00; }
.filter-container select { width: auto; min-width: 200px; background: #fff; color: #000; font-weight: bold; }
.filter-container label { font-weight: bold; color: #fff; }
input, select { width: 100%; padding: 8px; margin: 6px 0; color: #000; }
button { padding: 8px 16px; margin-top: 10px; cursor: pointer; background: #ffcc00; color: #900000; border:none; font-weight:bold; border-radius:4px;}
button:hover { background: #ff0000; color: #fff; }

/* Table */
table { width: 100%; border-collapse: collapse; margin-top: 20px; background-color: #fff; color: #000; }
th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
th { background: #bdc3c7; }
.main-content h2 { margin-top: 20px; color: #ffcc00; }

/* === CSS MỚI CHO PAGINATION === */
.pagination { margin-top: 20px; display: flex; justify-content: center; gap: 5px; }
.pagination button { margin-top:0; background: #333; color: #fff; border: 1px solid #444; padding: 8px 12px; cursor: pointer; transition:0.3s; }
.pagination button:hover { background: #ff0000; border-color: #ff0000; }
.pagination button.active { background: #ffcc00; color: #900000; font-weight: bold; border-color: #ffcc00; }
.pagination button:disabled { opacity: 0.5; cursor: not-allowed; }

footer { background: #900000; color: #ffcc00; text-align: center; padding: 12px 0; font-weight: bold; margin-top: auto;}
</style>
</head>
<body>
<div class="header">
    <h1><img src="https://cdn-icons-png.freepik.com/512/6475/6475938.png" style="width:30px; margin-right:10px;"> Quản lý nhân viên</h1>
    <a href="logout.php" class="logout-btn">Đăng xuất</a>
</div>

<div class="container">
<div class="sidebar">
    <h2>Menu</h2>
    <a href="report.php">Báo Cáo</a>
    <a href="profile.php">Tài khoản của tôi</a>
    <a href="chatbot.php">CHATBOT AI</a>
    <a href="index.php">Trang chủ</a>
    <a href="employee.php" class="active">Nhân viên</a>
    <a href="pay.php">Tiền lương</a>
    <a href="right.php">Phân Quyền</a>
    <a href="subsidy.php">Phụ Cấp</a>
    <a href="role.php">Chức Vụ</a>
    <a href="department.php">Phòng Ban</a>
    <a href="attendance.php">Chấm Công</a>
</div>

<div class="main-content">
    <?php if ($can_add): ?>
    <h2>Thêm / Sửa nhân viên</h2>
    <form id="employeeForm" autocomplete="off">
        <label>Họ tên</label><input type="text" name="hoten" required>
        <div style="display:flex; gap:10px;">
            <div style="flex:1"><label>Ngày sinh</label><input type="date" name="ngaysinh" required></div>
            <div style="flex:1"><label>Giới tính</label>
                <select name="gioitinh" required>
                    <option value="">-- Chọn --</option>
                    <option value="nam">Nam</option>
                    <option value="nữ">Nữ</option>
                </select>
            </div>
        </div>
        <label>Địa chỉ</label><input type="text" name="diachi">
        <label>Số điện thoại</label><input type="text" name="sdt" required>
        <label>Email</label><input type="email" name="email" required>
        <label>Ngày vào làm</label><input type="date" name="ngayvaolam" required>
        <label>Phòng ban</label><select name="id_phongban" required id="phongbanSelect"><option value="">-- Chọn phòng ban --</option></select>
        <label>Chức vụ</label><select name="id_chucvu" required id="chucvuSelect"><option value="">-- Chọn chức vụ --</option></select>
        <label>Trạng thái</label><select name="trangthai" required><option value="1">Đang làm</option><option value="0">Nghỉ việc</option></select>
        <label>Mật khẩu</label><input type="password" name="matkhau" required>
        <button type="submit">Thêm nhân viên</button>
    </form>
    <?php endif; ?>

    <div style="margin-top: 30px; display:flex; justify-content:space-between; align-items:flex-end;">
        <h2 style="margin:0;">Danh sách nhân viên</h2>
    </div>

    <div class="filter-container">
        <div>
            <label>Lọc theo Phòng ban:</label>
            <select id="filterPB" onchange="loadEmployees(1)"> <option value="">-- Tất cả phòng ban --</option>
            </select>
        </div>
        <div>
            <label>Lọc theo Chức vụ:</label>
            <select id="filterCV" onchange="loadEmployees(1)">
                <option value="">-- Tất cả chức vụ --</option>
            </select>
        </div>
        <!-- <button onclick="loadEmployees(1)" style="height: fit-content; margin-top: 22px;">
            Tìm kiếm</button> -->
    </div>

    <table id="employeeTable">
        <thead>
            <tr>
                <th>STT</th>
                <th>Họ tên</th>
                <th>Ngày sinh</th>
                <th>Giới tính</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Phòng ban</th>
                <th>Chức vụ</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <div id="pagination" class="pagination"></div>

</div>
</div>

<footer>&copy; 2025 Công ty J-TECH.</footer>

<script>
const CAN_ADD = <?php echo json_encode($can_add); ?>;
const CAN_DELETE = <?php echo json_encode($can_delete); ?>;
let editId = null;
let currentLimit = 10; // Số dòng mỗi trang

document.addEventListener('DOMContentLoaded', () => {
    loadOptions(); 
    loadEmployees(1); // Load trang 1 lúc đầu

    const form = document.getElementById('employeeForm');
    if(form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            data.action = editId ? 'update' : 'create';
            if (editId) data.id_nhanvien = editId;

            const res = await fetch('employee.api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await res.json();
            alert(result.message || (editId ? 'Cập nhật thành công' : 'Thêm thành công'));
            this.reset();
            editId = null;
            this.querySelector('button[type="submit"]').innerText = 'Thêm nhân viên';
            loadEmployees(1); // Load lại trang 1
        });
    }
});

// Hàm Load nhân viên có Phân trang (page mặc định là 1)
async function loadEmployees(page = 1) {
    const pbId = document.getElementById('filterPB').value;
    const cvId = document.getElementById('filterCV').value;
    
    // Gửi thêm tham số &page=...
    let url = `employee.api.php?action=read&page=${page}`;
    if(pbId) url += `&id_phongban=${pbId}`;
    if(cvId) url += `&id_chucvu=${cvId}`;

    const res = await fetch(url);
    const result = await res.json(); // API trả về {data: [], total_pages: 5, ...}
    
    // Xử lý dữ liệu
    const data = result.data; 
    const tbody = document.querySelector('#employeeTable tbody');
    tbody.innerHTML = '';
    
    if(!data || data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="10">Không tìm thấy nhân viên nào phù hợp</td></tr>';
        document.getElementById('pagination').innerHTML = '';
        return;
    }

    let stt = (page - 1) * currentLimit + 1; // Tính STT

    data.forEach(emp => {
        const deleteButton = CAN_DELETE ? `<button style="background:#b30000; color:#fff; padding:4px 8px; font-size:12px;" onclick="deleteEmployee(${emp.id_nhanvien})">Xoá</button>` : '';
        const editButton = CAN_ADD ? `<button style="background:#2980b9; color:#fff; padding:4px 8px; font-size:12px;" onclick="editEmployee(${emp.id_nhanvien})">Sửa</button>` : '';
        
        tbody.innerHTML += `
            <tr>
                <td>${stt++}</td>
                <td>${emp.hoten}</td>
                <td>${emp.ngaysinh}</td>
                <td>${emp.gioitinh}</td>
                <td>${emp.email}</td>
                <td>${emp.sdt}</td>
                <td data-pb="${emp.id_phongban}">${emp.tenphongban}</td>
                <td data-cv="${emp.id_chucvu}">${emp.tenchucvu}</td>
                <td>${emp.trangthai == 1 ? 'Đang làm' : 'Nghỉ việc'}</td>
                <td>
                    ${editButton} ${deleteButton}
                    <div style="display:none;">
                        <span class="dia-chi">${emp.diachi}</span>
                        <span class="ngay-vao-lam">${emp.ngayvaolam}</span>
                    </div>
                </td>
            </tr>
        `;
    });

    // Gọi hàm vẽ phân trang
    renderPagination(result.total_pages, result.current_page);
}

// Hàm vẽ các nút phân trang
function renderPagination(totalPages, currentPage) {
    const paginationDiv = document.getElementById('pagination');
    let html = '';

    if (totalPages > 1) {
        // Nút Trước
        if (currentPage > 1) {
            html += `<button onclick="loadEmployees(${currentPage - 1})">« Trước</button>`;
        }

        // Các nút số trang
        for (let i = 1; i <= totalPages; i++) {
            // Chỉ hiện trang đầu, cuối, và xung quanh trang hiện tại
            if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                let activeClass = (i === currentPage) ? 'active' : '';
                html += `<button class="${activeClass}" onclick="loadEmployees(${i})">${i}</button>`;
            } else if (i === currentPage - 3 || i === currentPage + 3) {
                html += `<button disabled>...</button>`;
            }
        }

        // Nút Sau
        if (currentPage < totalPages) {
            html += `<button onclick="loadEmployees(${currentPage + 1})">Sau »</button>`;
        }
    }
    paginationDiv.innerHTML = html;
}

// Các hàm phụ trợ giữ nguyên
async function loadOptions() {
    const pbSelect = document.getElementById('phongbanSelect');
    const filterPB = document.getElementById('filterPB');
    if (pbSelect || filterPB) {
        const resPB = await fetch('get_phongban.php');
        const pb = await resPB.json();
        pb.forEach(p => {
            const option = `<option value="${p.id_phongban}">${p.tenphongban}</option>`;
            if(pbSelect) pbSelect.innerHTML += option;
            if(filterPB) filterPB.innerHTML += option;
        });
    }
    const cvSelect = document.getElementById('chucvuSelect');
    const filterCV = document.getElementById('filterCV');
    if (cvSelect || filterCV) {
        const resCV = await fetch('get_chucvu.php');
        const cv = await resCV.json();
        cv.forEach(c => {
             const option = `<option value="${c.id_chucvu}">${c.tenchucvu}</option>`;
             if(cvSelect) cvSelect.innerHTML += option;
             if(filterCV) filterCV.innerHTML += option;
        });
    }
}

async function deleteEmployee(id) {
    if (!CAN_DELETE) { alert('Bạn không có quyền xoá!'); return; }
    if (confirm('Bạn có chắc muốn xoá?')) {
        const res = await fetch('employee.api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'delete', id_nhanvien: id })
        });
        const result = await res.json();
        alert(result.message || 'Đã xoá');
        loadEmployees(1); // Load lại trang 1 sau khi xoá
    }
}

function editEmployee(id) {
    editId = id;
    const row = [...document.querySelectorAll('#employeeTable tbody tr')]
        .find(r => r.children[9].querySelector(`button[onclick="editEmployee(${id})"]`));
    
    if (!row) return;

    // Lấy dữ liệu từ bảng (Lưu ý: vị trí cột đã thay đổi do thêm STT)
    document.querySelector('input[name="hoten"]').value = row.children[1].innerText;
    document.querySelector('input[name="ngaysinh"]').value = row.children[2].innerText;
    document.querySelector('select[name="gioitinh"]').value = row.children[3].innerText;
    document.querySelector('input[name="email"]').value = row.children[4].innerText;
    document.querySelector('input[name="sdt"]').value = row.children[5].innerText;
    
    // Các dữ liệu ẩn (Địa chỉ, ngày vào làm) lấy từ thẻ ẩn trong cột hành động
    const hiddenDiv = row.children[9].querySelector('div');
    document.querySelector('input[name="diachi"]').value = hiddenDiv.querySelector('.dia-chi').innerText;
    document.querySelector('input[name="ngayvaolam"]').value = hiddenDiv.querySelector('.ngay-vao-lam').innerText;

    document.querySelector('select[name="id_phongban"]').value = row.children[6].dataset.pb || '';
    document.querySelector('select[name="id_chucvu"]').value = row.children[7].dataset.cv || '';
    document.querySelector('select[name="trangthai"]').value = row.children[8].innerText == 'Đang làm' ? 1 : 0;
    
    document.querySelector('input[name="matkhau"]').required = false;
    document.querySelector('#employeeForm button[type="submit"]').innerText = 'Cập nhật nhân viên';
    document.getElementById('employeeForm').scrollIntoView({ behavior: 'smooth', block: 'center' });
}
</script>
</body>
</html>