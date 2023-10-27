<template>
    <v-container>
        <v-row justify="center">
            <v-col cols="12" sm="8" md="4">
                <v-card>
                    <v-card-title class="headline">
                        Inicio de sesion
                    </v-card-title>
                    <v-card-text>
                        <v-form @submit.prevent="login">
                            <v-text-field v-model="username" label="email"></v-text-field>
                            <v-text-field v-model="password" label="password"></v-text-field>
                            <v-btn type="submit">Entrar</v-btn>
                        </v-form>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
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