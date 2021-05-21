<template>
  <div>
    <ejs-grid 
      :dataSource="viewData" 
      :selectionSettings="selectionSettings" 
      :allowPaging='true' 
      :allowSorting='true'
      :contextMenuItems="menuItems"
      :contextMenuClick="onSelect"
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
      localData: [
        { id: 0, starred: true, important: true, sender: "Gmail", message: "test1", created_at: "May 1", read: false, has_attachment: false, labels:[]} ,
        { id: 1, starred: false, important: false, sender: "Test1", message: "test2", created_at: "May 2", read: false, has_attachment: true, labels:[] },
        { id: 2, starred: true, important: false, sender: "John Doe", message: "test3", created_at: "May 3", read: false, has_attachment: true, labels:[] },
        { id: 3, starred: false, important: true, sender: "Emily Doe", message: "test4", created_at: "May 4", read: false, has_attachment: false, labels:[] },
        { id: 4, starred: true, important: false, sender: "James Baxter", message: "test5", created_at: "May 5", read: false, has_attachment: false, labels:[] }
      ],
      viewData: [],
      menuItems:[
        { text: "Add Label" }
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
      console.log(args);
      if(args.item.text === "Add Label") {
        let row_data = args.rowInfo.rowData;
        /////Last construction here
        this.custom_labels.push({id:this.custom_labels.length , title: "Label_" + this.custom_labels.length});
        args.rowInfo.rowData.labels.push("Label_" + this.custom_labels.length);
        console.log(row_data);
        console.log(this.custom_labels);
      }
    },
    getContextMenu: function(args){
      console.log("bruh");
    },
    Custom(){
      console.log("bruh");
    }
  },

  provide: {
    grid: [ContextMenu, Sort, Edit, Page]
  },

  created(){
    let _this = this;

    this.$eventHub.$on("toggled_starred", (e)=>{
      console.log(e);

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
        _this.viewData[e.id].starred = e.starred;
        console.log(response.data.data_update);
      }).catch(error => {
        console.log(error);
        alert("somthing went wrong");
      });

    });

    this.$eventHub.$on("toggled_important", (e)=>{
      console.log(e);

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
        _this.viewData[e.id].important = e.important;
        //DO:: find id function
        console.log(response.data.data_update);
      }).catch(error => {
        console.log(error);
        alert("somthing went wrong");
      });
    });
  }
}
</script>

<style>
  @import url(https://cdn.syncfusion.com/ej2/material.css);
</style>
