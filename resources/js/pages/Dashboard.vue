<script>
import ViewerMedian from "../components/ViewerMedian.vue";
import TopGames from "../components/TopGames.vue";
import GameStreams from "../components/GameStreams.vue";
import StreamGapToTop from "../components/StreamGapToTop.vue";
import TopStreams from "../components/TopStreams.vue";
import UserFollowedStream from "../components/UserFollowedStream.vue";
import SharedTags from "../components/SharedTags.vue";
import StreamHourCount from "../components/StreamHourCount.vue";
import {ref} from "vue";

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

    setup () {
        const avatar = ref(localStorage.useravatar);
        return {avatar}
    },

    data() {
        return {
            received_data: null,
            stats:{},
            interval: null,
        }
    },
    created() {
        clearInterval(this.interval);
        const isLoggedIn = localStorage.usertoken !== "";
        if(!isLoggedIn) {
            router.push({name: 'home'})
        }
       this.loadStats();
       // this.interval = setInterval(() => {
       //     this.loadStats();
       // }, 1000);
    },
    deactivated() {
        clearInterval(this.interval);
    },
    methods: {
       loadStats() {
            const base_url = import.meta.env.VITE_BASE_URL
            const url = base_url+"/api/stats";
            this.received_data = null;
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
            this.stats = stats
        },

        logout() {
            const base_url = import.meta.env.VITE_BASE_URL
            const url = base_url+"/api/auth/logout";
            console.log(url);
            axios.post(url,{
                headers:{
                    Authorization: `Bearer ${localStorage.usertoken}`,
                    'Content-Type': 'application/json'
                }
            })
                .then(response => {
                    this.processLogout();
                    window.location.href = 'home';
                    router.push({name: 'home'});
                }).catch(error => {
                this.processLogout();
                router.push({name: 'home'});
            });
        },

        processLogout() {
            localStorage.setItem('usertoken', "");
            localStorage.setItem('useravatar', "");
            console.log("====");
        }
    }
}
</script>
<template>
    <header class="p-3 mb-2 text-bg-dark">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <span class="navbar-brand">
                    <img :src="'/images/streamstats.png'" style="height: 40px">
                </span>

                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="#" class="nav-link px-2 text-white">StreamLabs</a></li>
                    <li><a href="#" class="nav-link px-2 text-white">Twitch</a></li>
                </ul>

                <div class="text-end">
                    <button type="button" class="btn btn-warning">Logout</button>
                </div>
            </div>
        </div>
    </header>
    <div class="container h-100 d-flex justify-content-center align-items-center">
        <div class="container w-70 pt-3">
            <img :src="avatar" style="width: 100px" class="mb-3">
            <div class="row align-items-md-stretch">
                <div class="col-md-6">
                    <div class="h-100 rounded-3">
                        <div class="card bg-info col-12">
                            <div class="card-body">
                                <h5 class="card-title">Stream Viewers</h5>
                                <h6 class="card-subtitle text-muted">Median</h6>
                                <ViewerMedian v-if="stats.median!=null" :median="stats.median"></ViewerMedian>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="h-100 rounded-3">
                        <div class="card bg-light col-12">
                            <div class="card-body">
                                <StreamGapToTop v-if="stats.gaptotop!=null" :gapdata="stats.gaptotop"></StreamGapToTop>
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
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Matching tags in top 1000 Streams</h5>
                            <div class="card-text">
                                <SharedTags v-if="stats.sharedtags!=null" :sharedtags="stats.sharedtags"></SharedTags>
                            </div>
                        </div>
                    </div>
                    <div class="card">
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
        </div>
    </div>
</template>
