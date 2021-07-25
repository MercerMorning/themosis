
window.Vue = require('vue');

import Chat from '../components/Chat.vue';

Vue.component('chat-component', Chat);

const app = new Vue({
    el: '#app',
});