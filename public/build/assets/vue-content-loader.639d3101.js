import{J as l,I as a,_ as t,m as s}from"./@vue.04713aa0.js";var c=function(){return Math.random().toString(36).substring(2)},h=l({name:"ContentLoader",props:{width:{type:[Number,String]},height:{type:[Number,String]},viewBox:{type:String},preserveAspectRatio:{type:String,default:"xMidYMid meet"},speed:{type:Number,default:2},baseUrl:{type:String,default:""},primaryColor:{type:String,default:"#f9f9f9"},secondaryColor:{type:String,default:"#ecebeb"},primaryOpacity:{type:Number,default:1},secondaryOpacity:{type:Number,default:1},uniqueKey:{type:String},animate:{type:Boolean,default:!0}},setup:function(e){var r=a(function(){return e.uniqueKey?"".concat(e.uniqueKey,"-idClip"):c()}),u=a(function(){return e.uniqueKey?"".concat(e.uniqueKey,"-idGradient"):c()}),o=a(function(){var i;return(i=e.width)!==null&&i!==void 0?i:400}),d=a(function(){var i;return(i=e.height)!==null&&i!==void 0?i:130}),y=a(function(){var i;return(i=e.viewBox)!==null&&i!==void 0?i:"0 0 ".concat(o.value," ").concat(d.value)});return{idClip:r,idGradient:u,computedViewBox:y}},render:function(){return t("svg",{width:this.width,height:this.height,viewBox:this.computedViewBox,version:"1.1",preserveAspectRatio:this.preserveAspectRatio},[t("rect",{style:{fill:"url(".concat(this.baseUrl,"#").concat(this.idGradient,")")},"clip-path":"url(".concat(this.baseUrl,"#").concat(this.idClip,")"),x:"0",y:"0",width:"100%",height:"100%"},null),t("defs",null,[t("clipPath",{id:this.idClip},[this.$slots.default?this.$slots.default():t("rect",{x:"0",y:"0",rx:"5",ry:"5",width:"100%",height:"100%"},null)]),t("linearGradient",{id:this.idGradient},[t("stop",{offset:"0%","stop-color":this.primaryColor,"stop-opacity":this.primaryOpacity},[this.animate?t("animate",{attributeName:"offset",values:"-2; 1",dur:"".concat(this.speed,"s"),repeatCount:"indefinite"},null):null]),t("stop",{offset:"50%","stop-color":this.secondaryColor,"stop-opacity":this.secondaryOpacity},[this.animate?t("animate",{attributeName:"offset",values:"-1.5; 1.5",dur:"".concat(this.speed,"s"),repeatCount:"indefinite"},null):null]),t("stop",{offset:"100%","stop-color":this.primaryColor,"stop-opacity":this.primaryOpacity},[this.animate?t("animate",{attributeName:"offset",values:"-1; 2",dur:"".concat(this.speed,"s"),repeatCount:"indefinite"},null):null])])])])}});l(function(n,e){var r=e.attrs;return function(){return t(h,r,{default:function(){return[t("circle",{cx:"10",cy:"20",r:"8"},null),t("rect",{x:"25",y:"15",rx:"5",ry:"5",width:"220",height:"10"},null),t("circle",{cx:"10",cy:"50",r:"8"},null),t("rect",{x:"25",y:"45",rx:"5",ry:"5",width:"220",height:"10"},null),t("circle",{cx:"10",cy:"80",r:"8"},null),t("rect",{x:"25",y:"75",rx:"5",ry:"5",width:"220",height:"10"},null),t("circle",{cx:"10",cy:"110",r:"8"},null),t("rect",{x:"25",y:"105",rx:"5",ry:"5",width:"220",height:"10"},null)]}})}});l(function(n,e){var r=e.attrs;return function(){return t(h,r,{default:function(){return[t("rect",{x:"0",y:"0",rx:"3",ry:"3",width:"70",height:"10"},null),t("rect",{x:"80",y:"0",rx:"3",ry:"3",width:"100",height:"10"},null),t("rect",{x:"190",y:"0",rx:"3",ry:"3",width:"10",height:"10"},null),t("rect",{x:"15",y:"20",rx:"3",ry:"3",width:"130",height:"10"},null),t("rect",{x:"155",y:"20",rx:"3",ry:"3",width:"130",height:"10"},null),t("rect",{x:"15",y:"40",rx:"3",ry:"3",width:"90",height:"10"},null),t("rect",{x:"115",y:"40",rx:"3",ry:"3",width:"60",height:"10"},null),t("rect",{x:"185",y:"40",rx:"3",ry:"3",width:"60",height:"10"},null),t("rect",{x:"0",y:"60",rx:"3",ry:"3",width:"30",height:"10"},null)]}})}});l(function(n,e){var r=e.attrs;return function(){return t(h,r,{default:function(){return[t("rect",{x:"70",y:"15",rx:"4",ry:"4",width:"117",height:"6.4"},null),t("rect",{x:"70",y:"35",rx:"3",ry:"3",width:"85",height:"6.4"},null),t("rect",{x:"0",y:"80",rx:"3",ry:"3",width:"350",height:"6.4"},null),t("rect",{x:"0",y:"100",rx:"3",ry:"3",width:"380",height:"6.4"},null),t("rect",{x:"0",y:"120",rx:"3",ry:"3",width:"201",height:"6.4"},null),t("circle",{cx:"30",cy:"30",r:"30"},null)]}})}});l(function(n,e){var r=e.attrs;return function(){return t(h,r,{default:function(){return[t("rect",{x:"0",y:"0",rx:"3",ry:"3",width:"250",height:"10"},null),t("rect",{x:"20",y:"20",rx:"3",ry:"3",width:"220",height:"10"},null),t("rect",{x:"20",y:"40",rx:"3",ry:"3",width:"170",height:"10"},null),t("rect",{x:"0",y:"60",rx:"3",ry:"3",width:"250",height:"10"},null),t("rect",{x:"20",y:"80",rx:"3",ry:"3",width:"200",height:"10"},null),t("rect",{x:"20",y:"100",rx:"3",ry:"3",width:"80",height:"10"},null)]}})}});l(function(n,e){var r=e.attrs;return function(){return t(h,s(r,{viewBox:"0 0 400 480"}),{default:function(){return[t("circle",{cx:"30",cy:"30",r:"30"},null),t("rect",{x:"75",y:"13",rx:"4",ry:"4",width:"100",height:"13"},null),t("rect",{x:"75",y:"37",rx:"4",ry:"4",width:"50",height:"8"},null),t("rect",{x:"0",y:"70",rx:"5",ry:"5",width:"400",height:"400"},null)]}})}});export{h as C};
