<template>
<div id="important" class="items-center justify-center text-gray-400">
  <div v-if="data.has_attachment">
      <i class="fas fa-paperclip text-base"></i>
  </div>
  <div v-else>

  </div>
</div>
</template>

<script>
export default({
  data(){
    return{
      data:{},
      false: "false",
      true: "true"
    };
  }

});
</script>
