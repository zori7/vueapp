<template>
    <div class="container">

        <form @submit="save" id="create" enctype="multipart/form-data">

            <input type="hidden" name="_token" :value="csrf">

            <div class="form-group">
                <input type="text" placeholder="Name" v-model="post.name" class="form-control">
            </div>

            <div class="form-group">
                <textarea rows="10" placeholder="Text" v-model="post.text" class="form-control"></textarea>
            </div>

            <div class="mx-auto text-center" v-if="files.length > 1">
                <h2 class="text-primary">Choose main photo:</h2>
            </div>

            <div class="row">

                <div class="col-md-3 my-3" v-for="(file, key) in files">
                    <div class="preview">
                        <img :src="file.src" alt="" class="img-fluid img-thumbnail mx-auto d-block">
                        <div class="form-check text-center" v-if="files.length > 1">
                            <input class="form-check-input position-static" type="radio" v-model="post.main" name="main" :value="key">
                        </div>
                        <div class="text-center del" @click="removeFile(key)">X</div>
                    </div>
                </div>

            </div>

            <div class="form-group">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="customFile" ref="files" @change="handleFilesUpload()" multiple>
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
                    text: "",
                    main: 0
                },
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                files: []
            }
        },
        methods: {
            save (e) {
                e.preventDefault();

                if(this.post.name === "" || this.post.text === "") {
                    alert("Fill all inputs!");
                    return false;
                }

                let formData = new FormData();

                formData.append('name', this.post.name);

                formData.append('text', this.post.text);

                formData.append('main', this.post.main);

                for( var i = 0; i < this.files.length; i++ ){
                    let file = this.files[i];

                    formData.append('images[' + i + ']', file);
                }

                axios.post('/api/posts', formData).then(() => {
                    this.$router.push('/posts');
                });

            },
            handleFilesUpload () {

                let uploadedFiles = this.$refs.files.files;

                for( var i = 0; i < uploadedFiles.length; i++ ){
                    uploadedFiles[i].src = URL.createObjectURL(uploadedFiles[i]);
                    this.files.push( uploadedFiles[i] );
                }

                setTimeout(() => {
                    if (this.files.length + uploadedFiles.length > 1) {
                        $('input[type=radio]').first().attr('checked', true);
                    }
                }, 500);
            },
            removeFile (key) {
                this.files.splice( key, 1 );
            }
        }
    }
</script>

<style scoped>

    .preview .del {
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
