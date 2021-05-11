<template>
  <div id="app">
    <ejs-grid :dataSource="localData"></ejs-grid>
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
        { OrderID: 12345, CustomerID: "bruh", Price: 12.34 },
        { OrderID: 12346, CustomerID: "test", Price: 5 },
        { OrderID: 12347, CustomerID: "ee", Price: 14.34 },
        { OrderID: 12348, CustomerID: "bwruh", Price: 22.34 },
        { OrderID: 12349, CustomerID: "bruwah", Price: 15.34 },
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
