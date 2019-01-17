<template>
    <div class="container">

        <div class="user row">

            <div class="col-md-12 text-center">
                <h1 class="text-primary">Name: {{ user.name }}</h1>

                Email: {{ user.email }}<br>
                Joined: {{ user.created_at }}<br>
                Admin: {{ isAdmin }}

            </div>

        </div>

    </div>
</template>

<script>

    export default {
        data: function () {
            return {
                user: {},
                isAdmin: false
            }
        },
        props: ['id'],
        mounted () {
            axios.get('/api/users/' + this.id).then((response) => {
                this.user = response.data['user'];
                this.isAdmin = response.data['isAdmin'];
            });
        }
    }

</script>
