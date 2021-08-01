
window.Vue = require('vue');
window.axios = require('axios');
window.jQuery = require('jquery');
import * as $ from 'jquery';
// TODO this works but I'm not sure how to get the types right
// @ts-ignore
import * as select2 from "select2";

import "select2/dist/css/select2.css";


window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Chat from '../components/Chat.vue';

Vue.component('chat-component', Chat);

const app = new Vue({
    el: '#app',
});