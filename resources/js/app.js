const bootstrap = require('./bootstrap');
import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from 'axios';
import { store } from "./store/store";
export const bus = new Vue();
// import { router } from "./router.js";
import Vueditor from "vueditor";
// import WindowPortal from "./components/VueWindowPortal.vue";

import 'vueditor/dist/style/vueditor.min.css'

// console.log(bootstrap.get_token());
const sidebar = Vue.component("sidebar-component", require("./components/SidebarComponent.vue").default);
const navbar_buttons = Vue.component("navbar-buttons", require("./components/NavbarBtnsComponent.vue").default);
const searchbar = Vue.component("searchbar-component", require("./components/SearchBarComponent.vue").default);
const emailFullView = Vue.component("email-full-view", require("./components/EmailFullView.vue").default);
const inboxPagination = Vue.component("inbox-pagination", require("./components/subcomponents/PaginationTemplate.vue").default);
// const WindowPortal = Vue.component("WindowPortal", require("vue-window-portal").default);

const editor_config = {
  spellcheck: true,
  toolbar: [
    "removeFormat",
    "undo",
    "redo",
    "link",
    "|",
    "bold",
    "italic",
    "underline",
    "strikeThrough",
    "|",
    "justifyLeft",
    "justifyCenter",
    "justifyFull",
    "indent",
    "outdent",
    "|",
    "insertOrderedList", 
    "insertUnorderedList",
    "|",
    "subscript",
    "superscript",
    "|",
    "elements",
    "fontName",
    "fontSize",
    "foreColor",
    "backColor",
    "emoji",
    "picture",
    "table",
    "|",
    "sourceCode"
  ],
  fontName: [
    {val: "sans serif"},
    {val: "arial black"},
    {val: "times new roman"},
    {val: "Courier New"}
  ],
  fontSize: [
    "12px",
    "14px",
    "16px",
    "18px",
    "20px",
    "24px",
    "28px",
    "32px",
    "36px"
  ],
  uploadUrl: ""
};


Vue.use(Vueditor, editor_config);
Vue.use(VueRouter);

const router = new VueRouter({
  routes: [
    {
      name: "emailViewFull",
      path: "/emailViewFull/:email_id",
      component: emailFullView,
    }
  ]
});

const app = new Vue({
  el: '#app',
  store: store,
  router: router,
  components:{
    sidebar,
    navbar_buttons,
    searchbar,
    emailFullView,
    inboxPagination,
    // WindowPortal
  },
  methods:{
    toggle_sidebar(){
      this.$refs.sidebarComponent.toggleClick();
    }
  },
  mounted(){
    console.log(store.state.message);
  }
});
