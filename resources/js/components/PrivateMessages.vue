<template>
    <div class="container">
        <h1>Chats</h1>

        <div class="list-group">
            <router-link v-for="(chat, key) in chats" :to="'/pm/' + chat" :key="'link-' + chat" class="list-group-item list-group-item-action">{{ users[key].name }}</router-link>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                chats: [],
                users: []
            }
        },
        mounted () {
            axios.get('/api/pm').then((response) => {
                this.chats = response.data['chats'];
                this.users = response.data['users'];
            });
        }
    }
</script>
