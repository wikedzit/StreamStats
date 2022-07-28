<script>
export default {
    props: ['gamestreams'],
    data() {
        return {
            games: [],
        }
    },
    created() {
        this.games = this.gamestreams;
    },
    methods: {
    }
}
</script>
<template>
    <table class="table table-striped table-hover table-sm" style="font-size: 12px">
        <thead>
        <tr>
            <th scope="col">Game</th>
            <th scope="col"># Streams</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="game in games">
            <th>{{  game.game_name }}</th>
            <td>{{  game.streams_count }}</td>
        </tr>
        </tbody>
    </table>
</template>
