import './style.scss'
import hljs from 'highlight.js';
import 'highlight.js/styles/github-dark.css';
import Slider from './slider';

hljs.highlightAll();

window.Slider = new Slider();
