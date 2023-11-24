import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/assets/js/user/admin.js',
                'resources/assets/js/user/petugas.js',
                'resources/assets/js/user/warga.js',
                'resources/assets/js/kas/tagihan.js',
                'resources/assets/js/kas/kas-masuk.js',
                'resources/assets/js/kas/kas-keluar.js',
                'resources/assets/js/konten.js',
                'resources/assets/scss/index-admin.scss',
                'resources/assets/scss/login.scss',
                'resources/assets/scss/responsive-admin.scss',
            ],
            refresh: true,
        }),
    ],
});
