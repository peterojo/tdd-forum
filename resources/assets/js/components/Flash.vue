<template>
    <div class="alert alert-success alert-flash" role="alert" v-show="show">
        <strong>Success!</strong> {{ body }}
    </div>
</template>

<script>
    export default {
        props: [
            'message'
        ],
        data() {
            return {
                body: null,
                show: false
            }
        },
        methods: {
            flash(message) {
                this.body = message;
                this.show = true;
                this.hide();
            },
            hide() {
                setTimeout(() => {
                    this.show = false;
                }, 3000);
            }
        },
        mounted() {
            if (this.message) {
                this.flash(this.message);
            }

            window.events.$on('flash', (message) => {
                this.flash(message);
            });
        }
    }
</script>

<style>
    .alert-flash {
        position: fixed;
        right: 25px;
        bottom: 25px;
    }
</style>
