<template>
    <div class="container">
        <form @submit="save" id="editpost" enctype="multipart/form-data">

            <input type="hidden" name="_token" :value="csrf">

            <div class="form-group">
                <input type="text" name="name" placeholder="Name" v-model="post.name" class="form-control">
            </div>

            <div class="form-group">
                <textarea name="text" rows="10" placeholder="Text" class="form-control" v-model="post.text"></textarea>
            </div>

            <div class="form-group">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="customFile" name="img">
                    <label class="custom-file-label" for="customFile">Choose photo</label>
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
                post: {},
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        },
        props: ['id'],
        mounted () {
            axios.get('/edit/post/' + this.id).then((response) => {
                this.post = response.data;
            });
        },
        methods: {
            save (e) {
                e.preventDefault();

                if(this.post.name === "" || this.post.text === "") {
                    alert("Fill all inputs!");
                    return false;
                }

                var form = document.getElementById('editpost');

                var formData = new FormData(form);

                axios.post('/edit/post/' + this.id, formData).then(() => {
                    this.$router.push('/post/' + this.post.id);
                });

            }/*,
            changeFile(e) {
                var files = e.target.files || e.dataTransfer.files;

                this.post.img = files[0];
            }*/
        }
    }
</script>