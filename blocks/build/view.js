/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ ((module) => {

module.exports = window["React"];

/***/ }),

/***/ "@woocommerce/blocks-checkout":
/*!****************************************!*\
  !*** external ["wc","blocksCheckout"] ***!
  \****************************************/
/***/ ((module) => {

module.exports = window["wc"]["blocksCheckout"];

/***/ }),

/***/ "@woocommerce/blocks-components":
/*!******************************************!*\
  !*** external ["wc","blocksComponents"] ***!
  \******************************************/
/***/ ((module) => {

module.exports = window["wc"]["blocksComponents"];

/***/ }),

/***/ "@woocommerce/blocks-registry":
/*!******************************************!*\
  !*** external ["wc","wcBlocksRegistry"] ***!
  \******************************************/
/***/ ((module) => {

module.exports = window["wc"]["wcBlocksRegistry"];

/***/ }),

/***/ "@woocommerce/settings":
/*!************************************!*\
  !*** external ["wc","wcSettings"] ***!
  \************************************/
/***/ ((module) => {

module.exports = window["wc"]["wcSettings"];

/***/ }),

/***/ "@wordpress/api-fetch":
/*!**********************************!*\
  !*** external ["wp","apiFetch"] ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["wp"]["apiFetch"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/html-entities":
/*!**************************************!*\
  !*** external ["wp","htmlEntities"] ***!
  \**************************************/
/***/ ((module) => {

module.exports = window["wp"]["htmlEntities"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "@wordpress/url":
/*!*****************************!*\
  !*** external ["wp","url"] ***!
  \*****************************/
/***/ ((module) => {

module.exports = window["wp"]["url"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!**************************************************!*\
  !*** ./assets/js/payment-method-teya-cl/view.js ***!
  \**************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _woocommerce_blocks_registry__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @woocommerce/blocks-registry */ "@woocommerce/blocks-registry");
/* harmony import */ var _woocommerce_blocks_registry__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_woocommerce_blocks_registry__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_html_entities__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/html-entities */ "@wordpress/html-entities");
/* harmony import */ var _wordpress_html_entities__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_html_entities__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _woocommerce_settings__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @woocommerce/settings */ "@woocommerce/settings");
/* harmony import */ var _woocommerce_settings__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_woocommerce_settings__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _woocommerce_blocks_checkout__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @woocommerce/blocks-checkout */ "@woocommerce/blocks-checkout");
/* harmony import */ var _woocommerce_blocks_checkout__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_woocommerce_blocks_checkout__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _woocommerce_blocks_components__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @woocommerce/blocks-components */ "@woocommerce/blocks-components");
/* harmony import */ var _woocommerce_blocks_components__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_woocommerce_blocks_components__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @wordpress/api-fetch */ "@wordpress/api-fetch");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var _wordpress_url__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! @wordpress/url */ "@wordpress/url");
/* harmony import */ var _wordpress_url__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_wordpress_url__WEBPACK_IMPORTED_MODULE_9__);
var _settings$supports;










const settings = (0,_woocommerce_settings__WEBPACK_IMPORTED_MODULE_5__.getPaymentMethodData)('saltpay_cl', {});
const defaultLabel = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Teya - Consumer loans', 'saltpay-cl');
const label = (0,_wordpress_html_entities__WEBPACK_IMPORTED_MODULE_3__.decodeEntities)(settings?.title || '') || defaultLabel;
const logoUrl = settings?.logoUrl;
const {
  select
} = window.wp.data;
const {
  PAYMENT_STORE_KEY
} = window.wc.wcBlocksData;
const SaltpayCLDesc = () => {
  return (0,_wordpress_html_entities__WEBPACK_IMPORTED_MODULE_3__.decodeEntities)(settings.description || '');
};
function LoanDescription(loan) {
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    class: "saltpay-cl-paymentInfo"
  }, loan.paymentInfo);
}
function LoanLogo(loan) {
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    class: "saltpay-cl-logoUrl"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    src: loan.logoUrl,
    alt: ""
  }));
}
const LoansList = props => {
  const options = props.options;
  const onChange = props.onChange;
  const option = props.selected;
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: 'saltpay-cl-payment'
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_woocommerce_blocks_components__WEBPACK_IMPORTED_MODULE_7__.RadioControl, {
    label: defaultLabel,
    selected: option,
    onChange: onChange,
    options: options
  }));
};

/**
 * Content component
 */
