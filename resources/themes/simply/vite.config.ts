import { defineConfig } from "vite";
import { resolve, join } from 'node:path';

export default defineConfig(({ command }) =>
  command === "build"
    ? {
      publicDir: join("src", "assets"),
      build: {
        outDir: "assets",
        rollupOptions: {
          input: {
            app: resolve(__dirname, "src", "app.ts"),
            slider: resolve(__dirname, "src", "slider.ts"),
            highlight: resolve(__dirname, "src", "highlight.ts"),
          },
          output: {
            entryFileNames: "[name].js",
            chunkFileNames: "vendor/[name].js",
            assetFileNames: "[name].[ext]",
          },
        },
      },
    }
    : {}
);
