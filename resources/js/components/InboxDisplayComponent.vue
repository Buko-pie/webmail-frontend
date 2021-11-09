<template>
  <div :start="start">
    <div :get_splitter_pane_0_height="get_splitter_pane_0_height" :splitter_height="splitter_height"></div>
    <ejs-grid
      :height="inbox_height - 155.5"
      ref="grid"
      id="gridcomp"
      :dataSource="gmail_data" 
      :selectionSettings="selectionSettings" 
      :allowPaging="false"
      :allowSorting="true"
      :contextMenuItems="menuItems"
      :contextMenuClick="onSelect"
      :contextMenuOpen="contextMenuOpen"
      :rowDataBound="rowDataBound"
      :dataBound="dataBound"
      :recordClick="recordClick"
      :rowSelected="rowSelected"
      :rowDeselected="rowDeselected"
      :data="routes"
      :searchSettings="searchOptions"
      :pageSettings="pageSettings"
    >
      <e-columns>
        <e-column headerText="" :headerTemplate="header_template" :columns="custom_column"></e-column>
      </e-columns>
    </ejs-grid>
    <inbox-pagination id="inbox_pager" ref="inbox_pager"> </inbox-pagination>
  </div>
</template>

<script>
import Vue from "vue";
import moment from "moment";
import VueNotification from "@kugatsu/vuenotification";
import Overlay from "./subcomponents/Overlay.vue";
import { GridPlugin, PagerPlugin, ContextMenu, Sort, Edit, Page, Toolbar, Search } from "@syncfusion/ej2-vue-grids";

const fileIcons = require("file-icons-js");
let header_template = Vue.component("header-template", require("./subcomponents/HeaderTemplate.vue").default);
let subheader_template = Vue.component("subheader-template", require("./subcomponents/SubheaderTemplate.vue").default);
let starred_template = Vue.component("starred-template", require("./subcomponents/StarredTemplate.vue").default);
let important_template = Vue.component("important-template", require("./subcomponents/ImportantTemplate.vue").default);
let message_template = Vue.component("message-template", require("./subcomponents/MessageTemplate.vue").default);
let attachment_template = Vue.component("important-template", require("./subcomponents/AttachmentTemplate.vue").default);
let pagination_template = Vue.component("pagerTemplate", require("./subcomponents/PaginationTemplate.vue").default);
var overlayClass = Vue.extend(Overlay);

Vue.use(GridPlugin);
Vue.use(PagerPlugin);
Vue.use(VueNotification, {
  timer: 20
});
Vue.prototype.$eventHub = new Vue();

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

