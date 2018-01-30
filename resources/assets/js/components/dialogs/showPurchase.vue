<template>
    <v-layout row justify-center>
        <v-btn color="primary" dark v-on:click="show">Просмотреть</v-btn>
        <v-dialog v-model="dialog" persistent max-width="1000px">
            <!--<v-btn color="primary" dark v-on:click="show" slot="activator">Просмотреть</v-btn>-->
            <v-card>
                <v-toolbar color="indigo" dark>
                    <v-toolbar-title>Покупка</v-toolbar-title>
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
                <div class="amount"><span>Всего: {{ amount }} </span></div>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="dialog=false">Закрыть</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>

    export default {
        props: {
            id: Number
        },
        data: () => ({
            amount: 0,
            dialog: false,
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
             * Получаем список товаров с покупки
             */
            show: function() {
                let uri = '/purchase/' + this.id;
                Axios.get(uri).then((response) => {
                    this.headers = response.data.headers;
                    this.items   = response.data.items;
                    this.amount  = response.data.salesAmount;
                    this.dialog  = true;
                });
            },

        }
    }
</script>
<style>
    .amount{
        padding: 0 25px 15px 0;
        text-align: right;
    }
</style>