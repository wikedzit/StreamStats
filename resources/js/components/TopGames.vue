<script>
export default {
    props: ['topgames'],
    data() {
        return {
            top_games: [],
        }
    },
    created() {
        this.top_games = this.topgames;
    },
    methods: {
    }
}
</script>
<template>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Tops Games by Views</h5>
            <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
            <div class="card-text">
                <table class="table table-striped table-hover table-sm" style="font-size: 12px">
                    <thead>
                    <tr>
                        <th scope="col">Game</th>
                        <th scope="col"># Views</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="game in top_games">
                        <th scope="row">{{  game.game_name }}</th>
                        <td>{{  game.view_count }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
