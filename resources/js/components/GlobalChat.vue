<template>
    <div class="container">
        <h2 class="text-center">Global chat</h2>
        <div id="chat" class="container-fluid">

            <div class="row my-2 rounded" v-for="message in dataMessages">
                <div class="col-2">
                    <router-link :to="'/user/' + message.user.id">{{ message.user.name }}</router-link>
                </div>
                <div class="col-8 message">
                    {{ message.text }}
                </div>
                <div class="col-2">
                    {{ message.created }}
                </div>
            </div>

        </div>

        <div id="form" class="container">

            <form class="form-inline justify-content-center" @submit.prevent="sendMessage">

                <div class="form-group mx-sm-3 mx-2 mb-2 mt-2 input">
                    <input type="text" class="form-control" name="text" placeholder="Your message here" v-model="message">
                </div>
                <button type="submit" class="btn btn-primary mb-2 mt-2" :disabled="message.length === 0">Send</button>

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
                user: {}
            }
        },
        mounted () {

            axios.get('/api/isadmin').then((response) => {
                this.user = response.data['user'];
            });

            axios.get('/api/global').then((response) => {
                this.dataMessages = response.data;
                setTimeout(() => {
                    let chat = document.getElementById("chat");
                    chat.scrollTop = chat.scrollHeight;
                }, 200);
            });

            window.Echo.channel('global').listen('GlobalMessage', ({data}) => {
                this.dataMessages.push({
                    text: data['text'],
                    user: data['user'],
                    created: data['created']
                });
                setTimeout(() => {
                    let chat = document.getElementById("chat");
                    chat.scrollTop = chat.scrollHeight;
                }, 200);
            });
        },
        methods: {
            sendMessage () {
                let message = this.message;
                let date = new Date();
                let time = date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds();
                axios.post('/api/global', {
                    text: this.message,
                    user_id: this.user.id
                }).then(() => {
                    this.dataMessages.push({
                        text: message,
                        user: {
                            id: this.user.id,
                            name: this.user.name
                        },
                        created: time
                    });

                    setTimeout(() => {
                        let chat = document.getElementById("chat");
                        chat.scrollTop = chat.scrollHeight;
                    }, 200);
                });

                this.message = "";
            }
        }
    }
</script>

<style lang="scss" scoped>

    #chat {
        background: #eff;
        height: calc(100vh - 200px);
        min-height: 100px;
        overflow-y: scroll;
    }

    .row {
        background: #d4eaff;
        color: #3f3844;
    }

    .col-2 {
        display: flex;
        align-items: center;
    }

    .message {
        padding: 1rem;
    }

    .input {
        width: 60%;
    }

    .form-control {
        width: 100%;
    }

</style>
