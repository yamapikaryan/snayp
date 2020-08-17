import Vue from 'vue';

import routes from './routes';
import {BadgerAccordion, BadgerAccordionItem} from 'vue-badger-accordion';
import FirstComponent from "./components/FirstComponent";
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import '../sass/app.scss'




// Install BootstrapVue
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)


window.$ = window.jQuery = require('jquery');

window.Popper = require('popper.js').default;

require('bootstrap');

window._ = require('lodash');

Vue.component('BadgerAccordion', BadgerAccordion);
Vue.component('BadgerAccordionItem', BadgerAccordionItem);

/**

 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.


 require('./bootstrap');


 */


let firstComponent = Vue.component('first-component', FirstComponent);

const app = new Vue({
    el: '#app-next',
});
