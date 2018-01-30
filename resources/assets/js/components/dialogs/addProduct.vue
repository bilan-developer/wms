<template>
    <v-layout row justify-center>
        <v-dialog v-model="dialog" persistent max-width="1100px">
            <v-btn :color="color" slot="activator" v-on:click="">{{ nameBtn }}</v-btn>
            <v-card>
                <v-toolbar color="indigo" dark>
                    <v-toolbar-title>{{ toolbarTitle }}</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn  v-on:click="closeDialog" icon>
                        <v-icon>close</v-icon>
                    </v-btn>
                </v-toolbar>
                <v-card-text>
                    <add-form action="/test"></add-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="closeDialog">Закрыть</v-btn>
                    <v-btn color="blue darken-1" flat @click="saveData">Добавить в корзину</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
    import addForm from '../form/addProductForm.vue';
    
    export default {
        components: {
            addForm
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
            color: 'success'
        }),
        created: function(){
           if(this.type_btn === 'edit'){
               this.toolbarTitle = 'Редактирование товара';
               this.nameBtn = 'Редактировать';
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
            }
        }
    }
</script>
<style>
    .btn-close{
        cursor: pointer;
    }
</style>