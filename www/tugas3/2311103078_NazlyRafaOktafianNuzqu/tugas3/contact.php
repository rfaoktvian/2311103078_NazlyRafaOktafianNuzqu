<?php
// Include konfigurasi database
include_once 'includes/config.php';

// Inisialisasi variabel pesan
$message = '';

// Cek apakah form telah di-submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $msg = mysqli_real_escape_string($conn, $_POST['message']);
    
    // Query untuk menyimpan pesan
    $sql = "INSERT INTO messages (name, email, message) VALUES ('$name', '$email', '$msg')";
    
    if (mysqli_query($conn, $sql)) {
        $message = '<div class="alert alert-success">Pesan Anda berhasil dikirim!</div>';
    } else {
        $message = '<div class="alert alert-danger">Maaf, terjadi kesalahan saat mengirim pesan.</div>';
    }
}

// Include header
include_once 'includes/header.php';
?>

<div class="container mt-4">
    <h2>Hubungi Kami</h2>
    <p>Silakan isi form di bawah ini untuk menghubungi kami.</p>
    
    <?php echo $message; ?>
    
    <div class="row">
        <div class="col-md-6">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Pesan</label>
                    <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Kirim Pesan</button>
            </form>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informasi Kontak</h5>
                    <p class="card-text">
                        <strong>Alamat:</strong> Jalan Contoh No. 123, Jakarta<br>
                        <strong>Email:</strong> info@example.com<br>
                        <strong>Telepon:</strong> (021) 1234-5678
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include_once 'includes/footer.php';
?>