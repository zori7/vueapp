<template>
    <div class="container-fluid">

        <div class="container-fluid main-chat">
            <h1 class="text-center py-2">Welcome to our global chat!</h1>

            <div class="container" id="chat">

                <div class="card my-2" v-for="message in dataMessages">

                    <div class="card-header">
                        <div class="d-inline name px-3"><router-link :to="'/user/' + message.user_id">{{ message.user_name }}</router-link></div>
                        <div class="d-inline float-right">{{ message.created_at }}</div>
                    </div>

                    <div class="card-body">
                        {{ message.text }}
                    </div>

                </div>

            </div>
        </div>

        <div class="container-fluid message-form d-flex align-items-center">
            <form class="form-inline row justify-content-center" @submit.prevent="sendMessage">
                <div class="form-group new-message mx-2">
                    <input type="text" class="form-control" v-model="message" placeholder="Your message">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" :disabled="!message">Send</button>
                </div>
            </form>
        </div>

    </div>
</template>

<script>

    export default {
        data: function () {
            return {
                dataMessages: [],
                message: "",
                username: "",
                userId: -1,
                isAdmin: false
            }
        },
        mounted () {

            axios.get('/api/global').then((response) => {
                this.dataMessages = response.data;
                setTimeout(function () {
                    let chat = document.getElementById('chat');
                    chat.scrollTop = chat.scrollHeight;
                }, 500);
            });

            var socket = io.connect('http://192.168.10.10:3000');
            socket.on("news-action:App\\Events\\NewMessage", (data) => {
                this.dataMessages.push(data.message);
                setTimeout(function () {
                    let chat = document.getElementById('chat');
                    chat.scrollTop = chat.scrollHeight;
                }, 200);
            });

            axios.get('/api/isadmin').then((response) => {
                this.userId = response.data['user'].id;
                this.username = response.data['user'].name;
                this.isAdmin = response.data['isAdmin'];
            });

        },
        methods: {
            sendMessage () {

                let now = new Date().toLocaleTimeString();

                axios({
                    method: 'get',
                    url: '/api/chat/send-message',
                    params: { message: this.message, userId: this.userId, username: this.username, created_at: now }
                }).then(() => {
                    this.message = "";
                });

            }
        }
    }

</script>

<style lang="scss" scoped>

    .main-chat {
        height: calc(100vh - 120px);
        background: #f2f4ff;
        padding: 0;
    }

    h1 {
        height: 50px;
        margin: 0;
    }

    #chat {
        overflow: scroll;
        height: calc(100% - 50px);
    }

    .card {
        border-radius: 0;
    }

    .card-header {
        padding: 0.5rem;
    }

    .card-body {
        padding: 1rem;
    }

    .card-header .name {
        font-size: 1.2em;
        color: #0062cc;
    }

    .message-form {
        /*background: #f2f4ff;*/
        height: 65px;
    }

    .form-inline {
        width: 100%;
    }

    .new-message {
        width: 60%;
    }

    .new-message input {
        width: 100%;
    }

</style>
