<template>
    <div class="container">
        <div class="table-responsive">
            <table class="table table-condensed table-striped table-hover table-bordered">
                <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">Name</th>
                    <th scope="col">email</th>
                    <th scope="col">Posts</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="user in collection">
                    <th scope="row">{{ user.id }}
                        <div class="btn-group float-right">
                            <button class="btn btn-danger btn-xs" v-if="isAdmin" @click.prevent="deleteUser(user)">Delete</button>
                            <router-link v-bind:to="'/edit/user/' + user.id" class="btn btn-info btn-xs" v-if="isAdmin">Edit</router-link>
                        </div>
                    </th>
                    <td><router-link v-bind:to="'/user/' + user.id">{{ user.name }}</router-link></td>
                    <td>{{ user.email }}</td>
                    <td>{{ posts[user.id] }}</td>
                </tr>
                <tr v-if="isAdmin">
                    <th scope="row">
                        <button class="btn btn-primary btn-xs float-right" @click="createUser()" v-if="filled">Create new user</button>
                        <span v-else>New user:</span>
                    </th>
                    <td><input type="text" class="form-control" v-model="user.name" placeholder="Name"></td>
                    <td><input type="text" class="form-control" v-model="user.email" placeholder="email"></td>
                    <td><input type="password" class="form-control" v-model="user.password" placeholder="password"></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-primary" v-for="p in pagination.pages" @click.prevent="setPage(p)">{{ p }}</button>
        </div>
        <p>Displaying users from {{ startIndexV }} to {{ endIndexV }}</p>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                users: {},
                posts: {},
                perPage: 10,
                pagination: {},
                refreshing: false,
                user: {
                    name: "",
                    email: "",
                    password: ""
                },
                isAdmin: false
            }
        },
        mounted () {

            axios.get('/api/users').then((response) => {
                this.users = response.data[0];
                this.posts = response.data[1];
                this.setPage(1);
            });

            axios.get('/api/isadmin').then((response) => {
                this.isAdmin = response.data['isAdmin'];
            });

        },
        computed: {
            collection () {
                return this.paginate(this.users);
            },
            startIndexV () {
                return this.pagination.startIndex + 1;
            },
            endIndexV () {
                return this.pagination.endIndex + 1;
            },
            filled () {
                if(this.user.name != "" && this.user.email != "" && this.user.password != "") return true;
                else return false;
            }
        },
        methods: {
            setPage(p) {
                this.pagination = this.paginator(this.users.length, p);
            },

            paginate(data) {
                return _.slice(data, this.pagination.startIndex, this.pagination.endIndex + 1);
            },

            paginator(totalItems, currentPage) {
                let startIndex = (currentPage - 1) * this.perPage;
                let endIndex = Math.min(startIndex + this.perPage - 1, totalItems - 1);

                return {
                    currentPage: currentPage,
                    startIndex: startIndex,
                    endIndex: endIndex,
                    pages: _.range(1, Math.ceil( totalItems / this.perPage ) + 1)
                };
            },
            update() {
                axios.get('/api/users').then((response) => {
                    this.users = response.data[0];
                    this.posts = response.data[1];
                    this.setPage(1);
                });
            },
            deleteUser(user) {
                axios.delete('/api/users/' + user.id).then((response) => {
                    this.update();
                });

            },
            createUser() {
                let name = this.user.name;
                let email = this.user.email;
                let password = this.user.password;

                axios.post('/api/users', {

                    name: name, email: email, password: password

                }).then((response) => {
                    this.update();
                    this.user.name = "";
                    this.user.email = "";
                    this.user.password = "";
                });
            }
        }
    }
</script>

<style scoped>

</style>
