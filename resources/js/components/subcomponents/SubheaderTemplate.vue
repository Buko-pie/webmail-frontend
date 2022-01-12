<template>
  <div class="px-2 relative no-wrap">
    <div class="absolute z-40 flex">
      <div class="py-2">
        <div class="text-sm font-semibold cursor-pointer" @click="readSort">
          <p><i :class="read_sort_toggle ? class_1 : class_2"></i> Unread</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default{
  name: "SubheaderTemplate",
  data(){
    return{
      data:{},
      read_sort_toggle: false,
      class_1: "fas fa-chevron-up mr-1",
      class_2: "fas fa-chevron-down mr-1",
      false: "false",
      true: "true"
    };
  },
  methods:{
    readSort(){
      this.read_sort_toggle = !this.read_sort_toggle;
      this.$eventHub.$emit("read_sort_toggle", {value: this.read_sort_toggle});
    }
  }
};
</script>