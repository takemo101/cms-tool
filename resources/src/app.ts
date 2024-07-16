import intersect from "@alpinejs/intersect";
import dialog from "@fylgja/alpinejs-dialog";
import { Style, init } from "@master/css";
import Alpine from "alpinejs";
import style from "./master.css";
import "./style.css";
import "github-markdown-css/github-markdown-light.css";
import "@splidejs/splide/css";
import Splide from "@splidejs/splide";
import "./codemirror";
import type SunEditor from "suneditor/src/lib/core";
import CmsEditor from "./suneditor";
import Toastr from "./support/toastr";

// Definition of MasterCSS custom style
Style.extend("classes", style);
init();

// Register Toastr
window.Toastr = new Toastr();

// Register Splide
window.Splide = Splide;

const cmsEditor = new CmsEditor();

// Register SunEditor
window.CmsEditor = cmsEditor;

// reference: https://dev.to/wtho/get-started-with-alpinejs-and-typescript-4dgf

// Register AlpineJS
window.Alpine = Alpine;
Alpine.plugin(dialog);

// Register AlpineJS components
// reference: https://www.raymondcamden.com/2022/06/03/image-upload-preview-in-alpinejs
document.addEventListener("alpine:init", () => {
  Alpine.data("files", () => ({
    src: "",
    change() {
      const input = this.$refs.input as HTMLInputElement;

      const file = input.files?.[0];

      this.src = file?.name ?? "";
    },
    reset() {
      const input = this.$refs.input as HTMLInputElement;

      input.value = "";

      this.src = "";
    },
    select() {
      const input = this.$refs.input as HTMLInputElement;

      input.click();
    },
  }));

  Alpine.data<{
    editor?: SunEditor;
    init: () => void;
  }>("editor", () => ({
    init() {
      this.$nextTick(() => {
        const editor = cmsEditor.createSimply(this.$refs.editor);

        editor.onChange = () => {
          editor.save();
          this.$dispatch("editor:change");
        };

        this.editor = editor;
      });
    },
  }));
});

Alpine.plugin(intersect);
Alpine.start();
