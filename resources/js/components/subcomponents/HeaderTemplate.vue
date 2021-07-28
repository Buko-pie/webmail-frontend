<template>
  <div ref="header_template" class="flex relative items-center h-14">
    <div e-mappinguid="grid-column0">
      <div class="e-checkbox-wrapper e-css z-40">
        <input class="e-checkselectall e-focus" type="checkbox">
        <span class="e-frame e-icons e-uncheck"></span>
        <span class="e-label"> </span>
      </div>
      <ejs-dropdownbutton :items="drop_down_items" :select="dropDownSelect" class="absolute left-5 pl-0 py-1 pr-1.5"></ejs-dropdownbutton>
    </div>
    <div class="pl-5 flex">
      <div :class="{'hidden': items_selected}" class="flex">
        <ejs-tooltip content="Refresh" position="BottomCenter">
          <ejs-progressbutton id="refresh_progress" ref="refresh_progress"
          :enableProgress="true"
          :begin="refreshInbox"
          :progress="onProgress"
          :spinSettings="{position: 'Center'}" 
          :duration="loading_duration"
          :animationSettings="{ effect: 'ZoomOut' }"
          cssClass="e-round shadow-none" 
          iconCss="fas fa-redo-alt text-lg">
          </ejs-progressbutton>
        </ejs-tooltip>
        
        <ejs-tooltip content="More" position="BottomCenter">
          <ejs-dropdownbutton :items="more_items" iconCss="fas fa-ellipsis-v" cssClass="e-round shadow-none e-caret-hide"></ejs-dropdownbutton>
        </ejs-tooltip>
      </div>
      <div :class="{'hidden': !items_selected}" class="grid grid-cols-3 divide-x">
        <div class="px-2 flex">
          <!-- Button Archive  -->
          <ejs-tooltip content="Archive" position="BottomCenter">
            <ejs-button @click.native="btnArchive" iconCss="fas fa-archive" cssClass="e-round shadow-none" ></ejs-button>
          </ejs-tooltip>
          <!-- Button Report as Spam  -->
          <ejs-tooltip content="Report as spam" position="BottomCenter">
            <ejs-button @click.native="btnReportSpam" iconCss="fas fa-exclamation-triangle" cssClass="e-round shadow-none" ></ejs-button>
          </ejs-tooltip>
          <!-- Button Delete  -->
          <ejs-tooltip content="Delete" position="BottomCenter">
            <ejs-button @click.native="btnDelete" iconCss="fas fa-trash-alt" cssClass="e-round shadow-none" ></ejs-button>
          </ejs-tooltip>
        </div>

        <div class="px-2 flex">
          <!-- Button Read  -->
          <ejs-tooltip :content="read_tgl_button_tt_content" position="BottomCenter">
            <ejs-button @click.native="btnToggleRead" :iconCss="read_tgl_button_icon" cssClass="e-round shadow-none" ></ejs-button>
          </ejs-tooltip>

          <!-- Button Snooze
          <ejs-tooltip content="Snooze" position="BottomCenter">
            <ejs-dropdownbutton target="#snooze_listView" :items="more_items" iconCss="fas fa-clock" cssClass="e-round shadow-none e-caret-hide"></ejs-dropdownbutton>
            <ejs-listview id="snooze_listView" ref="snooze_listView" class="shadow-black-lg" :select="snoozeSelect" :dataSource="snooze_opitons" :fields="snooze_fields" :template="snooze_template"></ejs-listview>
          </ejs-tooltip> -->
          <!-- Button Move to  -->

          <ejs-tooltip content="Move to" position="BottomCenter">
            <ejs-button ref="btn_move" @click.native="btnMove" iconCss="fas fa-file-export" cssClass="e-round shadow-none" ></ejs-button>
          </ejs-tooltip>

          <!-- Button Labels  -->
          <ejs-tooltip content="Labels" position="BottomCenter">
            <ejs-button ref="btn_labels" @click.native="btnLabels" iconCss="fas fa-tag rotate-135" cssClass="e-round shadow-none" ></ejs-button>
          </ejs-tooltip>
        </div>

        <div class="px-2 flex">
          <!-- Button More  -->
          <ejs-tooltip content="More" position="BottomCenter">
            <ejs-dropdownbutton :items="more_items_selected" :select="moreOptions" iconCss="fas fa-ellipsis-v" cssClass="e-round shadow-none e-caret-hide"></ejs-dropdownbutton>
          </ejs-tooltip>
        </div>
      </div>
    </div>
    <div v-if="selected_all && !selected" class="ml-auto flex items-center justify-center content-center place-content-center">
        <p class="text-gray-500 text-base font-medium font-roboto"> All <b>{{ selected_items }}</b> emails on this page are selected.</p>
        <ejs-button ref="select_all_email" @click.native="selected_all_items" cssClass="shadow-none">Select all {{ inbox_total }} emails in {{ current_inbox }}</ejs-button>
    </div>
    <div v-if="selected" class="ml-auto flex items-center justify-center content-center place-content-center">
        <p class="text-gray-500 text-base font-medium font-roboto"> All <b>{{ inbox_total }}</b> emails on this page are selected.</p>
        <ejs-button ref="select_all_email" @click.native="clear_selected_items" cssClass="shadow-none">Clear selection</ejs-button>
    </div>
  </div>
