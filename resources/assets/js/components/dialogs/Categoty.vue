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
                    <category-form></category-form>
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
                                            @blur="categoryUpdate(props.item)"
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
    import categoryForm from '../form/CategoryForm.vue';


    export default {
        components: {
            categoryForm
        },
        data () {
            return {
                dialog: false,
                dialogForm: false,
                name: '',
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
            },

            /**
             * Удаление категории.
             */
            deleteCategory:function (item) {
                axios.post('/category/' + item.id, {
                    _method: 'DELETE',
                }).then(response => {
                    const index = this.$store.getters.categories.indexOf(item);
                    this.$store.getters.categories.splice(index, 1);
                    this.$store.dispatch('successBlock', {text:"Категория удалена", time:1000});
                }).catch(e => {  this.$store.dispatch('errorBlock', {text:"Ошибка", time:1000});})
            },
            categoryUpdate:function (item) {
                axios.post('/category/' + item.id, {
                    _method: 'PUT',
                    name: item.name,
                }).then(response => {
                    const index = this.$store.getters.categories.indexOf(item);
                    this.$store.getters.categories.splice(index, 1);

                    this.$store.dispatch('addCategory', {name:item.name, id:item.id});
                    this.$store.dispatch('successBlock', {text:"Категория изменина", time:1000});

                }).catch(e => {  this.$store.dispatch('errorBlock', {text:"Ошибка", time:1000});});
            }
        },
    }
</script>
<style>
    .btn-close{
        cursor: pointer;
    }
</style>