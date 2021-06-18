<template>
<div id="email_html_body">
  <div class="p-5" v-html="email_body_html">
    {{ email_body_html }}
  </div>
</div>
</template>

<script>
export default({
  name: "EmailViewTemplate",
  data(){
    return{
      data:{},
      false: "false",
      true: "true"
    };
  },
  computed:{
    email_body_html(){
      return this.$store.state.selected_email_html_body;
    }
  }
});
</script>
