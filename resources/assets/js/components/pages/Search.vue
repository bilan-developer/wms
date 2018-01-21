<template>
    <div>
        <v-card-title>
            <div>
                <v-btn color="error" @click="resetBasket">Сбросить</v-btn>
            </div>
            <div class="basket" @click="">
                <v-badge left>
                    <span slot="badge">{{ number }}</span>
                    <v-icon large color="grey lighten-1">shopping_cart</v-icon>
                </v-badge>
            </div>
            <v-spacer></v-spacer>
            <v-text-field
                    append-icon="search"
                    label="Поиск..."
                    single-line
                    hide-details
                    v-model="search"
            ></v-text-field>
        </v-card-title>
        <v-data-table
            v-bind:headers="tableHeaders"
            v-bind:items="tableItems"
            v-bind:search="search"
            v-bind:pagination.sync="pagination"
            v-bind:no-results-text="noResultsText"
            hide-actions
            class="elevation-1"
        >
            <template slot="items" slot-scope="props">

                <td class="text-xs-left">{{ props.item.tm }}</td>
                <td class="text-xs-left">{{ props.item.name }}</td>
                <td class="text-xs-center">{{ props.item.units }}</td>
                <td class="text-xs-center">{{ props.item.total }}</td>
                <td class="text-xs-center">{{ props.item.all }}</td>
                <td class="text-xs-center">{{ props.item.price }}</td>
                <td class="text-xs-center">
                    <add-product :id="props.item.id"></add-product>
                </td>
            </template>
        </v-data-table>
        <div class="text-xs-center pt-2">
            <v-pagination v-model="pagination.page" :length="pages"></v-pagination>
        </div>
        <div>
        </div>
    </div>

</template>

<script>
    import addProduct from '../dialogs/addProduct.vue';

    export default {

        components: {
            addProduct
        },

        data () {
            return {
                number: 0,
                search: '',
                noResultsText: 'Такого товара нет',
                pagination: {rowsPerPage: 10, page: 1},
                tableHeaders: {},
                tableItems: {}
            }
        },
        created: function(){
            console.log(window.basket);
            let uri = 'http://wms/get-products';
            Axios.get(uri).then((response) => {
                this.tableHeaders    = response.data.headers;
                this.tableItems      = response.data.items;
            });
        },

        computed: {
            pages () {
                return (this.pagination.rowsPerPage) ? Math.ceil(this.tableItems.length / this.pagination.rowsPerPage) : 0
            }
        },
        methods: {
            resetBasket: function() {
                this.number = 0;

            },
            removeFromList: function () {
            }
        }
    }
</script>
<style>
    .pagination__more{
        display: none;
    }
    .basket{
        margin-left: 25px;
        cursor: pointer;
    }
</style>