import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

const vitePort = Number(process.env.VITE_PORT ?? 5173);
const viteOrigin = process.env.VITE_DEV_SERVER_URL ?? `http://localhost:${vitePort}`;
const viteOriginUrl = new URL(viteOrigin);
const appOrigin = process.env.VITE_CORS_ORIGIN ?? 'http://localhost:8080';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: process.env.VITE_DEV_SERVER_HOST ?? '0.0.0.0',
        port: vitePort,
        strictPort: true,
        origin: viteOrigin,
        cors: {
            origin: [appOrigin, /^http:\/\/localhost(:\d+)?$/],
        },
        hmr: {
            host: viteOriginUrl.hostname,
            port: Number(viteOriginUrl.port) || vitePort,
            protocol: viteOriginUrl.protocol.replace(':', ''),
        },
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
