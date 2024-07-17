import intersect from "@alpinejs/intersect";
import dialog from "@fylgja/alpinejs-dialog";
import { Style, init as initMasterCss } from "@master/css";
import Alpine from "alpinejs";
import style from "./master.css";
import "./style.css";
import "github-markdown-css/github-markdown-light.css";
import "@splidejs/splide/css";
import Splide from "@splidejs/splide";
import "./codemirror";
import initAlpineJs from "./alpinejs";
import Toastr from "./support/toastr";

// Definition of MasterCSS custom style
Style.extend("classes", style);
initMasterCss();

// Register Toastr
window.Toastr = new Toastr();

// Register Splide
window.Splide = Splide;

// reference: https://dev.to/wtho/get-started-with-alpinejs-and-typescript-4dgf

// Register AlpineJS
window.Alpine = Alpine;

// Register AlpineJS components
// reference: https://www.raymondcamden.com/2022/06/03/image-upload-preview-in-alpinejs
document.addEventListener("alpine:init", initAlpineJs);

Alpine.plugin(dialog);
Alpine.plugin(intersect);
Alpine.start();
