<script>
import { defineComponent, ref } from 'vue'
import { Chart, Grid, Line } from 'vue3-charts'

export default {
    name: 'HourStreamChart',
    props:['streamcounts'],
    components: { Chart, Grid, Line },

    setup(props) {
        const data = ref(props.streamcounts)
        const direction = ref('horizontal')
        const margin = ref({
            left: 5,
            top: 5,
            right: 5,
            bottom: 5
        })

        return { data, direction, margin }
    },
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
    <Chart
        :size="{ width: 500, height: 400 }"
        :data="data"
        :margin="margin"
        :direction="direction">

        <template #layers>
            <Grid strokeDasharray="2,2" />
            <Line :dataKeys="['start_hour', 'stream_count']" />
        </template>
    </Chart>
</template>
