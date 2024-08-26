import type Slider from "./slider";

declare global {
  interface Window {
    Slider: Slider
  }
}
