import CodeMirror from "codemirror";

import "codemirror/lib/codemirror.css";
import "codemirror/theme/material.css";
import "codemirror/mode/twig/twig";
import "codemirror/mode/htmlmixed/htmlmixed";
import "codemirror/mode/javascript/javascript";
import "codemirror/addon/edit/matchbrackets";
import "codemirror/addon/edit/closetag";
import "codemirror/addon/selection/active-line";
import "codemirror/addon/display/panel";
import "codemirror/addon/mode/multiplex";
import "codemirror/keymap/sublime";

CodeMirror.defineMode("htmltwig", (config: CodeMirror.EditorConfiguration) => {
  return CodeMirror.multiplexingMode(CodeMirror.getMode(config, "text/html"), {
    open: "{[{#%]",
    close: "[}#%]}",
    mode: CodeMirror.getMode(config, {
      name: "twig",
      base: "text/html",
      htmlMode: true,
    }),
    parseDelimiters: true,
  });
});

window.CodeMirror = CodeMirror;
