require('./bootstrap');
import { createApp } from 'vue';

// const app = createApp({
//     data(){
//         return{
//           toggle_sidebar: true
//         }
//     },
//     mounted() {
//         console.log("Webmail component mounted");
//     }
// });
// app.component("webmail-component", require("./components/WebmailComponent.vue").default);
app.component("example-component", require("./components/ExampleComponent.vue").default);

// app.component("vue-grid-component", require("./components/ExampleComponent.vue").default);

// app.mount("#webmail");
app.mount("#app");