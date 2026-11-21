<?php
// Class Pasien untuk menampung data pasien.
class Pasien
{
    public $nama;
    public $username;
    public $umur;
    public $jenisKelamin;
    public $noHp;
    public $foto;

    public function __construct($nama, $username, $umur, $jenisKelamin, $noHp, $foto = null)
    {
        $this->nama = trim($nama);
        $this->username = trim($username);
        $this->umur = (int) $umur;
        $this->jenisKelamin = $jenisKelamin;
        $this->noHp = trim($noHp);
        $this->foto = $foto;
    }
}
