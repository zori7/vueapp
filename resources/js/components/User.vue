<template>
    <div class="container">

        <div class="user row">

            <div class="col-md-12 text-center">
                <h1 class="text-primary">Name: {{ user.name }}</h1>

                <div class="avatar" @click.prevent="own ? uploading = true : 0" v-if="!uploading">
                    <img :src="avatar" @mouseenter="active = true" @mouseleave="active = false" id="avatar" alt="user avatar" :class="own ? 'img-fluid avatar' : 'img-fluid'"><br>
                    <div v-if="active && own" class="btn btn-dark" @mouseenter="active = true" @mouseleave="active = false">Update image</div>
                </div>

                <div v-else>
                    <form action="" @submit.prevent="updateImage" enctype="multipart/form-data">
                        <div class="form-group row">
                            <div class="col-md-3"></div>
                            <div class="custom-file col">
                                <input type="file" class="custom-file-input" id="customFile" ref="file" @change="handleFileUpload()">
                                <label class="custom-file-label" for="customFile">Choose photo</label>
                            </div>
                            <div class="col-md-3"></div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">Upload</button>
                        </div>
                    </form>
                    <img :src="file.src" alt="" class="img-fluid my-3"><br>
                    <button v-if="avatar !== 'storage/no-user-image.png'" class="btn btn-danger mx-auto" @click.prevent="deleteImage">Delete my photo</button>
                    <button class="btn btn-warning" @click="uploading = false; file = 0">Cancel</button>
                </div>
                <br>

                <router-link :to="'/pm/' + user.id" v-if="user.id !== authId">Message</router-link>
                <br>

                Email: {{ user.email }}<br>
                Joined: {{ user.created_at }}<br>
                Admin: {{ isAdmin }}
                <br>

                <button class="btn btn-primary btn-sm" v-if="authAdmin && isAdmin === 'No'" @click="makeAdmin(user.id)">Make admin</button>

                <button class="btn btn-danger btn-sm" v-if="authAdmin && isAdmin === 'Yes'" @click="deleteAdmin(user.id)">Delete admin</button>

            </div>

        </div>

    </div>
</template>

<script>

    export default {
        data: function () {
            return {
                user: {},
                isAdmin: false,
                authAdmin: false,
                authId: -1,
                avatar: "storage/no-user-image.png",
                active: false,
                uploading: false,
                own: false,
                file: 0
            }
        },
        props: ['id'],
        mounted () {

            this.update();

            axios.get('/api/isadmin').then((response) => {
                this.authAdmin = response.data['isAdmin'];
                this.authId = response.data['user'].id;
                setTimeout(() => {
                    if(this.user.id === this.authId) {
                        this.own = true;
                    }
                }, 500);
            });
        },
        methods: {
            update() {

                axios.get('/api/users/' + this.id).then((response) => {
                    this.user = response.data['user'];
                    this.isAdmin = response.data['isAdmin'];
                    this.avatar = response.data['avatar'];
                });

            },
            makeAdmin(id) {

                axios.post('/api/admin/make/' + id).then(() => {
                    this.update();
                });

            },
            deleteAdmin(id) {

                axios.post('/api/admin/delete/' + id).then(() => {
                    this.update();
                });

            },
            handleFileUpload () {
                let image = this.$refs.file.files[0];
                image.src = URL.createObjectURL(image);
                this.file = image;
            },
            updateImage () {
                let formData = new FormData();

                formData.append('name', this.user.name);
                formData.append('email', this.user.email);
                formData.append('file', this.file);
                formData.append('_method', 'put');

                axios.post('/api/users/' + this.user.id, formData).then(() => {
                    this.uploading = false;
                    this.update();
                });
            },
            deleteImage () {
                axios.post('/api/deleteavatar/' + this.user.id).then(() => {
                    this.uploading = false;
                    this.update();
                });
            }
        }
    }

</script>

<style lang="scss" scoped>

    a {
        font-size: 2em;
    }

    img {
        min-width: 100px;
        max-width: 20%;
        &.avatar {
            transition: all .3s ease-in-out;
            &:hover {
                transition: all .2s ease-in-out;
                filter: blur(5px);
                cursor: pointer;
            }
        }
    }

</style>
