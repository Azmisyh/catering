<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<?php
// login.php
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = ($_POST['email']);
    $password = $_POST['password'];
    
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            
            if ($user['role'] == 'admin') {
                header('Location: admin/index.php');
            } else {
                header('Location: index.php');
            }
        } else {
            $error = 'Email atau password salah!';
        }
    } else {
        $error = 'Email atau password salah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Catering Delicious</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #FF6B35, #F7931E);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card login-card">
                    <div class="card-body p-5">
                        <h3 class="text-center mb-4"><i class="fas fa-utensils"></i> Login</h3>
                        
                        <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>
                        </form>
                        
                        <hr>
                        <p class="text-center mb-0">
                            Belum punya akun? <a href="register.php">Daftar Sekarang</a>
                        </p>
                        <p class="text-center mb-0 mt-2">
                            <a href="index.php">Kembali ke Beranda</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
