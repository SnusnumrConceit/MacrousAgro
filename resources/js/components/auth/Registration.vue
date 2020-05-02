<template>
    <v-dialog  width="500" @click:outside="resetForm">
        <template v-slot:activator="{ on }">
            <v-btn outlined text color="white" v-on="on">
                Регистрация
            </v-btn>
        </template>

        <v-card>
            <v-form>
                <v-card-title>Регистрация</v-card-title>
                <v-card-text>
                    <v-container>
                        <errors></errors>
                        <v-row>
                            <v-col cols="12" sm="12" md="12" lg="12">
                                <v-text-field v-model="user.email"
                                              :label="$t('users.form.labels.email')"
                                              required
                                              counter
                                              clearable
                                              maxlength="255"
                                              :rules="form.email.rules"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="12" md="6" lg="6">
                                <v-text-field v-model="user.last_name"
                                              :label="$t('users.form.labels.last_name')"
                                              required
                                              counter
                                              clearable
                                              maxlength="100"
                                              minlength="2"
                                              :rules="form.last_name.rules"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" md="6" lg="6">
                                <v-text-field v-model="user.first_name"
                                              :label="$t('users.form.labels.first_name')"
                                              required
                                              counter
                                              clearable
                                              maxlength="60"
                                              minlength="2"
                                              :rules="form.first_name.rules">
                                </v-text-field>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                                <v-menu ref="search-calendar-menu"
                                        v-model="birthdayCalendar"
                                        :close-on-content-click="false"
                                        transition="scale-transition"
                                        offset-y
                                        max-width="290px"
                                        min-width="200px">
                                    <template v-slot:activator="{ on }">
                                        <v-text-field v-model="user.display_birthday"
                                                      v-on="on"
                                                      :label="$t('users.form.labels.birthday')"
                                                      prepend-icon="event"
                                                      :rules="form.birthday.rules"
                                                      readonly
                                        ></v-text-field>
                                    </template>

                                    <v-date-picker v-model="user.birthday"
                                                   @input="birthdayCalendar = false"
                                                   :max="new Date().toISOString().substr(0,10)"
                                                   no-title
                                                   scrollable
                                                   v-if="birthdayCalendar"
                                                   :locale="$i18n.locale">

                                        <v-spacer></v-spacer>

                                        <v-btn color="blue darken-1" @click="birthdayCalendar = false" text>
                                            {{ $t('users.btn.cancel') }}
                                        </v-btn>
                                        <v-btn color="primary" outlined @click="birthdayCalendar = false">OK
                                        </v-btn>
                                    </v-date-picker>
                                </v-menu>
                            </v-col>
                            <v-col cols="12" sm="6" md="6">
                                <v-text-field v-model="user.password"
                                              :append-icon="show.password ? 'mdi-eye' : 'mdi-eye-off'"
                                              :label="$t('users.form.labels.password')"
                                              :type="show.password ? 'text' : 'password'"
                                              required
                                              counter
                                              clearable
                                              maxlength="60"
                                              minlength="8"
                                              :rules="form.password.rules"
                                              @click:append="show.password = ! show.password">

                                </v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" md="6">
                                <v-text-field v-model="user.password_confirmation"
                                              :append-icon="show.password_confirmation ? 'mdi-eye' : 'mdi-eye-off'"
                                              :label="$t('users.form.labels.password_confirmation')"
                                              :type="show.password_confirmation ? 'text' : 'password'"
                                              required
                                              counter
                                              clearable
                                              maxlength="60"
                                              minlength="8"
                                              :rules="form.password_confirmation.rules"
                                              @click:append="show.password_confirmation = ! show.password_confirmation">

                                </v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-btn outlined @click="register(user)" color="primary">Зарегистрироваться</v-btn>
                </v-card-actions>
            </v-form>
        </v-card>
    </v-dialog>
</template>

<script>
  import {mapActions} from 'vuex';

  export default {
    name: "registration",

    data() {
      return {
        user: {
          email: '',
          first_name: '',
          last_name: '',
          display_birthday: new Date().toLocaleString('ru', {
            year: 'numeric',
            month: 'numeric',
            day: 'numeric',
            timezone: 'utc'
          }),
          birthday: null,
          password: '',
          password_confirmation: ''
        },

        form: {
          valid: false,

          email: {
            rules: [
              v => v !== '' || this.$t('users.form.rules.email.required'),
              v => (v !== undefined && v !== null && v.length > 10) || this.$t('users.form.rules.email.min_length', {length: 10}),
              v => (v !== undefined && v !== null && v.length <= 255) || this.$t('users.form.rules.email.max_length', {length: 255}),
              v => /^([0-9a-z]{2,}[@][a-z]{2,10}[.][a-z]{2,3}){1,255}$/.test(v) || this.$t('users.form.rules.email.invalid_format')
            ]
          },

          birthday: {
            rules: [
              v => v !== '' || this.$t('users.form.rules.birthday.required'),
              v => (v !== undefined && v !== null && v < Date.now) || this.$t('users.form.rules.birthday.max_length'),
            ]
          },

          first_name: {
            rules: [
              v => v !== '' || this.$t('users.form.rules.first_name.required'),
              v => (v !== undefined && v !== null && v.length >= 2) || this.$t('users.form.rules.first_name.min_length', {length: 2}),
              v => (v !== undefined && v !== null && v.length <= 60) || this.$t('users.form.rules.first_name.max_length', {length: 60})
            ]
          },

          last_name: {
            rules: [
              v => v !== '' || this.$t('users.form.rules.last_name.required'),
              v => (v !== undefined && v !== null && v.length >= 2) || this.$t('users.form.rules.last_name.min_length', {length: 2}),
              v => (v !== undefined && v !== null && v.length <= 100) || this.$t('users.form.rules.last_name.max_length', {length: 100})
            ]
          },

          password: {
            rules: [
              v => v !== '' || this.$t('users.form.rules.password.required'),
              v => (v !== undefined && v !== null && v.length >= 8) || this.$t('users.form.rules.password.min_length', {length: 8}),
              v => (v !== undefined && v !== null && v.length <= 60) || this.$t('users.form.rules.password.max_length', {length: 255})
            ]
          },

          password_confirmation: {
            rules: [
              v => v !== '' || this.$t('users.form.rules.password_confirmation.required'),
              v => v === this.user.password || this.$t('users.form.rules.password_confirmation.not_equals')
            ]
          }
        },

        show: {
          password: false,
          password_confirmation: false
        },

        birthdayCalendar: false
      }
    },

    methods: {
      ...mapActions('auth', {
        'register': 'register'
      }),

      ...mapActions('errors', {
        'resetErrors': 'resetErrors'
      }),

      resetForm() {
          this.user = {
            email: '',
            first_name: '',
            last_name: '',
            display_birthday: new Date().toLocaleString('ru', {
              year: 'numeric',
              month: 'numeric',
              day: 'numeric',
              timezone: 'utc'
            }),
            birthday: null,
            password: '',
            password_confirmation: ''
          };

          this.resetErrors();
      }
    },

    watch: {
      'user.birthday': function (after) {
        if (after !== null) {
          this.user.display_birthday = after.split('-').reverse().join('.');
        }
      },
    }
  }
</script>

<style scoped>

</style>