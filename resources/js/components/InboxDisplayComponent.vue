<template>
  <div :start="start">
    <ejs-grid
      height=auto
      ref="grid"
      id="gridcomp"
      :dataSource="viewData" 
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
import { GridPlugin, ContextMenu, Sort, Edit, Page } from "@syncfusion/ej2-vue-grids";

let header_template = Vue.component("header-template", require("./subcomponents/HeaderTemplate.vue").default);
let subheader_template = Vue.component("subheader-template", require("./subcomponents/SubheaderTemplate.vue").default);
let starred_template = Vue.component("starred-template", require("./subcomponents/StarredTemplate.vue").default);
let important_template = Vue.component("important-template", require("./subcomponents/ImportantTemplate.vue").default);
let message_template = Vue.component("message-template", require("./subcomponents/MessageTemplate.vue").default);
let attachment_template = Vue.component("important-template", require("./subcomponents/AttachmentTemplate.vue").default);

Vue.use(GridPlugin);
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

export default({
  name: "InboxDisplayComponent",
  props:{
    custom_labels: Array,
    // routes: Object
  },

  data(){
    return{
      index: 0,
      routes: null,
      selected_item_unread: 0,
      selected_items_count: 0,
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
          headerTemplate: function(){
            return{
              template: subheader_template
            }
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
          template: function(){
            return{
              template: starred_template
            }
          },
          allowSorting: false
        },{
          //Column - Important
          field: "important",
          headerText: "",
          width: "40",
          allowSorting: false,
          template: function(){
            return{
              template: important_template
            }
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
          template: function(){
            return{
              template: message_template
            }
          }
        },{
          //Column - Has attachment
          field: "has_attachment",
          headerText: "",
          width: "60",
          "text-align": "Right",
          template: function(){
            return{
              template: attachment_template
            }
          },
          allowSorting: false
        },{
          //Column - Created At
          field: "created_at",
          headerText: "",
          width: "80",
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
      header_template: function(){
        return{
          template: header_template
        }
      },
      selectionSettings: { checkboxOnly: true }
    }
  },


  computed:{
    start(){
      console.log("vue-grids computed")
      this.routes = this.$store.state.routes;
      console.log(this.routes)
    },
  },

  mounted(){
    console.log("vue-grids mounted");
    // this.viewData = this.localData;
    let _this = this;
    // this.routes = this.$store.state.routes;
    console.log(this.routes)
    axios({
      method: "GET",
      url: this.routes.data_route,
      headers: {
        "Content-Type": "application/json",
        "Authorization": "Bearer {{ csrf_token() }}"
      },
      params: {
        token: "{{ csrf_token() }}",
        option: "get_all"
      }
    }).then(function (response) {
      let data = response.data.repackaged_data;
      _this.viewData = response.data.repackaged_data;
      console.log(response.data.gmail_data);
      console.log(response.data.data_1);
      console.log(response.data.repackaged_data);
    }).catch(error => {
      console.log(error);
      alert("somthing went wrong");
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
        /////Last construction here on add custome labels context menu
        this.custom_labels.push({id:this.custom_labels.length , title: "Label_" + this.custom_labels.length});
        args.rowInfo.rowData.labels.push("Label_" + this.custom_labels.length);
        console.log(row_data);
        console.log(this.custom_labels);
      }else if(args.item.text === "Mark as unread"){
        //Mark As Unread
        axios({
          method: "GET",
          url: _this.routes.toggle_route,
          headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer {{ csrf_token() }}"
          },
          params: {
            token: "{{ csrf_token() }}",
            column: "read",
            id: args.rowInfo.rowData.id,
            value: false
          }
        }).then(function (response) {
          _this.viewData[args.rowInfo.rowIndex].read = 0;
          args.rowInfo.row.classList.add("font-black");

        }).catch(error => {
          console.log(error);
          alert("somthing went wrong");
        });
      }else if(args.item.text === "Mark as read"){
        //Mark as read
        axios({
          method: "GET",
          url: _this.routes.toggle_route,
          headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer {{ csrf_token() }}"
          },
          params: {
            token: "{{ csrf_token() }}",
            column: "read",
            id: args.rowInfo.rowData.id,
            value: true
          }
        }).then(function (response) {
          _this.viewData[args.rowInfo.rowIndex].read = 1;
          args.rowInfo.row.classList.remove("font-black");

        }).catch(error => {
          console.log(error);
          alert("somthing went wrong");
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

    //Mark As Read on Email Click
    recordClick(args){
      let _this = this;
      if(args.cellIndex > 3 && args.cellIndex < 8){
        axios({
          method: "GET",
          url: _this.routes.toggle_route,
          headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer {{ csrf_token() }}"
          },
          params: {
            token: "{{ csrf_token() }}",
            column: "read",
            id: args.rowData.id,
            value: true
          }
        }).then(function (response) {
          _this.viewData[args.rowIndex].read = 1;
          args.row.classList.remove("font-black");
          _this.$store.dispatch("set_email_html_body", response.data.bodyHtml);

        }).catch(error => {
          console.log(error);
          alert("somthing went wrong");
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
      // console.log(e);

      axios({
        method: "GET",
        url: _this.routes.toggle_route,
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer {{ csrf_token() }}"
        },
        params: {
          token: "{{ csrf_token() }}",
          column: "starred",
          id: e.id,
          value: e.starred
        }
      }).then(function (response) {
        _this.get_table_index_by_id(e.id);
        _this.viewData[_this.index].starred = e.starred;
        
        console.log(response.data.data_update);
      }).catch(error => {
        console.log(error);
        alert("somthing went wrong");
      });

    });
    //Mark email as important
    this.$eventHub.$on("toggled_important", (e)=>{
      // console.log(e);

      axios({
        method: "GET",
        url: _this.routes.toggle_route,
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer {{ csrf_token() }}"
        },
        params: {
          token: "{{ csrf_token() }}",
          column: "important",
          id: e.id,
          value: e.important
        }
      }).then(function (response) {
        _this.get_table_index_by_id(e.id);
        _this.viewData[_this.index].important = e.important;

        console.log(response.data.data_update);
      }).catch(error => {
        console.log(error);
        alert("somthing went wrong");
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
        for (let i = 0; i < this.viewData.length; i++) {
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

      axios({
        method: "GET",
        url: this.routes.data_route,
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer {{ csrf_token() }}"
        },
        params: {
          token: "{{ csrf_token() }}",
          option: "get_all"
        }
      }).then(function (response) {
        let data = response.data.dummy_data;
        _this.viewData = formatDate(data);
        _this.$eventHub.$emit("stop_loading", {
          event: "stop_loading"
        });
      }).catch(error => {
        console.log(error);
        alert("somthing went wrong");
      });
    });
  }
});
</script>


