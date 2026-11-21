<?php
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/Pasien.php';

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
function kembali($pesan)
{
    header('Location: index.php?pesan=' . urlencode($pesan));
    exit;
}

// Hapus pasien.
if (isset($_GET['hapus'])) {
    $stmt = $pdo->prepare('SELECT foto FROM pasien WHERE id = ?');
    $stmt->execute([(int) $_GET['hapus']]);
    $fotoLama = $stmt->fetchColumn();
    $stmt = $pdo->prepare('DELETE FROM pasien WHERE id = ?');
    $stmt->execute([(int) $_GET['hapus']]);
    if ($fotoLama && is_file(__DIR__ . '/uploads/' . basename($fotoLama))) @unlink(__DIR__ . '/uploads/' . basename($fotoLama));
    kembali('hapus');
}

$aksi = $_GET['aksi'] ?? 'daftar';
$id = (int) ($_GET['id'] ?? 0);
$dataEdit = null;
if (in_array($aksi, ['edit', 'detail'], true)) {
    $stmt = $pdo->prepare('SELECT * FROM pasien WHERE id = ?');
    $stmt->execute([$id]);
    $dataEdit = $stmt->fetch();
    if (!$dataEdit) kembali('tidak-ada');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
    $idPost = (int) ($_POST['id'] ?? 0);
    $nama = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $umur = (int) ($_POST['umur'] ?? 0);
    $jk = $_POST['jenis_kelamin'] ?? '';
    $noHp = trim($_POST['no_hp'] ?? '');
    if ($nama === '' || $username === '' || $umur < 1 || !in_array($jk, ['Laki-laki', 'Perempuan'], true) || $noHp === '') kembali('gagal');

    $foto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK || $_FILES['foto']['size'] > 2 * 1024 * 1024) kembali('foto-gagal');
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($_FILES['foto']['tmp_name']);
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        if (!isset($allowed[$mime])) kembali('foto-gagal');
        $foto = 'pasien_' . bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], __DIR__ . '/uploads/' . $foto)) kembali('foto-gagal');
    }

    $pasien = new Pasien($nama, $username, $umur, $jk, $noHp, $foto);
    if ($idPost > 0) {
        $stmt = $pdo->prepare('SELECT foto FROM pasien WHERE id = ?');
        $stmt->execute([$idPost]);
        $fotoLama = $stmt->fetchColumn();
        if ($foto) {
            $stmt = $pdo->prepare('UPDATE pasien SET nama=?, username=?, umur=?, jenis_kelamin=?, no_hp=?, foto=? WHERE id=?');
            $stmt->execute([$pasien->nama, $pasien->username, $pasien->umur, $pasien->jenisKelamin, $pasien->noHp, $foto, $idPost]);
            if ($fotoLama && is_file(__DIR__ . '/uploads/' . basename($fotoLama))) @unlink(__DIR__ . '/uploads/' . basename($fotoLama));
        } else {
            $stmt = $pdo->prepare('UPDATE pasien SET nama=?, username=?, umur=?, jenis_kelamin=?, no_hp=? WHERE id=?');
            $stmt->execute([$pasien->nama, $pasien->username, $pasien->umur, $pasien->jenisKelamin, $pasien->noHp, $idPost]);
        }
        kembali('edit');
    } else {
        $stmt = $pdo->prepare('INSERT INTO pasien (nama,username,umur,jenis_kelamin,no_hp,foto) VALUES (?,?,?,?,?,?)');
        $stmt->execute([$pasien->nama, $pasien->username, $pasien->umur, $pasien->jenisKelamin, $pasien->noHp, $foto]);
        kembali('tambah');
    }
}

