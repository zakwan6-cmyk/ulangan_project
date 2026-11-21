<?php
require "koneksi.php"; $edit=true; $id=(int)($_GET['id']??0);
$stmt=$conn->prepare("SELECT * FROM pasien WHERE id=?"); $stmt->bind_param("i",$id); $stmt->execute(); $p=$stmt->get_result()->fetch_assoc();
if(!$p){header("Location:index.php");exit;} include "form.php";
?>