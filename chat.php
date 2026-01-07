<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$is_admin = is_admin();
$selected_user = $_GET['user_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Chat Realtime</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
body{background:#f4f6f9}
.chat-box{height:450px;overflow-y:auto;background:#f8f9fa;padding:15px}
.msg{margin-bottom:10px;display:flex}
.msg.user{justify-content:end}
.msg.admin{justify-content:start}
.bubble{padding:10px 15px;border-radius:15px;max-width:70%}
.user .bubble{background:#FF6B35;color:#fff}
.admin .bubble{background:#fff;border:1px solid #ddd}
</style>
</head>
<body>

<div class="container mt-4">
<h3><i class="fas fa-comments"></i> Chat Realtime</h3>

<?php if ($is_admin): ?>
<form method="GET" class="mb-3">
<select name="user_id" class="form-select" onchange="this.form.submit()" required>
<option value="">-- Pilih Customer --</option>
<?php
$q = mysqli_query($conn,"SELECT id,nama FROM users WHERE role='customer'");
while($u=mysqli_fetch_assoc($q)):
?>
<option value="<?=$u['id']?>" <?=($selected_user==$u['id'])?'selected':''?>>
<?=$u['nama']?>
</option>
<?php endwhile; ?>
</select>
</form>
<?php endif; ?>

<div class="card">
<div class="card-body chat-box" id="chatBox"></div>

<?php if (!$is_admin || $selected_user): ?>
<div class="card-footer">
<form id="chatForm">
<input type="hidden" id="target_user" value="<?=$is_admin?$selected_user:$user_id?>">
<div class="input-group">
<input type="text" id="pesan" class="form-control" placeholder="Ketik pesan..." required>
<button class="btn btn-primary">
<i class="fas fa-paper-plane"></i>
</button>
</div>
</form>
</div>
<?php endif; ?>
</div>
</div>

<script>
const chatBox = document.getElementById('chatBox');
const form = document.getElementById('chatForm');
const pesan = document.getElementById('pesan');
const target = document.getElementById('target_user');

function loadChat(){
    if(!target) return;
    fetch(`chat_fetch.php?user_id=${target.value}`)
    .then(res=>res.text())
    .then(html=>{
        chatBox.innerHTML = html;
        chatBox.scrollTop = chatBox.scrollHeight;
    });
}

if(form){
form.addEventListener('submit',e=>{
    e.preventDefault();
    fetch('chat_send.php',{
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:`pesan=${encodeURIComponent(pesan.value)}&user_id=${target.value}`
    }).then(()=>{
        pesan.value='';
        loadChat();
    });
});
}

setInterval(loadChat,2000);
loadChat();
</script>

</body>
</html>
