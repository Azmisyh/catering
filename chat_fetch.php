<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) exit;

$user_id = clean_input($_GET['user_id']);
$is_admin = is_admin();

if ($is_admin) {
    mysqli_query($conn,"UPDATE chat SET is_read=1 WHERE user_id='$user_id' AND pengirim='user'");
} else {
    mysqli_query($conn,"UPDATE chat SET is_read=1 WHERE user_id='$user_id' AND pengirim='admin'");
}

$q = mysqli_query($conn,"
    SELECT * FROM chat 
    WHERE user_id='$user_id'
    ORDER BY created_at ASC
");

while($c=mysqli_fetch_assoc($q)):
?>
<div class="msg <?=$c['pengirim']?>">
    <div class="bubble"><?=nl2br(htmlspecialchars($c['pesan']))?></div>
</div>
<?php endwhile; ?>
