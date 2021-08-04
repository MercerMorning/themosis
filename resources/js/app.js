import Echo from "laravel-echo"

const client = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher-js',
    // key: process.env.MIX_PUSHER_APP_KEY,
    key: 'fc88de43995e65622a5c',
    // cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    cluster: 'eu',
    encrypted: true,
});

window.Echo.channel('chat').listen('NewMessageEvent', (e) => {
    alert(123);
})
// window.Echo.private('chat')
//     .listen('NewMessageEvent', (e) => {
//         console.log(e);
//     });

// window.Vue = require('vue');
// window.axios = require('axios');
// window.jQuery = require('jquery');
// import * as $ from 'jquery';
// // TODO this works but I'm not sure how to get the types right
// // @ts-ignore
// import * as select2 from "select2";
//
// import "select2/dist/css/select2.css";
//
//
// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
//
// import Chat from '../components/Chat.vue';
//
// Vue.component('chat-component', Chat);
//
// const app = new Vue({
//     el: '#app',
// });