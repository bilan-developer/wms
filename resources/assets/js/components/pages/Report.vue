<template>
    <div class="col-md-12">
        <!--<div class="col-md-12">-->
            <!--<v-card-title>-->
                <!--<div class="col-md-12">-->
                    <!--<v-chip color="primary" label outline>Остаток на складе на {{ balance }} грн. <i class="material-icons">account_balance_wallet</i></v-chip>-->
                <!--</div>-->
                <!--<div class="col-md-12">-->
                    <!--<v-chip color="primary" label outline>Продаж на {{ salesAmount }} грн. <i class="material-icons">account_balance_wallet</i></v-chip>-->
                <!--</div>-->
                <!--<div class="col-md-12">-->
                    <!--<v-chip color="primary" label outline>Списаний на {{ marriageAmount }} грн. <i class="material-icons">account_balance_wallet</i></v-chip>-->
                <!--</div>-->
            <!--</v-card-title>-->
            <!--<div>-->
                <!--<p v-for="value in course" >-->
                    <!--{{ value.base_ccy }} / {{ value.ccy }} {{value.buy}} - {{value.sale}}-->
                <!--</p>-->
            <!--</div>-->
        <!--</div>-->
        <div class="col-md-12">
            <div class="col-md-12">
                <v-card-title>
                    <div class="btn-panel-stock">
                        <div>
                            <v-btn v-for="value in operations"
                                   color="info"
                                   v-on:click="getHistory(value.id)"
                            >
                                {{ value.name }}
                            </v-btn>
                        </div>
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
                    :headers="tableHeaders"
                    :items="tableItems"
                    :search="search"
                    :pagination.sync="pagination"
                    :no-results-text="noResultsText"
                    hide-actions
                    class="elevation-1"
            >
                <template slot="items" slot-scope="props">
                    <td class="text-xs-left">{{ props.item.product.name }}</td>
                    <td class="text-xs-left">{{ props.item.number }}</td>
                    <td class="text-xs-center">{{ props.item.price }}</td>
                    <td class="text-xs-center">{{ props.item.amount }}</td>
                    <td class="text-xs-center">{{ props.item.created_at }}</td>
                </template>
            </v-data-table>
            <div class="text-xs-center pt-2">
                <v-pagination v-model="pagination.page" :length="pages"></v-pagination>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                operations:[],
                balance:'',
                salesAmount:'',
                marriageAmount:'',
                course:[
                    {
                        ccy:'',
                        base_ccy:'',
                        buy:'',
                        sale:''
                    }
                ],
                search: '',
                noResultsText: 'Такого товара нет',
                pagination: {rowsPerPage: 10, page: 1, sortBy: 'created_at'},
                tableHeaders: [
                    {text: 'Товар', align: 'center', sortable: true, value: 'name' },
                    {text: 'Количество', align: 'center', sortable: true, value: 'number' },
                    {text: 'Цена', align: 'center', sortable: true, value: 'price' },
                    {text: 'Сумма', align: 'center', sortable: true, value: 'amount' },
                    {text: 'Дата', align: 'center',  value: 'created_at' },
                ],
                tableItems: []
            }
        },
        created: function(){
//            this.getBalance();
//            this.getCourse();
            this.getOperations();
            this.getHistory(1);
        },
        computed: {
            pages () {
                return (this.pagination.rowsPerPage) ? Math.ceil(this.tableItems.length / this.pagination.rowsPerPage) : 0
            }
        },
        methods: {
            getCourse: function () {
                let vueObject = this;
                Axios.get('/course').then((response) => {
                    vueObject.course = response.data;
                }).catch(e => {  this.$store.dispatch('errorBlock', {text:"Ошибка", time:1000});})
            },
                getHistory:function (id) {
                let uri = '/history/' + id;
                Axios.get(uri).then((response) => {
                    console.log(response);
                    this.tableItems = response.data;
                });
            },
            getOperations:function () {
                let uri = '/operations';
                Axios.get(uri).then((response) => {
                    this.operations = response.data;
                });
            },
        }
    }
</script>
<style>
    .sales-amount span span{
        cursor:pointer!important;
    }

</style>
