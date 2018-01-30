<template>
    <v-app id="inspire">
        <toasts></toasts>
        <v-navigation-drawer
                fixed
                clipped
                app
                v-model="drawer"
        >
            <v-list dense>
                <template v-for="(item, i) in items">
                    <v-layout
                            row
                            v-if="item.heading"
                            align-center
                            :key="i"
                    >
                        <v-flex xs6>
                            <v-subheader v-if="item.heading">
                                {{ item.heading }}
                            </v-subheader>
                        </v-flex>
                        <v-flex xs6 class="text-xs-center">
                            <a href="#!" class="body-2 black--text">EDIT</a>
                        </v-flex>
                    </v-layout>
                    <v-list-group v-else-if="item.children" v-model="item.model" no-action>
                        <v-list-tile slot="item" @click="">
                            <v-list-tile-action>
                                <v-icon>{{ item.model ? item.icon : item['icon-alt'] }}</v-icon>
                            </v-list-tile-action>
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    {{ item.text }}
                                </v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile
                                v-for="(child, i) in item.children"
                                :key="i"
                                @click=""
                        >
                            <v-list-tile-action v-if="child.icon">
                                <v-icon>{{ child.icon }}</v-icon>
                            </v-list-tile-action>
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    {{ child.text }}
                                </v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </v-list-group>
                    <v-list-tile v-else @click="toggle = item.comp ">
                        <v-list-tile-action>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>
                                {{ item.text }}
                            </v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </template>
            </v-list>
        </v-navigation-drawer>
        <v-toolbar
                color="blue darken-3"
                dark
                app
                clipped-left
                fixed
        >
            <v-toolbar-title :style="$vuetify.breakpoint.smAndUp ? 'width: 300px; min-width: 250px' : 'min-width: 72px'" class="ml-0 pl-3">
                <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
                <span class="hidden-xs-only">WMS</span>
            </v-toolbar-title>
            <div class="d-flex align-center" style="margin-left: auto">

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">

                    </a>
                <v-toolbar-title>{{ $store.state.user.user.data.name }}</v-toolbar-title>
                <v-menu bottom left>
                    <v-btn icon slot="activator" dark>
                        <v-icon>more_vert</v-icon>
                    </v-btn>
                    <v-list>
                        <v-list-tile v-for="item in rightMenu" :key="item.title" @click="">
                            <v-list-tile-title @click="item.method">{{ item.title }}</v-list-tile-title>
                        </v-list-tile>
                    </v-list>
                </v-menu>

            </div>
        </v-toolbar>
        <v-content>
            <search  v-if="toggle === 'search'"></search>
            <stock   v-if="toggle === 'stock'"></stock>
            <report  v-if="toggle === 'report'"></report>
            <purchase  v-if="toggle === 'purchase'"></purchase>
        </v-content>
    </v-app>
</template>

<script>
    import search from './pages/Search';
    import stock  from './pages/Stock';
    import report from './pages/Report';
    import purchase from './pages/Purchase';
    import toasts from './dialogs/Toasts.vue';



    export default {
        props: {
            source: String,
        },
        components: {
            search,
            stock,
            report,
            purchase,
            toasts
        },
        data: () => ({
            toggle: 'search',
            drawer: null,
            items: [
                { icon: 'search', text: 'Поиск', comp: 'search'},
                { icon: 'home', text: 'Склад', comp: 'stock' },
                { icon: 'content_copy', text: 'Отчёты', comp: 'report' },
                { icon: 'shopping_basket', text: 'Покупки', comp: 'purchase' },
            ],
            rightMenu: [
                { title : 'Настройки',  method: "" },
                { title : 'Выход',      method: function () {
                    return console.log('Выхddод');
                } }
            ]
        }),
        methods: {
            exit: function() {
                console.log('Выход');
            },
        },
        mounted() {
            // Инициализация пользователя
            this.$store.dispatch('initUser');
        }
    }
</script>