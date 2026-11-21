<?php
class Pasien { public $nama,$umur,$jk,$keluhan,$tanggal; public function __construct($n,$u,$j,$k,$t){$this->nama=trim($n);$this->umur=(int)$u;$this->jk=$j;$this->keluhan=trim($k);$this->tanggal=$t;} }
