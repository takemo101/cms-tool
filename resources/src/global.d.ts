import Splide from "@splidejs/splide";
import Alpine from "alpinejs";
import CodeMirror from "codemirror";

declare global {
  interface Window {
    Splide: any;
    Alpine: Alpine;
    CodeMirror: CodeMirror;
  }
}
