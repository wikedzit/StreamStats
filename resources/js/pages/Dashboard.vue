<script>
import ViewerMedian from "../components/ViewerMedian.vue";
import TopGames from "../components/TopGames.vue";
import GameStreams from "../components/GameStreams.vue";
import StreamGapToTop from "../components/StreamGapToTop.vue";
import TopStreams from "../components/TopStreams.vue";
import UserFollowedStream from "../components/UserFollowedStream.vue";
import SharedTags from "../components/SharedTags.vue";
import StreamHourCount from "../components/StreamHourCount.vue";

export default {
    components: {
        StreamHourCount,
        SharedTags,
        UserFollowedStream,
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
                    <div class="h-100 p-3 pt-0 rounded-3">
                        <div class="card bg-info col-12">
                            <div class="card-body">
                                <h5 class="card-title">Stream Viewers</h5>
                                <h6 class="card-subtitle text-muted">Median</h6>
                                <ViewerMedian v-if="stats.median!=null" :median="stats.median"></ViewerMedian>
                            </div>
                        </div>
                        <div class="card bg-light col-12 mt-4">
                            <div class="card-body">
                                <StreamGapToTop v-if="stats.gaptotop!=null" :gapdata="stats.gaptotop"></StreamGapToTop>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 p-2">
                        <div class="card-body">
                            <h5 class="card-title">Followed Streams</h5>
                            <div class="card-text">
                                <UserFollowedStream v-if="stats.followedstreams!=null" :followedstreams="stats.followedstreams">
                                </UserFollowedStream>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row pt-3">
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"># Streams per Game</h5>
                            <div class="card-text">
                                <GameStreams v-if="stats.gamestreams!=null" :gamestreams="stats.gamestreams"></GameStreams>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Tops Games by Views</h5>
                            <div class="card-text">
                                <TopGames v-if="stats.topgames!=null" :topgames="stats.topgames"></TopGames>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row pt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Tops Streams by Views</h5>
                            <div class="card-text">
                                <TopStreams v-if="stats.topstreams!=null" :topstreams="stats.topstreams"></TopStreams>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row pt-3">
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Stream Count Per Start Hour</h5>
                            <div class="card-text">
                                <StreamHourCount v-if="stats.streamcounts!=null" :streamcounts="stats.streamcounts"></StreamHourCount>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Shared Tags</h5>
                            <div class="card-text">
                                <SharedTags v-if="stats.sharedtags!=null" :sharedtags="stats.sharedtags"></SharedTags>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
