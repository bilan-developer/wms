<template>
    <v-layout row justify-center>
        <v-dialog v-model="dialog" persistent max-width="700px">
            <v-btn color="primary" dark slot="activator" v-on:click="getProduct">Добавить</v-btn>
            <v-card>
                <v-toolbar color="indigo" dark>
                    <v-toolbar-title>{{ product.name }}</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <v-container fluid>
                        <v-layout row>
                            <v-flex xs5>
                                <v-layout row>
                                    <v-flex xs6>
                                        <v-subheader>В наличии</v-subheader>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-flex xs6>
                                            <v-subheader>{{ product.total }}</v-subheader>
                                        </v-flex>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex xs2>
                            </v-flex>
                            <v-flex xs5>
                                <v-layout row>
                                    <v-flex xs6>
                                        <v-subheader>Цена</v-subheader>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-flex xs6>
                                            <v-subheader>{{ product.price }} <span> .грн</span></v-subheader>
                                        </v-flex>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                        </v-layout>
                        <v-layout row>
                            <v-flex xs5>
                                <v-layout row>
                                    <v-flex xs6>
                                        <v-subheader>Количество</v-subheader>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-flex xs6>
                                            <v-text-field
                                                    id="test"
                                                    v-model.number="number"
                                                    v-on:change="updateTotalPrice"
                                                    v-on:input="updateTotalPrice"
                                                    type="number"
                                                    required
                                                    autofocus
                                            ></v-text-field>
                                        </v-flex>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex xs2>
                            </v-flex>
                            <v-flex xs5>
                                <v-layout row>
                                    <v-flex xs6>
                                        <v-subheader>На</v-subheader>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-flex xs6>
                                            <v-subheader>{{ this.totalPrice }} <span> .грн</span></v-subheader>
                                        </v-flex>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="closeDialog">Close</v-btn>
                    <v-btn color="blue darken-1" flat @click="saveData">Save</v-btn>
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
            dialog: false,
            number: 0,
            total: 0,
            flag: false,
            product: {
                name: '',
                price: 0
            },
            totalPrice: 0

        }),

        methods: {

            /**
             * Закрываем диалоговое окно
             */
            closeDialog: function() {
                this.dialog = false;
                console.log(this.numberTest);

            },

            /**
             * Обновляем общую стоимость за товар
             */
            updateTotalPrice: function() {
                if(Math.round(Number(this.number)) >  Math.round((Number(this.total)))){
                    this.number = Math.round((Number(this.total)));
                }

                this.totalPrice = Math.round((Number(this.number) * Number(this.product.price)) * 100) / 100;
                this.product.total = Math.round((Number(this.total) - Number(this.number)) * 100) / 100;
                console.log(total);
            },

            /**
             * Сохраняем позицию в корзине
             */
            saveData: function () {
//                this.flag = false;
                this.product.total = Math.round((Number(this.product.total) - Number(this.number)) * 100) / 100;
                window.appVue.numberGoods++;
                window.appVue.products.push({
                    'id' : this.product.id,
                    'name' : this.product.name,
//                    '' : this.product.id,

                }) ;

                this.closeDialog();
            },

            /**
             * Получаем товар по id
             */
            getProduct: function() {
                if(!this.flag){
                    let uri = '/get-product/'+this.id;
                    Axios.get(uri).then((response) => {
                        console.log(response.data.product);
                        this.product = response.data.product;
                        this.total = response.data.product.total;
                    });
                }

            }
        }
    }
</script>