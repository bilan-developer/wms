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
                                                    v-on:blur="updateTotalPrice"
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
            col: 1,
            dialog: false,
            number: 0,
            total: 0,
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
            },

            /**
             * Обновляем общую стоимость за товар
             */
            updateTotalPrice: function() {
                if(Math.round(Number(this.number)) >  Math.round((Number(this.total)))){
                    this.number = Math.round((Number(this.total)));
                    this.$store.dispatch('errorBlock', "В наличии столько нет", 3000);
                }

                if(Math.round(Number(this.number)) < 0){
                    this.number = 0;
                    this.$store.dispatch('errorBlock', "Не может быть отрицательным", 3000);
                }

                this.totalPrice = Math.round((Number(this.number) * Number(this.product.price)) * 100) / 100;
                this.product.total = Math.round((Number(this.total) - Number(this.number)) * 100) / 100;
            },

            /**
             * Сохраняем позицию в корзине
             */
            saveData: function () {
                if(Math.round(Number(this.number)) === 0){
                    this.$store.dispatch('errorBlock', "Выберите минимальное количество", .000);
                    return false;
                }

                this.$store.dispatch('addProduct', {
                    product: this.product,
                    number: Math.round(Number(this.number))
                });

                this.$store.state.basket.quantity++;
                this.$store.dispatch('successBlock', "Товар добавлен в корзину", 3000);

                this.closeDialog();
            },

            /**
             * Получаем товар по id и проверяем на наличие его в корзине
             */
            getProduct: function() {
                this.number = 0;
                let uri = '/get-product/'+this.id;
                Axios.get(uri).then((response) => {

                    let id = this.id;
                    let inBasket = 0;
                    let products = this.$store.getters.getProducts;
                    $(products).each(function (key, value) {
                        if(id === value.product.id){
                            inBasket = inBasket + value.number;
                        }
                    });

                    this.product = response.data.product;
                    this.product.total = this.product.total - inBasket;
                    this.total = this.product.total;

                });
            }
        }
    }
</script>