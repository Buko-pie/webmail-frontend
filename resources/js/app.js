require('./bootstrap');
import Vue from 'vue';

// app.component("webmail-component", require("./components/WebmailComponent.vue").default);
// app.component("example-component", require("./components/ExampleComponent.vue").default);

Vue.component("vue-grid-component", require("./components/ExampleComponent.vue").default);

const app = new Vue({
    el: '#app'
});