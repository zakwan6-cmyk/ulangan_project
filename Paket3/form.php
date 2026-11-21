<?php
// Form tambah/edit pasien digunakan oleh tambah.php dan edit.php
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="style.css" rel="stylesheet"><title>Form Pasien</title></head>
<body><nav class="navbar navbar-dark bg-primary"><div class="container"><a class="navbar-brand fw-bold" href="index.php">KlinikZakwan</a></div></nav>
<div class="container py-4"><div class="card shadow-sm form-card"><div class="card-header bg-primary text-white"><h5 class="mb-0"><?= $edit?'Edit':'Tambah' ?> Data Pasien</h5></div><div class="card-body">
<form method="post" action="simpan.php" enctype="multipart/form-data">
<?php if($edit): ?><input type="hidden" name="id" value="<?= (int)$p['id'] ?>"><input type="hidden" name="foto_lama" value="<?= htmlspecialchars($p['foto']??'') ?>"><?php endif; ?>
<div class="row g-3">
<div class="col-md-6"><label class="form-label">Nama Pasien</label><input class="form-control" name="nama_pasien" required value="<?= htmlspecialchars($p['nama_pasien']??'') ?>"></div>
<div class="col-md-6"><label class="form-label">Username</label><input class="form-control" name="username" required value="<?= htmlspecialchars($p['username']??'') ?>"></div>
<div class="col-md-4"><label class="form-label">Usia</label><input class="form-control" type="number" min="0" name="usia" required value="<?= htmlspecialchars($p['usia']??'') ?>"></div>
<div class="col-md-4"><label class="form-label">Alergi Obat</label><input class="form-control" name="alergi_obat" value="<?= htmlspecialchars($p['alergi_obat']??'-') ?>"></div>
<div class="col-md-4"><label class="form-label">Jenis Kelamin</label><select class="form-select" name="jenis_kelamin" required><option value="">-- Pilih --</option><?php foreach(['Laki-laki','Perempuan'] as $jk): ?><option <?= (($p['jenis_kelamin']??'')===$jk)?'selected':'' ?>><?= $jk ?></option><?php endforeach; ?></select></div>
<div class="col-md-6"><label class="form-label">Alamat</label><textarea class="form-control" name="alamat" required rows="2"><?= htmlspecialchars($p['alamat']??'') ?></textarea></div>
<div class="col-md-6"><label class="form-label">No. HP</label><input class="form-control" name="no_hp" required value="<?= htmlspecialchars($p['no_hp']??'') ?>"></div>
<div class="col-12"><label class="form-label">Foto Pasien (jpg/png/webp, maksimal 3 MB)</label><input class="form-control" type="file" name="foto" accept=".jpg,.jpeg,.png,.webp"><?php if($edit && !empty($p['foto'])): ?><small class="text-muted">Foto sebelumnya: <?= htmlspecialchars($p['foto']) ?></small><?php endif; ?></div>
</div><div class="mt-4"><button class="btn btn-primary" type="submit">Simpan Data</button><a href="index.php" class="btn btn-secondary ms-2">Kembali</a></div></form>
</div></div></div></body></html>
