<template>
    <div class="container-fluid my-3">

        <div class="row justify-content-end my-2">
            <div class="col-md-8 col-11">
                <div class="card answer">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div>
                            Date: {{ answer.created_at }}
                        </div>
                        <div>
                            <button v-if="currentUser === answer.user_id || isAdmin" class="btn btn-sm btn-primary" @click="edit">
                                <span v-if="editing">Cancel</span>
                                <span v-else>Edit</span>
                            </button>
                            <button v-if="currentUser === answer.user_id || isAdmin" class="btn btn-sm btn-danger" @click="deleteAnswer(answer.id)">Delete</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <blockquote class="blockquote mb-0">

                            <div v-if="editing" class="container">
                                <form class="form" @submit.prevent="updateAnswer">
                                    <div class="form-group row">
                                        <input type="text" class="form-control col-md-9 mx-2" v-model="editedAnswer.text">
                                        <button type="submit" class="btn btn-sm btn-primary col mx-2">Save</button>
                                    </div>
                                </form>
                            </div>
                            <p v-else>{{ answer.text }}</p>
                            <footer class="blockquote-footer">
                                <router-link :to="'/user/' + answer.user_id">{{ answer.userName }}</router-link>
                            </footer>
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

    export default {

        data: function () {
            return {
                answer: {
                    text: "",
                    user_id: -1,
                    userName: "",
                    created_at: ""
                },
                currentUser: this.$parent.$parent.currentUser,
                isAdmin: this.$parent.$parent.isAdmin,
                editing: false,
                editedAnswer: {
                    text: ""
                }
            }
        },
        props: ['answer_id'],
        mounted () {
            this.update();
        },
        methods: {
            update () {
                axios.get('/api/answers/' + this.answer_id).then((response) => {
                    this.answer = response.data['answer'];
                    this.answer.userName = response.data['user']['name'];
                    this.editedAnswer.text = this.answer.text;
                });
            },
            deleteAnswer(id) {
                axios.delete('/api/answers/' + id).then(() => {
                    this.$parent.update();
                });
            },
            edit () {
                if(this.editing) {
                    this.editing = false;
                    this.editedAnswer.text = this.answer.text;
                } else {
                    this.editing = true;
                }
            },
            updateAnswer () {

                if(this.editedAnswer.text === "") {
                    alert("Your comment is empty!");
                    return false;
                }

                if(this.editedAnswer.text.length > 255) {
                    alert("Your comment is too long!");
                    return false;
                }

                axios.post('/api/answers/edit/' + this.answer.id, {
                    'text': this.editedAnswer.text,
                }).then(() => {
                    this.update();
                    this.editing = false;
                });

            }
        }

    }

</script>

<style>

    .answer {
        position: relative;
    }

    .arrow {
        width: 10%;
        height: 55px;
        border-bottom: 3px solid #575d60;
        border-left: 3px solid #575d60;
        border-radius: 0 0 0 5px;
        position: absolute;
        top: -29px;
        left: -8%;
    }

</style>
