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

  Alpine.data<{
    editor?: SunEditor;
    init: () => void;
  }>("editor", () => ({
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
}
