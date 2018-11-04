<template>
    <div class="col-md-12">
        <div class="col-md-12">
            <v-card-title>
                <div>
                    <v-chip color="primary" label outline>Списаний на {{ salesAmount.toFixed(2) }} грн. <i class="material-icons">account_balance_wallet</i></v-chip>
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
                    <show-purchase :id="props.item.id" :type=type :is_return="props.item.is_return" @updateTable="getInfoTable"></show-purchase>
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
                tableItems: [],
                type: "write-off"
            }
        },
        created: function(){
            this.getInfoTable();
        },
        computed: {
            pages () {
                return (this.pagination.rowsPerPage) ? Math.ceil(this.tableItems.length / this.pagination.rowsPerPage) : 0
            }
        },
        methods: {
            getInfoTable: function () {
                Axios.get('/write-off-list').then((response) => {
                    this.tableHeaders = response.data.headers;
                    this.tableItems   = response.data.items;
                    this.salesAmount  = response.data.salesAmount;
                });
            }
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
