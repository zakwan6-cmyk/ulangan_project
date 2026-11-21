<?php
require "koneksi.php";
$id=(int)($_POST['id']??0);
$nama=trim($_POST['nama_pasien']??''); $username=trim($_POST['username']??'');
$usia=(int)($_POST['usia']??0); $alergi=trim($_POST['alergi_obat']??'-');
$jk=$_POST['jenis_kelamin']??''; $alamat=trim($_POST['alamat']??''); $hp=trim($_POST['no_hp']??'');
$foto=$_POST['foto_lama']??'';
if(isset($_FILES['foto']) && $_FILES['foto']['error']===UPLOAD_ERR_OK){
  if($_FILES['foto']['size']>3*1024*1024){die("Ukuran foto maksimal 3 MB. <a href='index.php'>Kembali</a>");}
  $ext=strtolower(pathinfo($_FILES['foto']['name'],PATHINFO_EXTENSION));
  if(!in_array($ext,['jpg','jpeg','png','webp']))die("Format foto tidak didukung.");
  $new=uniqid('pasien_',true).'.'.$ext;
  if(move_uploaded_file($_FILES['foto']['tmp_name'],"uploads/".$new)){
    if($foto && file_exists("uploads/".$foto)) @unlink("uploads/".$foto);
    $foto=$new;
  }
}
if($id){
$stmt=$conn->prepare("UPDATE pasien SET foto=?,nama_pasien=?,username=?,usia=?,alergi_obat=?,jenis_kelamin=?,alamat=?,no_hp=? WHERE id=?");$stmt->bind_param("sssissssi",$foto,$nama,$username,$usia,$alergi,$jk,$alamat,$hp,$id);
}else{
$stmt=$conn->prepare("INSERT INTO pasien(foto,nama_pasien,username,usia,alergi_obat,jenis_kelamin,alamat,no_hp) VALUES(?,?,?,?,?,?,?,?)");$stmt->bind_param("sssissss",$foto,$nama,$username,$usia,$alergi,$jk,$alamat,$hp);
}
if($stmt->execute()){header("Location:index.php");exit;} echo "Gagal menyimpan: ".htmlspecialchars($stmt->error);
?>