import usersApi from '@/modules/user/api/usersApi'

export const loadUsers = async ({ commit }) => {
    const { data } = await usersApi.loadUsers()
    const users = []
    if (data.status == "success") {
        for (let id of Object.keys(data.data)) {
            users.push({
                id,
                ...data.data[id]
            })
        }
        commit('setUsers', users)
    }
}

export const loadUser = async ({ commit }, id) => {
    const { data } = await usersApi.loadUser(id)
    let user = {
        id: null,
        name: null,
        last_name: null,
        email: null,
        password: null,
        created_at: null,
        updated_at: null,
    }
    if (data.status == "success") {
        user = data.data
        commit('setUser', user)
    }
}

