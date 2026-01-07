<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) exit;

$pesan = clean_input($_POST['pesan']);
$user_id = clean_input($_POST['user_id']);
$pengirim = is_admin() ? 'admin' : 'user';

mysqli_query($conn,"
INSERT INTO chat (user_id,pesan,pengirim)
VALUES ('$user_id','$pesan','$pengirim')
");
