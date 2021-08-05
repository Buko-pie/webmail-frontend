<template>
<div id="message">
  <div class="flex">
    <p v-for="label_id in data.labels" :key="label_id">
      <span v-if="user_labels[label_id]" class="px-2 py-1 mr-2 font-medium text-xs rounded" :style="{ 'color': user_labels[label_id].color.textColor, 'background-color': user_labels[label_id].color.backgroundColor}">
        {{ user_labels[label_id].name }}
      </span>
      <span v-else-if="!label_id.includes('CATEGORY') && !user_labels[label_id] && label_id !== 'UNREAD' && label_id !== 'STARRED' && label_id !== 'IMPORTANT' && label_id !== 'INBOX'" class="px-2 py-1 mr-2 bg-gray-300 font-medium text-xs">
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
    user_labels(){
      let labels = this.$store.state.user_labels;
      let labelKey = [];

      labels.forEach(label => {
        labelKey[label.id] = {name: label.text, color: label.color.backgroundColor !== "#000000" ? label.color : { backgroundColor: "#d1d5db", textColor: "#000000" }}
      });

      return labelKey;
    },

    current_inbox(){
      return this.$store.state.current_inbox;
    }
  }
};
</script>
