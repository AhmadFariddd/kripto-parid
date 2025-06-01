<?php
session_start(); // Start the session
// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Cryptography</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
html{
    scroll-behavior: smooth;
}
body {
    margin: 0;
    padding: 2rem 1rem;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #fff;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
} header {
    text-align: center;
    margin-bottom: 3rem;
} header h1 {
    font-weight: 700;
    font-size: 2.5rem;
    letter-spacing: 3px;
    margin: 0;
    text-transform: uppercase;
} nav {
    margin-bottom: 2rem;
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    justify-content: center;
} nav a {
    text-decoration: none;
    background: #5a3ea0;
    color: white;
    font-weight: 600;
    padding: 0.5rem 1.25rem;
    border-radius: 10px;
    transition: background-color 0.3s ease;
    font-size: 1rem;
    display: inline-block;
} nav a:hover {
    background: #7b57c9;
} main {
    width: 100%;
    max-width: 900px;
  }
  section {
    background: rgba(255,255,255,0.1);
    border-radius: 15px;
    padding: 1.75rem 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
  }
  section h2 {
    margin-top: 0;
    font-weight: 600;
    font-size: 1.8rem;
    letter-spacing: 1.5px;
    margin-bottom: 1rem;
    text-transform: uppercase;
    color: #d3c9ff;
  }
  section p {
    font-size: 1rem;
    line-height: 1.5rem;
    margin-bottom: 1rem;
  }
  a.btn {
    display: inline-block;
    text-decoration: none;
    background: #5a3ea0;
    color: white;
    font-weight: 600;
    padding: 0.5rem 1.25rem;
    border-radius: 10px;
    transition: background-color 0.3s ease;
    font-size: 1rem;
  }
  a.btn:hover {
    background: #7b57c9;
  }
  footer {
    background: rgba(0,0,0,0.25);
    color: #ddd;
    font-size: 0.9rem;
    text-align: center;
    padding: 1rem;
    margin-top: auto;
    width: 100%;
    position: fixed;
    bottom: 0;
    left: 0;
  }
  @media (max-width: 600px) {
    section {
      padding: 1.2rem 1.5rem;
    }
  }
</style>
</head>
<body>
  <header>
    <h1>Cryptography</h1>
    <nav>
        <a href="#vigenere">Vigenère Cipher</a>
        <a href="#affine">Affine Cipher</a>
        <a href="#playfair">Playfair Cipher</a>
        <a href="logout.php">logout</a>
    </nav>
  </header>
  <main>
    <section id="vigenere">
        <h2>Vigenère Cipher</h2>
        <p>
            Vigenere Cipher adalah metode enkripsi klasik yang menggunakan kunci berulang untuk mengenkripsi teks. Mari saya jelaskan dengan detail dan contoh:<br>
            Cara Kerja Vigenere Cipher <br><br> 
            1. Menggunakan tabel Vigenere (kotak 26x26 berisi alfabet yang digeser) <br>
            2. Kunci diulang sepanjang teks yang akan dienkripsi <br>
            3. Setiap huruf dienkripsi berdasarkan pertemuan baris (huruf teks) dan kolom (huruf kunci) <br>
            Untuk dekripsi, proses dibalik: <br>

            1. Cari huruf cipher dalam baris kunci <br>
            2. Huruf asli adalah kolom di mana huruf cipher berada <br>

            Keunggulan dan Kelemahan <br>
            Keunggulan: <br>

            Lebih aman dari Caesar cipher <br>
            Sulit dipecahkan tanpa kunci <br><br>

            Kelemahan: <br>

            Rentan terhadap analisis frekuensi jika kunci pendek <br>
            Pola kunci yang berulang dapat dideteksi      
        </p>
      <a href="vigenere.php" class="btn">Vigenère Cipher</a>
    </section>

    <section id="affine">
        <h2>Affine Cipher</h2>
        <p>
            Affine Cipher adalah metode enkripsi klasik yang menggunakan fungsi matematika linear untuk mengenkripsi setiap huruf. Cipher ini menggabinkan operasi perkalian dan penjumlahan modular.
            Rumus Matematika <br>
            Enkripsi: E(x) = (ax + b) mod 26 <br>
            Dekripsi: D(y) = a⁻¹(y - b) mod 26 <br>
            Dimana: <br>

            x = posisi huruf asli (A=0, B=1, ..., Z=25) <br>
            y = posisi huruf terenkripsi <br>
            a = kunci perkalian (harus coprime dengan 26) <br>
            b = kunci penjumlahan <br>
            a⁻¹ = invers modular dari a <br>
            Syarat Kunci <br>
            Nilai a harus coprime dengan 26, artinya gcd(a,26) = 1. <br>
            Nilai a yang valid: 1, 3, 5, 7, 9, 11, 15, 17, 19, 21, 23, 25 
            <br><br>
            Kelebihan dan Kelemahan <br>
            Kelebihan: <br>

            1. Lebih kuat dari Caesar cipher <br>
            2. Menggunakan dua kunci berbeda <br>
            3. Relatif mudah diimplementasikan <br> <br>

            Kelemahan: <br>

            1. Masih rentan terhadap analisis frekuensi <br>
            2. Hanya ada 312 kemungkinan kunci (12 nilai a × 26 nilai b) <br>
            3. Pola dapat terdeteksi dengan ciphertext yang cukup panjang
        </p>
        <a href="affine.php" class="btn">Affine Cipher</a>
    </section>

    <section id="playfair">
        <h2>Playfair Cipher</h2>
        <p>
            Playfair Cipher adalah metode enkripsi klasik yang dikembangkan oleh Charles Wheatstone pada tahun 1854, tetapi dipopulerkan oleh Lord Playfair. Cipher ini mengenkripsi pasangan huruf (digraph) menggunakan matriks kunci 5×5.
            Karakteristik Utama <br><br>
            Enkripsi Digraph: <br>

            1. Mengenkripsi 2 huruf sekaligus, bukan huruf tunggal <br>
            2. Lebih aman dari cipher substitusi sederhana <br>
            3. Menghilangkan pola frekuensi huruf tunggal <br><br>

            Matriks Kunci 5x5: <br>

            Total 25 sel untuk 26 huruf alfabet <br>
            Biasanya I dan J digabung menjadi satu <br>
            Diisi dengan kata kunci tanpa duplikasi, lalu alfabet sisanya <br><br>

            Tiga Aturan Enkripsi: <br>

            1. Baris sama: Geser ke kanan (wrapping) <br>
            2. Kolom sama: Geser ke bawah (wrapping) <br>
            3. Persegi: Tukar kolom  <br>
            Kelebihan: <br>
            1. Lebih kuat dari cipher monoalfabet <br>
            2. Sulit dipecahkan tanpa kunci <br>
            3. Implementasi relatif mudah <br>
            4. Tidak memerlukan alat khusus <br><br>

            Kelemahan: <br>

            1. Masih rentan analisis frekuensi digraph <br>
            2. Pola dapat terdeteksi pada teks panjang <br>
            3. Hanya ada 26! kemungkinan kunci (terbatas) <br>
            4. Rentan terhadap serangan known-plaintex 
        </p>
        <a href="playfair.php" class="btn">Playfair Cipher</a>
    </section>
  </main>
  <footer>
    &copy; 2024 Ahmad Farid Adhe Riyadi/23080960115
  </footer>
</body>
</html>

