<template>
    <v-dialog width="500" @click:outside="resetForm">
        <template v-slot:activator="{ on }">
            <v-btn outlined text color="white" v-on="on">
                Войти
            </v-btn>
        </template>

        <v-card class="d-sm-inline-block">
            <v-form>
                <v-card-title>Авторизация</v-card-title>
                <v-card-text>
                    <v-container>
                        <errors></errors>
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
                                              type="password"
                                              :append-icon="show.password ? 'mdi-eye' : 'mdi-eye-off'"
                                              :type="show.password ? 'text' : 'password'"
                                              @click:append="show.password = ! show.password"
                                >
                                </v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-btn outlined @click="signIn(login)" color="primary">Войти</v-btn>
                </v-card-actions>
            </v-form>
        </v-card>
    </v-dialog>
</template>

<script>
  import {mapActions} from 'vuex';

  export default {
    name: "login",

    data() {
      return {
        login: {
          email: '',
          password: ''
        },

        show: {
          password: false
        }
      }
    },

    methods: {
      ...mapActions('auth', {
            'signIn': 'login'
          }
      ),

      ...mapActions('errors', {
        'resetErrors': 'resetErrors'
      }),

      resetForm() {
        this.login = {
          email: '',
          password: ''
        };

        this.resetErrors();
      }
    }
  }
</script>

<style scoped>

</style>