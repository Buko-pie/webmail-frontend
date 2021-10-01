<template>
  <div class="flex px-2 py-2">
    <div v-if="data.id === 0" class="flex items-center">
      <i class="fas fa-user-plus text-gray-500 pr-4"></i>
      <div>{{ data.text }}</div>
    </div>
  </div>
</template>

<script>
export default{
  name: "AccountsListTemplate",
}
</script>
