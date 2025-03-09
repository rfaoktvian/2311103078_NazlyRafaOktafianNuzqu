<?php
// Include header
include_once 'includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="p-5 mb-4 bg-light rounded-3">
                <div class="container-fluid py-5">
                    <h1 class="display-5 fw-bold">Selamat Datang di Website Saya!</h1>
                    <p class="col-md-8 fs-4">Ini adalah contoh website sederhana menggunakan HTML, CSS, Bootstrap, dan PHP.</p>
                    <a href="about.php" class="btn btn-primary btn-lg">Pelajari Lebih Lanjut</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Fitur 1</h5>
                    <p class="card-text">Deskripsi fitur 1 dari website ini.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Fitur 2</h5>
                    <p class="card-text">Deskripsi fitur 2 dari website ini.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Fitur 3</h5>
                    <p class="card-text">Deskripsi fitur 3 dari website ini.</p>
                </div>
            </div>
        </div>
    </div>
    
    <?php
    // Menampilkan pesan terbaru (opsional)
    if (isset($conn)) {
        $sql = "SELECT * FROM messages ORDER BY created_at DESC LIMIT 3";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            echo '<div class="row mt-4">';
            echo '<div class="col-md-12">';
            echo '<h3>Pesan Terbaru</h3>';
            echo '<div class="list-group">';
            
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="list-group-item">';
                echo '<div class="d-flex w-100 justify-content-between">';
                echo '<h5 class="mb-1">' . htmlspecialchars($row['name']) . '</h5>';
                echo '<small>' . date('d M Y', strtotime($row['created_at'])) . '</small>';
                echo '</div>';
                echo '<p class="mb-1">' . htmlspecialchars($row['message']) . '</p>';
                echo '</div>';
            }
            
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    }
    ?>
</div>

<?php
// Include footer
include_once 'includes/footer.php';
?>