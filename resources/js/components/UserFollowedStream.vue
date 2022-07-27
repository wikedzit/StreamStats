<script>
export default {
    props: ['followedstreams'],
    data() {
        return {
            streams: [],
        }
    },
    created() {
        this.streams = this.followedstreams;
    },
    methods: {
    }
}
</script>
<template>
    <table class="table table-striped table-hover table-sm" style="font-size: 12px">
        <thead>
        <tr>
            <th scope="col">Stream Title</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="stream in streams">
            <th scope="row">{{  stream.title }}</th>
        </tr>
        </tbody>
    </table>
</template>
