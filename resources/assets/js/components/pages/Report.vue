<template>
    <div class="col-md-12">
        <div class="col-md-12">
            <v-card-title>
                <div class="col-md-12">
                    <v-chip color="primary" label outline>Остаток на складе на {{ balance }} грн. <i class="material-icons">account_balance_wallet</i></v-chip>
                </div>
                <div class="col-md-12">
                    <v-chip color="primary" label outline>Продаж на {{ salesAmount }} грн. <i class="material-icons">account_balance_wallet</i></v-chip>
                </div>
                <div class="col-md-12">
                    <v-chip color="primary" label outline>Списаний на {{ marriageAmount }} грн. <i class="material-icons">account_balance_wallet</i></v-chip>
                </div>
            </v-card-title>
            <div>
                <p v-for="value in course" >
                    {{ value.base_ccy }} / {{ value.ccy }} {{value.buy}} - {{value.sale}}
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
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
                ]
            }
        },
        created: function(){
            this.getBalance();
            this.getCourse();
        },
        methods: {
            getBalance: function () {
                let vueObject = this;
                let uri = '/balance';
                Axios.get(uri).then((response) => {
                    let data = response.data;
                    vueObject.balance = data.balance;
                    vueObject.salesAmount = data.salesAmount;
                    vueObject.marriageAmount = data.marriageAmount;
                }).catch(e => {  this.$store.dispatch('errorBlock', {text:"Ошибка", time:1000});})
            },

            getCourse: function () {
                let vueObject = this;
                Axios.get('/course').then((response) => {
                    vueObject.course = response.data;
                    console.log(response);
                }).catch(e => {  this.$store.dispatch('errorBlock', {text:"Ошибка", time:1000});})
            }
        }
    }
</script>
<style>
    .sales-amount span span{
        cursor:pointer!important;
    }

</style>
