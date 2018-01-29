<template>
    <v-layout row justify-center>
        <div>
            <v-btn color="error" @click="clearBasket(true)">Очистить</v-btn>
        </div>
        <v-dialog v-model="dialog" persistent max-width="1000px">
            <div class="basket" slot="activator" @click="open">
                <v-badge left>
                    <span slot="badge">{{ $store.state.basket.quantity }}</span>
                    <v-icon large color="grey lighten-1">shopping_cart</v-icon>
                </v-badge>
            </div>
            <v-card>
                <v-toolbar color="indigo" dark>
                    <v-toolbar-title>Корзина</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <v-data-table
                            :headers="headers"
                            :items="items"
                            hide-actions
                            item-key="name"
                            :no-data-text="noData"
                    >
                        <template slot="items" slot-scope="props">
                                <td class="text-xs-left">{{ props.item.name }}</td>
                                <td class="text-xs-left">{{ props.item.tm }}</td>
                                <td class="text-xs-center">{{ props.item.units }}</td>
                                <td class="text-xs-center">{{ props.item.number }}</td>
                                <td class="text-xs-center">{{ props.item.price }}</td>
                                <td class="text-xs-center">{{ props.item.amount }}</td>
                        </template>
                    </v-data-table>
                </v-card-text>
                <div v-show="amount" class="amount"><span>Всего: {{ amount }} </span></div>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="dialog=false">Закрыть</v-btn>
                    <v-btn color="blue darken-1" flat :disabled="btnPay" @click="pay">Оплаченно</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>

    export default {
        data: () => ({
            btnPay:false,
            amount: 0,
            dialog: false,
            noData: 'Корзина пуста',
            headers: [
                { text: 'Название',   align: 'left',   value: 'name', sortable: true },
                { text: 'Марка',      align: 'left',   value: 'tm' },
                { text: 'Ед.',        align: 'center', value: 'units' },
                { text: 'Количество', align: 'center', value: 'number' },
                { text: 'Цена',       align: 'center', value: 'price' },
                { text: 'Cумма',      align: 'center', value: 'amount' },
            ],
            items: [
                {
                    tm: 'ELFINA',
                    name: 'ВД для стен "NORMA" 10 кг',
                    units: 'шт',
                    number: 24,
                    price: 45,
                    amount: 87,
                }
            ]
        }),
        methods: {
            /**
             * Сохраняем позицию в корзине
             */
            pay: function () {
                let positions = this.getIdNumber();
                this.btnPay = true;

                if(!positions.length){
                    this.$store.dispatch('errorBlock', {text:"Корзина пуста", time:1000});
                    return false
                }

                axios.post(`/pay`, {
                    data: {positions: positions, amount: this.amount}
                }).then(response => {
                    console.log(response);
                        if(response.data.status === 'ok'){
                            this.$store.dispatch('successBlock', {text:"Оплаченно", time:1000});
                            this.clearBasket(false);
                        }else{
                            this.$store.dispatch('errorBlock', {text:"Ошибка", time:1000});
                        }
                    })
                    .catch(e => {
                        this.$store.dispatch('errorBlock', {text:"Ошибка", time:1000});
                        this.btnPay = false;
                    })
            },

            getIdNumber: function () {
                let products = this.$store.getters.getProducts;
                let result = [];
                for(let key in products){
                    result.push({
                        id: products[key].product.id,
                        number: products[key].number
                    })
                }

                return result;
            },

            /**
             * Очистить корзину
             */
            clearBasket: function(message) {
                this.$store.dispatch('clearBasket');
                this.btnPay = true;

                if(message){
                    this.$store.dispatch('successBlock', {text:"Корзина очищена", time:1000});
                }

            },

            /**
             * Сохраняем позицию в корзине
             */
            open: function () {
                let products = this.$store.getters.getProducts;
                let item = [];
                let amount = 0;
                $(products).each(function (key, value) {
                    item.push(
                        {
                            tm: value.product.tm,
                            name: value.product.name,
                            units: value.product.units,
                            number: value.number,
                            price: value.product.price,
                            amount: Math.round(Number(value.number) * Math.round(Number(value.product.price)))
                        }
                    );
                    amount = amount + Math.round(Number(value.number) * Math.round(Number(value.product.price)));
                });

                this.items = item;
                this.amount = amount;
                this.btnPay = !amount;

            }
        }
    }
</script>
<style>
    .basket{
        margin-top: 10px;
        margin-left: 25px;
        cursor: pointer;
    }
    .amount{
        padding: 0 25px 15px 0;
        text-align: right;
    }
</style>