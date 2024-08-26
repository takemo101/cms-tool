import type Splide from "@splidejs/splide";
import type Alpine from "alpinejs";
import type CodeMirror from "codemirror";
import type CmsEditor from "./suneditor";
import type Toastr from "./support/toastr";

declare global {
  interface Window {
    Splide: typeof Splide;
    Alpine: typeof Alpine;
    CodeMirror: CodeMirror;
    Toastr: Toastr;
    lazyImage: (el: HTMLImageElement) => void;
  }
}
