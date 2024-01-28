import { init, Style } from '@master/css';
import Alpine from 'alpinejs';
import dialog from '@fylgja/alpinejs-dialog';
import intersect from '@alpinejs/intersect'
import style from './master.css';
import './style.css';
import 'github-markdown-css/github-markdown-light.css';
import '@splidejs/splide/css';
import Splide from '@splidejs/splide';
import './codemirror'

// Definition of MasterCSS custom style
Style.extend('classes', style);
init();

// Register Splide
window.Splide = Splide;

// reference: https://dev.to/wtho/get-started-with-alpinejs-and-typescript-4dgf

// Register AlpineJS
window.Alpine = Alpine;
Alpine.plugin(dialog);

// Register AlpineJS components
// reference: https://www.raymondcamden.com/2022/06/03/image-upload-preview-in-alpinejs
document.addEventListener('alpine:init', () => {
  Alpine.data('files', () => ({
    src: '',
    change() {
      let input = this.$refs.input as HTMLInputElement

      const file = input.files?.[0];

      this.src = file?.name ?? '';
    },
    reset() {
      let input = this.$refs.input as HTMLInputElement

      input.value = '';

      this.src = '';
    },
    select() {
      let input = this.$refs.input as HTMLInputElement

      input.click();
    }
  }))
});

Alpine.plugin(intersect);
Alpine.start();
