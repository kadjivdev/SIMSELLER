<template>

    <div class="row">   
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Création d'un nouveau bon de commande</h3>
                </div>
                    <div class="card-body">
                        <div class="alert alert-success alert-dismissible" v-if="message">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-check"></i> Alert!</h5>
                            {{ message }}
                        </div>
                            <input type="hidden" name="statut" value="Préparation" />
                            <input type="hidden" name="valeur"  v-model="formCmde.valeur" />
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Code<span class="text-danger">*</span></label>
                                        <input type="hidden" class="form-control form-control-sm" name="code"  value=""  autocomplete="code" style="text-transform: uppercase" v-model="formCmde.code" autofocus required>
                                        <input type="text" class="form-control form-control-sm text-center" name="code"  value=""  autocomplete="code" style="text-transform: uppercase" v-model="formCmde.code" autofocus readonly>
                                        <span class="text-danger" v-if="errors.code">{{ errors.code }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6"></div>
                                <div class="col-sm-3 float-right">
                                    <div class="form-group">
                                        <label>Date<span class="text-danger">*</span></label>
                                        <input type="date" @change="changeButton()" class="form-control form-control-sm text-center" name="dateBon" v-model="formCmde.dateBon" autofocus required>
                                        <span class="text-danger" v-if="errors.dateBon">{{ errors.dateBon }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Fournisseur<span class="text-danger">*</span></label>
                                        <select @change="changeButton()" class="form-control form-control-sm" name="fournisseur_id" v-model="formCmde.fournisseur_id" style="width: 100%;">
                                            <option value="" selected disabled>** choisir fournisseur **</option>
                                            <option  v-for="four in listefournisseur" v-bind:value="four.id" v-bind:key="four.id">{{four.sigle}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Type<span class="text-danger">*</span></label>
                                        <select @change="changeButton()" class="form-control form-control-sm" name="type_commande_id" v-model="formCmde.type_commande_id" style="width: 100%;">
                                            <option value="" selected disabled>** choisir type commande **</option>
                                            <option v-for="type in typecommande" v-bind:value="type.id" :key="type.id">{{type.libelle}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <i class="spinner-border float-right" v-if="loader"></i>
                                    <button v-else v-show="!commandCreer" type="button" @click="saveData()" class="btn btn-sm btn-success float-right">
                                        <i class="fa-solid fa-floppy-disk"></i>
                                        Enregistrer
                                    </button>
                                    <button v-show="commandCreer&&updated===false" type="button" @click="deleteData()" class="btn btn-sm btn-danger float-right">
                                        <i class="fa-solid fa-trash-can"></i>
                                        Supprimer
                                    </button>
                                    <button v-show="commandCreer&&updated===false" type="button" :disabled="etat" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="btn btn-sm btn-warning float-right mr-3">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        Modifier
                                    </button>
                                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">Confirmation <i class="fa-solid fa-triangle-exclamation"></i></h5>
                                        </div>
                                        <div class="modal-body">
                                            <h6>Modifier le fournisseur va vider les produits. Êtes vous sûr de vouloir modifier le fournisseur?</h6>
                                        </div>
                                        <div class="modal-footer">
                                                <div class="col-sm-3">
                                                </div>
                                                <div class="col-sm-3">
                                                    <button type="button" class="btn btn-sm btn-secondary note-btn-block float-left" data-bs-dismiss="modal"><i class="fa-solid fa-circle-left mr-1"></i>Retour</button>
                                                </div>
                                                <div class="col-sm-3">
                                                    <button type="button"  @click="editData()"  class="btn btn-sm btn-warning btn-block"  data-bs-dismiss="modal">Confirmer<i class="fa-solid fa-check ml-1"></i></button>
                                                </div>
                                                <div class="col-sm-3">
                                                </div>                                         
                                        </div>
                                        </div>
                                    </div>
                                    </div>                                    
                                </div>
                            </div>
                        <hr/>
                            <div class="row align-content-center" v-if="commandCreer">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Produits<span class="text-danger">*</span></label>
                                        <select class="form-control form-control-sm select2" name="produit_id" v-model="formProduit.produit_id" style="width: 100%;">
                                            <option selected disabled>** choisir le produit **</option>
                                            <option v-for="produit in listeProduit" v-bind:value="produit.id"  :key="produit.id">{{produit.libelle}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Quantité<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control form-control-sm" name="qteCommander" v-model="formProduit.qteCommander"  value="" min="1"  autocomplete="qteCommander" autofocus required>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>PU TTC<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-dollar-sign"></i>
                                                        </span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm" name="pu" v-model="formProduit.pu"  value=""  min="1"  autofocus required>
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="fas fa-cart-shopping"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2 ">
                                    <i class="spinner-border" v-if="productLoader"></i>
                                    <button type="button" v-else @click="productData()" class="btn btn-primary btn-sm" style="margin-top: 2.1em"><i class="fas fa-solid fa-plus"></i> Ajouter</button>
                                </div>
                            </div>
                            <div class="row" v-show="commandCreer">
                                <div class="col-md-12">
                                    <table class="table table-bordered table-striped table-sm"  style="font-size: 12px">
                                        <thead class="text-white text-center bg-gradient-gray-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Produit</th>
                                            <th>Qté en T</th>
                                            <th>PU TTC</th>
                                            <th>Montant</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <tr v-for="(produit, index) in detailsCommande" :key="produit.id">
                                            <td>{{index+1}}</td>
                                            <td>{{produit.libelle}}</td>
                                            <td>{{produit.pivot.qteCommander}}</td>
                                            <td>{{produit.pivot.pu|formatMoney}}</td>
                                            <td>{{produit.pivot.qteCommander*produit.pivot.pu|formatMoney}}</td>
                                            <td class="text-center">
                                                <!-- <a class="btn btn-warning btn-sm" href=""><i class="fa-solid fa-pen-to-square"></i></a> -->
                                                <button class="btn btn-danger btn-sm" type="button" @click="deleteDetailData(produit.id)"><i class="fa-solid fa-trash-can"></i></button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <br/>
                                            <td><b>Total</b></td>
                                            <td colspan="4" class="text-right"><b>{{ this.somme|formatMoney }} F CFA</b></td>
                                            <td></td>

                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row justify-content-center">
                                    <div class="col-sm-4">
                                        <button type="button" @click="redirectLink()" v-show="produitAjouter" class="btn btn-block btn-primary" >
                                            Ok
                                            <i class="fa-solid fa-check ml-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                    </div>

            </div>
        </div>
        <div class="col-md-1"></div>
    </div>
</template>
                <create-bon-commande
                    users= {{ Auth::user()->name }}
                    :listefournisseur="{{ $fournisseurs }}"
                    baseurl= {{ env('APP_BASE_URL') }}
                    weburl= {{ env('APP_WEB_URL') }}
                    :typecommande="{{ $typecommandes }}"
                    :produits="{{ $produits }}"
                    code="{{ $code }}"
                    valeur="{{ $valeur }}"

                ></create-bon-commande>
<script>

    export default {
        props: [
            'listefournisseur',
            'users',
            'baseurl',
            'weburl',
            'typecommande',
            'produits',
            'detailboncommande',
            'dates',
            'code',
            'valeur'
        ],
        data(){

            return {

                formCmde: {
                    code: this.code,
                    dateBon: new Date().toISOString().substr(0, 10),
                    statut: 'Préparation',
                    type_commande_id: "",
                    fournisseur_id: "",
                    users: this.users,
                    commande_id:null,
                    valeur: this.valeur
                },
                formProduit:{
                    bon_commande_id: null,
                    produit_id: null,
                    qteCommander: null,
                    pu: null,
                },
                errors:{
                    code: null,
                    dateBon: null,
                    type_commande_id: null,
                    fournisseur_id: null,
                },
                detailsCommande:[],
                productLoader: false,
                loader: false,
                commandCreer:false,
                produitAjouter:false,
                message:null,
                somme:0,
                etat:true,
                updated:false,
                listeProduit:[],
                //date: new Date().toISOString().substr(0, 10),   
            }
        },
        mounted() {

        },
        methods: {
            saveData(){
                this.loader = true
                let url = this.baseurl+'boncommandes/store'
                axios.post(url, this.formCmde).then((response) => {
                    this.message = response.data.message;
                    this.formCmde.commande_id = response.data.commande.id
                    this.formCmde.fournisseur_id = response.data.commande.fournisseur_id
                    this.commandCreer = true;
                    this.loader = false;
                    this.etat = true;

                }).catch((errors) => {
                    console.log(errors.response.data);
                    this.errors.code = errors.response.data.code ? errors.response.data.code[0] : null;
                    this.errors.dateBon = errors.response.data.dateBon ? errors.response.data.dateBon[0] : null;
                    this.loader = false;

                }).finally(() => {
                    axios.get(this.baseurl+'produits/liste/'+this.formCmde.fournisseur_id).then((response) => {
                        this.listeProduit = response.data.produits;
                        console.log(response);
                    }) 
                })
            },

            editData(){
                this.loader = true
                this.updated = true
                //console.log(this.formCmde)
                let url = this.baseurl+'boncommandes/update'
                axios.post(url, this.formCmde).then((response) => {
                    this.message = response.data.message;
                    this.loader = false;                 
                    this.updated = false;
                    this.etat = true;

                }).catch((errors) => {
                    console.log(errors.response.data.message);
                    this.errors.code = errors.response.data.code ? errors.response.data.code[0] : null;
                    this.errors.dateBon = errors.response.data.dateBon ? errors.response.data.dateBon[0] : null;
                    this.loader = false;
                    this.etat = true;
                    this.updated = false;
                    this.etat = true;

                }).finally(() => {
                    axios.get(this.baseurl+'detailboncommandes/empty/'+this.formCmde.commande_id).then((response) => {
                    })

                    axios.get(this.baseurl+'detailboncommandes/details/'+this.formCmde.commande_id).then((response) => {
                        this.detailsCommande = response.data.BonCommande;
                        this.somme = response.data.somme;
                        this.productLoader = false;
                    })

                    axios.get(this.baseurl+'produits/liste/'+this.formCmde.fournisseur_id).then((response) => {
                        this.listeProduit = response.data.produits;
                        console.log(this.listeProduit);
                    }) 

                })
            },


            productData(){
                this.productLoader = true;
                let url = this.baseurl+'detailboncommandes/store';
                this.formProduit.bon_commande_id = this.formCmde.commande_id
                axios.post(url, this.formProduit).then((response) => {
                    this.message = response.data;
                    this.productLoader = false;
                    this.produitAjouter = true;
                }).catch((error) => {
                    console.log(error);
                    this.productLoader = false;
                    //this.errors.code = error.errors.code ? error.errors.code[0] : null;
                }).finally(() => {
                    axios.get(this.baseurl+'detailboncommandes/details/'+this.formCmde.commande_id).then((response) => {
                        this.detailsCommande = response.data.BonCommande;
                        this.somme = response.data.somme;
                        this.productLoader = false;
                        this.formProduit.produit_id = null;
                        this.formProduit.qteCommander = null;
                        this.formProduit.qteCommander = null;
                        this.formProduit.pu = null;
                    })

                    axios.get(this.baseurl+'produits/liste/'+this.formCmde.fournisseur_id).then((response) => {
                        this.listeProduit = response.data.produits;
                        console.log(response);
                    })                     
                })
            },


            deleteDetailData(produit_id){
                let url = this.baseurl+'detailboncommandes/destroy/'+this.formCmde.commande_id+'/'+produit_id;
                this.formProduit.bon_commande_id = this.formCmde.commande_id

                axios.get(url, this.formProduit).then((response) => {
                    this.message = response.data;
                    this.produitAjouter = true;
                }).catch((error) => {
                    console.log(error);
                    //this.errors.code = error.errors.code ? error.errors.code[0] : null;
                }).finally(() => {
                    axios.get(this.baseurl+'detailboncommandes/details/'+this.formCmde.commande_id).then((response) => {
                        this.detailsCommande = response.data.BonCommande;
                        this.somme = response.data.somme;
                        this.productLoader = false;
                    })

                    axios.get(this.baseurl+'produits/liste/'+this.formCmde.fournisseur_id).then((response) => {
                        this.listeProduit = response.data.produits;
                        console.log(response);
                    })                     
                })
            },


            deleteData(){
                window.location.href=this.weburl+'boncommandes/delete/'+this.formCmde.commande_id;
            },


            redirectLink(){
                if(this.etat == false){
                    alert('Oops! Valider la modification en cours en cliquant sur le boutton modifier!')
                }else{
                    window.location.href=this.weburl+"boncommandes/index";
                }

            },


            changeButton(){
                this.etat = false;
            },

            
        }
    }
</script>

<style scoped>
</style>
