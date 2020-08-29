const initialState = {
    num: '∞',
};

const state = Object.assign({}, initialState);

const mutations = {
    'set': (state, data) => {
        state.num = data;
    },
};

export default {
    namespaced: true,
    state,
    mutations,
}
