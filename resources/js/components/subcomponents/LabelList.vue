<script>

export default({
  name: "LabelList",
  props:{
    items: { type: Object, required: true }
  },
  functional: true,
  render: function(createElement, { props, children }){
    let el = [];
    for(var label in props.items){
      // console.log(props.items[item]);
      let item = props.items[label];
      
      el.push(createElement('label-item',{ props: {item, label} }));
    }
    return el;
    // let labels  = props.items;
    // return Object.keys(labels).map((item)=>createElement('label-item',{ props: {item} }));

    // return Object.keys(labels).map(function(key,index){
    //   let item = labels[key];
    //   createElement('label-item',{ props: {key} })
    // });
  }
})
</script>
