<template>
    <div>
        <v-card>
            <v-form>
                <v-card-title>Регистрация</v-card-title>
                <v-card-text>
                    <v-row>
                        <v-text-field v-model="user.email"
                                      :label="$t('users.form.labels.email')"
                                      clearable
                                      counter>
                        </v-text-field>
                    </v-row>
                    <v-row>
                        <v-text-field v-model="user.password"
                                      :label="$t('users.form.labels.password')"
                                      clearable
                                      counter
                                      :rules="form.title.rules">
                        </v-text-field>
                    </v-row>
                    <div>
                        <v-btn outlined @click="signIn" color="primary">Зарегистрироваться</v-btn>
                    </div>
                </v-card-text>
            </v-form>
        </v-card>
    </div>
</template>

<script>
  export default {
    name: "registration",

    data() {
      return {
        user: {
          email,
          password,
          password_confirmation,

        }
      }
    },

    methods: {

    }
  }
</script>

<style scoped>

</style>