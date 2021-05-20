<template>
<div id="important" :class="[data.important ? 'text-yellow-500' : 'text-gray-500']" class="items-center justify-center ">
  <div v-if="data.important">
    <button v-on:click="toggledImportant()" class="px-2 py-1.5 rounded-full hover:bg-gray-300 focus:outline-none">
      <i class="fas fa-thumbtack text-base"></i>
    </button>
  </div>
  <div v-else>
    <button v-on:click="toggledImportant()" class="px-2 py-1.5 rounded-full hover:bg-gray-300 focus:outline-none">
      <i class="fas fa-thumbtack text-base"></i>
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
  },
  methods:{
    toggledImportant(){
      this.data.important = !this.data.important;
      this.$eventHub.$emit("toggled_important", {id: this.data.id, important: this.data.important});
    }
  }

});
</script>
