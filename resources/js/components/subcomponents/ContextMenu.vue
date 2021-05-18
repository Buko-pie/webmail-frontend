<script>
import Vue from "vue";
import { ContextMenuPlugin } from "@syncfusion/ej2-vue-navigations";

Vue.use(ContextMenuPlugin);

export default Vue.extend({
  data: function() {
    return {
      menuItems: [
        {
          text: "Cut",
          iconCss: "e-cm-icons e-cm-cut"
        },
        {
          text: "Copy",
          iconCss: "e-cm-icons e-cm-copy"
        },
        {
          text: "Paste",
          iconCss: "e-cm-icons e-cm-paste",
          items: [
            {
              text: "Paste Text",
              iconCss: "e-cm-icons e-cm-pastetext"
            },
            {
              text: "Paste Special",
              iconCss: "e-cm-icons e-cm-pastespecial"
            }
          ]
        },
        {
          separator: true
        },
        {
          text: "Link",
          iconCss: "e-cm-icons e-cm-link"
        },
        {
          text: "New Comment",
          iconCss: "e-cm-icons e-cm-comment"
        }
      ]
    };
  },
  methods: {
    beforeItemRender: function(args) {
      if (args.item.text === "Link") {
        args.element.classList.add("e-disabled");
      }
    }
  },
  mounted: function() {
    console.log("context menu loaded");
  }
});
</script>