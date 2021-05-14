<template>
<div class="e-input-group">
  <input class="e-input" type="text" placeholder="Date of Birth">
  <span class="e-input-group-icon e-input-calendar"></span>
</div>

</template>

<script>
import Vue from "vue";
import { TextBoxPlugin } from '@syncfusion/ej2-vue-inputs';
Vue.use(TextBoxPlugin);

export default Vue.extend({
  data: function() {
    return { }
  },
  mounted: function() {
    let input = document.querySelectorAll('.e-input-group .e-input,.e-float-input.e-input-group input');
    let inputIcon = document.querySelectorAll('.e-input-group-icon');
    let localObj = this;

    for (let i = 0; i < input.length; i++) {
      //Focus Event binding for input component
      input[i].addEventListener('focus', function() {
          localObj.getParentNode(input[i]).classList.add('e-input-focus');
      });
      //Blur Event binding for input component
      input[i].addEventListener('blur', function() {
        localObj.getParentNode(input[i]).classList.remove('e-input-focus');
      });
    }

    for (let i = 0; i < inputIcon.length; i++) {
      inputIcon[i].addEventListener('mousedown',  function() {
        this.classList.add('e-input-btn-ripple');
      });
      inputIcon[i].addEventListener('mouseup',  function() {
          let ele = this;
          setTimeout( function() {
            ele.classList.remove('e-input-btn-ripple'); 
          }, 500);
      });
    }
  },
  methods: {
    getParentNode: function(element) {
      let parentNode = element.parentNode;
      if (parentNode.classList.contains('e-input-in-wrap')) {
        return parentNode.parentNode;
      }
      return parentNode;
    }
  }
});


</script>
