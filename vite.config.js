import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

const enableRemoteFonts = process.env.VITE_REMOTE_FONTS !== '0';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: enableRemoteFonts
                ? [
                      bunny('Instrument Sans', {
                          weights: [400, 500, 600],
                      }),
                  ]
                : [],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
