import { createStore } from 'vuex'
import users from '../modules/user/store'

const store = createStore({
    modules: {
        users
    }
})

export default store