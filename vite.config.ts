import { defineConfig } from "vite";
import { resolve, join } from 'path';

export default defineConfig(({ command }) =>
  command === "build"
    ? {
      publicDir: join("resources", "src", "assets"),
      build: {
        outDir: join("resources", "static"),
        rollupOptions: {
          input: {
            app: resolve(__dirname, "resources", "src", "app.ts"),
          },
          output: {
            entryFileNames: "[name].js",
            chunkFileNames: "[name].js",
            assetFileNames: "[name].[ext]",
          },
        },
      },
    }
    : {}
);
