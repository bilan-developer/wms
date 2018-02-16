<template>
    <v-layout row justify-left>
        <v-dialog v-model="dialog" persistent max-width="1100px">
            <v-tooltip top slot="activator" >
                <v-btn class="top-left-block" slot="activator" v-if="type_btn === 'add'" color="success" v-on:click=""><i class="material-icons">add</i></v-btn>
                <span>Добавить товар</span>
            </v-tooltip>
            <v-tooltip top slot="activator">
                <v-btn class="top-left-block" v-if="type_btn === 'edit'" slot="activator" color="primary" v-on:click=""><i class="material-icons">border_color</i></v-btn>
                <span>Редактировать товар</span>
            </v-tooltip>
            <v-card>
                <v-toolbar color="indigo" dark>
                    <v-toolbar-title>{{ toolbarTitle }}</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn  v-on:click="closeDialog" icon>
                        <v-icon>close</v-icon>
                    </v-btn>
                </v-toolbar>
                <v-card-text>
                    <product-form v-if="dialog" :id="id" @updateTable="updateTable"></product-form>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
    import productForm from '../form/ProductForm.vue';
    
    export default {
        components: {
            productForm
        },
        props: {
            id: Number,
            type_btn: {
                type: String,
                default: 'add'
            }
        },
        data: () => ({
            dialog: false,
            toolbarTitle: 'Добавление товара',
            nameBtn: 'Добавить',
            color: 'success',
        }),
        created: function(){
           if(this.type_btn === 'edit'){
               this.toolbarTitle = 'Редактирование товара';
               this.nameBtn = "<i class='material-icons'>border_color</i>";
               this.color = 'primary';
           }
        },

        methods: {
            /**
             * Закрываем диалоговое окно
             */
            closeDialog: function() {
                this.dialog = false;
            },

            /**
             * Функция округления числа
             */
             modRound: function(value, precision){
                let precision_number = Math.pow(10, precision);
                return Math.round(value * precision_number) / precision_number;
            },

            updateTable: function () {
                this.$emit("updateTable");
            }
        },

    }
</script>
<style>
    .btn-close{
        cursor: pointer;
    }
</style>