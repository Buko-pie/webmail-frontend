<template>
<div id="message">
  <p>{{ data.message }}<span class="font-normal"> - {{ data.plain_text }}</span></p>
</div>
</template>

<script>
export default({
  name: "MessageTemplate",
  data(){
    return{
      data:{},
      false: "false",
      true: "true"
    };
  }
});
</script>
