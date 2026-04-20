# Tailwind CSS Setup - Production Ready

## ⚠️ Warning CDN Tailwind

Saat ini aplikasi menggunakan Tailwind CSS dari CDN:
```html
<script src="https://cdn.tailwindcss.com"></script>
```

**Warning yang muncul di console:**
> "cdn.tailwindcss.com should not be used in production. To use Tailwind CSS in production, install it as a PostCSS plugin or use the Tailwind CLI"

## Status Saat Ini
- ✅ **Development**: CDN approach berfungsi dengan baik, tidak ada masalah fungsionalitas
- ⚠️ **Production**: Perlu setup dengan PostCSS atau Tailwind CLI

## Setup untuk Production (Opsional)

Jika Anda ingin setup Tailwind CSS dengan benar untuk production, ikuti langkah ini:

### 1. Install Dependencies
```bash
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```

### 2. Konfigurasi tailwind.config.js
```javascript
export default {
  content: [
    "./resources/**/*.{blade.php,js,vue}",
    "./app/views/**/*.{blade.php,js}",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
```

### 3. Buat resources/css/app.css
```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```

### 4. Build CSS
```bash
npm run dev    # Development dengan watch
npm run build  # Production minified
```

### 5. Update Layout Files
Ganti `<script src="https://cdn.tailwindcss.com"></script>` dengan:
```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

## Untuk Sekarang
Menggunakan CDN adalah cara tercepat untuk development. Warning di console tidak mempengaruhi fungsionalitas aplikasi.

---
*Instruksi ini akan diterapkan saat aplikasi siap untuk production.*
