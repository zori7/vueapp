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
                post: {
                    name: "",
                    text: ""
                },
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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

            }
        }
    }
</script>