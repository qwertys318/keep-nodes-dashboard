<template>
    <div class="nodeRow">
        <tr class="nodeRow__top listTable__row listTable__row__noHover">
            <td :rowspan="isShowDetails?2:1" class="nodeRow__top__img">
                <img :src="'data:image/png;base64, '+item.img" :alt="item.address"/>
            </td>
            <td style="vertical-align:top;padding:5px;width:60%">
                <a @click="isShowDetails=!isShowDetails">{{ item.addressView }}</a>
            </td>
            <td style="vertical-align:top;padding:5px">
                {{ item.unbondedValue }}
            </td>
        </tr>
        <tr v-if="isShowDetails" class="nodeRow__bottom listTable__row listTable__row__noHover">
            <td colspan="2">
                <div>Added: {{ item.dateCreated }}</div>
                <div>Address: {{ item.address }}</div>
            </td>
        </tr>
    </div>
</template>

<script>
export default {
    props: {item: Object},
    data() {
        return {
            config: global.config,
            isShowDetails: false,
        }
    },
    methods: {
        search() {
            this.$emit('search', this.searchValue);
        }
    }
}
</script>

<style lang="scss">
.nodeRow {
    display: contents;

    &__top {
        &__img {
            padding: 5px;
            vertical-align: middle;
            width: 80px;
            background-color: #282828;
            border-bottom: thin solid rgba(255, 255, 255, 0.12) !important;
        }
    }

    &__top:only-child td, &__bottom td {
        border-bottom: thin solid rgba(255, 255, 255, 0.12) !important;
    }

    &__top:not(:only-child) td:not(:first-child) {
        border-bottom: none !important;
    }

    &__bottom td {
        padding: 5px !important;
    }
}
</style>
