<script>
import ViewerMedian from "../components/ViewerMedian.vue";
import TopGames from "../components/TopGames.vue";
import GameStreams from "../components/GameStreams.vue";
import StreamGapToTop from "../components/StreamGapToTop.vue";
import TopStreams from "../components/TopStreams.vue";

export default {
    components: {
        TopStreams,
        StreamGapToTop,
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
            <div class="row align-items-md-stretch">
                <div class="col-md-6">
                    <ViewerMedian v-if="stats.median!=null" :median="stats.median"></ViewerMedian>
                </div>
                <div class="col-md-6">
                    <StreamGapToTop v-if="stats.gaptotop!=null" :gapdata="stats.gaptotop"></StreamGapToTop>
                </div>
            </div>
            <div class="row pt-3">
                <div class="col-6">
                    <GameStreams v-if="stats.gamestreams!=null" :gamestreams="stats.gamestreams"></GameStreams>
                </div>

                <div class="col-6">
                    <TopGames v-if="stats.topgames!=null" :topgames="stats.topgames"></TopGames>
                </div>
            </div>
            <div class="row pt-3">
                <div class="col-12">
                    <TopStreams v-if="stats.topstreams!=null" :topstreams="stats.topstreams"></TopStreams>
                </div>
            </div>
        </div>
    </div>
</template>
