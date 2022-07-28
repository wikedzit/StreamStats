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
    <div class="card-text hstack gap-1 p-0">
        <div> <img :src="gap_to_top.thumbnail"></div>
        <div class="">
            <p class="lead fs-6">{{  gap_to_top.title }}</p>
            <p v-if="gap_to_top.gap===0" class="btn btn-sm btn-success">Is in Top 1000 list</p>
            <p v-else class="btn btn-sm btn-info">Needs {{  gap_to_top.gap }} to be in top 1000</p>
        </div>
    </div>
</template>
