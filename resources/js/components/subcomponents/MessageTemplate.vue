<template>
<div id="message">
  <div class="flex">
    <p v-for="label_id in data.labels" :key="label_id">
      <span v-if="user_labels_keyed[label_id]" class="px-2 py-1 mr-2 font-medium text-xs" :style="{ 'color': user_labels_keyed[label_id].color.textColor, 'background-color': user_labels_keyed[label_id].color.backgroundColor}">
        {{ user_labels_keyed[label_id].name }}
      </span>
      <span v-else-if="!label_id.includes('CATEGORY') && !user_labels_keyed[label_id] && label_id !== 'UNREAD' && label_id !== 'STARRED' && label_id !== 'IMPORTANT'" class="px-2 py-1 mr-2 bg-gray-300 font-medium text-xs">
        {{ label_id }}
      </span>
    </p>
    <p>{{ data.message }}<span class="font-normal"> - {{ data.plain_text }}</span></p>
  </div>
</div>
</template>

<script>

export default{
  name: "MessageTemplate",
  computed:{
    user_labels_keyed(){
      return this.$store.state.user_labels_keyed;
    },

    current_inbox(){
      return this.$store.state.current_inbox;
    }
  }
};
</script>
