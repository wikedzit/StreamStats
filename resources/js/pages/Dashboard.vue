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

    beforeCreate() {
        if(!localStorage.getItem('usertoken')) {
            window.location.href = "/";
        }
    },

    setup () {
        const avatar = ref(localStorage.getItem('useravatar'));
        const username = ref(localStorage.getItem('username'));
        return {avatar, username}
    },

    data() {
        return {
            stats:{},
            interval: null,
            loading: false
        }
    },

    created() {
        this.loadStats();
    },
    deactivated() {
        clearInterval(this.interval);
    },

    methods: {
       loadStats() {
           this.loading =  true;
            axios
                .get("/api/stats",{
                    headers:{
                        Authorization: `Bearer ${localStorage.usertoken}`,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (response.data.message === "loading") {
                        const stats = JSON.parse(localStorage.getItem('stats'));
                        console.log(stats);
                        this.stats = stats
                    }else {
                        this.setStats(response.data);
                    }
                    this.loading = false;
                })
                .catch(error=>{
                    if (error.response.status === 401) {
                        this.processLogout();
                    }
                })
        },

        setStats(stats) {
            this.stats = stats
            localStorage.setItem('stats', JSON.stringify(stats));
        },

        logout() {
            axios
                .post("/api/auth/logout",{
                headers:{
                    Authorization: `Bearer ${localStorage.usertoken}`,
                    'Content-Type': 'application/json'
                }
                })
                .then(response => {
                    this.processLogout();
                }).catch(error => {
                this.processLogout();
            });
        },

        processLogout() {
            localStorage.removeItem('usertoken');
            localStorage.removeItem('useravatar');
            localStorage.removeItem('username');
            localStorage.removeItem('stats');
            window.location.href = '/';
        }
    }
}
</script>
<style>
@import 'datatables.net-bs5';
</style>
<template>
    <header>
        <div class="px-3 py-2 text-bg-dark">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                    <span class="d-flex align-items-center my-2 my-lg-0 me-lg-auto">
                        <img :src="'/images/streamstats.png'" style="height: 40px">
                    </span>

                    <ul class="nav col-12 col-lg-auto my-2 justify-content-center my-md-0 text-small">
                        <li>
                            <a href="#" class="nav-link text-white">
                                <img :src="avatar" class="bi d-block mx-auto mb-1"  width="30" height="30">
                                {{ username }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="px-3 py-2 border-bottom mb-3">
            <div class="container d-flex flex-wrap justify-content-center">
                <p class="col-12 col-lg-auto mb-2 mb-lg-0 me-lg-auto lead"  style="color: #d162ff">
                    Your Twitch in Figures
                </p>

                <div class="text-end">
                    <button v-if="!loading" @click="loadStats" type="button" class="btn btn-info m-2">Fetch Latest</button>
                    <button v-if="loading" class="spinner-grow m-2 ml-4 text-dark border-0" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </button>
                    <button @click="logout" type="button" class="btn btn-warning">Logout</button>
                </div>
            </div>
        </div>
    </header>
    <div class="container h-100 d-flex justify-content-center align-items-center">
        <div class="container w-70 pt-3">
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
                <div class="col-md-6 ">
                    <div class="h-100 rounded-3">
                        <div class="card bg-light col-12">
                            <div class="card-body">

                                <h5 class="card-title text-primary"> Race to Top 1000 </h5>
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
                    <div class="card mb-3 bg-dark">
                        <div class="card-body">
                            <h5 class="card-title text-white">Matching tags in top 1000 Streams</h5>
                            <div class="card-text">
                                <SharedTags v-if="stats.sharedtags!=null" :sharedtags="stats.sharedtags"></SharedTags>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Followed Streams listed in Top 1000</h5>
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
                    <div class="card bg-warning">
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
                    <div class="card bg-light">
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
