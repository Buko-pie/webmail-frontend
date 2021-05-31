require('./bootstrap');
import Vue from 'vue';
import axios from 'axios'

// app.component("webmail-component", require("./components/WebmailComponent.vue").default);
// app.component("example-component", require("./components/ExampleComponent.vue").default);


const sidebar = Vue.component("sidebar-component", require("./components/SidebarComponent.vue").default);
const searchbar = Vue.component("searchbar-component", require("./components/SearchBarComponent.vue").default);

const app = new Vue({
  el: '#app',
  components:{
    sidebar,
    searchbar
  },
  methods:{
    toggle_sidebar(){
      this.$refs.sidebarComponent.toggleClick();
    }
  }
});
