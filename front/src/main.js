import './scss/main.scss';

import Vue from 'vue'
import App from './App.vue'
import vuetify from './plugins/vuetify';
import router from './router'
import VueClipboards from "vue-clipboards";
import Ewll from './../Main';
import VueResource from 'vue-resource';
import store from './store'

let snack = {
  install(Vue) {
    Vue.prototype.$snack = {
      listener: null,
      success(data) {
        if (null !== this.listener) {
          this.listener(data.text);
        }
      },
      danger(data) {
        return this.success(data);
      }
    }
  }
}

Vue.use(VueResource);
Vue.use(VueClipboards);
Vue.use(snack);
Vue.use(Ewll);

Vue.config.productionTip = false

new Vue({
  vuetify,
  store,
  router,
  render: h => h(App)
}).$mount('#app')
