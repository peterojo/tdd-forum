<template>

</template>

<script>
    export default {
        props: [
            'attributes'
        ],
        methods: {
            update() {
                axios.patch('/replies/'+this.attributes.id, { body: this.body });

                this.editing = false;
                flash("Updated!");
            },
            destroy() {
                axios.delete('/replies/'+this.attributes.id);

                $(this.$el).fadeOut(300, () => {
                    flash('Your reply has been deleted!');
                });
            }
        },
        data() {
            return {
                editing: false,
                body: this.attributes.body
            }
        }
    }
</script>