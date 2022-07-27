<script>
export default {
    props: ['gapdata'],
    data() {
        return {
            gap_to_top : {}
        }
    },
    created() {
        this.gap_to_top = this.gapdata
    },
    methods: {

    }
}
</script>
<template>
    <div class="h-100 p-2 bg-light border rounded-3">
        <h5 class="card-title">{{  gap_to_top.title }}</h5>
        <h6 class="card-subtitle mb-2 text-muted">To top 1000 need</h6>
        <div class="card-text">
            <h1>{{  gap_to_top.gap }}</h1> more viewers
        </div>
    </div>
</template>
