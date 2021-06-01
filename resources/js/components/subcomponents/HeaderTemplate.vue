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
          <!-- Button Snooze  -->
          <ejs-tooltip content="Snooze" position="BottomCenter">
            <ejs-dropdownbutton :items="more_items" iconCss="fas fa-clock" cssClass="e-round shadow-none e-caret-hide"></ejs-dropdownbutton>
          </ejs-tooltip>
          <!-- Button Move to  -->
          <ejs-tooltip content="Move to" position="BottomCenter">
            <ejs-button ref="btn_move" @click.native="btnMove" iconCss="fas fa-file-export" cssClass="e-round shadow-none" ></ejs-button>
          </ejs-tooltip>
        </div>

        <div class="px-2 flex">
          <!-- Button Labels  -->
          <ejs-tooltip content="Labels" position="BottomCenter">
            <ejs-button ref="btn_labels" @click.native="btnLabels" iconCss="fas fa-tag rotate-135" cssClass="e-round shadow-none" ></ejs-button>
          </ejs-tooltip>
          <!-- Button More  -->
          <ejs-tooltip content="More" position="BottomCenter">
            <ejs-dropdownbutton :items="more_items_selected" iconCss="fas fa-ellipsis-v" cssClass="e-round shadow-none e-caret-hide"></ejs-dropdownbutton>
          </ejs-tooltip>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from "vue";
import { DropDownButtonPlugin, ProgressButtonPlugin  } from "@syncfusion/ej2-vue-splitbuttons";
import { ButtonPlugin } from "@syncfusion/ej2-vue-buttons";
import { TooltipPlugin } from "@syncfusion/ej2-vue-popups";

Vue.use(DropDownButtonPlugin);
Vue.use(ProgressButtonPlugin);
Vue.use(ButtonPlugin);
Vue.use(TooltipPlugin);

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
        {
          id: 0,
          text: 'All'
        },{
          id: 1,
          text: 'None'
        },{
          id: 2,
          text: 'Read'
        },{
          id: 3,
          text: 'Unread'
        },{
          id: 4,
          text: 'Starred'
        },{
          id: 5,
          text: 'Unstarred'
        }
      ],
      more_items:[  
        {
          text: "Mark all as read"
        }
      ],
      more_items_selected: [
        {
          text: "Mark as read"
        },{
          text: "Mark as unread"
        },{
          text: "Mark as Important"
        },{
          text: "Mark as not Important"
        },{
          text: "Add star"
        },{
          text: "Remove star"
        },{
          text: "Mute"
        },{
          text: "Forward as attachment"
        }
      ]
    };
  },

  methods:{
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

    refreshInbox(){
      console.log("header refresh inbox");
      this.$eventHub.$emit("refresh_inbox", {
        event: "refresh_inbox"
      });
      this.loading = true;
    },

    btnArchive(){
      console.log("Move selected emails to archive");
    },

    btnReportSpam(){
      console.log("Report selected emails as spam");
    },

    btnDelete(){
      console.log("Delete selected emails");
    },

    btnToggleRead(){
      console.log(this.items_unread_selected ? "Mark selected emails as read" : "Mark selected emails as unread");
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
    }
  },

  mounted(){
    console.log(this.message + "bruhs");
    console.log(this.dropdown_btn_lbl);
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
  }
});
</script>