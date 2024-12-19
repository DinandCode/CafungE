<?php
include '../../config/koneksi.php'; // Koneksi ke database

// Periksa apakah kedai_id ada dalam URL
$kedai_id = isset($_COOKIE['kedai_id']) ? intval($_COOKIE['kedai_id']) : 0;

// Validasi kedai_id
if ($kedai_id <= 0) {
    die("Kedai ID tidak valid.");
}

// Query untuk mengambil data berdasarkan kedai_id
$query = "SELECT * FROM produk WHERE kedai_id = $kedai_id";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Kedai</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
        }

        /* Sidebar */
        #sidebar {
            transition: transform 0.3s ease-in-out;
        }

        /* Transisi layout */
        #main-content {
            transition: margin-right 0.3s ease-in-out, width 0.3s ease-in-out;
        }

        /* Gaya Kontainer */
        .menu-card {
            transition: transform 0.3s ease;
        }

        .menu-card:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="relative flex">
        <!-- Sidebar -->
        <div id="sidebar" class="fixed top-0 right-0 w-64 lg:w-72 bg-white h-full p-8 shadow-lg hidden">
            <h3 class="text-xl font-semibold mb-4">Pesanan Saya</h3>
            <div id="pesanan-kosong" class="text-center text-gray-500">
                <i class="fas fa-shopping-cart text-6xl mb-4"></i>
                <p>Belum Ada Pesanan</p>
            </div>
            <ul id="list-pesanan" class="space-y-4 hidden"></ul>
            <button class="w-full px-4 py-2 bg-purple-700 text-white rounded-full mt-4">
                Selesaikan Pesanan
            </button>
        </div>

        <!-- Konten Utama -->
        <div id="main-content" class="w-full lg:w-[calc(100%-0px)] p-8">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl lg:text-4xl font-bold text-purple-700">Kedai Ayam Geprek</h1>
                <div class="ml-4">
                    <button onclick="toggleSidebar()" class="px-4 py-2 bg-purple-700 text-white rounded-full">
                        <i class="fas fa-shopping-cart"></i>
                    </button>
                </div>
            </div>

            <!-- Banner -->
            <div class="bg-purple-700 text-white py-4 px-6 rounded-lg mb-8 text-center">
                <h2 class="text-2xl lg:text-3xl font-semibold">Menu Ayam Geprek</h2>
            </div>

            <!-- Daftar Menu -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="menu-card bg-white rounded-lg shadow-md overflow-hidden p-4">
                        <img src="<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['nama_produk']) ?>" class="w-full h-40 object-cover mb-4">
                        <h3 class="text-lg font-semibold mb-2"><?= htmlspecialchars($row['nama_produk']) ?></h3>
                        <p class="text-gray-500 mb-2">Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
                        <button onclick="tambahPesanan('<?= htmlspecialchars($row['nama_produk']) ?>', <?= $row['harga'] ?>)" class="px-4 py-2 bg-green-500 text-white rounded-full w-full">
                            Tambah Pesanan
                        </button>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <script>
        // Fungsi Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');

            if (sidebar.classList.contains('hidden')) {
                sidebar.classList.remove('hidden');
                mainContent.style.width = "calc(100% - 18rem)";
            } else {
                sidebar.classList.add('hidden');
                mainContent.style.width = "100%";
            }
        }

        // Fungsi Tambah Pesanan
        let pesanan = [];

        function tambahPesanan(namaMenu, harga) {
            pesanan.push({ nama: namaMenu, harga: harga });
            updateSidebar();
        }

        function updateSidebar() {
            const pesananKosong = document.getElementById('pesanan-kosong');
            const listPesanan = document.getElementById('list-pesanan');

            if (pesanan.length > 0) {
                pesananKosong.classList.add('hidden');
                listPesanan.classList.remove('hidden');
                listPesanan.innerHTML = '';

                pesanan.forEach((item, index) => {
                    listPesanan.innerHTML += `
                        <li class="flex justify-between items-center p-2 border-b">
                            <span>${item.nama}</span>
                            <span>Rp ${item.harga.toLocaleString()}</span>
                        </li>
                    `;
                });
            }
        }
    </script>
</body>
</html>
