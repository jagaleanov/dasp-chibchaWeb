<template>
    <v-app class="bg-blue-accent-1">
        <v-container>
        <v-row justify="center" class=".rounded-circle">
                <v-col cols="12" sm="8" md="4">
                    <v-card class="bg-blue-darken-4">
                        <v-divider class="border-opacity-0"></v-divider>
                        <v-card-title class="headline" align="center" >
                            Inicio de sesion
                        </v-card-title>
                        <v-divider class="border-opacity-0"></v-divider>
                        <v-card-text>
                            <v-form @submit.prevent="login">
                                <v-text-field v-model="username" label="correo" variant="outlined" prepend-icon="mdi-account-outline"></v-text-field>
                                <v-text-field v-model="password" label="clave" variant="outlined" prepend-icon="mdi-lastpass"></v-text-field>
                                <v-btn type="submit" class="bg-blue-darken-3" variant=".rounded-shaped" stacked prepend-icon="mdi-login">Entrar</v-btn>
                                <v-divider class="border-opacity-0"></v-divider>
                            </v-form>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </v-app>
</template>


<script>
    import axios from "axios";
    export default{
        data(){
            return {
                username:"",
                password:""
            };
        },methods:{
            login(){
                let credenciales={email:this.username,password:this.password}
                axios.post("https://chibchaweb.com/api/login",credenciales).then(response => {
                    let token=response.data.token
                    localStorage.setItem("token", token);
                    this.$router.push("/home");
                }).catch(error => {console.error("Error en el incio de sesion: ",error)});
            }
        }
    }
</script>

<style>

</style>