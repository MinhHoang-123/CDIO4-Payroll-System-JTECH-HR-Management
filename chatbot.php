<?php
session_start();
include 'connect.php';
include 'permission_functions.php';

$page_name = 'chatbot.php';
$user_id = $_SESSION['user_id'] ?? 0;

// Kiểm tra đăng nhập
if (!$user_id) {
    header("Location: login.php");
    exit();
}

// Lấy quyền
$can_view   = checkPermission($user_id, $page_name, 'can_view', $conn);
$can_add    = checkPermission($user_id, $page_name, 'can_add', $conn);
$can_delete = checkPermission($user_id, $page_name, 'can_delete', $conn);

if (!$can_view) {
    echo "<h1>Bạn không có quyền truy cập chatbot!</h1>";
    exit();
}

// Hàm tính thuế
function tinhThue($thuNhap){
    if ($thuNhap<=5000000) return $thuNhap*0.05;
    elseif ($thuNhap<=10000000) return $thuNhap*0.1;
    elseif ($thuNhap<=18000000) return $thuNhap*0.15;
    elseif ($thuNhap<=32000000) return $thuNhap*0.2;
    elseif ($thuNhap<=52000000) return $thuNhap*0.25;
    elseif ($thuNhap<=80000000) return $thuNhap*0.3;
    else return $thuNhap*0.35;
}

