<template>
    <v-layout>
        <v-dialog v-model="dialog" max-width="400px">
            <v-btn icon class="mx-0" slot="activator">
                <v-icon color="pink">add</v-icon>
            </v-btn>
            <v-card>
                <v-toolbar color="purple" dark>
                    <v-card-title>
                        <span class="headline">Добавить категорию</span>
                    </v-card-title>
                    <v-spacer></v-spacer>
                    <v-btn  v-on:click="close" icon>
                        <v-icon>close</v-icon>
                    </v-btn>
                </v-toolbar>

                <v-card-text>
                    <v-container style="width: 100%;">
                        <v-layout wrap>
                            <v-flex>
                                <v-text-field label="Название" v-model="name"></v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click.native="save">Сохранить</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-layout>
</template>
<script>
    export default {
        data () {
            return {
                dialog: false,
                name: '',
            }
        },
        methods: {
            deleteCategory: function (item) {
                const index = this.$store.getters.categories.indexOf(item);
                this.$store.getters.categories.splice(index, 1);
            },
            save () {
                axios.post('/category', {
                    name: this.name,
                }).then(response => {
                    this.$store.dispatch('addCategory', {name:response.data.name, id:response.data.id});
                    this.$store.dispatch('successBlock', {text:"Категория добавлена", time:1000});
                    this.close();
                }).catch(e => {  this.$store.dispatch('errorBlock', {text:"Ошибка", time:1000});})
            },
            close () {
                this.dialog = false;

            }
        },
    }
</script>
<style>
    .btn-close{
        cursor: pointer;
    }
</style>