import type Splide from "@splidejs/splide";
import type Alpine from "alpinejs";
import type CodeMirror from "codemirror";

declare global {
  interface Window {
    // biome-ignore lint/suspicious/noExplicitAny: <explanation>
    Splide: any;
    Alpine: Alpine;
    CodeMirror: CodeMirror;
  }
}
