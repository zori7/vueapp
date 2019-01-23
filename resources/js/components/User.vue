<template>
    <div class="container">

        <div class="user row">

            <div class="col-md-12 text-center">
                <h1 class="text-primary">Name: {{ user.name }}</h1>

                Email: {{ user.email }}<br>
                Joined: {{ user.created_at }}<br>
                Admin: {{ isAdmin }}

                <button class="btn btn-primary" v-if="authAdmin && isAdmin === 'No'" @click="makeAdmin(user.id)">Make admin</button>

                <button class="btn btn-danger" v-if="authAdmin && isAdmin === 'Yes'" @click="deleteAdmin(user.id)">Delete admin</button>

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
                authAdmin: false
            }
        },
        props: ['id'],
        mounted () {

            this.update();

            axios.get('/api/isadmin').then((response) => {
                this.authAdmin = response.data['isAdmin'];
            });
        },
        methods: {
            update() {

                axios.get('/api/users/' + this.id).then((response) => {
                    this.user = response.data['user'];
                    this.isAdmin = response.data['isAdmin'];
                });

            },
            makeAdmin(id) {

                axios.post('api/admin/make/' + id).then(() => {
                    this.update();
                });

            },
            deleteAdmin(id) {

                axios.post('api/admin/delete/' + id).then(() => {
                    this.update();
                });

            }
        }
    }

</script>
