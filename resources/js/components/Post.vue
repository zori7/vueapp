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
                <h1 class="text-primary">{{ post.name }} <i class="text-info">by {{ username }}</i></h1>

                <p>
                    {{ post.text }}
                </p>
            </div>

        </div>

    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                post: {
                    img: ""
                },
                username: "",
                images: []
            }
        },
        props: ['id'],
        mounted() {
            axios.get('/api/posts/' + this.id).then((response) => {

                /*for(var i = 1; i <= response.data['images'].length; i++){
                    let str = '<li data-target="#carouselExampleIndicators" data-slide-to="' + i + '" class="active"></li>';
                    this.$refs.ind.append(str);
                }*/

                this.post = response.data['post'];
                this.username = response.data['username'];
                this.images.push({src: this.post.img});
                $.each(response.data['images'], (key, image) => {
                    this.images.push(image);
                });

                setTimeout(function () {
                    $('.carousel-item').first().addClass('active');
                }, 500);

            });
        }
    }
</script>

<style scoped>
    .carousel {
        min-width: 200px;
    }
</style>
