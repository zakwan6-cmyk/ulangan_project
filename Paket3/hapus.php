<?php
require "koneksi.php"; $id=(int)($_GET['id']??0);
$stmt=$conn->prepare("SELECT foto FROM pasien WHERE id=?");$stmt->bind_param("i",$id);$stmt->execute();$p=$stmt->get_result()->fetch_assoc();
if($p){$del=$conn->prepare("DELETE FROM pasien WHERE id=?");$del->bind_param("i",$id);$del->execute();if($p['foto'] && file_exists("uploads/".$p['foto']))@unlink("uploads/".$p['foto']);}
header("Location:index.php");exit;
?>