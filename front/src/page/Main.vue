<template>
    <div class="companyList">
        <v-data-table
            :headers="headers"
            :items="items"
            :mobile-breakpoint="100"
            hide-default-footer
            :loading="listing.loading"
            :items-per-page="10000"
            :search="search"
        >
            <template v-slot:top>
                <nodes-table-toolbar @search="find" @added="loadItems"/>
            </template>
            <template slot="item" slot-scope="props">
                <node-row :item="props.item"/>
            </template>
        </v-data-table>
    </div>
</template>

<script>
import NodesTableToolbar from "../components/NodesTableToolbar";
import NodeRow from "../components/NodeRow";

export default {
    components: {NodesTableToolbar, NodeRow},
    data() {
        return {
            config: global.config,
            search: '',
            items: [],
            listing: null,
            headers: [
                {sortable: false,},
                {text: 'Address', value: 'address', align: 'start', sortable: false,},
                {text: 'Unbonded Value', value: 'unbondedValue', align: 'start', sortable: false,},
            ],
        }
    },
    created() {
        this.listing = this.$ewll.initListingForm(this, {
            url: '/crud/node',
            sort: {id: 'desc'},
            success: function (response) {
                this.items = response.body.items;
                this.$store.commit('nodesCounter/set', this.items.length);
            }.bind(this),
        });
    },
    mounted() {
        this.init()
    },
    methods: {
        init() {
            this.loadItems();
        },
        loadItems() {
            this.listing.submit();
        },
        find(query) {
            this.search = query;
        }
    }
}
</script>

<style lang="scss">
</style>
