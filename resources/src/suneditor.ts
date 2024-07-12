import "suneditor/dist/css/suneditor.min.css";
import CodeMirror from "codemirror";
import suneditor from "suneditor";
import lang from "suneditor/src/lang";
import type SunEditor from "suneditor/src/lib/core";
import {
  fontColor,
  formatBlock,
  hiliteColor,
  link,
  list,
  table,
} from "suneditor/src/plugins";

export default class CmsEditor {
  /**
   * Create a new SunEditor instance with the given options.
   *
   * @param element ID or HTMLElement
   * @returns
   */
  public createSimply(element: HTMLElement | string): SunEditor {
    return suneditor.create(element, {
      codeMirror: CodeMirror,
      plugins: [list, table, link, fontColor, hiliteColor, formatBlock],
      buttonList: [
        ["formatBlock"],
        ["bold", "underline", "italic", "strike"],
        ["fontColor", "hiliteColor"],
        ["link", "list", "table"],
        ["codeView"],
      ],
      height: "300px",
      width: "100%",
      lang: lang.ja,
    });
  }
}
