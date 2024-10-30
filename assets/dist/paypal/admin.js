import{R as e,_ as a,a as t,b as l,c as n}from"./@wordpress_i18n-d8f34da1.js";var o=booksterModules.antd;function r(){const r=o.Form.useFormInstance(),d=o.Form.useWatch(["addonPaymentPaypal","enabled"],r),s=o.Form.useWatch(["addonPaymentPaypal","is_live_mode"],r);return e.createElement(a.ReactCollapsible.Root,{open:d},e.createElement("div",{className:"bw-flex bw-items-center bw-gap-3 bw-bg-base-bg2/60 bw-p-3 bw-pr-4"},e.createElement(o.Form.Item,{name:["addonPaymentPaypal","enabled"],trigger:"onCheckedChange",valuePropName:"checked",noStyle:!0},e.createElement(a.Switch,{checkedChildren:"ON",unCheckedChildren:"OFF",id:"payment-paypal-enabled",onCheckedChange:function(e){const a=n.booksterHooks.applyFilters(n.HOOK_NAMES.settings.validateGatewayEnabledPath,{later:["payment","payment-later","enabled"],now:[]});e&&a.now.forEach((e=>{2===e.length&&"addonPaymentPaypal"===e[0]||r.setFieldValue(e,!1)}))}})),e.createElement(a.Label,{className:"bw-font-medium",htmlFor:"payment-paypal-enabled"},t.__("Paypal","bookster-paypal"))),e.createElement(a.CollapsibleContent,{forceMountWithAnimation:!0},e.createElement("div",{className:"bw-border-t bw-p-3 bw-pr-4"},e.createElement("div",{className:"bw-mb-3 bw-mt-1 bw-flex bw-items-center bw-gap-3"},e.createElement(o.Form.Item,{name:["addonPaymentPaypal","is_live_mode"],valuePropName:"checked",trigger:"onCheckedChange",noStyle:!0},e.createElement(a.Switch,{checkedChildren:"Live",unCheckedChildren:"Sandbox",id:"payment_paypal_is_live_mode",onClick:e=>e.stopPropagation()})),e.createElement(a.Label,{htmlFor:"payment_paypal_is_live_mode"},t.__("Sandbox or Live Mode","bookster-paypal")),e.createElement(a.Button,{className:"bw-ms-auto bw-rounded-full",color:"info",variant:"ghost",size:"icon",asChild:!0},e.createElement("a",{href:"https://developer.paypal.com/api/rest",target:"_blank"},e.createElement(l.HelpCircle,null)))),e.createElement("div",{className:"bw-grid bw-grid-cols-1 bw-gap-x-4 sm:bw-grid-cols-2"},e.createElement("div",null,e.createElement(o.Form.Item,{name:["addonPaymentPaypal","live_client_id"],label:t.__("Live Client Id","bookster-paypal"),hidden:!s,rules:[{required:d&&s}]},e.createElement(a.Input,{placeholder:t.__("Please enter the Client Id","bookster-paypal")})),e.createElement(o.Form.Item,{name:["addonPaymentPaypal","sandbox_client_id"],label:t.__("Sandbox Client Id","bookster-paypal"),hidden:s,rules:[{required:d&&!s}]},e.createElement(a.Input,{placeholder:t.__("Please enter the Client Id","bookster-paypal")}))),e.createElement("div",null,e.createElement(o.Form.Item,{name:["addonPaymentPaypal","live_client_secret"],label:t.__("Live Client Secret","bookster-paypal"),hidden:!s,rules:[{required:d&&s}]},e.createElement(a.PasswordInput,{placeholder:t.__("Please enter the Client Secret","bookster-paypal")})),e.createElement(o.Form.Item,{name:["addonPaymentPaypal","sandbox_client_secret"],label:t.__("Sandbox Client Secret","bookster-paypal"),hidden:s,rules:[{required:d&&!s}]},e.createElement(a.PasswordInput,{placeholder:t.__("Please enter the Client Secret","bookster-paypal")})))))))}n.booksterHooks.addFilter(n.HOOK_NAMES.settings.PaymentGateways,"bookster-paypal",(a=>[...a,e.createElement(r,{key:"bookster-paypal"})]),20),n.booksterHooks.addFilter(n.HOOK_NAMES.settings.validateGatewayEnabledPath,"bookster-paypal",(e=>({later:e.later,now:[...e.now,["addonPaymentPaypal","enabled"]]})),20),n.booksterHooks.addFilter(n.HOOK_NAMES.settings.initPaymentSettingsForm,"bookster-paypal",(e=>({...e,addonPaymentPaypal:window.booksterManagerData.addonPaymentPaypal})),20);
