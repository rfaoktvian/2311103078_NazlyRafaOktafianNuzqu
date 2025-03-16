<?php
// Aplikasi Manajemen Buku Sederhana

// Struktur Data menggunakan Array
$daftarBuku = [
    [
        'id' => 1,
        'judul' => 'PHP untuk Pemula',
        'penulis' => 'Ahmad Saputra',
        'tahun' => 2022,
        'kategori' => 'Pemrograman',
        'tersedia' => true
    ],
    [
        'id' => 2,
        'judul' => 'MySQL Dasar',
        'penulis' => 'Budi Santoso',
        'tahun' => 2021,
        'kategori' => 'Database',
        'tersedia' => true
    ],
    [
        'id' => 3,
        'judul' => 'Web Development',
        'penulis' => 'Citra Dewi',
        'tahun' => 2023,
        'kategori' => 'Pemrograman',
        'tersedia' => false
    ],
    [
        'id' => 4,
        'judul' => 'JavaScript Modern',
        'penulis' => 'Deni Kurniawan',
        'tahun' => 2022,
        'kategori' => 'Pemrograman',
        'tersedia' => true
    ],
    [
        'id' => 5,
        'judul' => 'HTML dan CSS',
        'penulis' => 'Eka Putra',
        'tahun' => 2020,
        'kategori' => 'Pemrograman',
        'tersedia' => false
    ]
];

// Function untuk menampilkan semua buku
function tampilkanSemuaBuku($daftarBuku) {
    echo "<h2>Daftar Semua Buku</h2>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Judul</th><th>Penulis</th><th>Tahun</th><th>Kategori</th><th>Status</th></tr>";
    
    // Struktur kontrol foreach untuk iterasi array
    foreach ($daftarBuku as $buku) {
        echo "<tr>";
        echo "<td>" . $buku['id'] . "</td>";
        echo "<td>" . $buku['judul'] . "</td>";
        echo "<td>" . $buku['penulis'] . "</td>";
        echo "<td>" . $buku['tahun'] . "</td>";
        echo "<td>" . $buku['kategori'] . "</td>";
        
        // Struktur kontrol if-else untuk menentukan status
        if ($buku['tersedia']) {
            echo "<td style='color: green;'>Tersedia</td>";
        } else {
            echo "<td style='color: red;'>Dipinjam</td>";
        }
        
        echo "</tr>";
    }
    echo "</table>";
}

// Function untuk mencari buku berdasarkan kategori
function cariBukuBerdasarkanKategori($daftarBuku, $kategori) {
    echo "<h2>Buku Kategori: $kategori</h2>";
    
    // Array untuk menyimpan hasil pencarian
    $hasilPencarian = [];
    
    // Struktur kontrol foreach dan if untuk filter array
    foreach ($daftarBuku as $buku) {
        if (strtolower($buku['kategori']) === strtolower($kategori)) {
            $hasilPencarian[] = $buku;
        }
    }
    
    // Struktur kontrol if-else untuk cek hasil pencarian
    if (count($hasilPencarian) > 0) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Judul</th><th>Penulis</th><th>Tahun</th><th>Status</th></tr>";
        
        foreach ($hasilPencarian as $buku) {
            echo "<tr>";
            echo "<td>" . $buku['id'] . "</td>";
            echo "<td>" . $buku['judul'] . "</td>";
            echo "<td>" . $buku['penulis'] . "</td>";
            echo "<td>" . $buku['tahun'] . "</td>";
            
            // Operator ternary (bentuk singkat dari if-else)
            echo "<td style='color: " . ($buku['tersedia'] ? "green" : "red") . ";'>" . 
                 ($buku['tersedia'] ? "Tersedia" : "Dipinjam") . "</td>";
            
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Tidak ada buku dalam kategori '$kategori'.</p>";
    }
}

// Function untuk statistik buku
function tampilkanStatistikBuku($daftarBuku) {
    echo "<h2>Statistik Buku</h2>";
    
    // Variabel untuk menyimpan statistik
    $totalBuku = count($daftarBuku);
    $bukuTersedia = 0;
    $bukuDipinjam = 0;
    $statistikKategori = [];
    $statistikTahun = [];
    
    // Hitung statistik menggunakan struktur kontrol loop dan if
    foreach ($daftarBuku as $buku) {
        // Hitung status buku
        if ($buku['tersedia']) {
            $bukuTersedia++;
        } else {
            $bukuDipinjam++;
        }
        
        // Hitung buku per kategori
        if (isset($statistikKategori[$buku['kategori']])) {
            $statistikKategori[$buku['kategori']]++;
        } else {
            $statistikKategori[$buku['kategori']] = 1;
        }
        
        // Hitung buku per tahun
        if (isset($statistikTahun[$buku['tahun']])) {
            $statistikTahun[$buku['tahun']]++;
        } else {
            $statistikTahun[$buku['tahun']] = 1;
        }
    }
    
    // Tampilkan hasil statistik
    echo "<p>Total Buku: $totalBuku</p>";
    echo "<p>Buku Tersedia: $bukuTersedia</p>";
    echo "<p>Buku Dipinjam: $bukuDipinjam</p>";
    
    echo "<h3>Jumlah Buku per Kategori:</h3>";
    echo "<ul>";
    // Struktur kontrol foreach untuk array asosiatif
    foreach ($statistikKategori as $kategori => $jumlah) {
        echo "<li>$kategori: $jumlah buku</li>";
    }
    echo "</ul>";
    
    echo "<h3>Jumlah Buku per Tahun:</h3>";
    echo "<ul>";
    // Sort array berdasarkan key (tahun)
    ksort($statistikTahun);
    foreach ($statistikTahun as $tahun => $jumlah) {
        echo "<li>Tahun $tahun: $jumlah buku</li>";
    }
    echo "</ul>";
}

