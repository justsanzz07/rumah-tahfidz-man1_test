# Design Direction: Laporan Rumah Tahfidz MAN 1 Probolinggo

## 1. Identity & Purpose
- **Nama Aplikasi**: Laporan Rumah Tahfidz
- **Institusi**: MAN 1 Probolinggo
- **Karakter**: Islamic Modern Luxury & Clean Clinical Dashboard. Bermartabat, teduh, terpercaya, dan berestetika tinggi.

## 2. Dials
- **ENERGY**: 3 (Kaya detail islami modern: aksen hairline emas, watermark geometris 8-point star, dan kartu terangkat)
- **RHYTHM**: 2 (Keseimbangan visual harmonis antara formulir pencatatan dan tabel rekapitulasi data)
- **MOTION**: 1 (Transisi mikroskopis lembut pada hover kartu, modal pop-in, dan focus ring)

## 3. Color Palette
- **Primary Islamic Forest Green**: `#065443` & `#033c30` (Hijau tua agung yang menenangkan dan berwibawa)
- **Emerald Accent**: `#059669` (Aksen hijau zamrud untuk elemen aktif dan nilai)
- **Warm Gold Accent**: `#c59b27` & `#fbf5df` (Aksen emas islami untuk garis batas atas kartu dan logo)
- **Canvas / Background**: `#f8f6f0` (Kanvas perkamen lembut yang nyaman di mata)
- **Card / Surface**: `#ffffff` (Putih bersih dengan bayangan elevasi halus `var(--shadow-card)`)
- **Ink / Typography**: `#0e2721` & `#2d4740` (Teks kontras tinggi, lolos standar WCAG AA)
- **Muted Text**: `#536c64`
- **Danger**: `#b91c1c` & `#fee2e2` (Merah terukur untuk aksi destruktif dengan konfirmasi)

## 4. Typography
- **Heading & Nilai**: `Fraunces`, serif berkarakter editorial untuk judul utama, emblem nilai, dan inisial avatar siswa.
- **Body & Formulir**: `Plus Jakarta Sans`, sans-serif dengan legibilitas prima pada tabel data dan input teks.

## 5. Visual Hierarchy & Polish
- **Header**: Gradien hijau hutan islami (`#04362b` ke `#065443`) dengan watermark geometris bintang 8-sudut halus (Rub el Hizb tessellation), emblem vektor Al-Qur'an, dan garis dasar emas 3.5px.
- **Kartu Utama**: Dilengkapi aksen garis emas atas (`.gold-edge`) yang memberikan kesan resmi dan berkelas.
- **Tab Segmented Bar**: Navigasi tab administrator berbentuk pil terpadu dengan ikon vektor SVG yang presisi (menggantikan emoji casual).
- **Sub-Tab Switcher**: Toggle elegan antara form manual dan form impor CSV pada halaman manajemen siswa.
- **Statistik Berwarna**: Kartu ringkasan dengan ikon SVG terintegrasi dalam wadah terangkat dan indikator warna tepi atas (Emerald, Cyan, dan Emas).
- **Evaluasi Nilai Interaktif**: Umpan balik langsung saat mengetik nilai (Mumtaz, Jayyid Jiddan, Jayyid, Maqbul, Dhaif) dengan skema warna islami yang memotivasi.
- **Tabel Data**: Zebra striping lembut, avatar inisial siswa bergradien, badge kelas dan jurusan, serta tombol aksi dengan ikon vektor.
- **Aksesibilitas**: Focus ring hijau tua terlihat jelas (`outline: 2px solid #065443`), navigasi keyboard penuh, dan dukungan tombol Escape untuk modal.
