---
description: 
---

# Workflow: Create Full Feature (Vue + Inertia)

Ketika diminta membuat fitur baru, generate SEMUA file berikut sekaligus tanpa menunggu konfirmasi. Ikuti Laravel Boost rules secara ketat.

## Input yang dibutuhkan
- Nama fitur (contoh: "Order", "Product", "Invoice")
- Domain folder (contoh: "Sales", "Inventory")
- Daftar kolom/field beserta tipenya
- Apakah ada status/tipe yang perlu Enum? (ya/tidak)

## Langkah Generate (sekaligus, urutan ini)

### 1. Migration
- Lokasi: database/migrations/
- UUID sebagai primary key: $table->uuid('id')->primary()
- JANGAN gunakan $table->enum() — gunakan $table->string() untuk kolom status/tipe
- Tambahkan timestamps() dan softDeletes() jika relevan

### 2. Model
- Lokasi: app/Models/[Domain]/[Name].php
- Gunakan HasUuids trait
- Definisikan $fillable
- Gunakan method casts() untuk casting Enum dan tipe primitif
- Buat Local Scopes yang relevan: scopeActive(), scopeFilter(), dll

### 3. Enum (jika ada status/tipe)
- Lokasi: app/Enums/[Name]Status.php atau app/Enums/[Name]Type.php
- PHP 8.1+ backed enum (string)
- Keys TitleCase: Active, Inactive, Pending
- Wajib ada method label(): string — human-readable bahasa Indonesia
- Wajib ada method color(): string — untuk UI (success, warning, danger, info)

### 4. DTO (jika parameter > 4)
- Lokasi: app/DTOs/[Domain]/Create[Name]DTO.php dan Update[Name]DTO.php
- Gunakan readonly properties
- Gunakan PHP 8 constructor promotion
- Nama wajib berakhiran DTO

### 5. Interface
- Lokasi: app/Services/[Domain]/[Name]ServiceInterface.php
- Definisikan method: index(), show(), store(), update(), destroy()
- Gunakan type hint yang ketat, return type Collection/Model/bool

### 6. Service
- Lokasi: app/Services/[Domain]/[Name]Service.php
- implements [Name]ServiceInterface
- Boleh akses Model langsung
- Gunakan DTO sebagai parameter jika field > 4
- Gunakan Local Scope dari Model untuk query
- JANGAN gunakan string interpolation — gunakan whereLike()

### 7. ServiceRegistryProvider
- Daftarkan binding baru di app/Providers/ServiceRegistryProvider.php
- $this->app->bind([Name]ServiceInterface::class, [Name]Service::class)

### 8. FormRequest
- Lokasi: app/Http/Requests/[Domain]/Store[Name]Request.php
- Lokasi: app/Http/Requests/[Domain]/Update[Name]Request.php
- Definisikan rules() yang lengkap dan authorize() return true

### 9. Controller
- Lokasi: app/Http/Controllers/[Domain]/[Name]Controller.php
- Inject [Name]ServiceInterface di constructor (BUKAN Concrete Class)
- ZERO query di controller — semua delegate ke Service
- ZERO business logic — jika ada if untuk logika, pindah ke Service
- Gunakan FormRequest untuk validasi
- Return Inertia::render() untuk response halaman
- Return redirect()->back() atau response()->json() untuk aksi

### 10. Vue Page
- Lokasi: resources/js/Pages/[Domain]/[Name]/Index.vue
- Lokasi: resources/js/Pages/[Domain]/[Name]/Create.vue
- Lokasi: resources/js/Pages/[Domain]/[Name]/Edit.vue
- Gunakan <script setup lang="ts">
- Gunakan useForm dari @inertiajs/vue3 untuk semua form submission
- Gunakan router dari @inertiajs/vue3 untuk navigasi manual
- Import route dari @/actions/... (Wayfinder)
- JANGAN import ref/computed/watch/onMounted — sudah auto-import
- Jika komponen >200 baris, ekstrak logic ke Composable di resources/js/Composables/

### 11. Route
- Tambahkan resource route di routes/web.php
- Route::resource('[name]', [Name]Controller::class)
- Gunakan middleware yang sesuai

### 12. Pest Feature Test
- Lokasi: tests/Feature/[Domain]/[Name]Test.php
- Test index, show, store, update, destroy
- Gunakan factory + RefreshDatabase
- Test validasi FormRequest (case kosong, case invalid)
- Test otorisasi jika ada middleware

## Aturan Tambahan
- Selalu cek composer.json untuk versi Laravel yang digunakan
- Semua nama class mengikuti PSR-4
- Tidak ada logika bisnis di Controller maupun Vue Page
- Jika ada relasi antar model, tambahkan method relasi di Model dan eager load di Service