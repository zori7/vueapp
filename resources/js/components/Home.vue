<template>
    <div class="container" v-if="show">
        <div class="jumbotron">
            <h1 class="display-4">Welcome to Vue App!</h1>
            <p class="lead">This is a simple Vue.js application with basic functions</p>
            <hr class="my-4">
            <p>At first you can open the <span class="text-info">Posts page</span></p>
            <router-link to="/posts" class="btn btn-primary btn-lg">Learn more &raquo;</router-link>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                show: true
            }
        },
        mounted () {
            var url = window.location.pathname;

            if(url.includes('login') || url.includes('register') || url.includes('password')) this.show = false;
        }
    }
</script>