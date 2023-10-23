export const setUsers = (state, users) => {
    state.usersAreLoading = true
    state.users = []
    state.users = [...state.users, ...users]
    state.usersAreLoading = false
}

export const setUser = (state, user) => {
    state.userIsLoading = true
    state.user = {
        id: user.id,
        name: user.name,
        last_name: user.last_name,
        email: user.email,
        password: user.password,
        created_at: user.created_at,
        updated_at: user.updated_at,
    }
    state.userIsLoading = false
}