export default{
  name: "InboxDisplayComponent",
  props:{
    custom_labels: Array,
    // routes: Object
  },

  data(){
    return{
      pageSettings: {
        pageSize: 120
      },
      current_selected: [],
      csrf_token: null,
      index: 0,
      max_pages: null,
      email_count: null,
      routes: null,
      selected_item_unread: 0,
      selected_item_starred: 0,
      selected_item_important: 0,
      selected_items_count: 0,
      inbox_height: null,
      has_nextPage: true,
      // pageSettings: { pageSize: 50, pageCount: 3 },
      test:["read", "ascending"],
      viewData: [],
      menuItems:[
        { id: "reply", text: "Reply", iconCss:"fas fa-reply"},
        { id: "reply_all", text: "Reply All", iconCss: "fas fa-reply-all"},
        { id: "forward", text: "Forward", iconCss: "fas fa-arrow-right" },
        { id: "mark_unread", text: "Mark as unread", iconCss: "fas fa-envelope" },
        { id: "archive", text: "Archive", iconCss: "fas fa-archive" },
        { id: "delete", text: "Delete", iconCss: "fas fa-trash" },
        { id: "mark_read", text: "Mark as read", iconCss: "fas fa-envelope-open-text" },
        { id: "move_to_inbox", text: "Move to Inbox" },
        { id: "delete_forever", text: "Delete Forever" },
        { id: "move_to", text: "Move To", iconCss: "fas fa-file-export" },
        { id: "label_as", text: "Label as", iconCss: "fas fa-tag rotate-135", items: [{text: "test"}] }
      ],
      select_option: null,
      selected_rows: [],
      filter: {
        type: "CheckBox"
      },
      custom_column:[
        {
          //Column - Check box
          type: "checkbox",
          allowFiltering: false,
          allowSorting: false,
          headerTemplate(){
            return{ template: subheader_template }
          },
          width: "45",
          customAttributes:{class: "overflow-visible"}
        },{
          //Column - ID
          field: "id",
          headerText: "",
          visible: false
        },{
          //Column - Starred
          field: "starred",
          headerText: "",
          width: "40",
          allowSorting: false,
          template(){
            return{ template: starred_template }
          },
          allowSorting: false
        },{
          //Column - Important
          field: "important",
          headerText: "",
          width: "40",
          allowSorting: false,
          template(){
            return{ template: important_template }
          },
          allowSorting: false
        },{
          //Column - Sender
          field: "sender",
          headerText: "",
          minWidth: "120",
          width: "120",
          maxWidth: "120",
          allowSorting: false
        },{
          //Column - Sender information
          field: "sender_info",
          headerText: "",
          visible: false
        },{
          //Column - Carbon copy information
          field: "cc_info",
          headerText: "",
          visible: false
        },{
          //Column - Blind carbon copy information
          field: "bcc_info",
          headerText: "",
          visible: false
        },{
          //Column - html body
          field: "body",
          headerText: "",
          visible: false
        },{
          //Column - Message
          field: "message",
          headerText: "",
          allowSorting: false,
          template(){
            return{ template: message_template }
          }
        },{
          //Column - Has attachment
          field: "has_attachment",
          headerText: "",
          width: "40",
          textAlign: "Right",
          template(){
            return{ template: attachment_template }
          },
          allowSorting: false
        },{
          //Column - Created At
          field: "created_at",
          headerText: "",
          width: "80",
          textAlign: "Right",
          allowSorting: false
        },{
          //Column - spacer
          field: "spacer",
          headerText: "",
          width: "20",
          visible: true
        },{
          //Column - Read
          field: "read",
          headerText: "",
          textAlign: "Right",
          visible: false
        }
      ],
      pagination_template(){
        return{ template: pagination_template }
      },
      header_template(){
        return{ template: header_template }
      },
      selectionSettings: { checkboxOnly: true }
    }
  },


  computed:{
    ref_headerTemplate(){
      return this.$store.state.headerTemplate;
    },

    ref_sidebar(){
      return this.$store.state.sidebar;
    },

    start(){
      console.log("vue-grids computed")
      this.routes = this.$store.state.routes;
      this.csrf_token = this.$store.state.csrf_token;
      this.$store.dispatch("set_inboxDisplay", this);
      
      let _this = this;
      axios.get(this.routes.data_route, {
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer " + this.csrf_token,
          "X-CSRF-TOKEN": this.csrf_token
        },
        params: {
          inbox: "INBOX",
          option: "first_run"
        }
      }).then(function (response) {
        let payload = response.data;
        console.log(payload);
        console.log("ref_headerTemplate");
        console.log(_this.ref_headerTemplate.loading);
        _this.ref_headerTemplate.show_loading = false;
        _this.ref_headerTemplate.loading = false;
        // _this.$store.dispatch("set_max_page", Math.ceil(payload.inbox_items_length / 50));
        _this.$store.dispatch("set_email_batch", formatDate(payload.repackaged_data));
        _this.$store.dispatch("set_inbox_items", payload.inbox_items_length);
        _this.$store.dispatch("set_inbox_total", payload.inbox_info.messagesTotal);
        _this.$store.dispatch("set_user_labels", payload.labels);
        
        _this.$eventHub.$emit("page_change");

        _this.has_nextPage = payload.has_nextPage;
        if(!payload.has_nextPage){
          _this.$eventHub.$emit("disable_nxtBtn", true);
        }
      }).catch(error => {
        console.log(error);
        this.$notification.error("somthing went wrong", {  timer: 5 });
      });
    },

    splitter_height(){
      this.inbox_height = (this.$store.state.splitter_height / 2);
    },

    get_splitter_pane_0_height(){
      this.inbox_height = this.$store.state.splitter_pane_0_height;
    },

    gmail_data(){
      return this.$store.state.email_batch;
    },

    current_page(){
      return this.$store.state.current_page;
    },

    max_page(){
      return this.$store.state.max_page;
    },

    inbox_items(){
      return this.$store.state.inbox_items;
    },

    current_inbox(){
      return this.$store.state.current_inbox;
    },

    searchOptions() {
      return this.$store.state.searchOptions;
    },

    user_email(){
      return this.$store.state.user_email;
    }
  },

  mounted(){
    console.log("vue-grids mounted");
    let _this = this;

    
  },

  methods:{
    onChange(value){
      this.value = value;
      this.$emit("change", value);
    },
    
    onSelect(args) {

      let _this = this;
      if(args.item.text === "Reply"){
        let from_email = args.rowInfo.rowData.sender_info.email !== null ? args.rowInfo.rowData.sender_info.email : args.rowInfo.rowData.sender_info.name;
        console.log(from_email);
        if(this.ref_sidebar.overlays.length < 1){
          this.ref_sidebar.overlays.push(0);

          var overlayInstance = new overlayClass({
            propsData: {
              ref_sidebar: this.ref_sidebar,
              index: this.ref_sidebar.overlays.length - 1,
              routes: this.routes,
              csrf_token: this.csrf_token,
              user_email: this.user_email,
              attachment_path: {
                saveUrl: this.routes.upload_attachment,
                removeUrl: this.routes.remove_attachment
              },
              email_action: "reply_email",
              reply_to_email: from_email,
              email_body_html: args.rowInfo.rowData.body,
              //rework request on email_body_html
            }
          });
 
          overlayInstance.$mount();
          this.ref_sidebar.$refs.overlays_container.appendChild(overlayInstance.$el);
        }
        // this.ref_sidebar.composeNew();
      }else if(args.item.text === "Add Label") {
        //Add Label
        let row_data = args.rowInfo.rowData;
        /////Last construction here on add custom labels context menu
        this.custom_labels.push({id:this.custom_labels.length , title: "Label_" + this.custom_labels.length});
        args.rowInfo.rowData.labels.push("Label_" + this.custom_labels.length);

        console.log(this.custom_labels);
      }else if(args.item.text === "Mark as unread"){
        //Mark As Unread

        axios.get(_this.routes.toggle_route, {
          headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + this.csrf_token,
            "X-CSRF-TOKEN": this.csrf_token
          },
          params: {
            column: "read",
            id: args.rowInfo.rowData.id,
            value: false
          }
        }).then(function (response) {

          // _this.viewData[args.rowInfo.rowIndex].read = false;

          _this.$store.dispatch("modify_email_batch", {
            index: args.rowInfo.rowIndex,
            property: "read",
            value: false
          });

          args.rowInfo.row.classList.add("font-black");

        }).catch(error => {
          console.log(error);
          _this.$notification.error("somthing went wrong", {  timer: 5 });
        });

      }else if(args.item.text === "Mark as read"){
        //Mark as read

        axios.get(_this.routes.toggle_route, {
          headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + this.csrf_token,
            "X-CSRF-TOKEN": this.csrf_token
          },
          params: {
            column: "read",
            id: args.rowInfo.rowData.id,
            value: true
          }
        }).then(function (response) {
          console.log(response.data);
          // _this.viewData[args.rowInfo.rowIndex].read = true;

          _this.$store.dispatch("modify_email_batch", {
            index: args.rowInfo.rowIndex,
            property: "read",
            value: true
          });

          args.rowInfo.row.classList.remove("font-black");

        }).catch(error => {
          console.log(error);
          _this.$notification.error("somthing went wrong", {  timer: 5 });
        });
      } else if(args.item.id === "delete") {
        axios.get(this.$store.state.routes.delete_mail,{
          headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + this.csrf_token,
            "X-CSRF-TOKEN": this.csrf_token
          },
          params: {
            id: args.rowInfo.rowData.id,
            inbox: 'INBOX'
          }
        }).then(function (response) {
          console.log(response);
          _this.$eventHub.$emit("refresh_inbox", {
            event: "refresh_inbox"
          });

          _this.$store.dispatch("set_inbox_items", response.data.inbox_items_length);
          _this.$store.dispatch("set_inbox_total", response.data.inbox_info.messagesTotal);
          
          _this.$eventHub.$emit("page_change");

          _this.has_nextPage = response.data.has_nextPage;
          if(!response.data.has_nextPage){
            _this.$eventHub.$emit("disable_nxtBtn", true);
          }
        }).catch(error => {
          console.log(error);
          this.$notification.error("somthing went wrong", {  timer: 5 });
        });
      } else if(args.item.id === "delete_forever") {
        axios.get(this.$store.state.routes.delete_mail_forever,{
          headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + this.csrf_token,
            "X-CSRF-TOKEN": this.csrf_token
          },
          params: {
            id: args.rowInfo.rowData.id,
            inbox: 'TRASH'
          }
        }).then(function (response) {
          console.log(response);
          _this.$eventHub.$emit("refresh_inbox", {
            event: "refresh_inbox"
          });
            _this.$store.dispatch("set_inbox_items", response.data.inbox_items_length);
          _this.$store.dispatch("set_inbox_total", response.data.inbox_info.messagesTotal);
          
          _this.$eventHub.$emit("page_change");

          _this.has_nextPage = response.data.has_nextPage;
          if(!response.data.has_nextPage){
            _this.$eventHub.$emit("disable_nxtBtn", true);
          }
        }).catch(error => {
          console.log(error);
          _this.$notification.error("somthing went wrong", {  timer: 5 });
        });
      } else if(args.item.id === "move_to_inbox") {
        axios.get(this.$store.state.routes.move_to_inbox,{
          headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + this.csrf_token,
            "X-CSRF-TOKEN": this.csrf_token
          },
          params: {
            id: args.rowInfo.rowData.id,
            inbox: 'TRASH'
          }
        }).then(function (response) {
          console.log(response);
          _this.$eventHub.$emit("refresh_inbox", {
            event: "refresh_inbox"
          });
            _this.$store.dispatch("set_inbox_items", response.data.inbox_items_length);
          _this.$store.dispatch("set_inbox_total", response.data.inbox_info.messagesTotal);
          
          _this.$eventHub.$emit("page_change");

          _this.has_nextPage = response.data.has_nextPage;
          if(!response.data.has_nextPage){
            _this.$eventHub.$emit("disable_nxtBtn", true);
          }
        }).catch(error => {
          console.log(error);
          _this.$notification.error("somthing went wrong", {  timer: 5 });
        });
      }
    },

    dropDownSelect(args){
      console.log(args);
    },

    getContextMenu(args){
      console.log(args);
    },

    rowDataBound(args){
      let _this = this;
      if(!args.data.read){
        args.row.classList.add("font-black");
      }

      if(this.select_option){
        if(this.select_option.text === "Read" && args.data.read){
          this.selected_rows.push(parseInt(args.row.getAttribute("aria-rowindex")));
        }else if(this.select_option.text === "Unread" && !args.data.read){
          this.selected_rows.push(parseInt(args.row.getAttribute("aria-rowindex")));
        }else if(this.select_option.text === "Starred" && args.data.starred){
          this.selected_rows.push(parseInt(args.row.getAttribute("aria-rowindex")));
        }else if(this.select_option.text === "Unstarred" && !args.data.starred){
          this.selected_rows.push(parseInt(args.row.getAttribute("aria-rowindex")));
        }
      }
    },

    dataBound(args){
      this.$refs.grid.ej2Instances.selectRows(this.selected_rows);

      this.selected_rows = [];
      this.select_option = null;
    },

    recordClick(args){
      //Mark As Read on Email Click

      let _this = this;
      if(args.cellIndex > 3 && args.cellIndex < 8){
        axios.get(_this.routes.toggle_route, {
          headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + this.csrf_token,
            "X-CSRF-TOKEN": this.csrf_token
          },
          params: {
            column: "read",
            with: "bodyHtml",
            id: args.rowData.id,
            value: true
          }
        }).then(function (response) {
          // console.log(response.data);
          // _this.viewData[args.rowIndex].read = true;

          _this.$store.dispatch("modify_email_batch", {
            index: args.rowIndex,
            property: "read",
            value: true
          });

          args.row.classList.remove("font-black");
          _this.$store.dispatch("set_email_html_body", response.data.bodyHtml);
          _this.$store.dispatch("set_email_data", response.data.email_data);
          _this.$store.dispatch("set_email_rowData", args.rowData);

          if(response.data.attachments_files.length > 0){
            let files = [];

            response.data.attachments_files.forEach(file => {
              files.push({
                name: file,
                icon: fileIcons.getClassWithColor(file)
              })
            });

            _this.$store.dispatch("set_email_attachments", files);
          }else{
            _this.$store.dispatch("set_email_attachments", null);
          }

        }).catch(error => {
          console.log(error);
          _this.$notification.error("somthing went wrong", {  timer: 5 });
        });
      }
    },

    contextMenuOpen(args){
      //On Context Menu Open
      console.log(args);
      let contextMenuObj = this.$refs.grid.ej2Instances.contextMenuModule.contextMenu;
      console.log(contextMenuObj);
      if(args.rowInfo.rowData){
        if(!args.rowInfo.rowData.read){
          contextMenuObj.showItems(["Mark as read"]);
          contextMenuObj.hideItems(["Mark as unread"]);
        }else{
          contextMenuObj.showItems(["Mark as unread"]);
          contextMenuObj.hideItems(["Mark as read"]);
        }
        if(!args.rowInfo.rowData.deleted) {
          contextMenuObj.showItems(["Delete"]);
          contextMenuObj.hideItems(["Move to Inbox"]);
          contextMenuObj.hideItems(["Delete Forever"]);
        } else {
          contextMenuObj.showItems(["Delete Forever"]);
          contextMenuObj.showItems(["Move to Inbox"]);
          contextMenuObj.hideItems(["Delete"]);
        }
      }else{

      }
      
    },

    rowSelected(args){
      this.current_selected = args.rowIndexes
      this.$store.dispatch("set_rowSelected", this.current_selected)
      this.$store.dispatch("set_actions", {
        read: args.data.read,
        important: args.data.important,
        starred: args.data.starred
      })
      this.$store.dispatch("set_selected_items_dataID", this.$refs.grid.ej2Instances.getSelectedRecords().map(e => e.id));
      
      if(args.rowIndexes){
        this.selected_items_count = args.rowIndexes.length;
        this.$store.dispatch("set_selected_items_count", this.selected_items_count);
        
        // if(args.rowIndexes.length === this.inbox_items){
        //   console.log('selected all');
        //   console.log(args.rowIndexes.length + " = " + this.inbox_items);
        // }
      }else{
        
      }

      if(args.data.length){
        args.data.forEach(value => {
          if(!value.read){
            this.selected_item_unread++;
          }
          if(value.starred){
            this.selected_item_starred++;
          }
          if(value.important){
            this.selected_item_important++;
          }
        });
      }else{
        if(!args.data.read){
          this.selected_item_unread++;
        }
        if(args.data.starred){
          this.selected_item_starred++;
        }
        if(args.data.important){
          this.selected_item_important++;
        }
      }

      //read toggle button condition
      if(this.selected_item_unread === 0){
        this.$eventHub.$emit("items_selected_unread", {
          value: false
        });
      }else{
        this.$eventHub.$emit("items_selected_unread", {
          value: true
        });
      }

      this.$eventHub.$emit("toggle_rowSelected", {
        value: true
      });
    },

    async removeCommon(first, second) {
      const spreaded = await [...first, ...second];
      return spreaded.filter(async(el) => {
        return await !(first.includes(el) && second.includes(el))
      })
    },

    async rowDeselected(args){
      const first = await this.current_selected
      const second = await args.rowIndexes
      await this.$store.dispatch("set_rowSelected", this.removeCommon(first, second))
      await this.$store.dispatch("set_selected_items_dataID", this.$refs.grid.ej2Instances.getSelectedRecords().map(e => e.id));

      if(args.rowIndexes){
        this.selected_items_count = this.selected_items_count - args.rowIndexes.length;
        this.$store.dispatch("set_selected_items_count", this.selected_items_count);
        this.$store.dispatch("set_selected_all_items", false);
        
        if(args.data.length){
          console.log(args.data.length);
          args.data.forEach(value => {
            if(!value.read){
              this.selected_item_unread--;
            }
          });
        }else{
          if(!args.data.read){
            this.selected_item_unread--;
          }
        }

        //read toggle button condition
        if(this.selected_item_unread === 0){
          this.$eventHub.$emit("items_selected_unread", {
            value: false
          });
        }else{
          this.$eventHub.$emit("items_selected_unread", {
            value: true
          });
        }

        if(this.selected_items_count <= 0){
          this.$eventHub.$emit("toggle_rowSelected", {
            value: false
          });
        }
        
      }else{
        this.$eventHub.$emit("toggle_rowSelected", {
          value: false
        });
      }
      
    },

    get_table_index_by_id(id){
      let _this = this;
      this.viewData.forEach(function(data, index) {
        if(data.id === id){
          _this.index = index;
        }
      });
    },

    soft_refresh(){
      this.$refs.grid.ej2Instances.refresh();
      this.ref_headerTemplate.show_loading = false;
    }
  },

  provide: {
    grid: [ContextMenu, Sort, Edit, Page, Toolbar, Search]
  },

  created(){
    let _this = this;

    //Mark email as starred
    this.$eventHub.$on("toggled_starred", (e)=>{
      axios.get(_this.routes.toggle_route, {
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer " + this.csrf_token,
          "X-CSRF-TOKEN": this.csrf_token
        },
        params: {
          column: "starred",
          id: e.id,
          value: e.starred
        }
      }).then(function (response) {
        _this.get_table_index_by_id(e.id);
        // _this.viewData[_this.index].starred = e.starred;

        _this.$store.dispatch("modify_email_batch", {
          index: _this.index,
          property: "starred",
          value: e.starred
        });
        
        console.log(response.data);
      }).catch(error => {
        console.log(error);
        _this.$notification.error("somthing went wrong", {  timer: 5 });
      });
    });

    //Mark email as important
    this.$eventHub.$on("toggled_important", (e)=>{
      axios.get(_this.routes.toggle_route, {
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer " + this.csrf_token,
          "X-CSRF-TOKEN": this.csrf_token
        },
        params: {
          column: "important",
          id: e.id,
          value: e.important
        }
      }).then(function (response) {
        _this.get_table_index_by_id(e.id);
        // _this.viewData[_this.index].important = e.important;

        _this.$store.dispatch("modify_email_batch", {
          index: _this.index,
          property: "important",
          value: e.important
        });

        console.log(response.data.data_update);
      }).catch(error => {
        console.log(error);
        _this.$notification.error("somthing went wrong", {  timer: 5 });
      });
    });
    //Sort by read toggle
    this.$eventHub.$on("read_sort_toggle", (e)=>{
      if(e.value){
        _this.$refs.grid.sortColumn("read", "Ascending");
      }else{
        _this.$refs.grid.sortColumn("read", "Descending");
      }
    });

    //Select by options
    this.$eventHub.$on("select_by", (e)=>{
      if(e.text == "All"){
        let indexes = []
        // for (let i = 0; i < this.viewData.length; i++) {
        //   indexes.push(i)
        // }

        for (let i = 0; i < this.$store.state.email_batch.length; i++) {
          indexes.push(i)
        }

        this.$refs.grid.ej2Instances.selectRows(indexes);
      }else if(e.text === "None"){
        this.$refs.grid.ej2Instances.refresh();
      }else{
        this.select_option = e;
        this.$refs.grid.ej2Instances.refresh();
      }
    });

    //Refresh Inbox
    this.$eventHub.$on("refresh_inbox", (e)=>{
      console.log("refresh_inbox");

      _this.ref_headerTemplate.show_loading = true;
      _this.ref_headerTemplate.loading = true;
      axios.get(this.routes.data_route, {
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer " + this.csrf_token,
          "X-CSRF-TOKEN": this.csrf_token
        },
        params: {
          inbox: this.current_inbox.name,
          label_id: this.current_inbox.id,
          option: this.current_inbox.type == 1 ? 'labeled' : null
        }
      }).then(function (response) {
        _this.$store.dispatch("set_email_batch", formatDate(response.data.repackaged_data));

        _this.ref_headerTemplate.show_loading = false;
          _this.ref_headerTemplate.loading = false;
        _this.$eventHub.$emit("stop_loading", {
          event: "stop_loading"
        });
      }).catch(error => {
        console.log(error);
        _this.$notification.error("somthing went wrong", {  timer: 5 });
      });
    });

    //Search Inbox
    this.$eventHub.$on("search_inbox", async (e)=>{
      //May do reworks here (putting this axios request to searchbar comp itself)
      _this.ref_headerTemplate.show_loading = true;
      _this.ref_headerTemplate.loading = true;

      let command = `in:${this.$store.state.current_inbox.name}`

      console.log('COMMAND IS IN OR LABEL', e.command === "in")

      if(e.command === "in" || e.command === "label") {
        command = `${e.command}:${e.query}`
      }

      //sets the search text
      let searchQuery = ""
      if(this.$store.state.search !== "") {
        searchQuery = this.$store.state.search
        // searchQuery = this.$store.state.search.split(":")
        // if(searchQuery.length > 1) {
        //   searchQuery = searchQuery[1].replace(/[()]/g, '')
        // }
      }
      // this.$refs.grid.search(searchQuery)
      if(this.$store.state.searchCommand === "from") {
        this.$store.dispatch("set_searchOptions", ['sender'])
      } else if(this.$store.state.searchCommand === "to") {
        this.$store.dispatch("set_searchOptions", ['receiver'])
      }

      if(Array.isArray(searchQuery)) {
        searchQuery = searchQuery[0]
      }

      console.log(`query --- ${command} ${searchQuery}`)

      await axios.get(this.routes.data_route, {
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer " + this.csrf_token,
          "X-CSRF-TOKEN": this.csrf_token
        },
        params: {
          inbox: this.$store.state.current_inbox.name,
          command,
          option: "search",
          query: searchQuery
        }
      }).then(function (response) {
        let payload = response.data;
        console.log(response);
        console.log("ref_headerTemplate");
        console.log(_this.ref_headerTemplate.loading);
        _this.ref_headerTemplate.show_loading = false;
        _this.ref_headerTemplate.loading = false;
        // _this.$store.dispatch("set_max_page", Math.ceil(payload.inbox_items_length / 50));
        _this.$store.dispatch("set_email_batch", formatDate(payload.repackaged_data));
        _this.$store.dispatch("set_inbox_items", payload.inbox_items_length);
        _this.$store.dispatch("set_inbox_total", payload.inbox_info.messagesTotal);
        // _this.$store.dispatch("set_user_labels", payload.labels);
        
        _this.$eventHub.$emit("page_change");

        _this.has_nextPage = payload.has_nextPage;
        if(!payload.has_nextPage){
          _this.$eventHub.$emit("disable_nxtBtn", true);
        }
      }).catch(error => {
        console.log(error);
        this.$notification.error("somthing went wrong", {  timer: 5 });
      });
    });

    //Next Page
    this.$eventHub.$on("page_next", (e) =>{
      console.log(_this.current_inbox);
      if(_this.has_nextPage){
        let this_page = _this.current_page;
        this_page++;
        _this.$store.dispatch("set_current_page", this_page);
        
        axios.get(this.routes.data_route, {
          headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + this.csrf_token,
            "X-CSRF-TOKEN": this.csrf_token
          },
          params: {
            option: "get_next_page",
            inbox: _this.current_inbox.name,
            page: _this.current_page
          }
        }).then(function (response) {
          _this.$store.dispatch("set_email_batch", formatDate(response.data.repackaged_data));
          _this.$store.dispatch("set_inbox_items", response.data.inbox_items_length);
          
          _this.$eventHub.$emit("page_change", "page_next");

          _this.has_nextPage = response.data.has_nextPage;
          if(!response.data.has_nextPage){
            _this.$eventHub.$emit("disable_nxtBtn", true);
          }
          _this.$eventHub.$emit("disable_prevBtn", false);
        }).catch(error => {
          console.log(error);
          alert("somthing went wrong");
        });
      }
      
      
    });

    this.$eventHub.$on("page_prev", (e) =>{
      if(_this.current_page > 0){
        let this_page = _this.current_page;
        this_page--;
        _this.$store.dispatch("set_current_page", this_page);
        
        axios.get(this.routes.data_route, {
          headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + this.csrf_token,
            "X-CSRF-TOKEN": this.csrf_token
          },
          params: {
            option: "get_next_page",
            inbox: _this.current_inbox.name,
            page: _this.current_page
          }
        }).then(function (response) {
          _this.$store.dispatch("set_email_batch", formatDate(response.data.repackaged_data));
          _this.$store.dispatch("set_inbox_items", response.data.inbox_items_length);
          
          _this.$eventHub.$emit("page_change", "page_prev");

          _this.has_nextPage = response.data.has_nextPage;
          if(_this.current_page === 0){
            _this.$eventHub.$emit("disable_prevBtn", true);
          }
          _this.$eventHub.$emit("disable_nxtBtn", false);
        }).catch(error => {
          console.log(error);
          alert("somthing went wrong");
        });
      }
      
    });
  }
};
</script>


