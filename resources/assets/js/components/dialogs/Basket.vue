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
                    <v-spacer></v-spacer>
                    <v-btn  v-on:click="dialog=false" icon>
                        <v-icon>close</v-icon>
                    </v-btn>
                </v-toolbar>
                <v-card-text class="card-text-basket">
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
                    <div class="amount">
                        <v-chip v-show="amount"  label outline color="primary">Всего: {{ amount }} грн.</v-chip>
                    </div>
                </v-card-text>

                <v-card-actions>
                    <v-flex xs6 class="coment-field">
                        <v-text-field
                                v-model="comment"
                                :disabled="btnPay"
                                name="comment"
                                label="Коментарий"
                                multi-line
                                rows="2"
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs6>
                        <div class="btn-panel">
                            <v-btn color="blue darken-1" flat :disabled="btnPay" @click="writeOff('write-off')">Списание</v-btn>
                            <v-btn color="blue darken-1" flat :disabled="btnPay" @click="writeOff('bay')">Оплаченно</v-btn>
                        </div>
                    </v-flex>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>

    export default {
        data: () => ({
            comment: '',
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
             * Функция округления числа
             */
            modRound: function(value, precision){
                let precision_number = Math.pow(10, precision);
                return Math.round(value * precision_number) / precision_number;
            },

            /**
             * Списание || Покупка.
             */
            writeOff: function (operation) {
                let positions = this.getIdNumber();
                this.btnPay = true;

                if(!positions.length){
                    this.$store.dispatch('errorBlock', {text:"Корзина пуста", time:1000});
                    return false
                }

                axios.post('/write-off', {
                    data: {operation: operation,positions: positions, amount: this.amount, comment: this.comment}
                }).then(response => {
                        if(response.data.status === 'ok'){
                            let message = (operation === 'bay') ? 'Оплаченно' : 'Списано';
                            this.$store.dispatch('successBlock', {text: message, time:1000});
                            this.clearBasket(false);
                            this.$emit('updateTable');
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
                        number: products[key].number,
                        amount: this.modRound(products[key].number * products[key].product.price, 2)
                    })
                }

                return result;
            },

            /**
             * Очистить корзину
             */
            clearBasket: function(message) {
                this.$store.dispatch('clearBasket');
                this.comment = '';
                this.btnPay = true;

                if(message){
                    this.$store.dispatch('successBlock', {text:"Корзина очищена", time:1000});
                }

            },

            /**
             * Открыть корзину
             */
            open: function () {
                let products = this.$store.getters.getProducts;
                let item = [];
                let amount = 0;
                let modRound = this.modRound;
                $(products).each(function (key, value) {
                    item.push(
                        {
                            tm: value.product.tm,
                            name: value.product.name,
                            units: value.product.units,
                            number: value.number,
                            price: value.product.price,
                            amount: modRound(value.number * value.product.price, 2)
                        }
                    );
                    amount = amount + modRound(value.number * value.product.price, 2);
                });

                this.items = item;
                this.amount = this.modRound(amount, 2);
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
        text-align: right;
    }
    .btn-panel{
        text-align: right;
        position: absolute;
        bottom: 26px;
        right: 5px;
    }
    .coment-field{
        margin-left: 25px;
    }
    .card-text-basket{
        padding-bottom: 0;
    }
</style>