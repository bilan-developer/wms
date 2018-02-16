<template>
    <v-layout row justify-left>
        <v-dialog v-model="dialog" persistent max-width="1100px" scrollable>
            <v-tooltip top slot="activator" >
                <v-btn class="top-left-block" slot="activator" color="purple " v-on:click=""><i class="material-icons">list</i></v-btn>
                <span>Категории</span>
            </v-tooltip>
            <v-card>
                <v-toolbar color="purple" dark>
                    <v-toolbar-title>{{ toolbarTitle }}</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn  v-on:click="closeDialog" icon>
                        <v-icon>close</v-icon>
                    </v-btn>
                </v-toolbar>
                <v-card-text style="height: 400px;">
                    <v-dialog v-model="dialogForm" max-width="400px">
                        <v-btn icon class="mx-0" slot="activator">
                            <v-icon color="pink">add</v-icon>
                        </v-btn>
                        <v-card>
                            <v-toolbar color="purple" dark>
                                <v-card-title>
                                    <span class="headline">Добавить категорию</span>
                                </v-card-title>
                                <v-spacer></v-spacer>
                                <v-btn  v-on:click="dialogForm=false" icon>
                                    <v-icon>close</v-icon>
                                </v-btn>
                            </v-toolbar>

                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                        <v-flex xs12 sm6 md4>
                                            <v-text-field label="Название" v-model="editedItem.name"></v-text-field>
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
                    <v-data-table
                            :items="this.$store.getters.categories"
                            hide-actions
                            hide-headers
                    >
                        <template slot="items" slot-scope="props">
                            <td>
                                <v-edit-dialog
                                        :return-value.sync="props.item.name"
                                        lazy
                                > {{ props.item.name }}
                                    <v-text-field
                                            slot="input"
                                            label="Edit"
                                            v-model="props.item.name"
                                            single-line
                                            counter
                                            :rules="[max25chars]"
                                    ></v-text-field>
                                </v-edit-dialog>
                            </td>
                            <td> <v-btn icon class="mx-0" @click="deleteCategory(props.item)">
                                <v-icon color="pink">delete</v-icon>
                            </v-btn></td>
                        </template>
                    </v-data-table>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
    export default {
        data () {
            return {
                dialog: false,
                dialogForm: false,
                editedItem: {
                    name: '',
                },
                toolbarTitle: 'Управление категориями',
                max25chars: (v) => v.length <= 25 || 'Длинное название',
            }
        },
        methods: {
            /**
             * Закрываем диалоговое окно
             */
            closeDialog: function() {
                this.dialog = false;
                console.log(this.categories);
                console.log(this.$store.getters.categories);
            },
            deleteCategory: function (item) {
                const index = this.$store.getters.categories.indexOf(item);
                this.$store.getters.categories.splice(index, 1);
            }
        },
    }
</script>
<style>
    .btn-close{
        cursor: pointer;
    }
</style>