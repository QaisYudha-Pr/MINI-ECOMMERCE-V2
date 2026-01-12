import './bootstrap';
import AOS from 'aos';
import 'aos/dist/aos.css';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
AOS.init({
    once: false,
    duration: 800,
});
Alpine.start();
