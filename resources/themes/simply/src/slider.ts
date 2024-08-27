import Splide from "@splidejs/splide";
import type { Options, Splide as SplideType } from "@splidejs/splide";

class Slider {
  public mount(target: string | HTMLElement, options: Options = {}): SplideType {
    return new Splide(target, {
      autoplay: true,
      interval: 5000,
      ...options,
    }).mount();
  }
}

window.Slider = new Slider();
