import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'
import VueAxios from 'vue-axios'
import VueTailwind from 'vue-tailwind'

import './assets/styles/index.css'

import TRichSelect from 'vue-tailwind/dist/t-rich-select'
import TDatePicker from 'vue-tailwind/dist/t-datepicker'

const VueTailwindSettings = {
  't-rich-select': {
    component: TRichSelect,
    props: {
      fixedClasses: {
        wrapper: 'w-full relative',
        buttonWrapper: 'inline-block relative w-full',
        selectButton: 'w-full flex text-left justify-between items-center px-3 py-2 text-black transition duration-100 ease-in-out border rounded shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none focus:ring-opacity-50 disabled:opacity-50 disabled:cursor-not-allowed',
        selectButtonLabel: 'block truncate',
        selectButtonPlaceholder: 'block truncate',
        selectButtonIcon: 'fill-current flex-shrink-0 ml-1 h-4 w-4',
        selectButtonClearButton: 'rounded flex flex-shrink-0 items-center justify-center absolute right-0 top-0 m-2 h-6 w-6 transition duration-100 ease-in-out',
        selectButtonClearIcon: 'fill-current h-3 w-3',
        dropdown: 'absolute w-full z-10 -mt-1 absolute border-b border-l border-r rounded-b shadow-sm z-10',
        optionsList: 'overflow-auto',
        searchWrapper: 'inline-block w-full',
        searchBox: 'inline-block w-full',
        option: 'cursor-pointer',
        disabledOption: 'opacity-50 cursor-not-allowed',
        highlightedOption: 'cursor-pointer',
        selectedOption: 'cursor-pointer',
        selectedHighlightedOption: 'cursor-pointer',
        optionLabel: 'truncate block',
        selectedIcon: 'fill-current h-4 w-4'
      },
      classes: {
        selectButton: 'bg-white border-gray-300',
        selectButtonPlaceholder: 'text-gray-400',
        selectButtonIcon: 'text-gray-600',
        selectButtonClearButton: 'hover:bg-blue-100 text-gray-600',
        dropdown: 'bg-white border-gray-300',
        dropdownFeedback: 'pb-2 px-3 text-gray-400 text-sm',
        loadingMoreResults: 'pb-2 px-3 text-gray-400 text-sm',
        searchWrapper: 'p-2 placeholder-gray-400',
        searchBox: 'px-3 py-2 bg-gray-50 text-sm rounded border focus:outline-none focus:shadow-outline border-gray-300',
        optgroup: 'text-gray-400 uppercase text-xs py-1 px-2 font-semibold',
        highlightedOption: 'bg-blue-100',
        selectedOption: 'font-semibold bg-gray-100 bg-blue-500 font-semibold text-white',
        selectedHighlightedOption: 'font-semibold bg-gray-100 bg-blue-600 font-semibold text-white',
        optionContent: 'flex justify-between items-center px-3 py-2',
        enterActiveClass: 'opacity-0 transition ease-out duration-100',
        enterToClass: 'opacity-100',
        leaveClass: 'transition ease-in opacity-100',
        leaveToClass: 'opacity-0 duration-75'
      },
      variants: {
        danger: {
          selectButton: 'border-red-300 bg-red-50 text-red-900',
          selectButtonPlaceholder: 'text-red-200',
          selectButtonIcon: 'text-red-500',
          selectButtonClearButton: 'hover:bg-red-200 text-red-500',
          dropdown: 'bg-red-50 border-red-300'
        },
        success: {
          selectButton: 'border-green-300 bg-green-50 text-green-900',
          selectButtonIcon: 'text-green-500',
          selectButtonClearButton: 'hover:bg-green-200 text-green-500',
          dropdown: 'bg-green-50 border-green-300'
        }
      }
    }
  },

  't-datepicker': {
    component: TDatePicker,
    props: {
      fixedClasses: {
        navigator: 'w-full relative flex',
        navigatorViewButton: 'flex items-center',
        navigatorViewButtonIcon: 'flex-shrink-0 h-5 w-5',
        navigatorViewButtonBackIcon: 'flex-shrink-0 h-5 w-5',
        navigatorLabel: 'flex items-center py-1',
        navigatorPrevButtonIcon: 'h-6 w-6 inline-flex',
        navigatorNextButtonIcon: 'h-6 w-6 inline-flex',

        inputWrapper: 'relative',
        viewGroup: 'inline-flex flex-wrap',
        view: 'w-64',
        calendarDaysWrapper: 'grid grid-cols-7',
        calendarHeaderWrapper: 'grid grid-cols-7',
        monthWrapper: 'grid grid-cols-4',
        yearWrapper: 'grid grid-cols-4',

        clearButton: 'flex flex-shrink-0 items-center justify-center absolute right-0 top-0 m-2 h-6 w-6',
        clearButtonIcon: 'fill-current h-3 w-3'
      }
    }
  }
}
Vue.use(VueTailwind, VueTailwindSettings)

Vue.use(VueAxios, axios)

Vue.config.productionTip = false

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
