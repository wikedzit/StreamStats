<script>
import ViewerMedian from "../components/ViewerMedian.vue";

export default {
    components: {
        ViewerMedian
    },
    data() {
        return {
            stats: null
        }
    },
    created() {
        this.loadStats();
    },

    methods: {
       loadStats() {
            const base_url = import.meta.env.VITE_BASE_URL
            const url = base_url+"/api/stats";
            axios.get(url,{
                headers:{
                    Authorization: `Bearer ${localStorage.usertoken}`,
                    'Content-Type': 'application/json'
                }
            })
                .then(response => {
                    console.log(response.data);
                    this.setStats(response.data);
                });
        },
        setStats(stats) {
            this.stats = stats;
        }
    }
}
</script>
<template>
    <div class="container h-100 d-flex justify-content-center align-items-center">
        <div class="container w-70">
            <h3>DashBoard</h3>
            <div class="row mb-10">
                <div class="col-6">
                    <ViewerMedian :median="stats.viewers_median"></ViewerMedian>
                </div>
            </div>
        </div>
    </div>
</template>
