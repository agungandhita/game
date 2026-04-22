---
description: 
---

# Workflow: Create Full Feature (Livewire + Blade + Tailwind)

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
- Wajib ada method color(): string — return class Tailwind (bg-green-100 text-green-700, dst)

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

### 8. Livewire Form Object
- Lokasi: app/Livewire/Forms/[Name]Form.php
- Extends Livewire\Form
- Definisikan semua public property sesuai field
- Gunakan #[Validate] attribute per property
- Buat method fill() untuk populate data saat edit
- Buat method reset() setelah submit sukses

### 9. Livewire Components
- Lokasi: app/Livewire/[Domain]/[Name]Index.php → halaman list + delete
- Lokasi: app/Livewire/[Domain]/[Name]Form.php → halaman create + edit (reuse 1 component)
- Inject [Name]ServiceInterface di constructor (BUKAN Concrete Class)
- ZERO business logic di component — semua delegate ke Service
- Gunakan #[Locked] untuk property id/uuid
- Gunakan $dispatch untuk trigger event antar component
- Gunakan #[Lazy] untuk komponen list yang berat

### 10. Blade Views
- Lokasi: resources/views/livewire/[domain]/[name]-index.blade.php
- Lokasi: resources/views/livewire/[domain]/[name]-form.blade.php
- Gunakan Tailwind CSS murni untuk styling
- Gunakan wire:model untuk binding form (wire:model.live hanya jika perlu real-time)
- Gunakan wire:click untuk trigger action
- Gunakan wire:loading untuk loading state
- Tampilkan label dan warna dari Enum method label() dan color()
- Komponen UI reusable (button, input, badge, modal) gunakan Blade Component <x-...>

### 11. Blade Components (jika belum ada)
- Lokasi: resources/views/components/
- Buat komponen: x-input, x-button, x-badge, x-modal jika belum tersedia
- Gunakan $attributes->merge() untuk class Tailwind tambahan
- Gunakan {{ $slot }} untuk konten dinamis

### 12. Route
- Tambahkan route di routes/web.php
- Route::get('/[name]', [Name]Index::class)->name('[name].index')
- Route::get('/[name]/create', [Name]Form::class)->name('[name].create')
- Route::get('/[name]/{id}/edit', [Name]Form::class)->name('[name].edit')
- Gunakan middleware yang sesuai

### 13. Pest Feature Test
- Lokasi: tests/Feature/[Domain]/[Name]Test.php
- Test render halaman index, create, edit (Livewire::test())
- Test action store, update, destroy
- Test validasi Form Object (case kosong, case invalid)
- Test otorisasi jika ada middleware
- Gunakan RefreshDatabase

## Aturan Tambahan
- Selalu cek composer.json untuk versi Laravel dan Livewire yang digunakan
- Semua nama class mengikuti PSR-4
- Tidak ada logika bisnis di Livewire Component maupun Blade view
- Enum color() wajib return Tailwind class string, bukan hex/nama warna
- Jika ada relasi antar model, tambahkan method relasi di Model dan eager load di Service