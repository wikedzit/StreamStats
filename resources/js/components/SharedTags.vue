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
    <table class="table table-striped table-hover table-sm" style="font-size: 12px">
        <thead>
        <tr>
            <th scope="col">Tag</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="tag in tags">
            <th scope="row">{{  tag }}</th>
        </tr>
        </tbody>
    </table>
</template>
