<template>
    <div class="substrate">
        <div class="progress_circular">
            <v-progress-circular indeterminate v-bind:size="100" v-bind:width="7" color="purple"></v-progress-circular>
        </div>
    </div>
</template>
<style>
    .substrate{
        background: #d5d5d5;
        width: 100%;
        height: 100%;
        position:absolute;
        opacity: 0.7;
        z-index: 999;

    }
    .progress_circular{
        width:100px;
        height:100px;
        position:absolute;
        left:50%;
        top:50%;
        margin:-150px 0 0 0;
        z-index: 999999;
    }
</style>