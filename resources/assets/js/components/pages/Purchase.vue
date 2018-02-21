<template>
    <div class="col-md-12">
        <div class="col-md-12">
            <v-card-title>
                <div>
                    <v-chip color="primary" label outline>Продаж на {{ salesAmount }} грн. <i class="material-icons">account_balance_wallet</i></v-chip>
                </div>
                <v-spacer></v-spacer>
                <div class="col-md-7">
                    <v-text-field
                            append-icon="search"
                            label="Поиск..."
                            single-line
                            hide-details
                            v-model="search"
                    ></v-text-field>
                </div>
            </v-card-title>
            <!--<v-card-title>-->
                <!--<v-container grid-list-xl text-xs-center>-->
                    <!--<v-layout row wrap>-->
                        <!--<v-flex xs2 offset-xs10>-->
                        <!--</v-flex>-->
                    <!--</v-layout>-->
                <!--</v-container>-->
                <!--&lt;!&ndash;<div class="sales-amount">&ndash;&gt;-->
                <!--&lt;!&ndash;</div>&ndash;&gt;-->
            <!--</v-card-title>-->
        </div>
        <v-data-table
                v-bind:headers="tableHeaders"
                v-bind:items="tableItems"
                v-bind:pagination.sync="pagination"
                v-bind:no-results-text="noResultsText"
                :search="search"
                hide-actions
                class="elevation-1"
        >
            <template slot="items" slot-scope="props">

                <td class="col-md-2 text-xs-left">{{ props.item.created_at }}</td>
                <td class="text-xs-left">{{ props.item.comment }}</td>
                <td class="col-md-2 text-xs-center">{{ props.item.amount }}</td>
                <td class="col-md-1 text-xs-right">
                    <show-purchase :id="props.item.id"></show-purchase>
                </td>
            </template>
        </v-data-table>
        <div class="text-xs-center pt-2">
            <v-pagination v-model="pagination.page" :length="pages"></v-pagination>
        </div>
    </div>
</template>

<script>
    import showPurchase from '../dialogs/showPurchase.vue';

    export default {
        components: {
            showPurchase
        },
        data () {
            return {
                search: '',
                salesAmount: 0,
                noResultsText: 'Информация не найдена',
                pagination: {rowsPerPage: 10, page: 1},
                tableHeaders: [],
                tableItems: []
            }
        },
        created: function(){
            let uri = '/purchase';
            Axios.get(uri).then((response) => {
                this.tableHeaders = response.data.headers;
                this.tableItems   = response.data.items;
                this.salesAmount  = response.data.salesAmount;
            });
        },

        computed: {
            pages () {
                return (this.pagination.rowsPerPage) ? Math.ceil(this.tableItems.length / this.pagination.rowsPerPage) : 0
            }
        },
        methods: {

        }
    }
</script>
<style>
    .pagination__more{
        display: none;
    }
    .sales-amount{
        text-align: right;
    }
    .sales-amount span span{
        cursor:pointer!important;
    }

</style>
