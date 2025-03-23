<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "car_sales";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) !== TRUE) {
    echo "Error creating database: " . $conn->error;
}

// Select database
$conn->select_db($dbname);

// Create table if not exists
$sql = "CREATE TABLE IF NOT EXISTS customers (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    nomor VARCHAR(20) NOT NULL,
    mobil VARCHAR(50) NOT NULL,
    alamat TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) !== TRUE) {
    echo "Error creating table: " . $conn->error;
}

// Inisialisasi variabel untuk menyimpan nilai input dan error
$id = $nama = $email = $nomor = $mobil = $alamat = "";
$namaErr = $emailErr = $nomorErr = $alamatErr = "";
$operation = "create"; // Default operation

// Process delete request
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM customers WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $success_message = "Data berhasil dihapus!";
    } else {
        $error_message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Process edit request
if (isset($_GET['edit'])) {
    $operation = "edit";
    $id = $_GET['edit'];
    $sql = "SELECT * FROM customers WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row['id'];
        $nama = $row['nama'];
        $email = $row['email'];
        $nomor = $row['nomor'];
        $mobil = $row['mobil'];
        $alamat = $row['alamat'];
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi Nama
    $nama = trim($_POST["nama"]);
    if (empty($nama)) {
        $namaErr = "Nama wajib diisi";
    }

    // Validasi Email
    $email = trim($_POST["email"]);
    if (empty($email)) {
        $emailErr = "Email wajib diisi";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Format email tidak valid";
    }

    // Validasi Nomor Telepon
    $nomor = trim($_POST["nomor"]);
    if (empty($nomor)) {
        $nomorErr = "Nomor Telepon wajib diisi";
    } elseif (!preg_match("/^[0-9]{10,15}$/", $nomor)) {
        $nomorErr = "Nomor Telepon harus berupa 10-15 digit angka";
    }

    // Validasi Alamat
    $alamat = trim($_POST["alamat"]);
    if (empty($alamat)) {
        $alamatErr = "Alamat wajib diisi";
    }

    // Menyimpan pilihan mobil
    $mobil = $_POST["mobil"];
    
    // If no errors, proceed with database operation
    if (empty($namaErr) && empty($emailErr) && empty($nomorErr) && empty($alamatErr)) {
        if (isset($_POST['operation']) && $_POST['operation'] == "edit") {
            // Update existing record
            $id = $_POST['id'];
            $sql = "UPDATE customers SET nama=?, email=?, nomor=?, mobil=?, alamat=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssi", $nama, $email, $nomor, $mobil, $alamat, $id);
            
            if ($stmt->execute()) {
                $success_message = "Data berhasil diperbarui!";
                $operation = "create"; // Reset to create mode
                $id = $nama = $email = $nomor = $mobil = $alamat = ""; // Clear form
            } else {
                $error_message = "Error: " . $stmt->error;
            }
        } else {
            // Insert new record
            $sql = "INSERT INTO customers (nama, email, nomor, mobil, alamat) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $nama, $email, $nomor, $mobil, $alamat);
            
            if ($stmt->execute()) {
                $success_message = "Data berhasil disimpan!";
                $id = $nama = $email = $nomor = $mobil = $alamat = ""; // Clear form
            } else {
                $error_message = "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}

// Fetch all records for display
$sql = "SELECT * FROM customers ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pembelian Mobil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="header">
        <h1><i class="fas fa-car"></i> Sistem Pembelian Mobil</h1>
        <p>Kelola semua pembelian mobil Anda dengan mudah</p>
    </div>

    <div class="container">
        <?php if (isset($success_message)): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
        </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
        </div>
        <?php endif; ?>

        <h2><?php echo $operation == "edit" ? "Edit Data Pembelian" : "Form Pembelian Mobil"; ?></h2>
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <input type="hidden" name="operation" value="<?php echo $operation; ?>">
            <?php if ($operation == "edit"): ?>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <?php endif; ?>

            <div class="form-row">
                <div class="form-col">
                    <div class="form-group">
                        <label for="nama"><i class="fas fa-user"></i> Nama Lengkap:</label>
                        <div class="input-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" id="nama" name="nama" value="<?php echo $nama; ?>" placeholder="Masukkan nama lengkap Anda">
                        </div>
                        <span class="error"><?php echo $namaErr; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> Email:</label>
                        <div class="input-icon">
                            <i class="fas fa-envelope"></i>
                            <input type="text" id="email" name="email" value="<?php echo $email; ?>" placeholder="contoh@email.com">
                        </div>
                        <span class="error"><?php echo $emailErr; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="nomor"><i class="fas fa-phone"></i> Nomor Telepon:</label>
                        <div class="input-icon">
                            <i class="fas fa-phone"></i>
                            <input type="text" id="nomor" name="nomor" value="<?php echo $nomor; ?>" placeholder="Masukkan 10-15 digit nomor">
                        </div>
                        <span class="error"><?php echo $nomorErr; ?></span>
                    </div>
                </div>

                <div class="form-col">
                    <div class="form-group">
                        <label for="mobil"><i class="fas fa-car"></i> Pilih Mobil:</label>
                        <div class="input-icon">
                            <i class="fas fa-car"></i>
                            <select id="mobil" name="mobil">
                                <option value="Sedan" <?php echo ($mobil == "Sedan") ? "selected" : ""; ?>>Sedan</option>
                                <option value="SUV" <?php echo ($mobil == "SUV") ? "selected" : ""; ?>>SUV</option>
                                <option value="Hatchback" <?php echo ($mobil == "Hatchback") ? "selected" : ""; ?>>Hatchback</option>
                                <option value="MPV" <?php echo ($mobil == "MPV") ? "selected" : ""; ?>>MPV</option>
                                <option value="Crossover" <?php echo ($mobil == "Crossover") ? "selected" : ""; ?>>Crossover</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="alamat"><i class="fas fa-map-marker-alt"></i> Alamat Pengiriman:</label>
                        <textarea id="alamat" name="alamat" placeholder="Masukkan alamat lengkap Anda"><?php echo $alamat; ?></textarea>
                        <span class="error"><?php echo $alamatErr; ?></span>
                    </div>
                </div>
            </div>

            <div class="button-container">
                <button type="submit">
                    <i class="fas <?php echo $operation == "edit" ? "fa-save" : "fa-plus-circle"; ?>"></i>
                    <?php echo $operation == "edit" ? "Perbarui Data" : "Beli Mobil"; ?>
                </button>
                <button type="reset" class="reset">
                    <i class="fas fa-undo"></i> Reset
                </button>
            </div>
        </form>
    </div>

    <?php if ($result->num_rows > 0): ?>
    <div class="container">
        <h3>Data Pembelian Mobil</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Nama</th>
                        <th width="15%">Email</th>
                        <th width="12%">Nomor Telepon</th>
                        <th width="10%">Mobil</th>
                        <th width="23%">Alamat</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while ($row = $result->fetch_assoc()): 
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['nomor']); ?></td>
                        <td><?php echo htmlspecialchars($row['mobil']); ?></td>
                        <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="?edit=<?php echo $row['id']; ?>" class="btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="?delete=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <script>
        // Simple client-side validation
        document.querySelector('form').addEventListener('submit', function(e) {
            let hasError = false;
            
            // Email validation
            const email = document.getElementById('email').value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email && !emailRegex.test(email)) {
                document.getElementById('email').nextElementSibling.textContent = "Format email tidak valid";
                hasError = true;
            }
            
            // Phone validation
            const phone = document.getElementById('nomor').value;
            const phoneRegex = /^[0-9]{10,15}$/;
            if (phone && !phoneRegex.test(phone)) {
                document.getElementById('nomor').nextElementSibling.textContent = "Nomor telepon harus 10-15 digit";
                hasError = true;
            }
            
            if (hasError) {
                e.preventDefault();
            }
        });
    </script>
</body>

</html>