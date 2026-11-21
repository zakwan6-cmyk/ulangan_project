<?php
require "koneksi.php";
$result = $conn->query("SELECT * FROM pasien ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Data Pasien - KlinikZakwan</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
 <div class="container-fluid px-4">
  <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-hospital"></i> KlinikZakwan</a>
  <div class="navbar-nav flex-row gap-3">
   <a class="nav-link active" href="index.php"><i class="bi bi-people"></i> Data Pasien</a>
   <a class="nav-link" href="kunjungan.php"><i class="bi bi-clipboard2-pulse"></i> Data Kunjungan</a>
  </div>
  <span class="navbar-text small ms-auto">Aplikasi KlinikZakwan Paket 3</span>
 </div>
</nav>
<main class="container-fluid px-3 px-md-4 py-4">
<div class="d-flex justify-content-between align-items-center mb-3">
 <div><h3 class="fw-bold mb-1">Data Pasien</h3><small class="text-muted">Daftar pasien KlinikZakwan</small></div>
 <a href="tambah.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Pasien</a>
</div>
<div class="card table-card shadow-sm"><div class="card-body p-3">
<div class="table-responsive">
<table class="table table-bordered table-striped align-middle mb-0">
<thead class="table-primary"><tr><th>No.</th><th>Foto</th><th>Nama Pasien</th><th>Username</th><th>Usia</th><th>Alergi Obat</th><th>Jenis Kelamin</th><th>Alamat</th><th>No. HP</th><th class="text-center">Aksi</th></tr></thead>
<tbody>
<?php if ($result && $result->num_rows): $no=1; while($p=$result->fetch_assoc()): ?>
<tr>
<td><?= $no++ ?></td>
<td><?php if($p['foto'] && file_exists("uploads/".$p['foto'])): ?><img class="foto" src="uploads/<?= htmlspecialchars($p['foto']) ?>" alt="Foto pasien"><?php else: ?><div class="no-foto"><i class="bi bi-person"></i></div><?php endif; ?></td>
<td><?= htmlspecialchars($p['nama_pasien']) ?></td><td><?= htmlspecialchars($p['username']) ?></td><td><?= (int)$p['usia'] ?> tahun</td>
<td><?= htmlspecialchars($p['alergi_obat']) ?></td><td><?= htmlspecialchars($p['jenis_kelamin']) ?></td><td><?= htmlspecialchars($p['alamat']) ?></td><td><?= htmlspecialchars($p['no_hp']) ?></td>
<td class="text-center aksi">
<a class="btn btn-info btn-sm text-white" title="Detail" href="detail.php?id=<?= $p['id'] ?>"><i class="bi bi-eye"></i></a>
<a class="btn btn-warning btn-sm" title="Edit" href="edit.php?id=<?= $p['id'] ?>"><i class="bi bi-pencil-square"></i></a>
<a class="btn btn-danger btn-sm" title="Hapus" href="hapus.php?id=<?= $p['id'] ?>" onclick="return confirm('Yakin ingin menghapus pasien ini?')"><i class="bi bi-trash"></i></a>
</td></tr>
<?php endwhile; else: ?>
<tr><td colspan="10" class="text-center py-4">Belum ada data pasien. Klik <b>Tambah Pasien</b> untuk mengisi data.</td></tr>
<?php endif; ?>
</tbody></table></div></div></div>
</main>
</body></html>
