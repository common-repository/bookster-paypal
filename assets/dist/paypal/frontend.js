import{r as t,R as e,b as n,_ as a,a as o,c as r}from"./@wordpress_i18n-d8f34da1.js";var i,c,s,l,u,d;(c=i||(i={})).INITIAL="initial",c.PENDING="pending",c.REJECTED="rejected",c.RESOLVED="resolved",(l=s||(s={})).LOADING_STATUS="setLoadingStatus",l.RESET_OPTIONS="resetOptions",l.SET_BRAINTREE_INSTANCE="braintreeInstance",(d=u||(u={})).NUMBER="number",d.CVV="cvv",d.EXPIRATION_DATE="expirationDate",d.EXPIRATION_MONTH="expirationMonth",d.EXPIRATION_YEAR="expirationYear",d.POSTAL_CODE="postalCode";var p=function(){return p=Object.assign||function(t){for(var e,n=1,a=arguments.length;n<a;n++)for(var o in e=arguments[n])Object.prototype.hasOwnProperty.call(e,o)&&(t[o]=e[o]);return t},p.apply(this,arguments)};function m(t,e){var n={};for(var a in t)Object.prototype.hasOwnProperty.call(t,a)&&e.indexOf(a)<0&&(n[a]=t[a]);if(null!=t&&"function"==typeof Object.getOwnPropertySymbols){var o=0;for(a=Object.getOwnPropertySymbols(t);o<a.length;o++)e.indexOf(a[o])<0&&Object.prototype.propertyIsEnumerable.call(t,a[o])&&(n[a[o]]=t[a[o]])}return n}function b(t,e,n){if(n||2===arguments.length)for(var a,o=0,r=e.length;o<r;o++)!a&&o in e||(a||(a=Array.prototype.slice.call(e,0,o)),a[o]=e[o]);return t.concat(a||Array.prototype.slice.call(e))}var f="data-react-paypal-script-id",y="react-paypal-js",w="dataSdkIntegrationSource",E="paypal";function g(t){var e=t,n=f;e[n];var a=m(e,[n+""]);return"react-paypal-js-".concat(function(t){for(var e="",n=0;n<t.length;n++){var a=t[n].charCodeAt(0)*n;t[n+1]&&(a+=t[n+1].charCodeAt(0)*(n-1)),e+=String.fromCharCode(97+Math.abs(a)%26)}return e}(JSON.stringify(a)))}function v(t,e){var n,a,o,r;switch(e.type){case s.LOADING_STATUS:return"object"==typeof e.value?p(p({},t),{loadingStatus:e.value.state,loadingStatusErrorMessage:e.value.message}):p(p({},t),{loadingStatus:e.value});case s.RESET_OPTIONS:return o=t.options[f],(null==(r=self.document.querySelector("script[".concat(f,'="').concat(o,'"]')))?void 0:r.parentNode)&&r.parentNode.removeChild(r),p(p({},t),{loadingStatus:i.PENDING,options:p(p((n={},n[w]=y,n),e.value),(a={},a[f]="".concat(g(e.value)),a))});case s.SET_BRAINTREE_INSTANCE:return p(p({},t),{braintreePayPalCheckoutInstance:e.value});default:return t}}var h=t.createContext(null);t.createContext({});var S=function(n){var a,o=n.className,r=void 0===o?"":o,c=n.disabled,s=void 0!==c&&c,l=n.children,u=n.forceReRender,d=void 0===u?[]:u,f=m(n,["className","disabled","children","forceReRender"]),y=s?{opacity:.38}:{},w="".concat(r," ").concat(s?"paypal-buttons-disabled":"").trim(),g=t.useRef(null),v=t.useRef(null),P=(a=function(t){if("function"==typeof(null==t?void 0:t.dispatch)&&0!==t.dispatch.length)return t;throw new Error("usePayPalScriptReducer must be used within a PayPalScriptProvider")}(t.useContext(h)),[p(p({},a),{isInitial:a.loadingStatus===i.INITIAL,isPending:a.loadingStatus===i.PENDING,isResolved:a.loadingStatus===i.RESOLVED,isRejected:a.loadingStatus===i.REJECTED}),a.dispatch])[0],k=P.isResolved,I=P.options,N=t.useState(null),A=N[0],O=N[1],T=t.useState(!0),C=T[0],j=T[1],R=t.useState(null)[1];function x(){null!==v.current&&v.current.close().catch((function(){}))}return t.useEffect((function(){if(!1===k)return x;var t,e=(void 0===(t=I.dataNamespace)&&(t=E),window[t]);if(void 0===e||void 0===e.Buttons)return R((function(){throw new Error(function(t){var e=t.reactComponentName,n=t.sdkComponentKey,a=t.sdkRequestedComponents,o=void 0===a?"":a,r=t.sdkDataNamespace,i=void 0===r?E:r,c=n.charAt(0).toUpperCase().concat(n.substring(1)),s="Unable to render <".concat(e," /> because window.").concat(i,".").concat(c," is undefined."),l="string"==typeof o?o:o.join(",");if(!l.includes(n)){var u=[l,n].filter(Boolean).join();s+="\nTo fix the issue, add '".concat(n,"' to the list of components passed to the parent PayPalScriptProvider:")+"\n`<PayPalScriptProvider options={{ components: '".concat(u,"'}}>`.")}return s}({reactComponentName:S.displayName,sdkComponentKey:"buttons",sdkRequestedComponents:I.components,sdkDataNamespace:I.dataNamespace}))})),x;try{v.current=e.Buttons(p(p({},f),{onInit:function(t,e){O(e),"function"==typeof f.onInit&&f.onInit(t,e)}}))}catch(n){return R((function(){throw new Error("Failed to render <PayPalButtons /> component. Failed to initialize:  ".concat(n))}))}return!1===v.current.isEligible()?(j(!1),x):g.current?(v.current.render(g.current).catch((function(t){null!==g.current&&0!==g.current.children.length&&R((function(){throw new Error("Failed to render <PayPalButtons /> component. ".concat(t))}))})),x):x}),b(b([k],d,!0),[f.fundingSource],!1)),t.useEffect((function(){null!==A&&(!0===s?A.disable().catch((function(){})):A.enable().catch((function(){})))}),[s,A]),e.createElement(e.Fragment,null,C?e.createElement("div",{ref:g,style:y,className:w}):l)};function P(t,e){void 0===e&&(e={});var n=document.createElement("script");return n.src=t,Object.keys(e).forEach((function(t){n.setAttribute(t,e[t]),"data-csp-nonce"===t&&n.setAttribute("nonce",e["data-csp-nonce"])})),n}function k(t){return window[t]}function I(t,e){if("object"!=typeof t||null===t)throw new Error("Expected an options object.");if(void 0!==e&&"function"!=typeof e)throw new Error("Expected PromisePonyfill to be a function.")}S.displayName="PayPalButtons";var N=function(n){var a,o=n.options,r=void 0===o?{clientId:"test"}:o,c=n.children,l=n.deferLoading,u=void 0!==l&&l,d=t.useReducer(v,{options:p(p({},r),(a={},a.dataJsSdkLibrary=y,a[w]=y,a[f]="".concat(g(r)),a)),loadingStatus:u?i.INITIAL:i.PENDING}),m=d[0],b=d[1];return t.useEffect((function(){if(!1===u&&m.loadingStatus===i.INITIAL)return b({type:s.LOADING_STATUS,value:i.PENDING});if(m.loadingStatus===i.PENDING){var t=!0;return function(t,e){if(void 0===e&&(e=Promise),I(t,e),"undefined"==typeof document)return e.resolve(null);var n=function(t){var e="https://www.paypal.com/sdk/js";t.sdkBaseUrl&&(e=t.sdkBaseUrl,delete t.sdkBaseUrl);var n,a,o=t,r=Object.keys(o).filter((function(t){return void 0!==o[t]&&null!==o[t]&&""!==o[t]})).reduce((function(t,e){var n,a=o[e].toString();return n=function(t,e){return(e?"-":"")+t.toLowerCase()},"data"===(e=e.replace(/[A-Z]+(?![a-z])|[A-Z]/g,n)).substring(0,4)||"crossorigin"===e?t.attributes[e]=a:t.queryParams[e]=a,t}),{queryParams:{},attributes:{}}),i=r.queryParams,c=r.attributes;return i["merchant-id"]&&-1!==i["merchant-id"].indexOf(",")&&(c["data-merchant-id"]=i["merchant-id"],i["merchant-id"]="*"),{url:"".concat(e,"?").concat((n=i,a="",Object.keys(n).forEach((function(t){0!==a.length&&(a+="&"),a+=t+"="+n[t]})),a)),attributes:c}}(t),a=n.url,o=n.attributes,r=o["data-namespace"]||"paypal",i=k(r);return o["data-js-sdk-library"]||(o["data-js-sdk-library"]="paypal-js"),function(t,e){var n=document.querySelector('script[src="'.concat(t,'"]'));if(null===n)return null;var a=P(t,e),o=n.cloneNode();if(delete o.dataset.uidAuto,Object.keys(o.dataset).length!==Object.keys(a.dataset).length)return null;var r=!0;return Object.keys(o.dataset).forEach((function(t){o.dataset[t]!==a.dataset[t]&&(r=!1)})),r?n:null}(a,o)&&i?e.resolve(i):function(t,e){void 0===e&&(e=Promise),I(t,e);var n=t.url,a=t.attributes;if("string"!=typeof n||0===n.length)throw new Error("Invalid url.");if(void 0!==a&&"object"!=typeof a)throw new Error("Expected attributes to be an object.");return new e((function(t,e){if("undefined"==typeof document)return t();!function(t){var e=t.onSuccess,n=t.onError,a=P(t.url,t.attributes);a.onerror=n,a.onload=e,document.head.insertBefore(a,document.head.firstElementChild)}({url:n,attributes:a,onSuccess:function(){return t()},onError:function(){var t=new Error('The script "'.concat(n,'" failed to load. Check the HTTP status code and response body in DevTools to learn more.'));return e(t)}})}))}({url:a,attributes:o},e).then((function(){var t=k(r);if(t)return t;throw new Error("The window.".concat(r," global variable is not available."))}))}(m.options).then((function(){t&&b({type:s.LOADING_STATUS,value:i.RESOLVED})})).catch((function(e){console.error("".concat("Failed to load the PayPal JS SDK script."," ").concat(e)),t&&b({type:s.LOADING_STATUS,value:{state:i.REJECTED,message:String(e)}})})),function(){t=!1}}}),[m.options,u,m.loadingStatus]),e.createElement(h.Provider,{value:p(p({},m),{dispatch:b})},c)},A=booksterModules.utils;const O=A.CURRENCY.toUpperCase();function T(){return window.booksterPublicData}var C=booksterModules.booking,j=booksterModules.decimal;function R(){return C.useBookingStore()}function x(t,e){const n=R();return A.useStore(n,t,e)}const D="paypalPaymentForm",L={isLoading:!1,transactionId:null,orderId:null,orderAmount:null};function _(){const{bookingDetailsValue:t}=C.useBookingLogic().input;return e.createElement(e.Fragment,null,e.createElement("p",{className:"bw-mb-4 bw-mt-6 bw-flex bw-items-center bw-justify-center bw-gap-1.5 bw-text-sm bw-font-semibold bw-text-success"},e.createElement(n.Lock,{className:"bw-h-3.5 bw-w-3.5"}),e.createElement("span",{className:"bw-overflow-x-auto bw-text-nowrap"},"All transactions are secure, your card information will not be stored.")),e.createElement("div",{className:"bw-rounded-lg bw-bg-base-bg2 bw-px-4 bw-py-2"},"Total booking price:"," ",e.createElement("strong",null,t&&A.formatPrice(t.tax.total))))}function B({children:t,...n}){return e.createElement("div",{className:"bw-relative bw-mb-4 bw-rounded bw-border bw-border-error/80 bw-bg-error/10 bw-px-4 bw-py-3 bw-text-error",...n},e.createElement("span",{className:"bw-block sm:bw-inline"},t))}function M(){const r=R(),i=x((t=>t.select.contact)),{isLoading:c}=x((t=>t.addonPaymentPaypal)),s=C.useBookingLogic(),{apptInput:l,transactionInput:u,bookingMetaInput:d,bookingDetailsValue:p}=s.input,m=s.process,[b,f]=t.useState(null);if(!(p&&l&&u&&i&&d))throw new Error("Sorry, Your Booking Selection is not Complete!");return e.createElement(e.Fragment,null,e.createElement(C.MainHeader,null),e.createElement(a.ScrollArea,{className:"btr-main-scrollarea bw-flex-grow"},e.createElement(a.XyzTransition,{appear:!0,xyz:"fade down-4"},e.createElement("div",{className:"bw-mx-auto bw-flex bw-min-h-[30rem] bw-max-w-[34rem] bw-flex-col bw-justify-center bw-p-4 bw-pt-8"},e.createElement("div",{className:"bw-flex-none"},b&&e.createElement(B,null,b),e.createElement(S,{style:{layout:"vertical"},createOrder:async()=>{const{orderAmount:t,orderId:e,transactionId:n}=r.getState().addonPaymentPaypal,a=p.tax.total;if(e&&t&&t.equals(a))return e;try{const t={apptInput:l,transactionInput:u,contactInput:i,bookingMetaInput:d||{}};if(e&&n){const o=await async function(t,e){return await A.api.patch("paypal/orders/amount",{json:{bookingRequestInput:e,transactionId:t}}).json()}(n,t);if(!a.equals(j.Decimal.fromString(o.orderAmount)))throw new Error("Sorry, Cannot calculate your Checkout!");return r.setState((t=>{t.addonPaymentPaypal.orderAmount=j.Decimal.fromString(o.orderAmount)})),e}{const e=await async function(t){return await A.api.post("paypal/orders",{json:{bookingRequestInput:t}}).json()}(t);if(!a.equals(j.Decimal.fromString(e.orderAmount)))throw new Error("Sorry, Cannot calculate your Checkout!");return r.setState((t=>{t.addonPaymentPaypal.orderAmount=a,t.addonPaymentPaypal.transactionId=e.transactionId,t.addonPaymentPaypal.orderId=e.orderId})),e.orderId}}catch(o){throw f(await A.getErrorMsg(o)),o}},onApprove:async()=>{const{orderId:t,transactionId:e}=r.getState().addonPaymentPaypal;try{if(r.setState((t=>{t.addonPaymentPaypal.isLoading=!0})),!t||!e)throw"Paypal Order Missing!";await async function(t){return await A.api.patch("paypal/orders/capture",{json:{transactionId:t}}).json()}(e);const{appointment:n,transaction:a,customer:o}=await A.requestBooking({apptInput:l,bookingMetaInput:d,transactionInput:u,contactInput:i});r.setState((t=>{t.booked.state="success",t.booked.appointment=n,t.booked.transaction=a,t.booked.customer=o})),m.mutate.nextAct()}catch(n){throw f(await A.getErrorMsg(n)),n}finally{r.setState((t=>{t.addonPaymentPaypal.isLoading=!1}))}}}),e.createElement(_,null),e.createElement(a.LoadingOverlay,{className:"-bw-inset-4 bw-z-[10000] bw-text-lg bw-font-medium bw-text-base-foreground/80 bw-opacity-80",loading:c},e.createElement(n.Loader2,{className:"bw-mr-4 bw-h-6 bw-w-6 bw-animate-spin"}),o.__("Booking your Appointment!","bookster-paypal")))))),e.createElement(C.MainFooter,{prevButton:e.createElement(a.Button,{key:"paypal-prev-btn",className:"bw-pl-0",variant:"link",disabled:c,onClick:()=>m.mutate.prevAct()},e.createElement(n.ArrowLeft,null),"Back")}))}function F(t){return e.createElement("div",{...t},e.createElement(N,{options:{clientId:T().addonPaymentPaypal.client_id,intent:"capture",components:"buttons",currency:O}},e.createElement(M,null)))}"payment-paypal"===T().paymentGateway.now&&(r.booksterHooks.addFilter(r.HOOK_NAMES.bookingForm.bookingConfig,"bookster-paypal",(function(t){const e=t.process.steps.map((t=>"checkout"===t.name?{...t,acts:[...t.acts,D]}:t));return{...t,process:{...t.process,steps:e}}})),r.booksterHooks.addFilter(r.HOOK_NAMES.bookingForm.bookingParts,"bookster-paypal",(function(t,n,a,o){return o.process.computed.act===D?{...t,main:{key:"paypal-payment-form",node:e.createElement(F,null)}}:t})),r.booksterHooks.addFilter(r.HOOK_NAMES.bookingForm.bookingInitState,"bookster-paypal",(function(t){return{...t,addonPaymentPaypal:L}})),r.booksterHooks.addFilter(r.HOOK_NAMES.bookingForm.bookingLogic,"bookster-paypal",(function(t,e,n){const{transactionId:a}=n.addonPaymentPaypal;return null!==a&&t.input.transactionInput&&(t.input.transactionInput.transactionId=a),t})),r.booksterHooks.addFilter(r.HOOK_NAMES.bookingForm.mutatePrevAct,"bookster-paypal",(function(t,e,n,{newPhase:a,oldPhase:o}){return a!==o&&"complete"===o&&(e.addonPaymentPaypal=L),t})));
