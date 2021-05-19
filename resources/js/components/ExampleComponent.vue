<template>
  <div>
    <ejs-grid 
      :dataSource="localData" 
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
        <e-column field='date' headerText='' width='80' text-align="Right"></e-column>
      </e-columns>
    </ejs-grid>
    <!-- <ejs-contextmenu target='#target' :items='menuItems' :select='onSelect'></ejs-contextmenu> -->
    <button @click="Custom()">
      test
    </button>
  </div>
</template>

<script>
import Vue from "vue";
import { GridPlugin, ContextMenu, Sort, Edit, Page } from "@syncfusion/ej2-vue-grids";

let starred_template = Vue.component("starred_template", require("./subcomponents/StarredTemplate.vue").default);
let important_template = Vue.component("important_template", require("./subcomponents/ImportantTemplate.vue").default);
let attachment_template = Vue.component("important_template", require("./subcomponents/AttachmentTemplate.vue").default);

Vue.use(GridPlugin);

function Custom() {
  console.log("bruh");
}

export default{
  props:{
    custom_labels: Array
  },

  data(){
    return{
      localData: [
        { id: 0, starred: true, important: true, sender: "Gmail", message: "test1", date: "May 1", read: false, has_attachment: false, labels:[]} ,
        { id: 1, starred: false, important: false, sender: "Test1", message: "test2", date: "May 2", read: false, has_attachment: true, labels:[] },
        { id: 2, starred: true, important: false, sender: "John Doe", message: "test3", date: "May 3", read: false, has_attachment: true, labels:[] },
        { id: 3, starred: false, important: true, sender: "Emily Doe", message: "test4", date: "May 4", read: false, has_attachment: false, labels:[] },
        { id: 4, starred: true, important: false, sender: "James Baxter", message: "test5", date: "May 5", read: false, has_attachment: false, labels:[] }
      ],
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

    if(this.custom_labels.length > 0){
      this.custom_labels.forEach(function(){

      });
      console.log(this.custom_labels);
    }
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
    getContextMenu:function(args){
      console.log("bruh");
    },
    Custom(){
      console.log("bruh");
    }
  },

  provide: {
    grid: [ContextMenu, Sort, Edit, Page]
  }
}
</script>

<style>
  @import url(https://cdn.syncfusion.com/ej2/material.css);
</style>
