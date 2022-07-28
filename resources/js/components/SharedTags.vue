<script>
export default {
    props:['sharedtags'],
    data() {
        return {
            tags: [],
        }
    },
    created() {
        this.tags = this.sharedtags;
    },
    methods: {
    }
}
</script>
<template>
    <span class="badge text-bg-warning m-1" v-for="tag in tags">{{ tag }}</span>
</template>
