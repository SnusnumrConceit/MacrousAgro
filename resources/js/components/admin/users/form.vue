<template>
    <div>
        <v-card v-show="! loading">
            <v-form v-model="form.valid" ref="user_form">
                <v-card-title>
                    Редактирование пользователя
                </v-card-title>
                <v-card-text>
                    <errors :errors="errors"></errors>
                    <v-row>
                        <v-col cols="12" sm="12" md="4">
                            <v-text-field v-model="user.email"
                                          :label="$t('users.form.labels.email')"
                                          required
                                          counter
                                          clearable
                                          maxlength="255"
                                          :rules="form.email.rules"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="12" md="4">
                            <v-text-field v-model="user.last_name"
                                          :label="$t('users.form.labels.last_name')"
                                          required
                                          counter
                                          clearable
                                          maxlength="100"
                                          minlength="2"
                                          :rules="form.last_name.rules"></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="6" md="4" lg="4">
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
                                    v-model="form_calendar"
                                    :close-on-content-click="false"
                                    transition="scale-transition"
                                    offset-y
                                    max-width="290px"
                                    min-width="200px">
                                <template v-slot:activator="{ on }">
                                    <v-text-field v-model="user.display_birthday"
                                                  :label="$t('users.form.labels.birthday')"
                                                  prepend-icon="event"
                                                  :rules="form.birthday.rules"
                                                  readonly
                                    ></v-text-field>
                                </template>

                                <v-date-picker v-model="user.birthday"
                                               first-day-of-week="1"
                                               no-title
                                               scrollable
                                               @input="form_calendar"
                                               :locale="$i18n.locale">
                                    <v-spacer></v-spacer>
                                    <v-btn color="blue darken-1" @click="form_calendar = false" text>
                                        {{ $t('users.btn.cancel') }}
                                    </v-btn>
                                    <v-btn text color="primary" @click="form_calendar = false">OK</v-btn>
                                </v-date-picker>
                            </v-menu>
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="success" outlined @click="save" :disabled="! form.valid">
                        {{ $t('users.btn.edit')}}
                    </v-btn>
                    <v-btn @click="goBack" color="default" outlined>
                        {{ $t('users.btn.back')}}
                    </v-btn>
                </v-card-actions>
            </v-form>
        </v-card>

        <v-skeleton-loader type="table-row-divider@4" v-show="loading"></v-skeleton-loader>
        <!--<v-btn outlined color="error" @click="remove">-->
            <!--{{ $t('users.btn.delete') }}-->
        <!--</v-btn>-->
    </div>
</template>

<script>
  export default {
    name: "form",

    data() {
      return {
        user: {
          email: '',
          last_name: '',
          first_name: '',
          birthday: null,
          display_birthday: new Date().toLocaleString('ru', {year: 'numeric', month: 'numeric', day: 'numeric', timezone: 'utc'})
        },

        form: {
          valid:false,
          email: {
            rules: [
              v => v !== '' || this.$t('users.form.rules.email.required'),
              v => (v !== undefined && v !== null && v.length > 10) || this.$t('users.form.rules.email.min_length', {length:10}),
              v => (v !== undefined && v !== null && v.length <= 255) || this.$t('users.form.rules.email.max_length', {length: 255}),
              v => (v !== undefined && v !== null && /^([0-9a-z]{2,}[@][a-z]{2,10}[.][a-z]{2,3}){1,255}$/.test(v)) || this.$t('users.form.rules.email.invalid_format')
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
              v => (v !== undefined && v !== null && v.length >= 2) || this.$t('users.form.rules.first_name.min_length', {length:2}),
              v => (v !== undefined && v !== null && v.length <= 60) || this.$t('users.form.rules.first_name.max_length', {length: 60})
            ]
          },

          last_name: {
            rules: [
              v => v !== '' || this.$t('users.form.rules.last_name.required'),
              v => (v !== undefined && v !== null && v.length >= 2) || this.$t('users.form.rules.last_name.min_length', {length:2}),
              v => (v !== undefined && v !== null && v.length <= 100) || this.$t('users.form.rules.last_name.max_length', {length: 100})
            ]
          },

          password: {
            rules: [
              v => v !== '' || this.$t('users.form.rules.password.required'),
              v => (v !== undefined && v !== null && v.length >= 8) || this.$t('users.form.rules.password.min_length', {length:8}),
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
        form_calendar: false,

        show: {
          password: false,
          password_confirmation: false
        },

        loading: false,
        errors: []
      }
    },

    computed: {
      id() {
        return this.$route.params.id;
      }
    },

    methods: {
      /**
       * Загрузить видео для редактирования
       *
       * @returns {Promise<void>}
       */
      async loadData() {
        this.loading = true;
        const response = await axios.get(`${this.$attrs.apiRoute}/users/${this.id}/edit`);

        if (response.data.status === 'error' || response.status !== 200) {
          this.$swal(this.$t('swal.title.error', response.data.msg, 'error'));
          this.loading = false;
          return false;
        }

        this.user = response.data.user;
        this.loading = false;
      },

      /**
       * Сохранить изменения видео
       *
       * @returns {Promise<void>}
       */
      async save() {
        try {
          const response = await axios.put(`${this.$attrs.apiRoute}/users/${this.id}`, {
            ...this.user
          });
          this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
          this.goBack();
        } catch (e) {
          this.errors = e.response.data.error;
        }
      },

      /**
       * Удалить видео
       *
       * @returns {Promise<void>}
       */
      async remove() {
        try {
          const response = await axios.delete(`${this.$attrs.apiRoute}/users/${this.id}`);
          this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
          this.goBack();
        } catch (e) {
          this.$swal(this.$t('swal.title.error'), e.response.data.msg, 'error');
        }
      },

      /**
       * Назад
       */
      goBack() {
        this.$router.go(-1);
      },

      /**
       * Последовательная загрузка данных
       *
       * @returns {Promise<void>}
       */
      async initData() {
        await this.loadData();
      }
    },
    
    watch: {
      'user.birthday': function (after) {
        if (after !== null) {
          this.user.display_birthday = after.split('-').reverse().join('.');
        }   
      },
      
      'user.display_birthday': function (after) {
        if (after === null) {
          this.user.birthday = null;
        }
      }
    },

    created() {
      if (this.id) {
        this.initData();
      }
    }
  }
</script>