<template>
  <div>
    <div v-if="!userIsLoading">
      <h4 class="modal-title mb-4">Detalles</h4>
        <strong>Nombre:</strong> {{ getUser.name }}<br>
        <strong>Apellido:</strong> {{ getUser.last_name }}<br>
        <strong>Correo electrónico:</strong> {{ getUser.email }}<br>
        <strong>Fecha de registro:</strong> {{ getUser.created_at }}<br>
        <strong>Fecha de actualización:</strong> {{ getUser.updated_at }}
    </div>

    <div v-if="userIsLoading" class=" row justify-content-md-center">
      <div class="col-3 alert-info text-center mt-5">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { mapGetters, mapActions, mapState } from 'vuex'
export default {
  props: {
    id: {
      type: String,
      required: true
    }
  },
  created() {
    this.loadUser(this.id)
  },
  data() {
    return {

    }
  },
  methods: {
    ...mapActions('users', ['loadUser'])
  },
  computed: {
    ...mapGetters('users', ['getUser']),
    ...mapState('users', ['userIsLoading'])
  },
};
</script>