// Xử lý API chatbot
if (isset($_POST['message'])) {
    $msg = trim($_POST['message']);
    $response = "";

    // ---------------------------
    // XEM DANH SÁCH NHÂN VIÊN
    if (preg_match('/xem danh sách nhân viên/i', $msg)) {
        $res = $conn->query("
            SELECT nv.*, pb.tenphongban, cv.tenchucvu
            FROM nhan_viens nv
            LEFT JOIN phong_bans pb ON nv.id_phongban = pb.id_phongban
            LEFT JOIN chuc_vus cv ON nv.id_chucvu = cv.id_chucvu
        ");
        $rows = [];
        $rows[] = "<table border='1' cellpadding='5' cellspacing='0'>
            <tr>
                <th>ID</th><th>Họ tên</th><th>Ngày sinh</th><th>Giới tính</th><th>Email</th>
                <th>SĐT</th><th>Địa chỉ</th><th>Ngày vào làm</th><th>Phòng ban</th>
                <th>Chức vụ</th><th>Trạng thái</th>
            </tr>";
        while($emp = $res->fetch_assoc()){
            $deleteButton = "<button onclick='alert(\"Xóa NV ID: {$emp['id_nhanvien']}\")'>Xóa</button>";
            $editButton = "<button onclick='alert(\"Sửa NV ID: {$emp['id_nhanvien']}\")'>Sửa</button>";
            $rows[] = "<tr>
                <td>{$emp['id_nhanvien']}</td>
                <td>{$emp['hoten']}</td>
                <td>{$emp['ngaysinh']}</td>
                <td>{$emp['gioitinh']}</td>
                <td>{$emp['email']}</td>
                <td>{$emp['sdt']}</td>
                <td>{$emp['diachi']}</td>
                <td>{$emp['ngayvaolam']}</td>
                <td data-pb='{$emp['id_phongban']}'>{$emp['tenphongban']}</td>
                <td data-cv='{$emp['id_chucvu']}'>{$emp['tenchucvu']}</td>
                <td>".($emp['trangthai']==1?'Đang làm':'Nghỉ việc')."</td>
            
            </tr>";
        }
        $rows[]="</table>";
        $response = implode('', $rows);
    }

    // ---------------------------
    // XEM BẢNG LƯƠNG
    elseif (preg_match('/xem bảng lương/i', $msg)) {
        $res = $conn->query("SELECT nv.id_nhanvien, nv.hoten, cv.tenchucvu, cv.hesoluong 
                             FROM nhan_viens nv
                             JOIN chuc_vus cv ON nv.id_chucvu=cv.id_chucvu");
        $rows = [];
        $rows[]="<table border='1' cellpadding='5' cellspacing='0'>
            <tr>
                <th>NV ID</th><th>Họ tên</th><th>Chức vụ</th><th>Lương cơ bản</th>
                <th>Phụ cấp</th><th>Thưởng</th><th>Phạt</th><th>Tổng lương</th><th>Thuế</th><th>Lương thực nhận</th>
            </tr>";
        while($nv = $res->fetch_assoc()){
            // Tính giờ công
            $cc = $conn->query("SELECT * FROM cham_congs WHERE id_nhanvien={$nv['id_nhanvien']} AND trangthai=1");
            $tong_gio = 0;
            while($c = $cc->fetch_assoc()){
                $tong_gio += round((strtotime($c['giora'])-strtotime($c['giovao']))/3600,2);
            }
            // Phụ cấp
            $pc = $conn->query("SELECT SUM(pc.sotien) AS tongphucap 
                                FROM nhan_vien_phu_caps nvp
                                JOIN phu_caps pc ON nvp.id_phucap=pc.id_phucap
                                WHERE nvp.id_nhanvien={$nv['id_nhanvien']}");
            $tongphucap = $pc->fetch_assoc()['tongphucap'] ?? 0;
            // Thưởng/phạt
            $thuong=0; $phat=0;
            $tp = $conn->query("SELECT loai, SUM(sotien) AS tong FROM thuong_phats WHERE id_nhanvien={$nv['id_nhanvien']} GROUP BY loai");
            while($r=$tp->fetch_assoc()){
                if($r['loai']=='thưởng') $thuong=$r['tong'];
                elseif($r['loai']=='phạt') $phat=$r['tong'];
            }
            $tongLuong = ($tong_gio*$nv['hesoluong']) + $tongphucap + $thuong - $phat;
            $thue = tinhThue($tongLuong);
            $thucnhan = $tongLuong - $thue;

            $rows[]="<tr>
                <td>{$nv['id_nhanvien']}</td>
                <td>{$nv['hoten']}</td>
                <td>{$nv['tenchucvu']}</td>
                <td>{$nv['hesoluong']}</td>
                <td>{$tongphucap}</td>
                <td>{$thuong}</td>
                <td>{$phat}</td>
                <td>{$tongLuong}</td>
                <td>{$thue}</td>
                <td>{$thucnhan}</td>
            </tr>";
        }
        $rows[]="</table>";
        $response = implode('', $rows);
    }

    // ---------------------------
// ---------------------------
// XEM DANH SÁCH CHẤM CÔNG
elseif (stripos($msg,'chấm công') !== false) { // dùng stripos để bỏ qua chữ hoa/thường
    $res = $conn->query("SELECT cc.*, nv.hoten 
                         FROM cham_congs cc 
                         LEFT JOIN nhan_viens nv ON cc.id_nhanvien = nv.id_nhanvien 
                         ORDER BY ngaylamviec DESC");
    $rows = [];
    $rows[] = "<table border='1' cellpadding='5' cellspacing='0'>
        <tr>
            <th>ID CC</th><th>NV ID</th><th>Họ tên</th><th>Ngày</th><th>Giờ vào</th><th>Giờ ra</th><th>Trạng thái</th>
        </tr>";
    while($r = $res->fetch_assoc()){
        $giovao = $r['giovao'] ?: '-';
        $giora = $r['giora'] ?: '-';
        $trangthai = $r['trangthai'] == 1 ? 'Đi làm' : 'Nghỉ phép';

        $editBtn = $can_add ? "<button onclick='alert(\"Sửa CC ID: {$r['id_chamcong']}\")'>Sửa</button>" : '';
        $deleteBtn = $can_delete ? "<button onclick='alert(\"Xóa CC ID: {$r['id_chamcong']}\")'>Xóa</button>" : '';
        $hanhdong = trim("$editBtn $deleteBtn");

        $rows[] = "<tr>
            <td>{$r['id_chamcong']}</td>
            <td>{$r['id_nhanvien']}</td>
            <td>{$r['hoten']}</td>
            <td>{$r['ngaylamviec']}</td>
            <td>{$giovao}</td>
            <td>{$giora}</td>
            <td>{$trangthai}</td>
           
        </tr>";
    }
    $rows[] = "</table>";
    $response = implode('', $rows);
}

 // XEM DANH SÁCH THƯỞNG/PHẠT
elseif (stripos($msg,'xem danh sách thưởng') !== false 
    || stripos($msg,'xem danh sách phạt') !== false 
    || stripis($msg,'thưởng/phạt') !== false) {

    $res = $conn->query("SELECT tp.*, nv.hoten FROM thuong_phats tp 
                         JOIN nhan_viens nv ON tp.id_nhanvien = nv.id_nhanvien
                         ORDER BY tp.ngayapdung DESC");

    $rows = [];
    $rows[] = "<table border='1' cellpadding='6' cellspacing='0' style='border-collapse:collapse;width:100%'>
                <tr style='background:#f2f2f2;font-weight:bold;text-align:center'>
                    <th>Nhân viên</th>
                    <th>Loại</th>
                    <th>Tiêu đề</th>
                    <th>Số tiền</th>
                    <th>Ngày áp dụng</th>
                   
                </tr>";

    while($row = $res->fetch_assoc()) {

        $editBtn   = $can_add    ? "<button onclick='alert(\"Sửa: {$row['id_thuongphat']}\")'>✏ Sửa</button>" : '';
        $deleteBtn = $can_delete ? "<button onclick='alert(\"Xóa: {$row['id_thuongphat']}\")'>🗑 Xóa</button>" : '';

        $rows[] = "<tr>
            <td>{$row['hoten']}</td>
            <td>{$row['loai']}</td>
            <td>{$row['tieude']}</td>
            <td>".number_format($row['sotien'])."</td>
            <td>{$row['ngayapdung']}</td>
            
        </tr>";
    }

    $rows[] = "</table>";
    $response = implode('', $rows);
}

    else $response="Xin lỗi, chưa hiểu lệnh. Vui lòng chọn câu lệnh gợi ý hoặc nhập đúng cú pháp.";

    echo $response;
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<meta charset="UTF-8">
    <h2 class="rainbow-text"><i class="fas fa-robot"></i> Chatbot AI</h2>
<style>
        
        /* Animation đổi màu chữ */
        @keyframes rainbow {
            0% { color: red; }
            14% { color: orange; }
            28% { color: yellow; }
            42% { color: green; }
            57% { color: blue; }
            71% { color: indigo; }
            85% { color: violet; }
            100% { color: red; }
        }

        h2.rainbow-text {
            font-size: 32px;
            font-weight: bold;
            animation: rainbow 3s linear infinite; /* 3s đổi liên tục */
        }

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    /* Background gradient */
    /* background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); */
    /* Hoặc hình nền nếu muốn */
    background: url('456.JPG')no-repeat center center fixed ;
    background-size: cover;
}
.container {
    max-width: 900px;
    margin: 20px auto;
    padding: 20px;
    background: rgba(255,255,255,0.95); /* trong suốt nhẹ */
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0,0,0,0.3);
}

#chatBox{height:400px;overflow-y:auto;border:1px solid #ccc;padding:10px;}
#messageForm{display:flex;margin-top:10px;}
#messageForm input{flex:1;padding:8px;}
#messageForm button{padding:8px 12px;margin-left:5px;}
.message.user{color:blue;margin:5px 0;}
.message.bot{color:green;margin:5px 0;}
.suggest-btn{margin:2px;padding:4px 8px;}
</style>
</head>
<body>
<div class="container">
    

    <!-- Gợi ý câu hỏi -->
    <div id="suggestions" style="margin-bottom:10px;">
        <strong>Gợi ý câu hỏi:</strong>
        <button class="suggest-btn">Xem danh sách nhân viên</button>
        <button class="suggest-btn">Xem bảng lương</button>
        <button class="suggest-btn">Xem danh sách chấm công</button>
        <button class="suggest-btn">Xem danh sách thưởng/phạt</button>
        
    </div>

    <!-- Chat box -->
    <div id="chatBox"></div>

    <!-- Form nhập -->
    <form id="messageForm">
        <input type="text" id="msgInput" placeholder="Nhập câu lệnh..." required>
        <button type="submit">Gửi</button>
    </form>
</div>

<script>
const form=document.getElementById('messageForm');
const input=document.getElementById('msgInput');
const chatBox=document.getElementById('chatBox');
const suggestBtns=document.querySelectorAll('.suggest-btn');

// Khi nhấn nút gợi ý, tự điền input và gửi
suggestBtns.forEach(btn=>{
    btn.addEventListener('click',()=>{
        const msg = btn.textContent;
        input.value = msg;
        form.dispatchEvent(new Event('submit'));
    });
});

form.addEventListener('submit', async (e)=>{
    e.preventDefault();
    const message = input.value.trim();
    if(!message) return;
    appendMessage('user', message);
    input.value='';
    
    const res = await fetch('chatbot.php',{
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:'message='+encodeURIComponent(message)
    });
    const data = await res.text();
    appendMessage('bot', data);
});

function appendMessage(type,text){
    const div=document.createElement('div');
    div.className='message '+type;
    div.innerHTML=text;
    chatBox.appendChild(div);
    chatBox.scrollTop = chatBox.scrollHeight;
}
</script>
</body>
</html>
