<template>
  <div class="flex">
    <ejs-button iconCss="fas fa-bell" cssClass='e-round shadow-none'></ejs-button>
    <ejs-button iconCss="fas fa-cog" cssClass='e-round shadow-none'></ejs-button>
    <div class="ml-3 relative">
      <div>
        <button @click="userDropdownMenu" type="button" class="bg-gray-800 flex text-sm rounded-full focus:outline-none" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
          <span class="sr-only">Open user menu</span>
          <img class="h-10 w-10 rounded-full" :src="user_profile_photo">
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from "vue";

import { ButtonPlugin } from "@syncfusion/ej2-vue-buttons";
import { TooltipPlugin } from "@syncfusion/ej2-vue-popups";
import { ListViewPlugin } from "@syncfusion/ej2-vue-lists";

Vue.use(ButtonPlugin);
Vue.use(TooltipPlugin);
Vue.use(ListViewPlugin);

export default Vue.extend({
  name: "NavbarBtnsComponent",
  data(){
    return{
      user_profile_photo: null,
    }
  },

  mounted(){
    this.user_profile_photo = this.$store.state.user_profile_photo;
  },

  methods:{
    userDropdownMenu(args){
      let _this = this;
      console.log(this.clientWidth);
      // console.log(args);
      // console.log(args.srcElement.getBoundingClientRect());

      // console.log(this.$store.state.message);
      this.$eventHub.$emit("show_custom_dropdown", {
        button: "user_dropdown",
        top: args.srcElement.getBoundingClientRect().top,
        left: args.srcElement.getBoundingClientRect().left
      });

      setTimeout(function() {
        _this.$store.dispatch("dropdown_btn_user_toggle", true);
      }, 0);
    }
  }
})
</script>
