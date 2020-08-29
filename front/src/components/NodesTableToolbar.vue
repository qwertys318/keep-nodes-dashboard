<template>
    <v-toolbar flat dense>
        <v-text-field v-model="searchValue"
                      @input="search"
                      v-on:keyup.enter="search"
                      hide-details
                      append-icon="mdi-magnify"
                      label="Search"
                      style="width:100%"
                      single-line
        />
        <v-spacer style="margin-left:15px"/>
        <v-dialog v-model="dialog" width="500" content-class="productCreateDialog" persistent>
            <template v-slot:activator="{ on }">
                <span style="margin-left:15px">
                    <v-btn v-on="on" @click="createForm.reset" style="color:#46dcb5" color="secondary" small>
                        <v-icon color="primary" left>mdi-plus</v-icon>
                        Add Node
                    </v-btn>
                </span>
            </template>
            <v-form @submit.prevent="createForm.submit" style="display:contents">
                <v-card>
                    <v-card-title>Add your own ECDSA node</v-card-title>
                    <v-card-text>
                        <v-text-field :error-messages="createForm.errors.address"
                                      v-model="createForm.data.address" label="Address"/>
                        <div class="error--text">
                            <span v-if="createForm.errors.form">{{ createForm.errors.form }}</span>
                        </div>
                    </v-card-text>
                    <v-divider></v-divider>
                    <v-card-actions>
                        <v-btn @click="dialog = false" text>Cancel</v-btn>
                        <v-spacer></v-spacer>
                        <v-btn type="submit" style="color:#46dcb5" color="secondary" :loading="createForm.loading">Add</v-btn>
                    </v-card-actions>
                </v-card>
            </v-form>
        </v-dialog>
    </v-toolbar>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
        return {
            config: global.config,
            dialog: false,
            createForm: null,
            searchValue: '',
        }
    },
    created() {
        this.createForm = this.$ewll.initForm(this, {
            url: '/crud/node',
            success: function () {
                this.dialog = false;
                this.$emit('added');
            }.bind(this),
        });
    },
    methods: {
        search() {
            this.$emit('search', this.searchValue);
        }
    }
}
</script>

<style lang="scss">
</style>
