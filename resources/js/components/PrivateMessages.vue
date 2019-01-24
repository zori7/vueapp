<template>
    <div class="container">
        <h1>Chats</h1>

        <div class="list-group">
            <router-link v-for="(chat, key) in chats" :to="'/pm/' + chat" :key="'link-' + chat" class="list-group-item list-group-item-action">
                {{ users[key].name }}
                <span v-if="messages[key]" class="text-primary"> - new message: <h3>{{ messages[key] }}</h3></span>
            </router-link>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                chats: [],
                users: [],
                messages: []
            }
        },
        mounted () {
            axios.get('/api/pm').then((response) => {
                this.chats = response.data['chats'];
                this.users = response.data['users'];
                this.messages = response.data['messages'];
            });
        }
    }
</script>