$dataPasien = $pdo->query('SELECT * FROM pasien ORDER BY id DESC')->fetchAll();
$pesan = $_GET['pesan'] ?? '';
$pesanTeks = ['tambah' => 'Data pasien berhasil ditambahkan.', 'edit' => 'Data pasien berhasil diperbarui.', 'hapus' => 'Data pasien berhasil dihapus.', 'gagal' => 'Periksa kembali data yang kamu masukkan.', 'foto-gagal' => 'Foto harus JPG, PNG, atau WEBP dan maksimal 2 MB.', 'tidak-ada' => 'Data pasien tidak ditemukan.'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KlinikZakwan - Data Pasien</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <main class="container">
        <header class="page-header">
            <h1>KlinikZakwan</h1>
            <p>Aplikasi Pengelolaan Data Pasien</p>
        </header>
        <?php if ($pesan && isset($pesanTeks[$pesan])): ?><div class="alert <?= in_array($pesan, ['gagal', 'foto-gagal', 'tidak-ada'], true) ? 'alert-error' : '' ?>"><?= e($pesanTeks[$pesan]) ?></div><?php endif; ?>
        <?php if ($aksi === 'tambah' || $aksi === 'edit'): ?>
            <section class="panel form-panel">
                <div class="panel-heading">
                    <h2><?= $aksi === 'edit' ? 'Edit Data Pasien' : 'Tambah Pasien' ?></h2>
                </div>
                <form method="post" enctype="multipart/form-data" class="patient-form"><input type="hidden" name="id" value="<?= e($dataEdit['id'] ?? '') ?>">
                    <div class="form-grid">
                        <div class="form-group"><label for="nama">Nama Pasien</label><input id="nama" name="nama" maxlength="100" required value="<?= e($dataEdit['nama'] ?? '') ?>"></div>
                        <div class="form-group"><label for="username">Username</label><input id="username" name="username" maxlength="50" required value="<?= e($dataEdit['username'] ?? '') ?>"></div>
                        <div class="form-group"><label for="umur">Usia</label><input id="umur" name="umur" type="number" min="1" max="120" required value="<?= e($dataEdit['umur'] ?? '') ?>"></div>
                        <div class="form-group"><label for="jenis_kelamin">Jenis Kelamin</label><select id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="">-- Pilih --</option>
                                <option value="Laki-laki" <?= ($dataEdit['jenis_kelamin'] ?? '') === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="Perempuan" <?= ($dataEdit['jenis_kelamin'] ?? '') === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                            </select></div>
                        <div class="form-group"><label for="no_hp">No. HP</label><input id="no_hp" name="no_hp" type="tel" maxlength="20" required value="<?= e($dataEdit['no_hp'] ?? '') ?>"></div>
                        <div class="form-group"><label for="foto">Foto Pasien (opsional)</label><input id="foto" name="foto" type="file" accept="image/jpeg,image/png,image/webp"><small>Format JPG, PNG, WEBP. Maksimal 2 MB.</small></div>
                    </div>
                    <div class="form-actions"><button class="button" type="submit" name="simpan">Simpan Data</button><a class="button button-cancel" href="index.php">Kembali</a></div>
                </form>
            </section>
        <?php elseif ($aksi === 'detail' && $dataEdit): ?>
            <section class="panel">
                <div class="panel-heading">
                    <h2>Detail Pasien</h2>
                </div>
                <div class="detail-grid">
                    <div><?php if ($dataEdit['foto']): ?><img class="detail-photo" src="uploads/<?= e(rawurlencode($dataEdit['foto'])) ?>" alt="Foto pasien"><?php else: ?><div class="detail-photo photo-placeholder">Belum ada foto</div><?php endif; ?></div>
                    <div>
                        <p><b>Nama Pasien:</b> <?= e($dataEdit['nama']) ?></p>
                        <p><b>Username:</b> <?= e($dataEdit['username']) ?></p>
                        <p><b>Usia:</b> <?= e($dataEdit['umur']) ?></p>
                        <p><b>Jenis Kelamin:</b> <?= e($dataEdit['jenis_kelamin']) ?></p>
                        <p><b>No. HP:</b> <?= e($dataEdit['no_hp']) ?></p>
                    </div>
                </div><a class="button button-cancel" href="index.php">Kembali</a> <a class="button button-edit" href="?aksi=edit&id=<?= e($dataEdit['id']) ?>">Edit</a>
            </section>
        <?php else: ?>
            <a class="button button-add" href="?aksi=tambah">+ Tambah Pasien</a>
            <section class="list-section">
                <h2>Daftar Pasien</h2>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama Pasien</th>
                                <th>Username</th>
                                <th>Usia</th>
                                <th>Jenis Kelamin</th>
                                <th>No. HP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($dataPasien): foreach ($dataPasien as $i => $pasien): ?><tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?php if ($pasien['foto'] && is_file(__DIR__ . '/uploads/' . basename($pasien['foto']))): ?><img class="patient-photo" src="uploads/<?= e(rawurlencode($pasien['foto'])) ?>" alt="Foto <?= e($pasien['nama']) ?>"><?php else: ?><div class="patient-photo photo-placeholder">-</div><?php endif; ?></td>
                                        <td><?= e($pasien['nama']) ?></td>
                                        <td><?= e($pasien['username']) ?></td>
                                        <td><?= e($pasien['umur']) ?></td>
                                        <td><?= e($pasien['jenis_kelamin']) ?></td>
                                        <td><?= e($pasien['no_hp']) ?></td>
                                        <td class="actions"><a class="button button-detail" href="?aksi=detail&id=<?= e($pasien['id']) ?>">Detail</a><a class="button button-edit" href="?aksi=edit&id=<?= e($pasien['id']) ?>">Edit</a><a class="button button-delete" href="?hapus=<?= e($pasien['id']) ?>" onclick="return confirm('Yakin ingin menghapus pasien ini?')">Hapus</a></td>
                                    </tr><?php endforeach;
                                    else: ?><tr>
                                    <td colspan="8" class="empty">Belum ada data pasien. Klik + Tambah Pasien untuk mengisi data.</td>
                                </tr><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        <?php endif; ?>
    </main>
</body>

</html>