<template>
    <div class="container">

        <div class="post row">

            <div v-if="post.img != 'storage/no-image.png'" class="col-md-9 mx-auto my-2 col-sm-12">
                <img :src="'/' + post.img" class="img-fluid mx-auto" alt="">
            </div>


            <div class="col-md-12 text-center">
                <h1 class="text-primary">{{ post.name }} by {{ username }}</h1>

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
                username: ""
            }
        },
        props: ['id'],
        mounted() {
            axios.get('/post/' + this.id).then((response) => {
                this.post = response.data['post'];
                this.username = response.data['username'];
            });
        }
    }
</script>