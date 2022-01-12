<template>
  <div v-if="isParent(item, label) && item._prop">
    <div class="flex items-center mt-1">
      <i class="fas fa-caret-down p-1 text-center hover:bg-gray-300 cursor-pointer" @click="expand = !expand"/>
      <a @click="goToLabel(item._prop.text, item._prop.id)" @mouseover="hovered = true" @mouseleave="hovered = false" :class="[ current_inbox.name === label ? 'label_items_selected' : 'label_items' ]" href="#">
        <div class="sidebar_icons">
          <i class="fas fa-tag rotate-135 text-lg" 
              :style="{ color: user_labels_keyed[item._prop.id].color.backgroundColor }"
          />
        </div>
        <p class="sidebar_text">{{ label }}</p>
        <div class="flex ml-auto items-center justify-center text-center">
          <ejs-dropdownbutton v-show="hovered" :items="more_items" :beforOpen="beforeOpen(item._prop)" :select="label_options" @click.native="selectLabel(item._prop)" @mouseover.native="hover_opts = true" @mouseleave.native="hover_opts = false" iconCss="fas fa-ellipsis-v leading-5" cssClass="e-round shadow-none e-caret-hide w-9 h-9 items-center justify-center"></ejs-dropdownbutton>

          <div v-if="user_labels_keyed[item._prop.id].messagesUnread > 0" v-show="!hovered" class="px-2.5 py-1 bg-red-500 text-white rounded-full">
            <p>{{ user_labels_keyed[item._prop.id].messagesUnread }}</p>
          </div>

        </div>
      </a> 
    </div>
    <div v-show="expand" class="ml-3">
      <labels-list :items="item" class="ml-2"></labels-list>
    </div>
  </div>

  <div v-else-if="label !== '_prop'" class="mt-1">
    <a @click="goToLabel(item._prop.text, item._prop.id)" @mouseover="hovered = true" @mouseleave="hovered = false" :class="[ current_inbox.name === label ? 'label_items_selected' : 'label_items' ]" href="#">
      <div class="sidebar_icons ml-6">
        <i  class="fas fa-tag rotate-135 text-lg" 
            :style="{ color: user_labels_keyed[item._prop.id].color.backgroundColor }"
        />
      </div>
      <p class="sidebar_text">{{ label }}</p>
      <div class="flex ml-auto items-center justify-center text-center">
        <ejs-dropdownbutton v-show="hovered" :items="more_items" :beforOpen="beforeOpen(item._prop)" :select="label_options" @click.native="selectLabel(item._prop)" @mouseover.native="hover_opts = true" @mouseleave.native="hover_opts = false" iconCss="fas fa-ellipsis-v leading-5" cssClass="e-round shadow-none e-caret-hide w-9 h-9 items-center justify-center"></ejs-dropdownbutton>

        <div v-if="user_labels_keyed[item._prop.id].messagesUnread > 0" v-show="!hovered" class="px-2.5 py-1 bg-red-500 text-white rounded-full">
          <p>{{ user_labels_keyed[item._prop.id].messagesUnread }}</p>
        </div>

      </div>
    </a> 
  </div>
</template>
<script>
import Vue from "vue";
import moment from "moment";
import { ButtonPlugin , RadioButtonPlugin, CheckBoxPlugin } from "@syncfusion/ej2-vue-buttons";
import VueNotification from "@kugatsu/vuenotification";

const labels_list = Vue.component("labels-list", require("./LabelList.vue").default);

Vue.use(ButtonPlugin);
Vue.use(RadioButtonPlugin);
Vue.use(CheckBoxPlugin);
Vue.use(VueNotification, {
  timer: 20
});

function formatDate(data) {
  data.forEach(function(value) {
    if(moment(value.created_at).isSame(new Date(), "day")){
      value.created_at = moment(value.created_at).format("LT");
    }else if(moment(value.created_at).isSame(new Date(), "year")){
      value.created_at = moment(value.created_at).format("MMM DD");
    }else{
      value.created_at = moment(value.created_at).format("YYYY/MM/DD");
    }
  });

  return data;
}

export default Vue.extend({
  name: "LabelItems",
  props:["item", "label"],
  data(){
    return {
      expand: false,
      hovered: false,
      hover_opts: false,
      more_items:[  
        { id: 0, text: "In label list", disabled: true},
        { id: 1, iconCss:"", text: "Show", value: "setLblListVis", vis_op: "labelShow"},
        { id: 2, iconCss:"", text: "Show if unread", value: "setLblListVis", vis_op: "labelShowIfUnread"},
        { id: 3, iconCss:"", text: "Hide", value: "setLblListVis", vis_op: "labelHide"},
        { id: 4, text: "", separator: true},

        { id: 5, text: "In message list", disabled: true},
        { id: 6, iconCss:"", text: "Show", value: "setMsgListVis", vis_op: "show"},
        { id: 7, iconCss:"", text: "Hide", value: "setMsgListVis", vis_op: "hide"},
        { id: 8, text: "", separator: true},
        
        { id: 9, text: "Edit label", value: "editLabel"},
        { id: 10, text: "Delete label", value: "deleteLabel" }
      ],
    }
  },
  computed:{
    ref_sidebar(){
      return this.$store.state.sidebar;
    },

    ref_headerTemplate(){
      return this.$store.state.headerTemplate;
    },

    current_inbox(){
      return this.$store.state.current_inbox;
    },

    user_labels(){
      return this.$store.state.user_labels;
    },

    user_labels_keyed(){
      return this.$store.state.user_labels_keyed;
    },
  },

  methods:{
    isParent(item, label){
      if(Object.keys(item).length > 1 && label !== "id"){
        return true;
      }else{
        return false;
      }
    },

    expandParent(id){
      this.parentClicked = id;
    },

    selectLabel(label){
      this.ref_sidebar.selected_label = label;
    },

    label_options(args){
      this.ref_sidebar.label_options(args);
    },

    beforeOpen(args){
      switch (args.labelListVisibility) {
        case "labelShow":
          this.more_items[1].iconCss = "fas fa-check";
          break;

        case "labelShowIfUnread":
          this.more_items[2].iconCss = "fas fa-check";
          break;

        case "labelHide":
          this.more_items[3].iconCss = "fas fa-check";
          break;

        default:
          break;
      }

      switch (args.messageListVisibility) {
        case "show":
          this.more_items[6].iconCss = "fas fa-check";
          break;

        case "hide":
          this.more_items[7].iconCss = "fas fa-check";
          break;

        default:
          break;
      }
    },

    goToLabel(label_name, label_id){
      if(!this.hover_opts){
        let _this = this;

        let params = {
          inbox: label_name,
          label_id: label_id,
          option: "labeled"
        };

        this.ref_headerTemplate.show_loading = true;
        this.ref_headerTemplate.loading = true;
        this.$store.dispatch("goToLabel", params).then((response) => {
          console.log(response);
          if(response){
              _this.$store.dispatch("set_current_inbox", {name: label_name, id: label_id, type: 1});
              _this.$store.dispatch("set_email_batch", formatDate(response.data.repackaged_data));
              _this.$store.dispatch("set_inbox_items", response.data.inbox_items_length);
              _this.$store.dispatch("set_inbox_total", response.data.inbox_info.messagesTotal);
          }
          _this.ref_headerTemplate.show_loading = false;
          _this.ref_headerTemplate.loading = false;
          _this.$eventHub.$emit("page_change");
        }).catch(error => {
          console.log(error);
          this.$notification.error("somthing went wrong", {  timer: 5 });
        });
      }
    }
  },
  components:{
    labels_list,
  },
});
</script>
