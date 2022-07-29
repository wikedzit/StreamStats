<template>
    <div class="text-center">
        <main>
            <div class="container py-4">
                <header class="pb-3 mb-4 border-bottom">
                    <a href="/" class="d-flex align-items-center text-dark text-decoration-none">
                        <img :src="'/images/streamstats.png'" style="height: 40px">
                    </a>
                </header>

                <div class="p-5 mb-4 bg-light rounded-3">
                    <div class="container-fluid py-5">
                        <h1 class="display-5 fw-bold">Get Access to your stats</h1>
                        <p class="col-md-12 fs-4 text-align-center">
                            Have a quick look at how the channels you watch compare to the top 1000 Twitch live streams.
                        </p>
                        <a v-if="!loading" :href="getRedirectUrl()" class="btn btn-lg btn-primary fs-4" type="button"
                           style="background-color: #d162ff">
                            Sign in with Twitch
                        </a>
                        <div  v-if="loading" class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
export default {
    data() {
        return {
            error: null,
            loading: false,
            twitch_auth_creds: {
                response_type: 'code',
                client_id: import.meta.env.VITE_TWITCH_CLIENT_ID,
                redirect_uri: import.meta.env.VITE_TWITCH_REDIRECT_URI,
                scope: import.meta.env.VITE_TWITCH_SCOPE
            }
        }
    },

    beforeCreate() {
        if(localStorage.getItem('usertoken')) {
            router.push({name: 'dashboard'})
        }
    },

    created() {
        const isLoggedIn = localStorage.usertoken !== "";
        if(isLoggedIn) {
            //router.push({name: 'dashboard'})
        }
    },

    mounted() {
        const access_details = document.location.search;
        if (access_details) {
            const base_url = import.meta.env.VITE_BASE_URL
            const callback = base_url+"/api/auth/callback"+access_details;
            this.loading = true;
            axios.get(callback)
                .then(response => {
                    this.loading = false;
                    this.processLogin(response.data);
                    router.push({name: 'dashboard'})
                })
                .catch(error => console.log(error))
        }
    },

    methods: {
        getRedirectUrl() {
            const url = import.meta.env.VITE_TWITCH_AUTH_URL + "/authorize";
            const params = new URLSearchParams(this.twitch_auth_creds).toString();
            return url+"?"+ params;
        },

        processLogin(data) {
            localStorage.setItem('usertoken', data.access_token)
            localStorage.setItem('useravatar', data.avatar);
            localStorage.setItem('username', data.username);
            router.push({name: 'dashboard'})
        }
    }
}
</script>
