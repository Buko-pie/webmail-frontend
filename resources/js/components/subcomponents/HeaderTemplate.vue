<template>
  <div class="flex">
    <div e-mappinguid="grid-column0">
      <div class="e-checkbox-wrapper e-css">
        <input class="e-checkselectall e-focus" type="checkbox">
        <span class="e-frame e-icons e-uncheck"></span>
        <span class="e-label"> </span>
      </div>
    </div>
  </div>
</template>

<script>
export default({
  data(){
    return{
      data:{},
      false: "false",
      true: "true"
    };
  }
});
</script>