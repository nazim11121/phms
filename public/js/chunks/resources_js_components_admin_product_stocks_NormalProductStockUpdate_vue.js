"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_components_admin_product_stocks_NormalProductStockUpdate_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/admin/product_stocks/NormalProductStockUpdate.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/admin/product_stocks/NormalProductStockUpdate.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: ["product", "warehouses", "old_stocks"],
  data: function data() {
    return {
      items: [{
        id: '',
        warehouse: "",
        stock: 0,
        quantity: 0,
        customer_buying_price: 0,
        price: 0,
        adjust_type: ''
      }],
      adjust_type: ['Add', 'Subtract'],
      stock_alert_quantity: 0
    };
  },
  mounted: function mounted() {
    var _this = this;
    if (this.old_stocks.length > 0) {
      this.items = [];
      this.old_stocks.map(function (item) {
        _this.items.push({
          id: item.id,
          warehouse: item.warehouse_id,
          stock: item.quantity,
          quantity: 0,
          adjust_type: '',
          customer_buying_price: item.customer_buying_price,
          price: item.price
        });
      });
    }
    this.stock_alert_quantity = this.product.stock_alert_quantity;
  },
  methods: {
    checkDuplicate: function checkDuplicate(index, e) {
      var id = e.target.value;
      var is_duplicate = this.items.filter(function (item) {
        return item.warehouse == id;
      });
      if (is_duplicate.length > 1) {
        this.$swal.fire({
          icon: "error",
          text: "Duplicate warehouse selected!"
        });
        this.items.splice(index, 1);
      }
    },
    addItem: function addItem() {
      this.items.push({
        id: '',
        warehouse: "",
        stock: 0,
        quantity: 0,
        customer_buying_price: 0,
        price: 0,
        adjust_type: ''
      });
    },
    deleteItem: function deleteItem(index) {
      this.items.splice(index, 1);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/admin/product_stocks/NormalProductStockUpdate.vue?vue&type=template&id=06a54144&":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/admin/product_stocks/NormalProductStockUpdate.vue?vue&type=template&id=06a54144& ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function render() {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "col-sm-12"
  }, [_c("table", {
    staticClass: "table table-bordered mb-4"
  }, [_c("tr", [_c("th", {
    attrs: {
      colspan: "3"
    }
  }, [_c("img", {
    staticClass: "img-100-60",
    attrs: {
      src: _vm.product.thumb_url,
      alt: _vm.product.name
    }
  }), _vm._v(" "), _c("span", {
    staticClass: "ml-4"
  }, [_c("small", [_vm._v(_vm._s(_vm.__("custom.product_name")) + ": ")]), _vm._v("\n                    " + _vm._s(_vm.product.name) + "\n                ")])]), _vm._v(" "), _c("th", {
    attrs: {
      colspan: "2"
    }
  }, [_vm._v("\n                " + _vm._s(_vm.__("custom.alert_quantity")) + "\n                "), _c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.stock_alert_quantity,
      expression: "stock_alert_quantity"
    }],
    staticClass: "form-control",
    attrs: {
      type: "number",
      name: "alert_quantity",
      required: "",
      placeholder: _vm.__("custom.alert_quantity")
    },
    domProps: {
      value: _vm.stock_alert_quantity
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.stock_alert_quantity = $event.target.value;
      }
    }
  })])]), _vm._v(" "), _c("tr", [_c("th", [_vm._v(_vm._s(_vm.__("custom.warehouse_name")))]), _vm._v(" "), _c("th", {
    attrs: {
      width: "20%"
    }
  }, [_vm._v(_vm._s(_vm.__("custom.current_stock")) + " "), _vm.product.weight_unit ? _c("small", [_vm._v("(" + _vm._s(_vm.product.weight_unit.name) + ")")]) : _vm._e()]), _vm._v(" "), _c("th", {
    attrs: {
      width: "20%"
    }
  }, [_vm._v(_vm._s(_vm.__("custom.qty")) + " "), _vm.product.weight_unit ? _c("small", [_vm._v("(" + _vm._s(_vm.product.weight_unit.name) + ")")]) : _vm._e()]), _vm._v(" "), _vm._v(" "), _c("th", [_vm._v(_vm._s(_vm.__("custom.adjust_type")) + " "), _c("small", [_vm._v("(" + _vm._s(_vm.__("custom.stock")) + ")")])]), _vm._v(" "), _c("th")]), _vm._v(" "), _vm._l(_vm.items, function (item, index) {
    return _c("tr", {
      key: index
    }, [_c("td", {
      attrs: {
        width: "30%"
      }
    }, [_c("select", {
      directives: [{
        name: "model",
        rawName: "v-model",
        value: item.warehouse,
        expression: "item.warehouse"
      }],
      staticClass: "form-control",
      attrs: {
        name: "warehouse_stock[" + index + "][warehouse]"
      },
      on: {
        change: [function ($event) {
          var $$selectedVal = Array.prototype.filter.call($event.target.options, function (o) {
            return o.selected;
          }).map(function (o) {
            var val = "_value" in o ? o._value : o.value;
            return val;
          });
          _vm.$set(item, "warehouse", $event.target.multiple ? $$selectedVal : $$selectedVal[0]);
        }, function ($event) {
          return _vm.checkDuplicate(index, $event);
        }]
      }
    }, [_c("option", {
      attrs: {
        value: "",
        selected: ""
      }
    }, [_vm._v(_vm._s(_vm.__("custom.select_warehouse")))]), _vm._v(" "), _vm._l(_vm.warehouses, function (item, index) {
      return _c("option", {
        key: index,
        domProps: {
          value: item.id
        }
      }, [_vm._v("\n                        " + _vm._s(item.name) + "\n                    ")]);
    })], 2)]), _vm._v(" "), _c("td", [_c("input", {
      directives: [{
        name: "model",
        rawName: "v-model",
        value: item.stock,
        expression: "item.stock"
      }],
      staticClass: "form-control",
      attrs: {
        readonly: "1",
        type: "number",
        name: "warehouse_stock[" + index + "][stock]"
      },
      domProps: {
        value: item.stock
      },
      on: {
        input: function input($event) {
          if ($event.target.composing) return;
          _vm.$set(item, "stock", $event.target.value);
        }
      }
    })]), _vm._v(" "), _c("td", [_c("input", {
      directives: [{
        name: "model",
        rawName: "v-model",
        value: item.quantity,
        expression: "item.quantity"
      }],
      staticClass: "form-control",
      attrs: {
        min: "0",
        width: "30px",
        type: "number",
        name: "warehouse_stock[" + index + "][quantity]"
      },
      domProps: {
        value: item.quantity
      },
      on: {
        input: function input($event) {
          if ($event.target.composing) return;
          _vm.$set(item, "quantity", $event.target.value);
        }
      }
    // })]), _vm._v(" "), _c("td", [_c("input", {
    //   directives: [{
    //     name: "model",
    //     rawName: "v-model",
    //     value: item.price,
    //     expression: "item.price"
    //   }],
    //   staticClass: "form-control",
    //   attrs: {
    //     min: "0",
    //     width: "30px",
    //     type: "number",
    //     name: "warehouse_stock[" + index + "][price]"
    //   },
    //   domProps: {
    //     value: item.price
    //   },
    //   on: {
    //     input: function input($event) {
    //       if ($event.target.composing) return;
    //       _vm.$set(item, "price", $event.target.value);
    //     }
    //   }
    // })]), _vm._v(" "), _c("td", [_c("input", {
    //   directives: [{
    //     name: "model",
    //     rawName: "v-model",
    //     value: item.customer_buying_price,
    //     expression: "item.customer_buying_price"
    //   }],
    //   staticClass: "form-control",
    //   attrs: {
    //     min: "0",
    //     width: "30px",
    //     type: "number",
    //     name: "warehouse_stock[" + index + "][customer_buying_price]"
    //   },
    //   domProps: {
    //     value: item.customer_buying_price
    //   },
    //   on: {
    //     input: function input($event) {
    //       if ($event.target.composing) return;
    //       _vm.$set(item, "customer_buying_price", $event.target.value);
    //     }
    //   }
    })]), _vm._v(" "), _vm.old_stocks.length > 0 ? _c("td", {
      attrs: {
        width: "10%"
      }
    }, [_c("select", {
      directives: [{
        name: "model",
        rawName: "v-model",
        value: item.adjust_type,
        expression: "item.adjust_type"
      }],
      staticClass: "form-control",
      attrs: {
        name: "warehouse_stock[" + index + "][adjust_type]"
      },
      on: {
        change: function change($event) {
          var $$selectedVal = Array.prototype.filter.call($event.target.options, function (o) {
            return o.selected;
          }).map(function (o) {
            var val = "_value" in o ? o._value : o.value;
            return val;
          });
          _vm.$set(item, "adjust_type", $event.target.multiple ? $$selectedVal : $$selectedVal[0]);
        }
      }
    }, [_c("option", {
      attrs: {
        value: "",
        selected: ""
      }
    }, [_vm._v(_vm._s(_vm.__("custom.select_adjust_type")))]), _vm._v(" "), _vm._l(_vm.adjust_type, function (item, index) {
      return _c("option", {
        key: index,
        domProps: {
          value: item
        }
      }, [_vm._v(_vm._s(item))]);
    })], 2)]) : _c("td", {
      attrs: {
        width: "1%"
      }
    }, [_c("select", {
      directives: [{
        name: "model",
        rawName: "v-model",
        value: item.adjust_type,
        expression: "item.adjust_type"
      }],
      staticClass: "form-control",
      attrs: {
        name: "warehouse_stock[" + index + "][adjust_type]"
      },
      on: {
        change: function change($event) {
          var $$selectedVal = Array.prototype.filter.call($event.target.options, function (o) {
            return o.selected;
          }).map(function (o) {
            var val = "_value" in o ? o._value : o.value;
            return val;
          });
          _vm.$set(item, "adjust_type", $event.target.multiple ? $$selectedVal : $$selectedVal[0]);
        }
      }
    }, [_c("option", {
      attrs: {
        value: "",
        selected: ""
      }
    }, [_vm._v(_vm._s(_vm.__("custom.select_adjust_type")))]), _vm._v(" "), _c("option", {
      attrs: {
        value: "Add",
        selected: ""
      }
    }, [_vm._v("Add")])])]), _vm._v(" "), _c("td", {
      attrs: {
        width: "5%"
      }
    }, [item.id ? _c("button", {
      staticClass: "btn btn-sm btn-outline-danger",
      attrs: {
        type: "button",
        disabled: ""
      },
      on: {
        click: function click($event) {
          return _vm.deleteItem(index);
        }
      }
    }, [_c("i", {
      staticClass: "fa fa-trash"
    })]) : _c("button", {
      staticClass: "btn btn-sm btn-outline-danger",
      attrs: {
        type: "button"
      },
      on: {
        click: function click($event) {
          return _vm.deleteItem(index);
        }
      }
    }, [_c("i", {
      staticClass: "fa fa-trash"
    })])])]);
  }), _vm._v(" "), _c("tfoot", [_c("tr", [_c("td", {
    attrs: {
      colspan: "7"
    }
  }, [_c("button", {
    staticClass: "btn btn-sm btn-info float-right",
    attrs: {
      type: "button",
      title: _vm.__("custom.add_warehouse")
    },
    on: {
      click: _vm.addItem
    }
  }, [_c("i", {
    staticClass: "fa fa-plus"
  })])])])])], 2)]);
};
var staticRenderFns = [];
render._withStripped = true;


