<template>
                    <div class="card-body">
                        <div class="alert alert-success alert-dismissible" v-if="message">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-check"></i> Alert!</h5>
                            {{ message }}
                        </div>
                            <div class="row align-content-center">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Produits<span class="text-danger">*</span></label>
                                        <input type="hidden" class="form-control form-control-sm" name="produit_id" v-bind:value="formProduit.id" :key="formProduit.id" min="1"  autocomplete="produit_id" autofocus required>
                                        <input type="redonly" class="form-control form-control-sm"  v-model="formProduit.libelle" disabled>
                                        <!-- <select class="form-control form-control-sm select2" name="produit_id"  v-model="formProduit.produit_id" style="width: 100%;">
                                            <option  v-bind:value="formProduit.id" :key="formProduit.id" selected>{{formProduit.libelle}}</option>
                                            <option v-for="produit in produits" v-bind:value="produit.id"  :key="produit.id">{{produit.libelle}}</option>
                                        </select> -->
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label>Qté Cmder<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control form-control-sm" name="qteCommander" v-model="formProduit.qteCommander"  value="" min="1"  autocomplete="qteCommander" autofocus required>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>PU TTC<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-dollar-sign"></i>
                                                        </span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm" name="pu" v-model="formProduit.pu"  value=""  autofocus required>
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="fas fa-cart-shopping"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Remise</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-dollar-sign"></i>
                                                        </span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm" name="remise" v-model="formProduit.remise"  value=""  autofocus required>
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="fas fa-cart-shopping"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label>Qté Valider</label>
                                        <input type="number" class="form-control form-control-sm" name="qteValider" v-model="formProduit.qteValider"  value="" min="1"  autocomplete="qteCommander" autofocus required>
                                    </div>
                                </div>                               
                                <div class="col-sm-1 ">
                                    <i class="spinner-border" v-if="productLoader"></i>
                                    <button type="button" @click="updateData()" class="btn btn-primary btn-sm btn-block text-center" style="margin-top: 2.1em"><i class="fa-solid fa-circle-check"></i></button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered table-striped table-sm"  style="font-size: 12px">
                                        <thead class="text-white text-center bg-gradient-gray-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Produit</th>
                                            <th>Qté Cmder</th>
                                            <th>Qté Valider</th>
                                            <th>PU TTC</th>
                                            <th>Remise</th>
                                            <th>Montant</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <tr v-for="(produit, index) in detailsCommande" :key="produit.id">
                                            <td>{{index+1}}</td>
                                            <td>{{produit.libelle}}</td>
                                            <td>{{produit.pivot.qteCommander}}</td>
                                            <td>{{produit.pivot.qteValider}}</td>
                                            <td>{{produit.pivot.pu}}</td>
                                            <td>{{produit.pivot.remise}}</td>
                                            <td>{{produit.pivot.qteCommander*produit.pivot.pu}}</td>
                                            <td class="text-center">
                                                <button class="btn btn-warning btn-sm"  type="button" @click="getData(produit)"><i class="fa-solid fa-pen-to-square"></i></button>
                                                <button class="btn btn-danger btn-sm" type="button" @click="deleteData(produit.id)"><i class="fa-solid fa-trash-can"></i></button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <br/>
                                            <td><b>Total</b></td>
                                            <td colspan="5" class="text-right"><b>{{ this.somme }} F CFA</b></td>
                                            <td></td>

                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row justify-content-center">
                                    <div class="col-sm-4">
                                        <button type="button" @click="redirectLink()" v-show="produitModifier" class="btn btn-block btn-primary" >
                                            Ok
                                            <i class="fa-solid fa-check ml-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                    </div>
</template>

<script>
    export default {
        props: [
            'boncommande',
            'listefournisseur',
            'users',
            'baseurl',
            'weburl',
            'typecommande',
            'produits',
            'detailboncommande',
        ],
        data(){
            return {
                formProduit:{
                    bon_commande_id: this.boncommande.id,
                    produit_id: null,
                    libelle: null,
                    qteCommander: null,
                    pu: null,
                    remise: null,
                    qteValider: null,
                    users: this.users,

                },

                getProduit:[],
                detailsCommande:[],
                productLoader: false,
                loader: false,
                commandCreer:false,
                produitModifier:false,
                message:null,
                somme:0,
                //date: new Date().toISOString().substr(0, 10),
            }
        },
        mounted() {
            this.showData()
        },
        methods: {

            getData(produit){
                    this.formProduit.produit_id =produit.id,
                    this.formProduit.libelle =produit.libelle,
                    this.formProduit.qteCommander =produit.pivot.qteCommander,
                    this.formProduit.pu =produit.pivot.pu,
                    this.formProduit.remise =produit.pivot.remise,
                    this.formProduit.qteValider =produit.pivot.qteValider
                //this.getProduit = produit;
                //console.log(this.getProduit);
            },

            updateData(){
                this.productLoader = true;
                let url = this.baseurl+'detailboncommandes/update';
                axios.post(url, this.formProduit).then((response) => {
                    this.message = response.data;
                    this.productLoader = false;
                    this.produitModifier = true;
                }).catch((errors) => {
                    this.loader = false;                
                    }).finally(() => {
                    axios.get(this.baseurl+'detailboncommandes/details/'+this.formProduit.bon_commande_id).then((response) => {
                        this.detailsCommande = response.data.BonCommande;
                        this.somme = response.data.somme;
                        this.productLoader = false;
                        this.formProduit.produit_id = null;
                        this.formProduit.qteCommander = null;
                        this.formProduit.pu = null;
                        this.formProduit.remise = null;
                        this.formProduit.qteValider = null;
                    })
                })
            },


            deleteData(produit_id){
                let url = this.baseurl+'detailboncommandes/destroy/'+this.boncommande.id+'/'+produit_id;
                this.formProduit.bon_commande_id = this.formCmde.commande_id

                axios.get(url, this.formProduit).then((response) => {
                    this.message = response.data;
                    this.produitModifier = true;
                }).catch((error) => {
                    console.log(error);
                    //this.errors.code = error.errors.code ? error.errors.code[0] : null;
                }).finally(() => {
                    axios.get(this.baseurl+'detailboncommandes/details/'+this.formCmde.commande_id).then((response) => {
                        this.detailsCommande = response.data;
                        this.productLoader = false;
                    })
                })
            },

            showData(){
                axios.get(this.baseurl+'detailboncommandes/details/'+this.boncommande.id).then((response) => {
                    this.detailsCommande = response.data.BonCommande;
                    this.somme = response.data.somme;
                    this.productLoader = false;
            })
            },

            redirectLink(){
                window.location.href=this.weburl+"boncommandes/index";
            },




        }
    }
</script>

<style scoped>

</style>