</template>

<script>
import Vue from "vue";
import moment from "moment";
import VModal from 'vue-js-modal';
import VueNotification from "@kugatsu/vuenotification";

import { DropDownButtonPlugin, ProgressButtonPlugin  } from "@syncfusion/ej2-vue-splitbuttons";
import { ButtonPlugin } from "@syncfusion/ej2-vue-buttons";
import { TooltipPlugin } from "@syncfusion/ej2-vue-popups";
import { ListViewPlugin } from "@syncfusion/ej2-vue-lists";

const snooze_template = Vue.component("snooze_template", require("./SnoozeListViewTemplate").default);

Vue.use(DropDownButtonPlugin);
Vue.use(ProgressButtonPlugin);
Vue.use(ButtonPlugin);
Vue.use(TooltipPlugin);
Vue.use(ListViewPlugin);
Vue.use(VModal, { dialog: true });
Vue.use(VueNotification, {
  timer: 20
});

export default Vue.extend({
  name: "HeaderTemplate",
  data(){
    return{
      data:{},
      items_selected: false,
      items_unread_selected: false,
      loading: true,
      read_tgl_button_tt_content: "",
      read_tgl_button_icon: "",
      loading_duration: 15000,
      drop_down_items:[
        { id: 0, text: 'All' },
        { id: 1, text: 'None' },
        { id: 2, text: 'Read' },
        { id: 3, text: 'Unread' },
        { id: 4, text: 'Starred' },
        { id: 5, text: 'Unstarred' }
      ],
      more_items:[  
        { id: 0, text: "Mark all as read" }
      ],
      more_items_selected: [
        { id: 0, text: "Mark as read" },
        { id: 1, text: "Mark as unread" },
        { id: 2, text: "Mark as important" },
        { id: 3, text: "Mark as not important" },
        { id: 4, text: "Add star" },
        { id: 5, text: "Remove star" },
        // { id: 6, text: "Mute" },
        // { id: 7, text: "Forward as attachment" }
      ],
      snooze_opitons :[
        { id: 0, class: "data", text: "Later today", day_time: "6:00 PM", category: "Snooze until..." },
        { id: 1, class: "data", text: "Tommorow", day_time: moment().add(1,'days').format("ddd") + ", 8:00 AM", category: "Snooze until..." },
        { id: 2, class: "data", text: "This weekend", day_time: "Sat, 8:00 AM", category: "Snooze until..." },
        { id: 3, class: "data", text: "Next week", day_time: "Mon, 8:00 AM", category: "Snooze until..." },
        { id: 4, class: "data", text: "Pick date & time", category: "Snooze until..." }
      ],
      snooze_fields: { tooltip: 'text', text: 'text', groupBy: 'category' },
      snooze_template(){
        return{
          template: snooze_template
        }
      },
    }
  },

  computed:{
    message(){
      return this.$store.state.message;
    },

    dropdown_btn_lbl(){
      return this.$store.state.dropdown_btn_lbl;
    },

    dropdown_btn_mv(){
      return this.$store.state.dropdown_btn_mv;
    },

    inbox_items(){
      return this.$store.state.inbox_items;
    },

    inbox_total(){
      return this.$store.state.inbox_total;
    },

    current_inbox(){
      return this.$store.state.current_inbox;
    },

    selected_items(){
      return this.$store.state.selected_items_count
    },

    selected_items_dataID(){
      return this.$store.state.selected_items_dataID
    },

    selected_all(){
      return this.$store.state.selected_items_count === this.inbox_items;
    },

    selected(){
      return this.$store.state.selected_all_items;
    }

  },

  mounted(){
    console.log(moment().add(1,'days').format("ddd") + ", 8:00 AM");
    // this.snooze_opitons[1].day_time = ;
    // console.log(this.snooze_opitons[1].day_time);
  },

  methods:{
    refreshInbox(){
      console.log("header refresh inbox");
      this.$eventHub.$emit("refresh_inbox", {
        event: "refresh_inbox"
      });
      this.loading = true;
    },

    selectedItemsTo(option, dataIDs, route) {
      let _this = this;

      axios.get(route.set_many_route, {
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer {{ csrf_token() }}"
        },
        params: {
          option: option,
          dataIDs: dataIDs,
        }
      }).then(function (response) {
        _this.refreshInbox();
        console.log(response);
      }).catch(error => {
        console.log(error);
        _this.$notification.error("somthing went wrong", {  timer: 5 });
      });
    },

    dropDownSelect(args){
      this.$eventHub.$emit("select_by", {
        id: args.item.properties.id, 
        text: args.item.properties.text
      });
    },

    onProgress(args){
      if(this.loading){
        args.percent = 0;
        this.loading_duration = this.loading_duration + 100;
      }else{
        args.percent = 100;
        this.loading = false;
      }
    },


    btnArchive(){
      console.log("Move selected emails to archive");
      console.log(this.selected_items_dataID);
      this.selectedItemsTo(8, this.$store.state.selected_items_dataID, this.$store.state.routes);
    },

    btnReportSpam(){
      console.log("Report selected emails as spam");
    },

    btnDelete(){
      console.log("Delete selected emails");
    },

    btnToggleRead(){
      console.log(this.items_unread_selected ? "Mark selected emails as read" : "Mark selected emails as unread");
      if(this.items_unread_selected){
        this.selectedItemsTo(0, this.$store.state.selected_items_dataID, this.$store.state.routes);
      }else{
        this.selectedItemsTo(1, this.$store.state.selected_items_dataID, this.$store.state.routes);
      }

      this.refreshInbox();
    },

    btnSnooze(){
      console.log("Snooze selected emails");
    },

    btnMove(){
      let _this = this;
      this.$eventHub.$emit("show_custom_dropdown", {
        button: "btn_move",
        top: this.$refs.btn_move.$el.getBoundingClientRect().top,
        left: this.$refs.btn_move.$el.getBoundingClientRect().left
      });

      setTimeout(function() {
        _this.$store.dispatch("dropdown_btn_mv_toggle", !this.dropdown_btn_mv);
      }, 0);
    },

    btnLabels(){
      let _this = this;
      this.$eventHub.$emit("show_custom_dropdown", {
        button: "btn_labels",
        top: this.$refs.btn_labels.$el.getBoundingClientRect().top,
        left: this.$refs.btn_labels.$el.getBoundingClientRect().left
      });

      setTimeout(function() {
        _this.$store.dispatch("dropdown_btn_lbl_toggle", !this.dropdown_btn_lbl);
      }, 0);
      
    },

    btnMore(){
      console.log("Show more options on selected emails");
    },

    snoozeSelect(args){
      this.$refs.snooze_listView.selectItem();
      if(args.data.text === "Pick date & time"){
        this.$eventHub.$emit("show_datepick_modal", {
          data: "show_datepick_modal modal open"
        });
      }
    },

    selected_all_items(){
      this.$store.dispatch("set_selected_all_items", true);
    },

    clear_selected_items(){ 
      this.$store.dispatch("set_selected_all_items", false);
    },

    moreOptions(args){
      switch (args.item.id) {
        case 0:
          //Mark as read
          console.log("Mark as read");
          this.selectedItemsTo(args.item.id, this.$store.state.selected_items_dataID, this.$store.state.routes);
        break;
        
        case 1:
          //Mark as unread
          console.log("Mark as unread");
          this.selectedItemsTo(args.item.id, this.$store.state.selected_items_dataID, this.$store.state.routes);
        break;
        
        case 2:
          //Mark as important
          console.log("Mark as important");
          this.selectedItemsTo(args.item.id, this.$store.state.selected_items_dataID, this.$store.state.routes);
        break;
        
        case 3:
          //Mark as not important
          console.log("Mark as not important");
          this.selectedItemsTo(args.item.id, this.$store.state.selected_items_dataID, this.$store.state.routes);
        break;
        
        case 4:
          //Add star
          console.log("Add star");
          this.selectedItemsTo(args.item.id, this.$store.state.selected_items_dataID, this.$store.state.routes);
        break;
        
        case 5:
          //Remove star
          console.log("Remove star");
          this.selectedItemsTo(args.item.id, this.$store.state.selected_items_dataID, this.$store.state.routes);
        break;
        
        case 6:
          //Mute - disabled
          console.log("Mute");
          this.selectedItemsTo(args.item.id, this.$store.state.selected_items_dataID, this.$store.state.routes);
        break;
        
        case 7:
          //Forward as attachment - disabled
          console.log("Forward as attachment");
          this.selectedItemsTo(args.item.id, this.$store.state.selected_items_dataID, this.$store.state.routes);
        break;
      
        default:
          console.log("somthing went wrong!");
        break;
      }

      this.refreshInbox();
    }
  },

  created(){
    this.$eventHub.$on("stop_loading", (e) => {
      console.log("stop_loading");
      this.loading = false;
    });

    this.$eventHub.$on("toggle_rowSelected", (e) => {
      this.items_selected = e.value;
    });

    this.$eventHub.$on("items_selected_unread", (e) => {
      this.items_unread_selected = e.value;
      this.read_tgl_button_tt_content = e.value ? "Mark as read" : "Mark as unread";
      this.read_tgl_button_icon = e.value ? "fas fa-envelope-open-text" : "fas fa-envelope";
    });
  },
});
</script>