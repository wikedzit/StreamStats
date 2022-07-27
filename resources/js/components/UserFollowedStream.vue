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
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Followed Streams</h5>
            <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
            <div class="card-text">
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
            </div>
        </div>
    </div>
</template>
