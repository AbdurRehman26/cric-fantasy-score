import './bootstrap';

import 'flowbite';
import 'flowbite/dist/datepicker.js';

import.meta.glob([
    '../img/**',
]);

import ApexCharts from 'apexcharts';
window.ApexCharts = ApexCharts;

import htmx from "htmx.org";
window.htmx = htmx;
window.htmx.defineExtension('disable-element', {
    onEvent: function (name, evt) {
        let elt = evt.detail.elt;
        let target = elt.getAttribute("hx-disable-element");
        let targetElements = (target === "self") ? [elt] : document.querySelectorAll(target);

        for (let i = 0; i < targetElements.length; i++) {
            if (name === "htmx:beforeRequest" && targetElements[i]) {
                targetElements[i].disabled = true;
            } else if (name === "htmx:afterRequest" && targetElements[i]) {
                targetElements[i].disabled = false;
            }
        }
    }
});
document.body.addEventListener('htmx:configRequest', (event) => {
    event.detail.headers['X-CSRF-TOKEN'] = document.head.querySelector('meta[name="csrf-token"]').content;
});
document.body.addEventListener('htmx:beforeRequest', (event) => {
    let targetElements = event.target.querySelectorAll('[hx-disable]');
    for (let i = 0; i < targetElements.length; i++) {
        targetElements[i].disabled = true;
    }
});
document.body.addEventListener('htmx:afterRequest', (event) => {
    let targetElements = event.target.querySelectorAll('[hx-disable]');
    for (let i = 0; i < targetElements.length; i++) {
        targetElements[i].disabled = false;
    }
});

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

