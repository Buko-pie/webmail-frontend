const bootstrap = require('./bootstrap');
import Vue from 'vue';
import axios from 'axios';
import { store } from "./store/store";
import Vueditor from 'vueditor'

import 'vueditor/dist/style/vueditor.min.css'

// console.log(bootstrap.get_token());
const sidebar = Vue.component("sidebar-component", require("./components/SidebarComponent.vue").default);
const navbar_buttons = Vue.component("navbar-buttons", require("./components/NavbarBtnsComponent.vue").default);
const searchbar = Vue.component("searchbar-component", require("./components/SearchBarComponent.vue").default);

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

const app = new Vue({
  el: '#app',
  store: store,
  components:{
    sidebar,
    navbar_buttons,
    searchbar,
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
