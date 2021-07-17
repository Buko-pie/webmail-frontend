<template>
  <div :start="start">
    <div :get_splitter_pane_0_height="get_splitter_pane_0_height" :splitter_height="splitter_height"></div>
    <ejs-grid
      :height="inbox_height - 155.5"
      ref="grid"
      id="gridcomp"
      :dataSource="gmail_data" 
      :selectionSettings="selectionSettings" 
      :allowPaging="true"
      :allowSorting="true"
      :contextMenuItems="menuItems"
      :contextMenuClick="onSelect"
      :contextMenuOpen="contextMenuOpen"
      :rowDataBound="rowDataBound"
      :dataBound="dataBound"
      :recordClick="recordClick"
      :rowSelected="rowSelected"
      :rowDeselected="rowDeselected"
      :pagerTemplate="pagination_template"
      :pageSettings="pageSettings"
      :data="routes"
    >
      <e-columns>
        <e-column headerText="" :headerTemplate="header_template" :columns="custom_column"></e-column>
      </e-columns>
    </ejs-grid>
  </div>
</template>

<script>
import Vue from "vue";
import moment from "moment";
import VueNotification from "@kugatsu/vuenotification";
import { GridPlugin, ContextMenu, Sort, Edit, Page } from "@syncfusion/ej2-vue-grids";

const fileIcons = require("file-icons-js");
let header_template = Vue.component("header-template", require("./subcomponents/HeaderTemplate.vue").default);
let subheader_template = Vue.component("subheader-template", require("./subcomponents/SubheaderTemplate.vue").default);
let starred_template = Vue.component("starred-template", require("./subcomponents/StarredTemplate.vue").default);
let important_template = Vue.component("important-template", require("./subcomponents/ImportantTemplate.vue").default);
let message_template = Vue.component("message-template", require("./subcomponents/MessageTemplate.vue").default);
let attachment_template = Vue.component("important-template", require("./subcomponents/AttachmentTemplate.vue").default);
let pagination_template = Vue.component("pagerTemplate", require("./subcomponents/PaginationTemplate.vue").default);

Vue.use(GridPlugin);
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
      csrf_token: null,
      index: 0,
      current_page: 1,
      max_pages: null,
      email_count: null,
      routes: null,
      selected_item_unread: 0,
      selected_items_count: 0,
      inbox_height: null,
      pageSettings: { pageSize: 50, pageCount: 3 },
      test:["read", "ascending"],
      viewData: [],
      menuItems:[
        { id: "mark_unread", text: "Mark as unread" },
        { id: "mark_read", text: "Mark as read" },
        // { text: "Add Label" }
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
          width: "38",
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
          minWidth: "100",
          width: "150",
          maxWidth: "160",
          allowSorting: false
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
          width: "60",
          "text-align": "Right",
          template(){
            return{ template: attachment_template }
          },
          allowSorting: false
        },{
          //Column - Created At
          field: "created_at",
          headerText: "",
          width: "100",
          "text-align": "Right",
          allowSorting: false
        },{
          //Column - Read
          field: "read",
          headerText: "",
          "text-align": "Right",
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
    start(){
      console.log("vue-grids computed")
      this.routes = this.$store.state.routes;
      this.csrf_token = this.$store.state.csrf_token;
      console.log(this.routes)
    },

    splitter_height(){
      this.inbox_height = (this.$store.state.splitter_height / 2);
    },

    get_splitter_pane_0_height(){
      this.inbox_height = this.$store.state.splitter_pane_0_height;
    },

    gmail_data(){
      return this.$store.state.email_batch;
    }
  },

  mounted(){
    console.log("vue-grids mounted");
    let _this = this;

    axios.get(this.routes.data_route, {
      headers: {
        "Content-Type": "application/json",
        "Authorization": "Bearer " + this.csrf_token,
        "X-CSRF-TOKEN": this.csrf_token
      },
      params: {
        option: "get_all"
      }
    }).then(function (response) {
      // _this.viewData = formatDate(response.data.repackaged_data);
      _this.email_count = response.data.inbox_items_length;
      _this.max_pages = Math.ceil(response.data.inbox_items_length / 50);
      _this.$store.dispatch("set_email_batch", formatDate(response.data.repackaged_data));
    }).catch(error => {
      console.log(error);
      this.$notification.error("somthing went wrong", {  timer: 5 });
    });
  },

  methods:{
    onChange(value){
      this.value = value;
      this.$emit("change", value);
    },
    onSelect(args) {

      let _this = this;
      if(args.item.text === "Add Label") {
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
          console.log(response.data);
          // _this.viewData[args.rowIndex].read = true;

          _this.$store.dispatch("modify_email_batch", {
            index: args.rowIndex,
            property: "read",
            value: true
          });

          args.row.classList.remove("font-black");
          _this.$store.dispatch("set_email_html_body", response.data.bodyHtml);
          _this.$store.dispatch("set_email_data", response.data.email_data);

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
      let contextMenuObj = this.$refs.grid.ej2Instances.contextMenuModule.contextMenu;
      if(!args.rowInfo.rowData.read){
        contextMenuObj.showItems(["Mark as read"]);
        contextMenuObj.hideItems(["Mark as unread"]);
      }else{
        contextMenuObj.showItems(["Mark as unread"]);
        contextMenuObj.hideItems(["Mark as read"]);
      }
    },

    rowSelected(args){
      this.$store.dispatch("set_selected_items_dataID", this.$refs.grid.ej2Instances.getSelectedRecords().map(e => e.id));

      if(args.rowIndexes){
        this.selected_items_count = args.rowIndexes.length;
        this.$store.dispatch("set_selected_items_count", this.selected_items_count);
      }

      if(args.data.length){
        args.data.forEach(value => {
          if(!value.read){
            this.selected_item_unread++;
          }
        });
      }else{
        if(!args.data.read){
          this.selected_item_unread++;
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

    rowDeselected(args){
      this.$store.dispatch("set_selected_items_dataID", this.$refs.grid.ej2Instances.getSelectedRecords().map(e => e.id));

      if(args.rowIndexes){
        this.selected_items_count = this.selected_items_count - args.rowIndexes.length;
        this.$store.dispatch("set_selected_items_count", this.selected_items_count);
        
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
  },

  provide: {
    grid: [ContextMenu, Sort, Edit, Page]
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

      axios.get(this.routes.data_route, {
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer " + this.csrf_token,
          "X-CSRF-TOKEN": this.csrf_token
        },
        params: {
          option: "get_all"
        }
      }).then(function (response) {
        _this.$store.dispatch("set_email_batch", formatDate(response.data.repackaged_data));

        _this.$eventHub.$emit("stop_loading", {
          event: "stop_loading"
        });
      }).catch(error => {
        console.log(error);
        _this.$notification.error("somthing went wrong", {  timer: 5 });
      });
    });

    this.$eventHub.$on("page_next", (e) =>{
      //========== This part is still WIP ==========
      axios.get(this.routes.data_route, {
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer " + this.csrf_token,
          "X-CSRF-TOKEN": this.csrf_token
        },
        params: {
          option: "get_next_page",
          page: this.current_page
        }
      }).then(function (response) {
        _this.current_page++;
        _this.$store.dispatch("set_email_batch", formatDate(response.data.repackaged_data));
        if(_this.current_page === _this.max_pages){
          this.$eventHub.$emit("pagination_test");
        }
      }).catch(error => {
        console.log(error);
        alert("somthing went wrong");
      });
    });
  }
};
</script>


