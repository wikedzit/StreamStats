<script>
import ViewerMedian from "../components/ViewerMedian.vue";

export default {
    components: {
        ViewerMedian
    },
    data() {
        return {
        }
    },
    created() {
    },

    methods: {
    }
}
</script>
<template>
    <div class="container h-100 d-flex justify-content-center align-items-center">
        <div class="container w-70">
            <h3>DashBoard</h3>
            <div class="row mb-10">
                <div class="col-6">
                    <ViewerMedian :median="567"></ViewerMedian>
                </div>
            </div>
        </div>
    </div>
</template>
