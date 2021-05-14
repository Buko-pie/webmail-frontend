<template>
<div id="starred" :class="[data.starred ? 'text-yellow-500' : 'text-gray-500']" class="items-center justify-center pb-0.5">
  <div v-if="data.starred">
    <button v-on:click="data.starred = !data.starred" class="p-1 rounded-full hover:bg-gray-300 focus:outline-none">
      <i class="fas fa-star text-base"></i>
    </button>
  </div>
  <div v-else>
    <button v-on:click="data.starred = !data.starred" class="p-1 rounded-full hover:bg-gray-300 focus:outline-none">
      <i class="far fa-star text-base"></i>
    </button>
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
