<script>
import DataTable from 'datatables.net-vue3';

export default {
    props: ['topstreams'],
    components: {
        DataTable
    },
    data() {
        return {
            streams: [],
            columns: [
                { data: 'title' },
                { data: 'game_name' },
                { data: 'viewer_count' }
            ]
        }
    },
    created() {
        this.streams = this.topstreams;
    },
    methods: {
    }
}
</script>
<style>
    table {
        font-size: 12px;
    }
    td {
        padding: 0 10px 0;
    }
</style>
<template>
    <DataTable
        :columns="columns"
        :data="streams"
        :search="false"
        class="table table-hover table-striped table-sm table-bordered"
        width="100%"
    >
        <thead>
            <tr>
                <th scope="col">Stream Title</th>
                <th scope="col">Game Name</th>
                <th scope="col"># Views</th>
            </tr>
        </thead>
    </DataTable>
</template>
