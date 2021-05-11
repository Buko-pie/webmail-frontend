<template>
  <div id="app">
    <ejs-grid :dataSource="localData">
      <e-columns>
        <e-column type='checkbox' :allowFiltering='false' :allowSorting='false' width='60'></e-column>
        <e-column field='sender' headerText=''></e-column>
        <e-column field='message' headerText=''></e-column>
        <e-column field='date' headerText='' text-align="Right"></e-column>
      </e-columns>
    </ejs-grid>
  </div>
</template>

<script>
import Vue from "vue";
import { GridPlugin } from "@syncfusion/ej2-vue-grids";
Vue.use(GridPlugin);

export default{
  data(){
    return{
      localData: [
        { sender: "Gmail", message: "test1", date: "May 1" },
        { sender: "Test1", message: "test2", date: "May 2" },
        { sender: "John Doe", message: "test3", date: "May 3" },
        { sender: "Emily Doe", message: "test4", date: "May 4" },
        { sender: "James Baxter", message: "test5", date: "May 5" },
      ]
    }
  },
  mounted(){
    console.log("vue-grids mounted");
  }
}
</script>

<style>
  @import url(https://cdn.syncfusion.com/ej2/material.css);
</style>
