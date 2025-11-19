PROJECT FRONTEND GUIDELINE

1. Struktur Folder
   project/
  │
  ├── assets/
  │   ├── css/
  │   │   ├── base.css
  │   │   ├── layout.css
  │   │   ├── components.css
  │   │   ├── utils.css
  │   │   ├── responsive.css
  │   │   └── pages/
  │   │       ├── landing.css
  │   │       ├── profil.css
  │   │       ├── arsip.css
  │   │       └── lainnya
  │   │
  │   ├── js/
  │   │   ├── main.js
  │   │   ├── navbar.js
  │   │   └── helpers/
  │   │       ├── dom.js
  │   │       ├── event.js
  │   │       └── storage.js
  │   │
  │   ├── img/
  │
  ├── index.html atau index.php
  ├── profil.html atau profil.php
  └── arsip.html atau arsip.php

2. Aturan HTML dan PHP
   - Gunakan atribut data-* sebagai hook JavaScript, bukan class atau id.
   - Tidak diperbolehkan menggunakan inline JavaScript seperti onclick.
   - Struktur HTML harus menggunakan urutan: navbar, sidebar, main.
   - Gunakan class dengan penamaan konsisten untuk layout dan komponen.
   - Setiap halaman wajib menyertakan file CSS secara berurutan:
     1) base.css
     2) utils.css
     3) components.css
     4) layout.css
     5) responsive.css

    Contoh struktur HTML standar:
     <body>
      <nav class="navbar">
          <button data-nav-toggle></button>
      </nav>
       
      <aside class="sidebar" data-sidebar>
          Menu
      </aside>
       
      <main class="main">
          <section class="section">
              Konten
          </section>
      </main>
      
      <script type="module" src="assets/js/main.js"></script>
    </body>


3. CSS Guideline
   3.1 base.css
     File dasar yang berisi:
    - Reset CSS
    - Import font Montserrat dan Poppins, Heading menggunakan font Montserrat, body menggunakan Poppins
    - Warna utama dan sekunder
    - Variabel global seperti spacing, radius, dan shadow
    - Pengaturan font untuk heading dan body

   3.2 layout.css
      Berisi struktur tata letak seperti:
      - Navbar
      - Sidebar
      - Wrapper atau container
      - Grid layout
      - Spacing layout (padding dan margin section)
      - Tidak diperbolehkan menambahkan komponen di sini.

   3.3 components.css
      Berisi komponen reusable seperti:
      - Button
      - Card
      - Table
      - Tag atau badge
      - Input form
      - Modal
      - Alert
      Komponen harus dapat digunakan di semua halaman.
      
      3.4 utils.css
      Berisi helper class yang dipakai untuk kondisi khusus dan styling cepat, seperti:
      .flex
      .flex-center
      .items-center
      .w-full
      .text-center
      .gap-10
      .mt-20
      Utils tidak boleh menggantikan styling utama.
      
      3.5 responsive.css
      Semua aturan mobile dan tablet hanya ditulis di file ini, bukan dicampur dengan file lain.

  4. JavaScript Guideline
     4.1 Struktur utama
        - main.js sebagai pusat inisialisasi modul
        - navbar.js untuk logika navbar dan sidebar
        - theme.js untuk mode terang dan gelap
        - helpers untuk fungsi kecil seperti selector dan event handler
      
      4.2 main.js
        import { initNavbar } from "./navbar.js";
        import { initTheme } from "./theme.js";
        
        document.addEventListener("DOMContentLoaded", () => {
            initNavbar();
            initTheme();
        });
      
      4.3 Aturan JavaScript
        - Gunakan const untuk nilai tetap, let untuk nilai yang berubah.
        - Gunakan arrow function.
        - Semua fitur harus dibuat modular.
        - Jangan memanipulasi style lewat JavaScript secara langsung, gunakan class.
        - Tidak diperbolehkan membuat variabel global.
        - selector harus menggunakan data-* bukan class.
      
  5. Naming Convention
      CSS
      - Layout: navbar, sidebar, main
      - Komponen: btn-primary, card-header
      - Halaman: landing-hero, profil-header
      - State: is-active, is-open, disabled
      
      JS
      - Function: initNavbar, setupFilter
      - File: navbar.js, profile.js
      - Data attribute: data-nav-toggle, data-sidebar
      
      Folder
     - Gunakan nama lowercase
     - Gunakan dash, bukan underscore
      
  7. Quality Standard
      - Semua halaman harus memenuhi syarat berikut:
      - Responsif di perangkat mobile dan tablet
      - Menggunakan variabel warna, bukan warna manual
      - Tidak ada margin atau padding dengan angka acak
      - Komponen harus dapat dipakai ulang
      - Tidak diperbolehkan ada console.log di production
      - Gunakan Prettier untuk formatting
      - Semua gambar harus dioptimasi sebelum digunakan
      
  7. Template HTML Standar
      <!DOCTYPE html>
      <html lang="id">
      <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <link rel="stylesheet" href="assets/css/base.css">
          <link rel="stylesheet" href="assets/css/utils.css">
          <link rel="stylesheet" href="assets/css/components.css">
          <link rel="stylesheet" href="assets/css/layout.css">
          <link rel="stylesheet" href="assets/css/responsive.css">
          <title>Nama Halaman</title>
      </head>
      
      <body>
          <nav class="navbar">
              <button data-nav-toggle></button>
          </nav>
          <aside class="sidebar" data-sidebar>
              Isi Sidebar
          </aside>
          <main class="main">
              <section class="section">
                  Konten
              </section>
          </main>
          <script type="module" src="assets/js/main.js"></script>
      </body>
      </html>
      
