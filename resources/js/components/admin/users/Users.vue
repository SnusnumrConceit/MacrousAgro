<template>
    <div>
        <v-card>
            <!-- TODO вынести в компонент users-search -->
            <v-toolbar color="white">
                <v-toolbar-title>
                    Пользователи
                </v-toolbar-title>

                <v-divider class="mx-4" vertical inset></v-divider>

                <v-spacer></v-spacer>

                <v-text-field v-model="search.keyword"
                              @keyup.enter="onSearch"
                              append-icon="search"
                              label="Поиск"
                              single-line></v-text-field>

                <v-spacer></v-spacer>

                <v-menu ref="search-calendar-menu"
                        v-model="calendar"
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        max-width="290px"
                        min-width="200px">
                    <template v-slot:activator="{ on }">
                        <v-text-field
                                v-on="on"
                                v-model="search.display_updated_at"
                                label="Последнее действие"
                                hint="Последнее действие"
                                persistent-hint
                                dense
                                readonly
                                clearable
                                single-line
                                prepend-icon="event"
                        ></v-text-field>
                    </template>

                    <v-date-picker :locale="$i18n.locale"
                                   width="290"
                                   first-day-of-week="1"
                                   color="primary"
                                   no-title
                                   scrollable
                                   @input="calendar = false"
                                   v-model="search.updated_at">
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" @click="calendar = false" text>
                            {{ $t('users.btn.cancel') }}
                        </v-btn>
                        <v-btn color="primary" outlined @click="calendar = false">OK</v-btn>
                    </v-date-picker>
                </v-menu>

                <v-spacer></v-spacer>

                <!-- TODO вынести в компонент users-create-form -->
                <v-dialog v-model="modal" max-width="700px" max-height="1000px">
                    <template v-slot:activator="{on}">
                        <v-btn outlined color="success" class="" dark v-on="on">
                            <i class="pe-7s-plus"></i> {{ $t('users.btn.add')}}
                        </v-btn>
                    </template>

                    <v-card>
                        <v-form v-model="form.valid" ref="user_form">
                            <v-card-title class="headline">
                                Пользователь
                            </v-card-title>

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
                                                    v-model="form_calendar"
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
                                                               @input="form_calendar = false"
                                                               :max="new Date().toISOString().substr(0,10)"
                                                               no-title
                                                               scrollable
                                                               v-if="form_calendar"
                                                               :locale="$i18n.locale">

                                                    <v-spacer></v-spacer>

                                                    <v-btn color="blue darken-1" @click="form_calendar = false" text>
                                                        {{ $t('users.btn.cancel') }}
                                                    </v-btn>
                                                    <v-btn color="primary" outlined @click="form_calendar = false">OK
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
                                <v-spacer></v-spacer>

                                <v-btn color="success" @click="save()" :disabled="! form.valid">Сохранить</v-btn>
                                <v-btn color="blue darken-1" text @click="close()">Отмена</v-btn>

                            </v-card-actions>
                        </v-form>
                    </v-card>
                </v-dialog>

            </v-toolbar>
            <v-row v-if="calendar" :justify="'center'" :align="'center'">

            </v-row>

            <v-card-text>
                <!-- TODO вынести в компонент users-list -->

                <v-btn color="success" outlined @click="exportData" class="float-right">Экспорт</v-btn>

                <v-simple-table :fixed-header="! calendar" :height="750" v-show="! loading && users.length">
                    <template v-slot:default>
                        <thead>
                        <th v-for="header in table.headers" :key="header.text" class="text-left">
                            {{ header }}
                        </th>
                        </thead>
                        <tbody>
                        <tr v-for="user in users" :key="user.id">
                            <td class="text-left">
                                {{ fullName(user) }}
                            </td>
                            <td class="text-left">
                                {{ user.email }}
                            </td>
                            <td class="text-left">
                                {{ user.display_birthday }}
                            </td>
                            <td class="text-left">
                                {{ user.registration_date }}
                            </td>
                            <td class="text-left">
                                {{ user.last_activity_date }}
                            </td>
                            <td class="text-right">
                                <v-tooltip top color="primary">
                                    <template v-slot:activator="{ on }">
                                        <v-icon v-on="on"
                                                small
                                                @click="$router.push(`/admin/users/${user.id}`)"
                                        >
                                            mdi-pencil
                                        </v-icon>
                                    </template>
                                    <span>
                                        Править
                                    </span>
                                </v-tooltip>

                                <v-tooltip top color="error">
                                    <template v-slot:activator="{ on }">
                                        <v-icon color="red"
                                                v-on="on"
                                                small
                                                @click="remove(user.id)"
                                        >
                                            mdi-delete
                                        </v-icon>
                                    </template>
                                    <span>
                                        Удалить
                                    </span>
                                </v-tooltip>
                            </td>
                        </tr>
                        </tbody>
                    </template>
                </v-simple-table>
                <span v-show="! loading" class="d-flex flex-row-reverse">
                    Всего: {{ pagination.total }}
                </span>

                <v-skeleton-loader type="table-row-divider@6" v-show="loading">

                </v-skeleton-loader>

                <v-alert color="info" outlined v-if="! loading && ! users.length">
                    <div class="">
                        <span v-show="! searching">
                            Пользователи отсутствуют в системе
                        </span>
                        <span v-show="searching">
                            По Вашему запросу ничего не найдено
                        </span>
                    </div>
                </v-alert>
            </v-card-text>
        </v-card>

    </div>
</template>

