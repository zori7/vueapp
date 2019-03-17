<template>
    <div class="container">
        <h2 class="text-center"><router-link :to="'/user/' + targetUser.id">{{ targetUser.name }}</router-link></h2>
        <div id="chat" class="container-fluid">



            <div class="row my-2" v-for="(message, key) in dataMessages">
                <div class="col-md-4 col-1" v-if="message.class === 'request'"></div>
                <div class="col-md-8 col-11" :class="message.class + ' message'">
                    {{ message.text }}
                    <button class="btn btn-danger btn-sm" @click="deleteMessage(key)" v-if="message.class === 'request'">Delete</button>
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
                currentUser: {},
                targetUser: {}
            }
        },
        props: ['id', 'don'],
        mounted () {
            axios.get('/api/isadmin').then((response) => {
                this.currentUser = response.data['user'];

                window.Echo.private('pm.' + this.currentUser.id).listen('PrivateMessage', ({data}) => {
                    this.dataMessages.push({
                        text: data.text,
                        class: 'response'
                    });

                    axios.post('/api/readmessage/' + data.id);

                    setTimeout(() => {
                        let chat = document.getElementById("chat");
                        chat.scrollTop = chat.scrollHeight;
                    }, 300);
                });

                axios.post('/api/readall/' + this.id);
            });

            axios.get('/api/users/' + this.id).then((response) => {
                this.targetUser = response.data['user'];
            });


            axios.get('/api/pm/' + this.id).then((response) => {

                this.dataMessages = response.data;

                setTimeout(() => {
                    let chat = document.getElementById("chat");
                    chat.scrollTop = chat.scrollHeight;
                }, 300);
            });
        },
        methods: {
            sendMessage () {

                let message = this.message;

                axios.post('/api/pm', {
                    text: this.message,
                    target_user_id: this.targetUser.id,
                    channel_id: this.targetUser.id
                }).then((response) => {
                    this.dataMessages.push({
                        text: message,
                        class: 'request',
                        id: response.data
                    });

                    setTimeout(() => {
                        let chat = document.getElementById("chat");
                        chat.scrollTop = chat.scrollHeight;
                    }, 200);
                });

                this.message = "";

            },
            deleteMessage(key) {

                axios.delete('/api/pm/' + this.dataMessages[key].id).then(() => {

                    this.dataMessages.splice(key, 1);

                });
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

    .message {
        padding: 1rem;
    }

    .request {
        border-radius: 10px 10px 0 10px;
        background: #5993ff;
        color: #fff;
        transition: all .15s ease-in-out;
        position: relative;
        .btn {
            position: absolute;
            top: 3px;
            right: 3px;
            border-radius: 3px 10px 3px 3px;
        }
        &:hover {
            background: #387cff;
        }
        &::selection {
            background: #fff;
            color: #4b4c54;
        }
    }
    
    .response {
        border-radius: 10px 10px 10px 0;
        background: #bed1e2;
        color: #4b4c54;
        transition: all .15s ease-in-out;
        &:hover {
            background: #afc0d0;
        }
    }

    .input {
        width: 60%;
    }

    .form-control {
        width: 100%;
    }

</style>