const Content = props => {
  const [loans, loansValue] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.useState)([]);
  const [loansOptions, loansOptionsValue] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.useState)([]);
  const [option, setOption] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.useState)('');
  const {
    billing,
    eventRegistration,
    emitResponse
  } = props;
  const {
    onPaymentSetup
  } = eventRegistration;
  const amount = billing.cartTotal.value;
  const onLoanChange = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.useCallback)(value => {
    setOption(value);
  }, [option, setOption, props]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.useEffect)(() => {
    const isPaymentIdle = select(PAYMENT_STORE_KEY).isPaymentIdle();
    if (isPaymentIdle && amount > 0 && !loans.length) {
      const queryParams = {
        'amount': amount
      };
      const fetchData = async () => {
        const data = await _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_8___default()({
          path: (0,_wordpress_url__WEBPACK_IMPORTED_MODULE_9__.addQueryArgs)('/saltpay-cl/v1/get-loans', queryParams)
        }).then(result => {
          if (result['loans'] !== undefined) {
            loansValue(result['loans']);
            setOption(result['loans'][0].loanTypeId);
            let radioOptions = [];
            result['loans']?.map(loan => {
              let radioOption = {
                "label": loan.paymentName,
                "value": loan.loanTypeId,
                "description": LoanDescription(loan),
                'secondaryDescription': LoanLogo(loan)
              };
              radioOption.onChange = function () {
                setOption(loan.loanTypeId);
                //props.valueRef.current = loan.loanTypeId;
              };
              radioOptions.push(radioOption);
            });
            loansOptionsValue(radioOptions);
          }
        });
      };
      let result = fetchData().catch(console.error);
    }
  }, [amount, loansValue, option, setOption]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.useEffect)(() => {
    const unsubscribe = onPaymentSetup(async () => {
      const metaData = {};
      if (loans.length) {
        let chosenLoan = {};
        if (option !== '') {
          loans.map(loan => {
            if (loan.loanTypeId == option) {
              chosenLoan = loan;
            }
          });
        } else {
          chosenLoan = loans[0];
        }
        if (Object.keys(chosenLoan).length) {
          if (chosenLoan.loanTypeId !== undefined) metaData.loan_payment_id = chosenLoan.loanTypeId.toString();
          if (chosenLoan.maxNumberOfPayments !== undefined) metaData.max_number_of_payments = chosenLoan.maxNumberOfPayments.toString();
        }
      }
      if (Object.keys(metaData).length !== 0) {
        const billingData = props.billing.billingData;
        if (billingData.email !== undefined && billingData.email !== "") {
          metaData['billing_email'] = billingData.email;
        }
        if (billingData.phone !== undefined && billingData.phone !== "") {
          metaData['billing_phone'] = billingData.phone;
        }
        if (billingData.ssid !== undefined && billingData.ssid !== "") {
          metaData['ssid'] = billingData.ssid;
        }
        let description = '';
        props.cartData.cartItems.forEach(cartItem => {
          if (description !== "") description += ', ';
          description += cartItem.name;
        });
        if (description !== "") {
          metaData['description'] = description;
        }
        return {
          type: emitResponse.responseTypes.SUCCESS,
          meta: {
            paymentMethodData: metaData
          }
        };
      }
      return {
        type: emitResponse.responseTypes.ERROR,
        message: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('There was an error', 'saltpay-cl')
      };
    });

    // Unsubscribes when this component is unmounted.
    return () => {
      unsubscribe();
    };
  }, [emitResponse.responseTypes.ERROR, emitResponse.responseTypes.SUCCESS, onPaymentSetup, props, loans, option]);
  return [(0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(SaltpayCLDesc, null), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(LoansList, {
    ...props,
    loans: loans,
    options: loansOptions,
    onChange: onLoanChange,
    selected: option
  })];
};

/**
 * Label component
 *
 * @param {*} props Props from payment API.
 */
const Label = props => {
  const {
    PaymentMethodLabel
  } = props.components;
  return [(0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PaymentMethodLabel, {
    text: label
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    src: logoUrl,
    alt: label
  })];
};

/**
 * Payment method config object.
 */
const SaltpayCLPaymentMethod = {
  name: 'saltpay_cl',
  label: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(Label, null),
  content: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(Content, null),
  edit: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(Content, null),
  canMakePayment: () => true,
  ariaLabel: label,
  supports: {
    features: (_settings$supports = settings?.supports) !== null && _settings$supports !== void 0 ? _settings$supports : []
  }
};
(0,_woocommerce_blocks_registry__WEBPACK_IMPORTED_MODULE_2__.registerPaymentMethod)(SaltpayCLPaymentMethod);
})();

/******/ })()
;
//# sourceMappingURL=view.js.map