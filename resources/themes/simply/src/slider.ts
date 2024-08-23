import "@splidejs/splide/css";
import Splide from "@splidejs/splide";
import type { Options, Splide as SplideType } from "@splidejs/splide";

export default class Slider {
  public mount(target: string | HTMLElement, options: Options = {}): SplideType {
    return new Splide(target, {
      autoplay: true,
      interval: 5000,
      ...options,
    }).mount();
  }
}
