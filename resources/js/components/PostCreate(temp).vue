<template>
    <div class="container">
        <form @submit="save" id="create" enctype="multipart/form-data">

            <input type="hidden" name="_token" :value="csrf">

            <div class="form-group">
                <input type="text" name="name" placeholder="Name" v-model="post.name" class="form-control">
            </div>

            <div class="form-group">
                <textarea name="text" rows="10" placeholder="Text" v-model="post.text" class="form-control"></textarea>
            </div>

            <div class="row">

                <div class="col-md-3 my-3" v-for="image in images">
                    <div class="preview">
                        <img :src="image" alt="" class="img-fluid img-thumbnail mx-auto d-block">
                        <div class="text-center" @click="deleteImages()">X</div>
                    </div>
                </div>

            </div>

            <div class="form-group">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="customFile" ref="files" name="img[]" @change="preview()" multiple>
                    <label class="custom-file-label" for="customFile">Choose photo(s)</label>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Save</button>

        </form>

    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                post: {
                    name: "",
                    text: ""
                },
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                images: [],
                main: 0
            }
        },
        methods: {
            save (e) {
                e.preventDefault();

                if(this.post.name === "" || this.post.text === "") {
                    alert("Fill all inputs!");
                    return false;
                }

                var form = document.getElementById('create');

                var formData = new FormData(form);

                axios.post('/create/post', formData).then(() => {
                    this.$router.push('/posts');
                });

            },
            preview () {
                var input = $('#customFile')[0];

                if(input.files && input.files[0]) {

                    $.each(input.files, (i, file) => {

                        var reader = new FileReader();

                        reader.onloadend = () => {
                            this.images.push(reader.result)
                        };
                        reader.readAsDataURL(file);
                    });

                    /*var reader = new FileReader();

                    for(var file of input.files) {

                        reader.readAsDataURL(file);

                        reader.onload = (e) => {

                            this.images.push(e.target.result);

                        }
                    }*/



                }
            },
            deleteImages () {

                var input = $('#customFile')[0];

                this.images = [];
                input.files = [];
                console.log(input.files);

                //this.preview();

            }
        }
    }
</script>

<style scoped>

    .preview {
        position: relative;
    }

    .preview div {
        cursor: pointer;
        line-height: 25px;
        width: 25px;
        height: 25px;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.82);
        color: #b91d19;
        font-size: 1.2em;
        position: absolute;
        top: 0;
        right: 0;
    }
</style>
