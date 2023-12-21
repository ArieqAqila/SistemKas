import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js', //Plugins
                
                /* Admin or 'Petugas' Page JS Assets */
                'resources/assets/js/index-admin.js',
                'resources/assets/js/user/admin.js',
                'resources/assets/js/user/petugas.js',
                'resources/assets/js/user/warga.js',
                'resources/assets/js/kas/tagihan.js',
                'resources/assets/js/kas/kas-masuk.js',
                'resources/assets/js/kas/kas-keluar.js',
                'resources/assets/js/konten.js',
                'resources/assets/js/kategori.js',

                /* 'Warga' Page JS Assets */
                'resources/assets/js/index-warga.js',
                'resources/assets/js/warga/reset-password.js',

                /* Admin or 'Petugas' Page SCSS Assets */
                'resources/assets/scss/index-admin.scss',
                'resources/assets/scss/login.scss',
                'resources/assets/scss/responsive-admin.scss',

                /* 'Warga' Page SCSS Assets */
                'resources/assets/scss/index-warga.scss',
            ],
            refresh: true,
        }),
    ],
});
