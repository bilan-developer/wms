<template v-if="open">
    <form>
        <v-text-field
                label="Название"
                v-model="name"
                :error-messages="nameErrors"
                @input="$v.name.$touch()"
                @blur="$v.name.$touch()"
                required
        ></v-text-field>
        <v-text-field
                label="Марка"
                v-model="tm"
                :error-messages="tmErrors"
                @input="$v.tm.$touch()"
                @blur="$v.tm.$touch()"
                required
        ></v-text-field>
        <v-text-field
                label="Ед."
                v-model="units"
                :error-messages="unitsErrors"
                @input="$v.units.$touch()"
                @blur="$v.units.$touch()"
                required
        ></v-text-field>
        <v-text-field
                label="В наличии"
                type="number"
                v-model.number="total"
                :error-messages="totalErrors"
                @input="$v.total.$touch()"
                @blur="$v.total.$touch()"
                required
        ></v-text-field>
        <v-text-field
                label="Необходимо"
                type="number"
                v-model.number="all"
                :error-messages="allErrors"
                @input="$v.all.$touch()"
                @blur="$v.all.$touch()"
                required
        ></v-text-field>
        <v-text-field
                label="Цена"
                type="number"
                v-model.number="price"
                :error-messages="priceErrors"
                @input="$v.price.$touch()"
                @blur="$v.price.$touch()"
                required
        ></v-text-field>

        <!--<v-select-->
                <!--label="Категория"-->
                <!--v-model="category"-->
                <!--:items="categoryItem"-->
                <!--:error-messages="categoryErrors"-->
                <!--@change="$v.category.$touch()"-->
                <!--@blur="$v.category.$touch()"-->
                <!--required-->
        <!--&gt;</v-select>-->


        <v-btn @click="submit">Добавить</v-btn>
        <v-btn @click="clear">Очистить</v-btn>
    </form>
</template>
<script>
    import { validationMixin } from 'vuelidate'
    import { required, maxLength, minLength, minValue, numeric, email } from 'vuelidate/lib/validators'

    export default {
        mixins: [validationMixin],
        validations: {
            name: { required, minLength: minLength(3) },
            tm: { required, minLength: minLength(3) },
            units: { required, minLength: minLength(1) },
            total: { required, minValue: minValue(0) },
            all: { required, minValue: minValue(0) },
            price: { required, minValue: minValue(0) },

//            category: { required },
        },
        props: {
            id: Number
        },
        data () {
            return {
                name: '',
                tm: '',
                units: 'шт.',
                total: 0,
                all: 0,
                price: 0,
//                category: null,
//                categoryItem: [
//                    'Item 1',
//                    'Item 2',
//                    'Item 3',
//                    'Item 4'
//                ],
            }
        },
        methods: {
            submit: function () {
                this.$v.$touch();
                if (!this.$v.$error) {
                    if (this.id) {
                        this.update();
                    } else {
                        this.create();
                    }
                }
            },
            clear () {
                this.$v.$reset();
                this.name = '';
                this.tm = '';
                this.units = 'шт.';
                this.total = 0;
                this.all = 0;
                this.price= 0;
                this.category = null;
            },
            create: function() {
                axios.post('/product', {
                    name: this.name,
                    tm: this.tm,
                    units: this.units,
                    total: this.total,
                    all: this.all,
                    price: this.price
                }).then(response => {
                    this.$store.dispatch('successBlock', {text:"Товар добавлен", time:1000});
                    this.clear();
                    console.log('1 - levels');

                    this.$emit("updateTable");
                }).catch(e => {  this.$store.dispatch('errorBlock', {text:"Ошибка", time:1000});})
            },
            update: function () {
                axios.post('/product/' + this.id, {
                        _method: 'PUT',
                        name: this.name,
                        tm: this.tm,
                        units: this.units,
                        total: this.total,
                        all: this.all,
                        price: this.price
                }).then(response => {
                    this.$store.dispatch('successBlock', {text:"Товар обновлён", time:1000});
                    console.log('1 - levels');
                    this.$emit("updateTable");
                }).catch(e => {  this.$store.dispatch('errorBlock', {text:"Ошибка", time:1000});})

            }

        },
        mounted: function () {
            if(this.id){
                console.log('Редактирование');
                let url = '/product/' + this.id;
                    axios.get(url).then((response) => {
                        let product = response.data.product;
                        this.name  = product.name,
                        this.tm    =  product.tm,
                        this.units = product.units,
                        this.total = product.total,
                        this.all   = product.all,
                        this.price =  product.price
                });
            }
        },
        computed: {
//            categoryErrors () {
//                const errors = []
//                if (!this.$v.category.$dirty) return errors
//                !this.$v.category.required && errors.push('Не выбрана категория')
//                return errors
//            },
            nameErrors () {
                const errors = []
                if (!this.$v.name.$dirty) return errors
                !this.$v.name.minLength && errors.push('Название должно содержать больше 3 символов')
                !this.$v.name.required && errors.push('Укажите название товара')
                return errors
            },
            tmErrors () {
                const errors = []
                if (!this.$v.tm.$dirty) return errors
                !this.$v.tm.minLength && errors.push('Марка товара должна содержать больше 3 символов')
                !this.$v.tm.required && errors.push('Укажите марку товара')
                return errors
            },
            unitsErrors () {
                const errors = []
                if (!this.$v.units.$dirty) return errors
                !this.$v.units.minLength && errors.push('Единица измерения должна содержать больше 1 символов')
                !this.$v.units.required && errors.push('Укажите (кратко) единицу измерения товара')
                return errors
            },
            totalErrors () {
                const errors = []
                if (!this.$v.total.$dirty) return errors
                !this.$v.total.minValue && errors.push('Не может быть меньше 0')
                !this.$v.total.required && errors.push('Укажите минимальное количество (0)')
                return errors
            },
            allErrors () {
                const errors = []
                if (!this.$v.all.$dirty) return errors
                !this.$v.all.minValue && errors.push('Не может быть меньше 0')
                !this.$v.all.required && errors.push('Укажите минимальное количество (0)')
                return errors
            },
            priceErrors () {
                const errors = []
                if (!this.$v.price.$dirty) return errors
                !this.$v.price.minValue && errors.push('Цену не может быть меньше 0')
                !this.$v.price.required && errors.push('Укажите цену за единицу товара')
                return errors
            },
        }
    }
</script>