/***/ }),

/***/ "./resources/js/components/admin/product_stocks/NormalProductStockUpdate.vue":
/*!***********************************************************************************!*\
  !*** ./resources/js/components/admin/product_stocks/NormalProductStockUpdate.vue ***!
  \***********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _NormalProductStockUpdate_vue_vue_type_template_id_06a54144___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./NormalProductStockUpdate.vue?vue&type=template&id=06a54144& */ "./resources/js/components/admin/product_stocks/NormalProductStockUpdate.vue?vue&type=template&id=06a54144&");
/* harmony import */ var _NormalProductStockUpdate_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./NormalProductStockUpdate.vue?vue&type=script&lang=js& */ "./resources/js/components/admin/product_stocks/NormalProductStockUpdate.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _NormalProductStockUpdate_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _NormalProductStockUpdate_vue_vue_type_template_id_06a54144___WEBPACK_IMPORTED_MODULE_0__.render,
  _NormalProductStockUpdate_vue_vue_type_template_id_06a54144___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/admin/product_stocks/NormalProductStockUpdate.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/admin/product_stocks/NormalProductStockUpdate.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************!*\
  !*** ./resources/js/components/admin/product_stocks/NormalProductStockUpdate.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_NormalProductStockUpdate_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./NormalProductStockUpdate.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/admin/product_stocks/NormalProductStockUpdate.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_NormalProductStockUpdate_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/admin/product_stocks/NormalProductStockUpdate.vue?vue&type=template&id=06a54144&":
