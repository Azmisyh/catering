<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<?php
// register.php
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = ($_POST['nama']);
    $email = ($_POST['email']);
    $telepon = ($_POST['telepon']);
    $alamat = ($_POST['alamat']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Cek email sudah ada
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        $error = 'Email sudah terdaftar!';
    } else {
        $query = "INSERT INTO users (nama, email, telepon, alamat, password) 
                  VALUES ('$nama', '$email', '$telepon', '$alamat', '$password')";
        
        if (mysqli_query($conn, $query)) {
            $success = 'Registrasi berhasil! Silakan login.';
        } else {
            $error = 'Registrasi gagal. Silakan coba lagi.';
        }
    }
    if ($query) {
        header("Location: login.php");
        exit;
    } else {
        echo "Register gagal!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Catering Delicious</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #FF6B35, #F7931E);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 40px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <h3 class="text-center mb-4"><i class="fas fa-user-plus"></i> Daftar Akun</h3>
                        
                        <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="nama" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Telepon</label>
                                <input type="tel" class="form-control" name="telepon" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea class="form-control" name="alamat" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" required minlength="6">
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mb-3">Daftar</button>
                        </form>
                        
                        <hr>
                        <p class="text-center mb-0">
                            Sudah punya akun? <a href="login.php">Login</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>


</html>