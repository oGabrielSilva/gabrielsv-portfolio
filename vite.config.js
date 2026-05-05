import tailwindcss from "@tailwindcss/vite";
import fs from "fs";
import laravel from "laravel-vite-plugin";
import path from "path";
import { defineConfig } from "vite";

function getFiles(dir, ignore = []) {
    const fullPath = path.resolve(__dirname, dir);
    if (!fs.existsSync(fullPath)) return [];

    return fs
        .readdirSync(fullPath)
        .filter((file) => file.endsWith(".js") && !ignore.includes(file))
        .map((file) => path.join(dir, file).replace(/\\/g, "/"));
}

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/card-generator.js", // Esse estava fora da pasta tools

                // Injeta automaticamente todos os JS da pasta tools
                ...getFiles("resources/js/tools"),
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ["**/storage/framework/views/**"],
        },
    },
});