// Function untuk mencari buku berdasarkan kata kunci (judul atau penulis)
function cariBuku($daftarBuku, $katakunci) {
    echo "<h2>Hasil Pencarian: '$katakunci'</h2>";
    
    $hasilPencarian = [];
    $katakunci = strtolower($katakunci);
    
    // Struktur kontrol foreach dan if untuk pencarian
    foreach ($daftarBuku as $buku) {
        // Menggunakan operator OR (||) dan fungsi strpos() untuk mencari kata kunci
        if (strpos(strtolower($buku['judul']), $katakunci) !== false || 
            strpos(strtolower($buku['penulis']), $katakunci) !== false) {
            $hasilPencarian[] = $buku;
        }
    }
    
    // Struktur kontrol if-else untuk cek hasil pencarian
    if (count($hasilPencarian) > 0) {
        echo "<p>Ditemukan " . count($hasilPencarian) . " buku.</p>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Judul</th><th>Penulis</th><th>Tahun</th><th>Kategori</th><th>Status</th></tr>";
        
        foreach ($hasilPencarian as $buku) {
            echo "<tr>";
            echo "<td>" . $buku['id'] . "</td>";
            echo "<td>" . $buku['judul'] . "</td>";
            echo "<td>" . $buku['penulis'] . "</td>";
            echo "<td>" . $buku['tahun'] . "</td>";
            echo "<td>" . $buku['kategori'] . "</td>";
            echo "<td style='color: " . ($buku['tersedia'] ? "green" : "red") . ";'>" . 
                 ($buku['tersedia'] ? "Tersedia" : "Dipinjam") . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Tidak ada buku yang cocok dengan kata kunci '$katakunci'.</p>";
    }
}

// Fungsi untuk menambahkan buku baru (simulasi)
function tambahBuku($daftarBuku, $judul, $penulis, $tahun, $kategori) {
    // Generate ID baru (ID terakhir + 1)
    $idBaru = count($daftarBuku) > 0 ? max(array_column($daftarBuku, 'id')) + 1 : 1;
    
    // Buat array buku baru
    $bukuBaru = [
        'id' => $idBaru,
        'judul' => $judul,
        'penulis' => $penulis,
        'tahun' => $tahun,
        'kategori' => $kategori,
        'tersedia' => true  // Defaultnya tersedia
    ];
    
    // Tambahkan ke array daftarBuku
    $daftarBuku[] = $bukuBaru;
    
    echo "<h2>Buku Berhasil Ditambahkan!</h2>";
    echo "<p>Judul: $judul</p>";
    echo "<p>Penulis: $penulis</p>";
    echo "<p>Tahun: $tahun</p>";
    echo "<p>Kategori: $kategori</p>";
    
    return $daftarBuku;
}

// Simulasi form pencarian (sederhana)
echo "<h1>Aplikasi Manajemen Buku</h1>";
echo "<div style='margin-bottom: 20px;'>";
echo "<form method='get'>";
echo "<input type='text' name='cari' placeholder='Cari judul atau penulis...'>";
echo "<input type='submit' value='Cari'>";
echo "</form>";
echo "</div>";

// Simulasi penambahan buku baru
if (isset($_GET['tambah']) && $_GET['tambah'] == 'true') {
    // Dalam aplikasi nyata, data ini akan diambil dari form
    $daftarBuku = tambahBuku(
        $daftarBuku, 
        'Laravel Framework', 
        'Faisal Rahman', 
        2023, 
        'Pemrograman'
    );
}

// Struktur kontrol untuk menentukan tindakan berdasarkan parameter URL
if (isset($_GET['cari']) && !empty($_GET['cari'])) {
    // Jika ada parameter pencarian
    cariBuku($daftarBuku, $_GET['cari']);
} elseif (isset($_GET['kategori'])) {
    // Jika ada parameter kategori
    cariBukuBerdasarkanKategori($daftarBuku, $_GET['kategori']);
} elseif (isset($_GET['statistik'])) {
    // Jika ingin melihat statistik
    tampilkanStatistikBuku($daftarBuku);
} else {
    // Default: tampilkan semua buku
    tampilkanSemuaBuku($daftarBuku);
}

// Navigasi sederhana
echo "<div style='margin-top: 20px;'>";
echo "<a href='index.php'>Semua Buku</a> | ";
echo "<a href='index.php?kategori=Pemrograman'>Kategori Pemrograman</a> | ";
echo "<a href='index.php?kategori=Database'>Kategori Database</a> | ";
echo "<a href='index.php?statistik=true'>Statistik</a> | ";
echo "<a href='index.php?tambah=true'>Tambah Buku Baru (Simulasi)</a>";
echo "</div>";
?>