<template>
    <v-app>
        <v-snackbar v-model="snack.isShow">{{ snack.text }}</v-snackbar>
        <v-card>
            <v-card width="85%" style="margin:0 auto" elevation="0">
                <v-card-title style="word-break:break-word;padding-left:0">
                <span>
                    <span style="color:#46dcb5">KEEP</span>
                    ECDSA Nodes Dashboard
                </span>
                </v-card-title>
            </v-card>
        </v-card>
        <v-card width="85%" style="margin:30px auto 0;background-color:transparent;" elevation="0">
            <v-card width="50%" height="100px" style="display:flex;align-items:center;">
                <v-card-text>
                    <span style="font-size:22px">Nodes added: <span style="color:#46dcb5">{{$store.state.nodesCounter.num}}</span></span>
                </v-card-text>
            </v-card>
        </v-card>
        <v-card width="85%" style="margin:30px auto">
            <router-view :search="search"/>
        </v-card>
    </v-app>
</template>

<script>
export default {
    data() {
        return {
            config: global.config,
            search: '',
            snack: {isShow: false, text: ''},
        }
    },
    created() {
        this.config.totalNodes = '∞';
        this.$snack.listener = function (text) {
            this.snack.text = text;
            this.snack.isShow = false;
            this.snack.isShow = true;
        }.bind(this);
    },
}
</script>

<style lang="scss">
</style>
