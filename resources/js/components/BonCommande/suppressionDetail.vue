<template>
    <div style="display: inline-block">
        <button class="btn btn-danger btn-sm" type="button" data-bs-toggle="modal" :data-bs-target="'#staticBackdrop'+this.detailboncommandeid" ><i class="fa-solid fa-trash-can"></i></button>
        <div class="modal fade" :id="'staticBackdrop'+this.detailboncommandeid" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" :aria-labelledby="'staticBackdropLabel'+this.detailboncommandeid" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" :id="'staticBackdropLabel'+this.detailboncommandeid">Confirmation <i class="fa-solid fa-triangle-exclamation"></i></h5>
                    </div>
                    <div class="modal-body">
                        <h6>Êtes vous sûr de vouloir supprimer le produit <b>{{this.produit}}</b>?</h6>
                    </div>
                    <div class="modal-footer">
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-sm btn-secondary note-btn-block" data-bs-dismiss="modal"><i class="fa-solid fa-circle-left mr-1"></i>Retour</button>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" @click="deleteData()" class="btn btn-sm btn-warning btn-block">Confirmer<i class="fa-solid fa-check ml-1"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        props : [
            'detailboncommandeid',
            'produit',
            'baseurl',
            'weburl',


        ],

        mounted() {
            console.log('Component mounted.')
        },

        methods: {
            deleteData(){
                axios.get(this.baseurl+'detailboncommandes/destroy/'+this.detailboncommandeid).then((response) => {
                    window.location.href=this.weburl
                } )
            }
        }
    }
</script>
