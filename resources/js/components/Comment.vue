<template>
    <div class="container-fluid my-3">

        <div class="container-fluid">
            <div class="row justify-content-center my-2">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div>
                                Date: {{ comment.created_at }}
                            </div>
                            <div>
                                <button v-if="currentUser === comment.user_id || isAdmin" class="btn btn-sm btn-primary" @click="edit">
                                    <span v-if="editing">Cancel</span>
                                    <span v-else>Edit</span>
                                </button>
                                <button v-if="currentUser === comment.user_id || isAdmin" class="btn btn-sm btn-danger" @click="deleteComment(comment.id)">Delete</button>
                                <button v-if="!answering" @click="showForm" class="btn btn-sm btn-primary">Answer &raquo;</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <blockquote class="blockquote mb-0">

                                <div v-if="editing" class="container">
                                    <form class="form" @submit.prevent="updateComment">
                                        <div class="form-group row">
                                            <input type="text" class="form-control col-md-9 mx-2" v-model="editedComment.text">
                                            <button type="submit" class="btn btn-sm btn-primary col mx-2">Save</button>
                                        </div>
                                    </form>
                                </div>
                                <p v-else>{{ comment.text }}</p>
                                <footer class="blockquote-footer">
                                    <router-link :to="'/user/' + comment.user_id">{{ comment.userName }}</router-link>
                                </footer>
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <answer v-for="(answer, i) in answers" :answer_id="answer" :id="'answer_' + comment_id + '_' + i" :key="answer"></answer>

        <div class="container-fluid my-3" v-if="answering">
            <div class="row justify-content-end my-2">
                <div class="col-md-8 col-11">

                    <form @submit.prevent="saveAnswer()" method="post" id="answerForm">

                        <div class="form-group">
                            <input  @blur="blur" type="text" class="form-control" :id="'text_' + comment.id" v-model="answer.text" placeholder="Your comment here">
                        </div>

                        <div class="form-group">
                            <button type="button" v-if="answer.text" @mousedown.prevent="saveAnswer()" class="btn btn-block btn-primary">Submit</button>
                            <button v-else @click.prevent="showForm" class="btn btn-block btn-warning">Cancel</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

    </div>
</template>

<script>

    import answer from './Answer.vue';

    export default {

        data: function () {
            return {
                comment: {
                    text: ""
                },
                answer: {
                    text: ""
                },
                currentUser: this.$parent.currentUser,
                isAdmin: false,
                editing: false,
                editedComment: {
                    text: ""
                },
                answers: [],
                answering: false
            }
        },
        components: {
            answer: answer
        },
        props: ['comment_id'],
        mounted () {
            this.update();
            setTimeout(() => {
                this.isAdmin = this.$parent.isAdmin;
            }, 200);
        },
        methods: {
            update () {
                axios.get('/api/comments/' + this.comment_id).then((response) => {
                    this.comment = response.data['comment'];
                    this.comment.userName = response.data['user']['name'];
                    this.editedComment.text = this.comment.text;
                    this.answers = response.data['answers'];
                    if(response.data['answers'].length > 0) {
                        setTimeout(() => {
                            var arrow = $("<div/>").addClass('arrow');
                            $('#answer_' + this.comment_id + '_0').find('div').find('div').find('div')[0].prepend(arrow[0]);
                        }, 500);
                    }

                });
            },
            deleteComment(id) {
                axios.delete('/api/comments/' + id).then(() => {
                    this.$parent.updateComments();
                });
            },
            edit () {
                if(this.editing) {
                    this.editing = false;
                    this.editedComment.text = this.comment.text;
                } else {
                    this.editing = true;
                }
            },
            updateComment () {

                if(this.editedComment.text === "") {
                    alert("Your comment is empty!");
                    return false;
                }

                if(this.editedComment.text.length > 255) {
                    alert("Your comment is too long!");
                    return false;
                }

                axios.put('/api/comments/' + this.comment.id, {
                    'text': this.editedComment.text,
                }).then(() => {
                    this.update();
                    this.editing = false;
                });

            },
            showForm () {
                if (this.answering) {
                    this.answering = false;
                } else {

                    this.answering = true;
                    setTimeout(() => {
                        $('#text_' + this.comment_id).focus();
                    }, 200);

                }
            },
            saveAnswer () {

                if(this.answer.text === "") {
                    alert("Your comment is empty!");
                    return false;
                }

                if(this.answer.text.length > 255) {
                    alert("Your comment is too long!");
                    return false;
                }

                axios.post('/api/answers', {
                    'text': this.answer.text,
                    'comment_id': this.comment_id
                }).then(() => {
                    this.update();
                    this.answer.text = "";
                });

            },
            blur () {
                this.answer.text = "";
                setTimeout(() => {
                    this.answering = !this.answering;
                }, 100);
            }
        }

    }

</script>

<style scoped>

</style>
