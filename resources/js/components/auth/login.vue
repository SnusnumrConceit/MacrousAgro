<template>
    <div>
        <v-card class="d-sm-inline-block row col-6">
            <v-form>
                <v-card-title>Авторизация</v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col>
                            <v-text-field v-model="login.email"
                                          :label="$t('users.form.labels.email')"
                                          clearable
                                          >
                            </v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-text-field v-model="login.password"
                                          :label="$t('users.form.labels.password')"
                                          clearable
                            >
                            </v-text-field>
                        </v-col>
                    </v-row>
                    <div>
                        <v-btn outlined @click="signIn" color="primary">Войти</v-btn>
                    </div>
                </v-card-text>
            </v-form>
        </v-card>
    </div>
</template>

<script>
  export default {
    name: "login",

    data() {
      return {
        login: {
          email: '',
          password: ''
        }
      }
    },

    methods: {
      async signIn() {
        const cookie = await axios.get('/sactum/csrf-cookie');
        console.log(cookie);

        const response = await axios.post('/login', {...this.login}, {
          headers: {
            'X-CSRF-TOKEN': $('meta').attr('content')
          }
        });

        console.log(response);
      }
    }
  }
</script>

<style scoped>

</style>