import Splide from "@splidejs/splide";
import Alpine from "alpinejs";

declare global {
  interface Window {
    Splide: any;
    Alpine: Alpine;
  }
}
