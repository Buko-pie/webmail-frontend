<template>
  <div class="flex w-full p-2 bg-gray-700">
    <p class="text-white font-semibold text-sm">{{ current_subject }}</p>
    <ejs-button 
      class="ml-auto text-xs"
      @click.native="close_overlay"
    >
    x
    </ejs-button>
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