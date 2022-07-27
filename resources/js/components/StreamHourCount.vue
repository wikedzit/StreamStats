<script>
export default {
    props:['streamcounts'],
    data() {
        return {
            stream_counts: [],
        }
    },
    created() {
        this.stream_counts = this.streamcounts;
    },
    methods: {
    }
}
</script>
<template>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Stream Count Per Start Hour</h5>
            <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
            <div class="card-text">
                <table class="table table-striped table-hover table-sm" style="font-size: 12px">
                    <thead>
                    <tr>
                        <th scope="col">Start Hour</th>
                        <th scope="col"># Streams</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="record in stream_counts">
                        <th scope="row">{{  record.start_hour }}</th>
                        <td>{{  record.stream_count }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
