<?php
require_once 'config.php';

// =======================
// TAMBAH / EDIT MENU
// =======================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_menu   = $_POST['nama_menu'];
    $kategori_id = $_POST['kategori_id'];
    $deskripsi   = $_POST['deskripsi'];
    $harga       = $_POST['harga'];
    $status      = $_POST['status'];

    // =======================
    // UPLOAD GAMBAR
    // =======================
    $gambar = '';
    if (!empty($_FILES['gambar']['name'])) {
        $folder = '../uploads/menu/';
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $gambar = 'uploads/menu/' . time() . '_' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['gambar']['tmp_name'], '../' . $gambar);
    }

    // =======================
    // UPDATE
    // =======================
    if (!empty($_POST['edit_id'])) {
        $id = $_POST['edit_id'];
        $update_gambar = $gambar ? ", gambar='$gambar'" : "";

        $query = "UPDATE menu SET 
                    nama_menu='$nama_menu',
                    kategori_id='$kategori_id',
                    deskripsi='$deskripsi',
                    harga='$harga',
                    status='$status'
                    $update_gambar
                  WHERE id='$id'";
        $msg = 'Menu berhasil diupdate!';
    } 
    // =======================
    // INSERT
    // =======================
    else {
        $query = "INSERT INTO menu 
                    (nama_menu, kategori_id, deskripsi, harga, status, gambar) 
                  VALUES 
                    ('$nama_menu', '$kategori_id', '$deskripsi', '$harga', '$status', '$gambar')";
        $msg = 'Menu berhasil ditambahkan!';
    }

    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = $msg;
        header("Location: menu.php");
        exit;
    }
}

// =======================
// HAPUS MENU
// =======================
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $get = mysqli_fetch_assoc(mysqli_query($conn, "SELECT gambar FROM menu WHERE id='$id'"));
    if ($get && file_exists('../' . $get['gambar'])) {
        unlink('../' . $get['gambar']);
    }

    mysqli_query($conn, "DELETE FROM menu WHERE id='$id'");
    $_SESSION['success'] = 'Menu berhasil dihapus!';
    header("Location: menu.php");
    exit;
}

// DATA
$result = mysqli_query($conn, "SELECT m.*, k.nama_kategori FROM menu m 
                               LEFT JOIN kategori k ON m.kategori_id = k.id 
                               ORDER BY m.created_at DESC");
$result_kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kelola Menu</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- === TAMBAHAN STYLE UNTUK FIXED HEADER === -->
<style>
.fixed-top-bar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background: #ffffff;
    border-bottom: 1px solid #ddd;
    z-index: 1050;
    padding: 10px 20px;
}
body {
    padding-top: 70px; /* ruang agar konten tidak ketutup */
}
</style>
</head>
<body>

<!-- ==================================================
     BACK BUTTON FIXED DI ATAS (TAMBAHAN SAJA)
=================================================== -->
<div class="fixed-top-bar d-flex justify-content-between align-items-center">
    <div>
        <a href="index.php" class="btn btn-secondary btn-sm">
            ⬅ Kembali ke Dashboard
        </a>
        <a href="../index.php" class="btn btn-outline-primary btn-sm ms-2">
            🌐 Lihat Website
        </a>
    </div>
    <strong>Admin • Kelola Menu</strong>
</div>
<!-- ================== AKHIR TAMBAHAN ================= -->

<div class="container mt-4">
    <h3>Kelola Menu</h3>

    <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
    <?php endif; ?>

    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalMenu">
        + Tambah Menu
    </button>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>No</th>
            <th>Gambar</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        <?php $no=1; while($m=mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td>
                <?php if ($m['gambar']): ?>
                    <img src="../<?= $m['gambar'] ?>" width="60" class="rounded">
                <?php endif; ?>
            </td>
            <td><?= $m['nama_menu'] ?></td>
            <td><?= $m['nama_kategori'] ?></td>
            <td><?= format_rupiah($m['harga']) ?></td>
            <td><?= $m['status'] ?></td>
            <td>
                <button class="btn btn-warning btn-sm" onclick='editMenu(<?= json_encode($m) ?>)'>Edit</button>
                <a href="?delete=<?= $m['id'] ?>" onclick="return confirm('Hapus?')" class="btn btn-danger btn-sm">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- MODAL -->
<div class="modal fade" id="modalMenu">
<div class="modal-dialog">
<form method="POST" enctype="multipart/form-data" class="modal-content">
<div class="modal-header">
<h5 id="modalTitle">Tambah Menu</h5>
</div>
<div class="modal-body">

<input type="hidden" name="edit_id" id="edit_id">

<input type="text" name="nama_menu" id="nama_menu" class="form-control mb-2" placeholder="Nama Menu" required>

<select name="kategori_id" id="kategori_id" class="form-control mb-2" required>
<option value="">Kategori</option>
<?php mysqli_data_seek($result_kategori,0); while($k=mysqli_fetch_assoc($result_kategori)): ?>
<option value="<?= $k['id'] ?>"><?= $k['nama_kategori'] ?></option>
<?php endwhile; ?>
</select>

<textarea name="deskripsi" id="deskripsi" class="form-control mb-2" placeholder="Deskripsi" required></textarea>

<input type="number" name="harga" id="harga" class="form-control mb-2" placeholder="Harga" required>

<input type="file" name="gambar" class="form-control mb-2">

<select name="status" id="status" class="form-control" required>
<option value="tersedia">Tersedia</option>
<option value="habis">Habis</option>
</select>

</div>
<div class="modal-footer">
<button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
<button class="btn btn-primary">Simpan</button>
</div>
</form>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function editMenu(m){
    document.getElementById('modalTitle').innerText='Edit Menu';
    edit_id.value=m.id;
    nama_menu.value=m.nama_menu;
    kategori_id.value=m.kategori_id;
    deskripsi.value=m.deskripsi;
    harga.value=m.harga;
    status.value=m.status;
    new bootstrap.Modal(document.getElementById('modalMenu')).show();
}
</script>

</body>
</html>
