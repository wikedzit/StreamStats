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
    <div class="card-text">
        <h1 style="color: white">{{  viewer_median  }}</h1>
    </div>
</template>
