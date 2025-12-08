import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

const buildBase = '/proyectodemo/build/';

export default defineConfig({
    base: process.env.NODE_ENV === 'production' ? buildBase : '/',
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/sass/app.scss",
                "resources/js/app.js",
            ],
            refresh: true,
        }),
    ],
});
