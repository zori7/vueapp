<template>
    <div class="container">

        <div class="post row">

            <div v-if="post.img !== 'storage/no-image.png'" id="carouselExampleIndicators" class="carousel slide mx-auto my-2" data-ride="carousel">
                <ol class="carousel-indicators" ref="ind">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li v-for="(image, key) in images" v-if="key !== images.length - 1" data-target="#carouselExampleIndicators" :data-slide-to="key + 1"></li>
                </ol>

                <div class="carousel-inner">

                    <div class="carousel-item" v-for="image in images">
                        <img class="d-block w-100" :src="image.src" alt="">
                    </div>

                </div>

                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>

            <div class="col-md-12 text-center">
                <h1 class="text-primary">{{ post.name }} by <router-link :to="'/user/' + user.id" class="text-info">{{ user.name }}</router-link></h1>

                <p>
                    {{ post.text }}
                </p>
            </div>

        </div>

        <hr>

        <comment v-for="comment in comments" :comment_id="comment" :key="comment"></comment>

        <div class="container">
            <div class="row justify-content-center">
                <div class="my-3 col-md-6">

                    <form @submit.prevent="saveComment()" method="post">

                        <div class="form-group">
                            <input type="text" class="form-control" v-model="comment.text" placeholder="Your comment here">
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>

                    </form>

                </div>
            </div>
        </div>

    </div>
</template>

<script>

    import comment from "./Comment.vue";

    export default {
        data: function () {
            return {
                post: {
                    img: ""
                },
                user: {
                    name: "",
                    id: -1
                },
                images: [],
                comments: [],
                comment: {
                    text: ""
                }
            }
        },
        components: {
          comment: comment
        },
        props: ['id'],
        mounted() {
            this.update();
        },
        methods: {
            update () {
                axios.get('/api/posts/' + this.id).then((response) => {

                    this.post = response.data['post'];
                    this.comments = response.data['comments'];
                    this.user.name = response.data['username'];
                    this.user.id = response.data['userid'];
                    this.images = [];
                    this.images.push({src: this.post.img});
                    $.each(response.data['images'], (key, image) => {
                        this.images.push(image);
                    });

                    setTimeout(function () {
                        $('.carousel-item').first().addClass('active');
                    }, 500);

                });
            },
            saveComment () {

                axios.post('/api/comments', {
                    'text': this.comment.text,
                    'post_id': this.id
                }).then(() => {
                    this.update ();
                });

                this.comment.text = "";
            }
        }
    }
</script>

<style scoped>
    .carousel {
        min-width: 200px;
    }
</style>
