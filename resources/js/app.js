/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


// Bon de commande
Vue.component('create-bon-commande', require('./components/BonCommande/createBonComande.vue').default);

Vue.component('edit-bon-commande', require('./components/BonCommande/editBonCommande.vue').default);

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

Vue.component('suppression-detail', require('./components/BonCommande/suppressionDetail.vue').default);


Vue.component('dashboard-component', require('./components/DashboardComponent.vue').default);


//Commande client
Vue.component('suppression-commander', require('./components/CommandeClient/suppressionCommander.vue').default);

// Banque Vue Router
Vue.component('banque-index-component', require('./components/banques/IndexComponent').default);

Vue.component('banque-create-component', require('./components/banques/CreateComponent').default);



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


const app = new Vue({
    el: '#app',
});


Vue.filter('formatMoney', function(amount,decimalCount= 0,decimal = ",", thousands = " ") {
    try {
        decimalCount = Math.abs(decimalCount);
        decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

        const negativeSign = amount < 0 ? "-" : "";

        let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
        let j = (i.length > 3) ? i.length % 3 : 0;

        return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
    } catch (e) {
        console.log(e)
    }
});

