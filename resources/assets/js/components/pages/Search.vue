<template>
    <div class="col-md-12">
        <div class="col-md-12">
            <v-card-title>
                <div class="col-md-2">
                    <basket @updateTable="getProducts"></basket>
                </div>
                <div class="col-md-7 col-md-offset-3">
                    <v-text-field
                            append-icon="search"
                            label="Поиск..."
                            single-line
                            hide-details
                            v-model="search"
                    ></v-text-field>
                </div>
            </v-card-title>
        </div>

        <v-data-table
            :headers="tableHeaders"
            :items="tableItems"
            :search="search"
            :pagination.sync="pagination"
            :no-results-text="noResultsText"
            hide-actions
            class="elevation-1"
        >
            <template slot="items" slot-scope="props">

                <td class="text-xs-left">{{ props.item.name }}</td>
                <td class="text-xs-left">{{ props.item.tm }}</td>
                <td class="text-xs-center">{{ props.item.units }}</td>
                <td class="text-xs-center">{{ props.item.total }}</td>
                <td class="text-xs-center">{{ props.item.all }}</td>
                <td class="text-xs-center">{{ props.item.price }}</td>
                <td class="text-xs-right col-md-1 col-md-offset-1">
                    <add-product-basket :id="props.item.id"></add-product-basket>
                </td>
            </template>
        </v-data-table>
        <div class="text-xs-center pt-2">
            <v-pagination v-model="pagination.page" :length="pages"></v-pagination>
        </div>
    </div>
</template>

<script>
    import addProductBasket from '../dialogs/addProductBasket.vue';
    import basket from '../dialogs/Basket.vue';

    export default {

        components: {
            addProductBasket,
            basket
        },

        data () {
            return {
                search: '',
                noResultsText: 'Такого товара нет',
                pagination: {rowsPerPage: 10, page: 1},
                tableHeaders: [],
                tableItems: []
            }
        },
        created: function(){
            this.getProducts();
        },

        computed: {
            pages () {
                return (this.pagination.rowsPerPage) ? Math.ceil(this.tableItems.length / this.pagination.rowsPerPage) : 0
            }
        },
        methods: {
            getProducts: function () {
                let uri = '/get-products';
                Axios.get(uri).then((response) => {
                    this.tableHeaders    = response.data.headers;
                    this.tableItems      = response.data.items;
                });
            }
        }
    }
</script>
<style>
    .pagination__more{
        display: none;
    }
</style>