<script>
  import debounce from '../../../debounce';
  import infiniteScrollMixin from '../../../mixins/infinite_scroll';
  import { mapActions } from 'vuex';

  export default {
    name: "index",

    mixins: [infiniteScrollMixin],

    data() {
      return {
        users: [],

        searching: false,

        search: {
          keyword: '',
          updated_at: null,
          display_updated_at: new Date().toLocaleString('ru', {
            year: 'numeric',
            month: 'numeric',
            day: 'numeric',
            timezone: 'utc'
          })
        },

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

        modal: false,
        form_calendar: false,

        show: {
          password: false,
          password_confirmation: false
        },

        table: {
          headers: [
            'Фамилия и Имя',
            'Email',
            'Дата рождения',
            'Зарегистрирован',
            'Последние действия',
            ''
          ]
        },

        pagination: {
          last_page: 1,
          page: 1,
          total: ''
        },

        calendar: false,
        loading: false
      }
    },

    computed: {
      createFormRoute() {
        return '/admin/users/create';
      },

      editFormRoute() {
        return '/admin/users/update';
      },

      fullName() {
        return user => `${user.first_name} ${user.last_name}`;
      }
    },

    methods: {
      ...mapActions('errors', {
        'setErrors': 'setErrors'
      }),

      /**
       * Загрузка списка пользователей
       *
       * @returns {Promise<boolean>}
       */
      async loadData() {
        this.loading = true;

        try {
          const response = await axios.get(`${this.$attrs.apiRoute}/users`, {
            params: {
              page: this.pagination.page
            }
          });

          this.users = (this.pagination.page === 1)
              ? response.data.users.data
              : this.users.concat(response.data.users.data);
          this.pagination.last_page = response.data.users.last_page;
          this.pagination.total = response.data.users.total;
        } catch (e) {
          this.$swal(this.$t('swal.title.error'), e.response.data.msg, 'error');
        } finally {
          this.loading = false;
        }
      },

      /**
       * Удаление пользователя
       *
       * @param id
       * @returns {Promise<boolean>}
       */
      async remove(id) {
        try {
          const response = await axios.delete(`${this.$attrs.apiRoute}/users/${id}`);

          this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
          this.users = this.users.filter((user) => user.id !== id);
        } catch (e) {
          this.$swal(this.$t('swal.title.error'), e.response.data.msg, 'error');
        }
      },

      /**
       * Добавление пользователя
       *
       * @returns {Promise<boolean>}
       */
      async save() {
        try {
          const response = await axios.post(`${this.$attrs.apiRoute}/users`, this.user);
          this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
          this.loadData();
          this.close();
        } catch (e) {
          this.setErrors(e.response.data.errors);
        }
      },

      /**
       * Экспорт пользователей
       */
      exportData() {
        axios({
          url: `${this.$attrs.apiRoute}/users/export`,
          method: 'POST',
          responseType: 'blob', // important
        }).then((response) => {
          console.log(response.data);
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'users.xlsx');
          document.body.appendChild(link);
          link.click();
        });
      },

      /**
       * Закрыть модальное окно
       */
      close() {
        this.modal = false;
        this.$refs.user_form.reset();
      },

      // edit(user) {
      //   this.user = {...user};
      //   this.modal = true;
      // },

      /**
       * Обработчик события debounce-поиска пользователей
       */
      onSearch() {
        if (this.search.keyword.length < 3) {
          return;
        }

        this.searchData(this);
      },

      /**
       * Поиск пользователей
       */
      searchData: debounce((vm) => {
        vm.loading = true;
        axios.get(`${vm.$attrs.apiRoute}/users`, {
          params: {
            page: vm.pagination.page,
            ...vm.search
          }
        })
            .then(response => {
              vm.users = (vm.pagination.page === 1)
                  ? response.data.users.data
                  : vm.users.concat(response.data.users.data);
              vm.pagination.last_page = response.data.users.last_page;
              vm.pagination.total = response.data.users.total;
              vm.loading = false;
            })
            .catch(error => {
              vm.$swal(vm.$t('swal,title.error'), error.data.msg, 'error');
            })
        ;
      }, 300),

      /**
       * Обработчик события скролла в таблице
       */
      onScroll: function () {
        this.paginationScroll(this, $('.v-data-table__wrapper')[0]);
      },
    },

    watch: {
      'search': {
        handler: function (after, before) {
          if (after.updated_at !== null || after.keyword.length > 3) {
            this.pagination.page = 1;
            this.searching = true;

            this.searchData(this);
          }

          if (! after.updated_at && ! after.keyword.length) {
            this.pagination.page = 1;
            this.searching = false;

            this.loadData();
          }
        },

        deep: true
      },

      'search.keyword': function (after, before) {
        if (after.length > 3) {
          this.onSearch();
        }
      },

      'search.updated_at': function (after) {
        if (after !== null) {
          this.search.display_updated_at = after.split('-').reverse().join('.');
        }
      },

      'search.display_updated_at': function (after) {
        if (after === null) {
          this.search.updated_at = null;
        }
      },

      'user.birthday': function (after) {
        if (after !== null) {
          this.user.display_birthday = after.split('-').reverse().join('.');
        }
      },

      'user.display_updated_at': function (after) {
        if (after === null) {
          this.user.birthday = null;
        }
      }
    },

    created() {
      this.loadData();
    },

    mounted() {
      $('.v-data-table__wrapper')[0].addEventListener('scroll', this.onScroll);
    }
  }
</script>