<template>
  <div>
    <div>
      <h4 class="modal-title mb-1">Listado</h4>
      <nav class="nav justify-content-end mb-3">
        <router-link to="/users/new" class="btn btn-outline-primary">
          <i class="bi bi-person"></i> Nuevo usuario
        </router-link>
      </nav>
      <router-view></router-view>
      <table v-if="!usersAreLoading" class="table table-striped">
        <thead>
          <tr>
            <th scope="col">Id</th>
            <th scope="col">Nombre</th>
            <th scope="col">Apellido</th>
            <th scope="col">Correo electrónico</th>
            <th scope="col">Fecha de registro</th>
            <th scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in getUsers" :key="user.id">
            <td>{{ user.id }}</td>
            <td>{{ user.name }}</td>
            <td>{{ user.last_name }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.created_at }}</td>
            <td>
              <button @click="$router.push(`/users/${user.id}/edit`)" type="button"
                class="btn btn-primary btn-sm ms-1 mb-1">
                <i class="bi bi-pencil-square" title="Editar usuario"></i>
              </button>
              <button @click="$router.push(`/users/${user.id}`)" type="button" class="btn btn-primary btn-sm ms-1 mb-1">
                <i class="bi bi-eye" title="Ver detalles"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-if="usersAreLoading" class=" row justify-content-md-center">
        <div class="col-3 alert-info text-center mt-5">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>
<script>
import { mapGetters, mapActions, mapState } from 'vuex'
export default {
  created() {
    this.loadUsers()
  },
  data() {
    return {

    }
  },
  methods: {
    ...mapActions('users', ['loadUsers'])
  },
  computed: {
    ...mapGetters('users', ['getUsers']),
    ...mapState('users', ['usersAreLoading'])
  },
};
</script>
