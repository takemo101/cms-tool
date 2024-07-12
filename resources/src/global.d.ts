import type Splide from "@splidejs/splide";
import type Alpine from "alpinejs";
import type CodeMirror from "codemirror";
import type CmsEditor from "./suneditor";
import type Toastr from "./support/toastr";

declare global {
  interface Window {
    // biome-ignore lint/suspicious/noExplicitAny: <explanation>
    Splide: any;
    Alpine: Alpine;
    CodeMirror: CodeMirror;
    Toastr: Toastr;
    CmsEditor: CmsEditor;
  }
}
