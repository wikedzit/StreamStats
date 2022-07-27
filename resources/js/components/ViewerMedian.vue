<script>
export default {
    props: ['median'],
    data() {
        return {
            viewer_median: null,
        }
    },
    created() {
        this.viewer_median = this.median
    },
    methods: {
    }
}
</script>
<template>
    <div class="h-100 p-3 text-bg-info rounded-3">
        <h5 class="card-title">Stream Viewers</h5>
        <h6 class="card-subtitle mb-2 text-muted">Median</h6>
        <div class="card-text">
            <h1>{{  viewer_median  }}</h1>
        </div>
    </div>
</template>
