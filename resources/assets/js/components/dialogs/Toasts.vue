<template>
    <v-card color="grey lighten-4">
        <v-snackbar
                :timeout="$store.state.toasts.time"
                :color="$store.state.toasts.color"
                :top="true"
                :right="true"
                :multi-line="true"
                v-model="$store.state.toasts.status"
                v-on:click="close"
        >
            {{ $store.state.toasts.text }}
        </v-snackbar>
    </v-card>
</template>

<script>
    export default {
        methods: {
            close() {
                this.$store.state.toasts.status = false;
            }
        }
    }
</script>