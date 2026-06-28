<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: ../auth/login.php');
    exit();
}

require_once '../config/database.php';

$id = isset($_GET['id']) ? $_GET['id'] : '';

if (!$id) {
    header('Location: index.php');
    exit();
}

$query = "SELECT * FROM mahasiswa WHERE id = $id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    header('Location: index.php');
    exit();
}

$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa - Sistem Informasi Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <i class="bi bi-mortarboard-fill"></i>
                <h3>SIM Kampus</h3>
            </div>

            <nav class="sidebar-nav">
                <a href="../dashboard.php" class="nav-item">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
                <a href="index.php" class="nav-item active">
                    <i class="bi bi-people-fill"></i>
                    <span>Data Mahasiswa</span>
                </a>
                <a href="tambah.php" class="nav-item">
                    <i class="bi bi-person-plus-fill"></i>
                    <span>Tambah Mahasiswa</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="../auth/logout.php" class="nav-item">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Navbar -->
            <nav class="navbar">
                <div class="navbar-content">
                    <h2>Edit Mahasiswa</h2>
                    <div class="navbar-user">
                        <i class="bi bi-person-circle"></i>
                        <span><?php echo ucfirst($_SESSION['username']); ?></span>
                    </div>
                </div>
            </nav>

            <!-- Content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card-table">
                                <div class="table-header mb-4">
                                    <h5>Form Edit Data Mahasiswa</h5>
                                </div>

                                <form action="update.php" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">
                                                <i class="bi bi-card-text"></i> NIM
                                            </label>
                                            <input type="text" name="nim" class="form-control" required value="<?php echo $data['nim']; ?>" readonly>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">
                                                <i class="bi bi-person-fill"></i> Nama
                                            </label>
                                            <input type="text" name="nama" class="form-control" required value="<?php echo $data['nama']; ?>">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">
                                                <i class="bi bi-gender-ambiguous"></i> Jenis Kelamin
                                            </label>
                                            <select name="jenis_kelamin" class="form-control" required>
                                                <option value="Laki-laki" <?php echo ($data['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                                                <option value="Perempuan" <?php echo ($data['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">
                                                <i class="bi bi-book-fill"></i> Jurusan
                                            </label>
                                            <input type="text" name="jurusan" class="form-control" required value="<?php echo $data['jurusan']; ?>">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">
                                                <i class="bi bi-building"></i> Fakultas
                                            </label>
                                            <input type="text" name="fakultas" class="form-control" required value="<?php echo $data['fakultas']; ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">
                                                <i class="bi bi-calendar"></i> Semester
                                            </label>
                                            <select name="semester" class="form-control" required>
                                                <option value="1" <?php echo ($data['semester'] == 1) ? 'selected' : ''; ?>>1</option>
                                                <option value="2" <?php echo ($data['semester'] == 2) ? 'selected' : ''; ?>>2</option>
                                                <option value="3" <?php echo ($data['semester'] == 3) ? 'selected' : ''; ?>>3</option>
                                                <option value="4" <?php echo ($data['semester'] == 4) ? 'selected' : ''; ?>>4</option>
                                                <option value="5" <?php echo ($data['semester'] == 5) ? 'selected' : ''; ?>>5</option>
                                                <option value="6" <?php echo ($data['semester'] == 6) ? 'selected' : ''; ?>>6</option>
                                                <option value="7" <?php echo ($data['semester'] == 7) ? 'selected' : ''; ?>>7</option>
                                                <option value="8" <?php echo ($data['semester'] == 8) ? 'selected' : ''; ?>>8</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">
                                                <i class="bi bi-envelope-fill"></i> Email
                                            </label>
                                            <input type="email" name="email" class="form-control" required value="<?php echo $data['email']; ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">
                                                <i class="bi bi-telephone-fill"></i> No HP
                                            </label>
                                            <input type="text" name="no_hp" class="form-control" required value="<?php echo $data['no_hp']; ?>">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="bi bi-geo-alt-fill"></i> Alamat
                                        </label>
                                        <textarea name="alamat" class="form-control" rows="3" required><?php echo $data['alamat']; ?></textarea>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle"></i> Update
                                        </button>
                                        <a href="index.php" class="btn btn-secondary">
                                            <i class="bi bi-arrow-left"></i> Kembali
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>