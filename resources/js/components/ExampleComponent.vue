<template>
  <div>
    <ejs-grid 
      :dataSource="viewData" 
      :selectionSettings="selectionSettings" 
      :allowPaging='true' 
      :allowSorting='true'
      :contextMenuItems="menuItems"
      :contextMenuClick="onSelect"
      :contextMenuOpen="contextMenuOpen"
      :rowDataBound="rowDataBound"
      :recordClick="recordClick"
      :rowSelected="rowSelected"
    >
      <e-columns>
        <e-column type='checkbox' :allowFiltering='false' :allowSorting='false' width='45'></e-column>
        <e-column field='id' headerText='' width='38' :template='starred_template' :visible='false'></e-column>
        <e-column field='starred' headerText='' width='38' :template='starred_template'></e-column>
        <e-column field='important' headerText='' width='40' :template='important_template'></e-column>
        <e-column field='sender' headerText='' minWidth="100" width="150" maxWidth="160"></e-column>
        <e-column field='message' headerText=''></e-column>
        <e-column field='has_attachment' width='60' text-align="Right" headerText='' :template='attachment_template'></e-column>
        <e-column field='created_at' headerText='' width='150' text-align="Right"></e-column>
      </e-columns>
    </ejs-grid>
  </div>
</template>

<script>
import Vue from "vue";
import { GridPlugin, ContextMenu, Sort, Edit, Page } from "@syncfusion/ej2-vue-grids";

let starred_template = Vue.component("starred_template", require("./subcomponents/StarredTemplate.vue").default);
let important_template = Vue.component("important_template", require("./subcomponents/ImportantTemplate.vue").default);
let attachment_template = Vue.component("important_template", require("./subcomponents/AttachmentTemplate.vue").default);

Vue.use(GridPlugin);
Vue.prototype.$eventHub = new Vue()

export default{
  props:{
    custom_labels: Array,
    routes: Object
  },

  data(){
    return{
      index: 0,
      viewData: [],
      menuItems:[
        { id: 0, text: "Mark As Unread" },
        // { text: "Add Label" }
      ],
      filter: {
        type: "CheckBox"
      },
      starred_template: function(){
        return{
          template: starred_template
        }
      },
      important_template: function(){
        return{
          template: important_template
        }
      },
      attachment_template: function(){
        return{
          template: attachment_template
        }
      },
      selectionSettings: { checkboxOnly: true }
    }
  },

  mounted(){
    console.log("vue-grids mounted");
    // this.viewData = this.localData;
    let _this = this;
    
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
      _this.viewData = response.data.dummy_data;
      console.log(response.data.dummy_data);
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
    onSelect: function(args) {

      let _this = this;
      if(args.item.text === "Add Label") {
        //Add Label
        let row_data = args.rowInfo.rowData;
        /////Last construction here
        this.custom_labels.push({id:this.custom_labels.length , title: "Label_" + this.custom_labels.length});
        args.rowInfo.rowData.labels.push("Label_" + this.custom_labels.length);
        console.log(row_data);
        console.log(this.custom_labels);
      }else if(args.item.text === "Mark As Unread"){
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
      }
    },
    getContextMenu: function(args){
      console.log("bruh");
    },
    rowDataBound(args){
      console.log(args);
      if(!args.data.read){
        args.row.classList.add("font-black");
      }
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

        }).catch(error => {
          console.log(error);
          alert("somthing went wrong");
        });
       
      }
    },
    contextMenuOpen(args){
      //On Context Menu Open
      
    },
    rowSelected(args){
      console.log(args);
      if(!args.data.read){
        this.menuItems[0].text = "Mark As Read";

        console.log(this.menuItems);
      }
    },
    get_table_index_by_id(id){
      let _this = this;
      this.viewData.forEach(function(data, index) {
        if(data.id === id){
          _this.index = index;
        }
      });
    }
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
  }
}
</script>


