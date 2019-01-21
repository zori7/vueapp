<template>
    <div class="container py-4">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="name" id="name" v-model="user.name">
        </div>
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" name="email" id="email" v-model="user.email">
        </div>
        <div class="form-group">
            <label for="password">New password <span class="text-info">[optional]</span></label>
            <input type="password" class="form-control" name="password" id="password" placeholder="[old password]" v-model="user.password">
        </div>
        <button class="btn btn-success" @click="save()">Save</button>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                user: {}
            }
        },
        props: ['id'],
        mounted () {
            axios.get('/api/users/' + this.id + '/edit').then((response) => {
                this.user = response.data['user'];
            });
        },
        methods: {
            save () {

                if(this.user.name === "" || this.user.password === "") {
                    alert("Fill all inputs!");
                    return false;
                }

                axios.put('/api/users/' + this.id, {name: this.user.name, email: this.user.email, password: this.user.password}).then(() => {
                    this.$router.push('/user/' + this.user.id);
                });

            }
        }
    }
</script>
