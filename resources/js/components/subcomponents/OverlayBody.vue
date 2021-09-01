<template>
  <div :start="start">
    <Vueditor ref="vueditor"></Vueditor>
  </div>
</template>
<script>
import Vue from "vue";

export default Vue.extend({
  name: "OverlayBody",
  data(){
    return{
      width: "600px",
    }
  },
  computed:{
    start(){
      this.$store.dispatch("set_overlay_body", this);
    },
  }
});
</script>
