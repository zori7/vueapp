<template>
    <div class="container my-3">

        <div class="row justify-content-center my-2">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Date: {{ comment.createdAt }}
                    </div>
                    <div class="card-body">
                        <blockquote class="blockquote mb-0">
                            <p>{{ comment.text }}</p>
                            <footer class="blockquote-footer">{{ comment.userName }}</footer>
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
                comment: {
                    text: "",
                    userName: "",
                    createdAt: ""
                }
            }
        },
        props: ['comment_id'],
        mounted () {
            axios.get('/api/comments/' + this.comment_id).then((response) => {
                this.comment.text = response.data['comment']['text'];
                this.comment.userName = response.data['username'];
                this.comment.createdAt = response.data['comment']['created_at'];
            });
        }

    }

</script>

<style scoped>

</style>
