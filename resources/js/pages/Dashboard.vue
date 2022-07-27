<script>
import ViewerMedian from "../components/ViewerMedian.vue";
import TopGames from "../components/TopGames.vue";
import GameStreams from "../components/GameStreams.vue";

export default {
    components: {
        GameStreams,
        TopGames,
        ViewerMedian,
    },
    data() {
        return {
            stats:{}
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
            <div class="row">
                <div class="col-6">
                    <ViewerMedian v-if="stats.median!=null" :median="stats.median"></ViewerMedian>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <GameStreams v-if="stats.gamestreams!=null" :gamestreams="stats.gamestreams"></GameStreams>
                </div>

                <div class="col-4">
                    <TopGames v-if="stats.topgames!=null" :topgames="stats.topgames"></TopGames>
                </div>
            </div>
        </div>
    </div>
</template>
