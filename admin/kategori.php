<?php
// admin/kategori.php
require_once 'config.php';


// Tambah/Edit Kategori
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_kategori = clean_input($_POST['nama_kategori']);
    $deskripsi = clean_input($_POST['deskripsi']);
    
    if (isset($_POST['edit_id']) && $_POST['edit_id']) {
        $id = clean_input($_POST['edit_id']);
        $query = "UPDATE kategori SET nama_kategori='$nama_kategori', deskripsi='$deskripsi' WHERE id='$id'";
        $msg = 'Kategori berhasil diupdate!';
    } else {
        $query = "INSERT INTO kategori (nama_kategori, deskripsi) VALUES ('$nama_kategori', '$deskripsi')";
        $msg = 'Kategori berhasil ditambahkan!';
    }
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = $msg;
        redirect('kategori.php');
    }
}

// Hapus Kategori
if (isset($_GET['delete'])) {
    $id = clean_input($_GET['delete']);
    mysqli_query($conn, "DELETE FROM kategori WHERE id='$id'");
    $_SESSION['success'] = 'Kategori berhasil dihapus!';
    redirect('kategori.php');
}

$result = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #FF6B35;
            --secondary-color: #F7931E;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: linear-gradient(180deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 20px;
            overflow-y: auto;
        }
        .sidebar .nav-link {
            color: white;
            padding: 12px 15px;
            margin-bottom: 5px;
            border-radius: 8px;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
        }
        .main-content {
            margin-left: 250px;
            padding: 30px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4 class="mb-4"><i class="fas fa-utensils"></i> Admin Panel</h4>
        <nav class="nav flex-column">
            <a class="nav-link" href="index.php"><i class="fas fa-home"></i> Dashboard</a>
            <a class="nav-link" href="pesanan.php"><i class="fas fa-shopping-cart"></i> Kelola Pesanan</a>
            <a class="nav-link" href="menu.php"><i class="fas fa-utensils"></i> Kelola Menu</a>
            <a class="nav-link" href="paket.php"><i class="fas fa-box"></i> Kelola Paket</a>
            <a class="nav-link active" href="kategori.php"><i class="fas fa-tags"></i> Kategori</a>
            <a class="nav-link" href="customer.php"><i class="fas fa-users"></i> Customer</a>
            <a class="nav-link" href="../chat.php"><i class="fas fa-comments"></i> Chat</a>
            <a class="nav-link" href="laporan.php"><i class="fas fa-chart-bar"></i> Laporan</a>
            <hr class="bg-white">
            <a class="nav-link" href="../index.php"><i class="fas fa-globe"></i> Lihat Website</a>
            <a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Kelola Kategori</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalKategori">
                <i class="fas fa-plus"></i> Tambah Kategori
            </button>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        while($kat = mysqli_fetch_assoc($result)): 
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><strong><?php echo $kat['nama_kategori']; ?></strong></td>
                            <td><?php echo $kat['deskripsi']; ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="editKategori(<?php echo htmlspecialchars(json_encode($kat)); ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="kategori.php?delete=<?php echo $kat['id']; ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Yakin hapus kategori ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalKategori" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="formKategori">
                    <div class="modal-body">
                        <input type="hidden" name="edit_id" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori *</label>
                            <input type="text" class="form-control" name="nama_kategori" id="nama_kategori" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" id="deskripsi" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editKategori(kat) {
            document.getElementById('modalTitle').textContent = 'Edit Kategori';
            document.getElementById('edit_id').value = kat.id;
            document.getElementById('nama_kategori').value = kat.nama_kategori;
            document.getElementById('deskripsi').value = kat.deskripsi;
            new bootstrap.Modal(document.getElementById('modalKategori')).show();
        }
        
        document.getElementById('modalKategori').addEventListener('hidden.bs.modal', function () {
            document.getElementById('formKategori').reset();
            document.getElementById('edit_id').value = '';
            document.getElementById('modalTitle').textContent = 'Tambah Kategori';
        });
    </script>
</body>
</html>
