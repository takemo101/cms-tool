import Alpine from "alpinejs";
import type SunEditor from "suneditor/src/lib/core";
import { createSimply } from "./suneditor";

// alpine:init event
export default function init() {
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

  type Editor = SunEditor | undefined;

  Alpine.data<{
    editor: Editor;
    init: () => void;
  }>("editor", () => ({
    editor: undefined,
    init() {
      this.$nextTick(() => {
        const editor = createSimply(this.$refs.editor);

        editor.onChange = () => {
          editor.save();
          this.$dispatch("editor:change");
        };

        this.editor = editor;
      });
    },
  }));

  Alpine.data("inputGuide", (maxLength) => ({
    value: "",
    maxLength: maxLength as number,
    init() {
      this.$watch("value", (value) => {
        if (value.length > this.maxLength) {
          this.value = value.substring(0, this.maxLength);
        }
      });
    },
    get guideText() {
      return `${this.value.length.toLocaleString()} / ${this.maxLength.toLocaleString()}`;
    },
  }));
}