/*!******************************************************************************************************************!*\
  !*** ./resources/js/components/admin/product_stocks/NormalProductStockUpdate.vue?vue&type=template&id=06a54144& ***!
  \******************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_NormalProductStockUpdate_vue_vue_type_template_id_06a54144___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_NormalProductStockUpdate_vue_vue_type_template_id_06a54144___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_NormalProductStockUpdate_vue_vue_type_template_id_06a54144___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./NormalProductStockUpdate.vue?vue&type=template&id=06a54144& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/admin/product_stocks/NormalProductStockUpdate.vue?vue&type=template&id=06a54144&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js":
/*!********************************************************************!*\
  !*** ./node_modules/vue-loader/lib/runtime/componentNormalizer.js ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ normalizeComponent)
/* harmony export */ });
/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

function normalizeComponent(
  scriptExports,
  render,
  staticRenderFns,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier /* server only */,
  shadowMode /* vue-cli only */
) {
  // Vue.extend constructor export interop
  var options =
    typeof scriptExports === 'function' ? scriptExports.options : scriptExports

  // render functions
  if (render) {
    options.render = render
    options.staticRenderFns = staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = 'data-v-' + scopeId
  }

  var hook
  if (moduleIdentifier) {
    // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = shadowMode
      ? function () {
          injectStyles.call(
            this,
            (options.functional ? this.parent : this).$root.$options.shadowRoot
          )
        }
      : injectStyles
  }

  if (hook) {
    if (options.functional) {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functional component in vue file
      var originalRender = options.render
      options.render = function renderWithStyleInjection(h, context) {
        hook.call(context)
        return originalRender(h, context)
      }
    } else {
      // inject component registration as beforeCreate hook
      var existing = options.beforeCreate
      options.beforeCreate = existing ? [].concat(existing, hook) : [hook]
    }
  }

  return {
    exports: scriptExports,
    options: options
  }
}


/***/ })

}]);