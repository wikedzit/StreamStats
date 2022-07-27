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
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Shared Tags</h5>
            <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
            <div class="card-text">
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
            </div>
        </div>
    </div>
</template>
