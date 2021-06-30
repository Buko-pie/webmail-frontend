<template>
  <div class="flex w-56 items-center content-center">
    <div v-if="data.id === 4" class="pr-3">
      <i class="far fa-calendar-alt text-lg"></i>
    </div>

    <div>
      {{ data.text }}
    </div>
    
    <div v-if="data.id !== 4" class="ml-auto text-gray-400">
      {{ data.day_time }}
    </div>
  </div>
</template>

<script>
export default({
  name: "SnoozeListViewTemplate",
  data(){
    return{
      data:{}
    }
  }
})
</script>
