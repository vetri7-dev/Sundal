import{R as H,j as o}from"./ui-bi0Gf_iY.js";import{g as R,H as z}from"./app-BeJsKiMm.js";import A from"./Header-GPICwCS_.js";import F from"./Footer-TM0uqDAV.js";import{u as T}from"./use-favicon-DRNsIFgg.js";import"./utils-DVuJ_tgg.js";import"./menu-EnrRNlYZ.js";import"./mail-Bg1wOhk5.js";import"./phone-Br58kngx.js";import"./map-pin-KgJ9Phr1.js";import"./instagram-D-L75uE0.js";import"./twitter-orWMQzi7.js";function W(){var p,u,f,h,x,g,b,y,j,_,v,C,N;const D=`
    .prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
      color: #1f2937;
      font-weight: 600;
      margin-top: 2rem;
      margin-bottom: 1rem;
    }
    
    .prose h1 { font-size: 2.25rem; }
    .prose h2 { font-size: 1.875rem; }
    .prose h3 { font-size: 1.5rem; }
    
    .prose p {
      margin-bottom: 1.5rem;
      line-height: 1.75;
    }
    
    .prose ul, .prose ol {
      margin: 1.5rem 0;
      padding-left: 1.5rem;
    }
    
    .prose li {
      margin-bottom: 0.5rem;
    }
    
    .prose a {
      color: var(--primary-color);
      text-decoration: underline;
    }
    
    .prose blockquote {
      border-left: 4px solid var(--primary-color);
      padding-left: 1rem;
      margin: 1.5rem 0;
      font-style: italic;
      background-color: #f9fafb;
      padding: 1rem;
    }
    
    .prose img {
      max-width: 100%;
      height: auto;
      border-radius: 0.5rem;
      margin: 1.5rem 0;
    }
  `,{page:t,customPages:I=[],settings:e,globalSettings:i}=R().props,c=((u=(p=e==null?void 0:e.config_sections)==null?void 0:p.theme)==null?void 0:u.primary_color)||"#3b82f6",l=((h=(f=e==null?void 0:e.config_sections)==null?void 0:f.theme)==null?void 0:h.secondary_color)||"#8b5cf6",d=((g=(x=e==null?void 0:e.config_sections)==null?void 0:x.theme)==null?void 0:g.accent_color)||"#10b981";return T(),H.useEffect(()=>{const r=(i==null?void 0:i.is_demo)||!1;let s="left";if(r){const m=(P=>{var w;if(typeof document>"u")return null;const k=`; ${document.cookie}`.split(`; ${P}=`);if(k.length===2){const E=(w=k.pop())==null?void 0:w.split(";").shift();return E?decodeURIComponent(E):null}return null})("layoutPosition");(m==="left"||m==="right")&&(s=m)}else{const a=i==null?void 0:i.layoutDirection;(a==="left"||a==="right")&&(s=a)}const n=s==="right"?"rtl":"ltr";document.documentElement.dir=n,document.documentElement.setAttribute("dir",n),setTimeout(()=>{document.documentElement.getAttribute("dir")!==n&&(document.documentElement.dir=n,document.documentElement.setAttribute("dir",n))},1)},[]),o.jsxs(o.Fragment,{children:[o.jsxs(z,{children:[o.jsx("title",{children:t.meta_title||t.title}),t.meta_description&&o.jsx("meta",{name:"description",content:t.meta_description}),o.jsx("style",{children:D})]}),o.jsxs("div",{className:"min-h-screen bg-white",style:{"--primary-color":c,"--secondary-color":l,"--accent-color":d,"--primary-color-rgb":((b=c.replace("#","").match(/.{2}/g))==null?void 0:b.map(r=>parseInt(r,16)).join(", "))||"59, 130, 246","--secondary-color-rgb":((y=l.replace("#","").match(/.{2}/g))==null?void 0:y.map(r=>parseInt(r,16)).join(", "))||"139, 92, 246","--accent-color-rgb":((j=d.replace("#","").match(/.{2}/g))==null?void 0:j.map(r=>parseInt(r,16)).join(", "))||"16, 185, 129"},children:[o.jsx(A,{"max-w-7xl":!0,"mx-auto":!0,settings:e,customPages:I,sectionData:((v=(_=e==null?void 0:e.config_sections)==null?void 0:_.sections)==null?void 0:v.find(r=>r.key==="header"))||{},brandColor:c}),o.jsx("main",{className:"pt-16",children:o.jsx("div",{className:"max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12",children:o.jsxs("div",{className:"max-w-4xl mx-auto",children:[o.jsxs("header",{className:"text-center mb-12",children:[o.jsx("h1",{className:"text-4xl font-bold text-gray-900 mb-4",children:t.title}),o.jsx("div",{className:"w-24 h-1 bg-gradient-to-r from-blue-500 to-purple-600 mx-auto rounded-full"})]}),o.jsx("article",{className:"max-w-none",children:o.jsx("div",{className:"text-gray-700 leading-relaxed text-lg",dangerouslySetInnerHTML:{__html:t.content}})})]})})}),o.jsx(F,{settings:e,sectionData:((N=(C=e==null?void 0:e.config_sections)==null?void 0:C.sections)==null?void 0:N.find(r=>r.key==="footer"))||{},brandColor:c})]})]})}export{W as default};
