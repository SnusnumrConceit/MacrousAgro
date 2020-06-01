<template>
    <div>
        <v-card v-show="! loading">
            <v-form v-model="form.valid" ref="user_form">
                <v-card-title class="headline">
                    {{ $t('users.form.header') }}
                </v-card-title>

                <v-card-text>
                    <v-container>
                        <errors />

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
                                                   ref="birthday-date-picker"
                                                   no-title
                                                   scrollable
                                                   v-if="birthdayCalendar"
                                                   :locale="$i18n.locale">

                                        <v-spacer />

                                        <v-btn color="blue darken-1"
                                               @click="birthdayCalendar = false"
                                               text>
                                            {{ $t('buttons.cancel') }}
                                        </v-btn>
                                        <v-btn color="primary" outlined
                                               @click="birthdayCalendar = false">
                                            OK
                                        </v-btn>
                                    </v-date-picker>
                                </v-menu>
                            </v-col>
                            <v-col cols="12" sm="6" md="6" v-if="! id">
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
                            <v-col cols="12" sm="6" md="6" v-if="! id">
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
                    <v-spacer v-if="! id"/>

                    <v-btn color="success"
                           outlined
                           @click="id ? update() : create()"
                           :disabled="! form.valid">
                        {{ $t(btnSave) }}
                    </v-btn>
                    <v-btn color="blue darken-1"
                           text
                           @click="id ? $router.back() : hide()">
                        {{ $t(btnCancel) }}
                    </v-btn>

                </v-card-actions>
            </v-form>
        </v-card>

        <v-skeleton-loader type="table-row-divider@4" v-show="loading"></v-skeleton-loader>
    </div>
</template>

<script>
  import {mapActions} from 'vuex';

  export default {
    name: "UserForm",

    data() {
      return {
        user: {
          email: '',
          first_name: '',
          last_name: '',
          display_birthday: null,
          /**
          new Date().toLocaleString('ru', {
            year: 'numeric',
            month: 'numeric',
            day: 'numeric',
            timezone: 'utc'
          }),
           **/
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

        loading: false,

        birthdayCalendar: false
      }
    },

    computed: {
      id() {
        return this.$route.params.id;
      },

      btnSave() {
        return this.id ? 'buttons.edit' : 'buttons.save';
      },

      btnCancel() {
        return this.id ? 'buttons.back' : 'buttons.cancel';
      }
    },

    methods: {
      /**
       * Показ ошибок в форме
       */
      ...mapActions('errors', [
        'resetErrors',
        'setErrors'
      ]),

      /**
       * Показ / обнуление уведомлений
       */
      ...mapActions('notifications', [
        'showNotification'
      ]),

      /**
       * Загрузить видео для редактирования
       *
       * @returns {Promise<void>}
       */
      async getUser() {
        try {
          const response = await axios.get(`/users/${this.id}/edit`);
          this.user = response.data.user;
        } catch (e) {
          this.showNotification({ type: 'error', message: e.response.data.message});
        }
      },

      /**
       * Добавление пользователя
       *
       * @returns {Promise<boolean>}
       */
      async create() {
        try {
          const response = await axios.post(`/users`, this.user);

          this.showNotification({ type: 'success', message: response.data.message});

          this.$root.$emit('user-created');
          this.hide();
        } catch (e) {
          this.setErrors(e.response.data.errors);
        }
      },

      /**
       * Сохранить изменения видео
       *
       * @returns {Promise<void>}
       */
      async update() {
        try {
          const response = await axios.put(`/users/${this.id}`, {
            ...this.user
          });

          this.showNotification({ type: 'success', message: response.data.message});

          this.$router.back();
          this.resetForm();
        } catch (e) {
          this.setErrors(e.response.data.errors);
        }
      },

      /**
       * Обнуление формы
       *
       * Reset form
       */
      resetForm() {
        this.$refs.user_form.reset();
      },

      /**
       * Закрыть модальное окно
       */
      hide() {
        this.$emit('hide-modal');
        this.resetForm();
      },

      /**
       * Последовательная загрузка данных
       *
       * @returns {Promise<void>}
       */
      async initData() {
        this.loading = true;

        await this.getUser();

        this.loading = false;
      }
    },

    watch: {
      'user.birthday': function (after) {
        if (after !== null) {
          console.log(this.user.birthday);
          this.user.display_birthday = after.split('-').reverse().join('.');
        }
      },

      'user.display_birthday': function (after) {
        if (after === null) {
          this.user.birthday = null;
        }
      },

      /** изменение формата выбора даты рождения **/
      'birthdayCalendar': function (after) {
        after && setTimeout(() => this.$refs['birthday-date-picker'].activePicker = 'YEAR');
      }
    },

    created() {
      if (this.id) {
        this.initData();
      }
    }
  }
</script>