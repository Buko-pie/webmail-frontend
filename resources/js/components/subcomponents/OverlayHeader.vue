<template>
  <div :start="start" class="flex w-full p-2 bg-gray-700">
    <p class="text-white font-semibold text-sm">{{ current_subject }}</p>
    <div class="ml-auto flex">
      <ejs-button 
        class="bg-gray-700 shadow-none hover:bg-gray-400"
        iconCss="text-white fas fa-window-minimize"
      >
      </ejs-button>
      <ejs-button 
        class="bg-gray-700 shadow-none hover:bg-gray-400"
        iconCss="text-white text-sm far fa-window-maximize"
      >
      </ejs-button>
      <ejs-button 
        class="bg-gray-700 shadow-none hover:bg-gray-400"
        iconCss="text-white text-sm far fa-window-close"
        @click.native="close_overlay"
      >
      </ejs-button>
    </div>
  </div>
</template>
<script>
import Vue from "vue";
import { ButtonPlugin } from "@syncfusion/ej2-vue-buttons";

Vue.use(ButtonPlugin);
export default Vue.extend({
  name: "OverlayHeader",
  data() {},
  computed:{
    start(){
      this.$store.dispatch("add_overlays", this);
    },

    overlay_index(){
      return this.$store.state.overlays.length;
    },

    ref_sidebar(){
      return this.$store.state.sidebar;
    },

    current_subject:{
      get(){
        return this.$store.state.current_subject;
      },set(new_data){
        this.$store.dispatch("set_current_subject", new_data);
      }
    }
  },

  methods:{
    close_overlay(){
      this.ref_sidebar.$refs.overlay_dialog.hide();
    }
  }
});
</script>