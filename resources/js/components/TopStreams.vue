<script>
export default {
    props: ['topstreams'],
    data() {
        return {
            streams: [],
        }
    },
    created() {
        this.streams = this.topstreams;
    },
    methods: {
    }
}
</script>
<template>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Tops Streams by Views</h5>
            <div class="card-text">
                <table class="table table-striped table-hover table-sm" style="font-size: 12px">
                    <thead>
                    <tr>
                        <th scope="col">Stream Title</th>
                        <th scope="col">Game Name</th>
                        <th scope="col"># Views</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="stream in streams">
                        <th scope="row">{{  stream.title }}</th>
                        <td>{{  stream.game_name }}</td>
                        <td>{{  stream.viewer_count }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
