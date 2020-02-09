<template>
    <div>
        <v-card>
            <v-data-table :headers="table.headers"
                          :items="users"
                          :items-per-page="15"
                          class="evelation-1"
                          :page="pagination.page"
                          :search="'Search'"
                          :locale="$i18n.locale"
                          :noDataText="$t('v-data-table.no-data-text')"
                          :noResultsText="$t('v-data-table.no-results-text')"
                          loading
                          multi-sort
                          :sort-by="['email', 'name', 'birthday', 'created_at', 'updated_at']"
                          :sort-desc="[false, false, false, true, false]"
                          :loading-text="$t('v-data-table.loading-text')" :per-page="$t('v-data-table.per-page')"
                          :footer-props="{itemsPerPageText: 'На странице'}">
                <template v-slot:top>
                    <v-toolbar flat color="white">
                        <v-toolbar-title>
                            Пользователи
                        </v-toolbar-title>

                        <v-divider class="mx-4" vertical inset></v-divider>
                        <v-spacer></v-spacer>

<!--                        <v-btn outlined color="secondary" dark @click="calendar = ! calendar">
                            <span>{{ (calendar) ? 'Скрыть календарь' : 'Показать календарь' }}</span>
                        </v-btn>-->
                        <v-text-field
                            slot="activator"
                            v-model="search.filter.birthday"
                            label="Дата рождения"
                            hint="DD/MM/YYYY format"
                            persistent-hint
                            prepend-icon="event"
                            @blur="search.filter.birthday = parseDate(search.filter.birthday)"
                        ></v-text-field>

                        <v-date-picker :locale="$i18n.locale"
                                       width="550"
                                       first-day-of-week="1"
                                       color="success"
                                       v-if="calendar"
                                       v-model="search.filter.birthday">
                        </v-date-picker>

                        <v-text-field v-model="search.keyword" @keyup.enter="onSearch" append-icon="search" label="Поиск" single-line></v-text-field>

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
                                                    <v-text-field v-model="user.birthday"
                                                                  @click="form_calendar = true"
                                                                  :label="$t('users.form.labels.birthday')"
                                                                  prepend-icon="event"
                                                                  :rules="form.birthday.rules"
                                                                  readonly
                                                    ></v-text-field>

                                                    <v-date-picker v-model="user.birthday"
                                                                   :style="{position: 'absolute', right: '20%', 'z-index': 3}"
                                                                   no-title
                                                                   scrollable
                                                                   v-if="form_calendar"
                                                                   :locale="$i18n.locale">
                                                        <v-spacer></v-spacer>
                                                        <v-btn flat color="blue darken-1" @click="form_calendar = false" text>
                                                            {{ $t('users.btn.cancel') }}
                                                        </v-btn>
                                                        <v-btn flat color="primary" outlined @click="form_calendar = false">OK</v-btn>
                                                    </v-date-picker>
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
                </template>
                 <template v-slot:item-action="{ item }">
                     <v-icon
                         small
                         class="mr-2"
                         @click="edit(item)"
                     >
                         edit
                     </v-icon>
                     <v-icon
                         small
                         @click="remove(item)"
                     >
                         delete
                     </v-icon>
                 </template>

            </v-data-table>
        </v-card>

    </div>
</template>

<script>
  import debounce from '../../../debounce';

  export default {
    name: "index",

    data() {
      return {
        users: [],

        is_search: false,

        search: {
          keyword: '',
          order_by: '',
          filter: {
            birthday: new Date().toISOString().substr(0, 10),
          }
        },

        user: {
          email: '',
          first_name: '',
          last_name: '',
          birthday: new Date().toISOString().substr(0, 10),
          password: '',
          password_confirmation: ''
        },

        form: {
          valid:false,
          email: {
            rules: [
              v => v.length > 0 || this.$t('users.form.rules.email.required'),
              v => v.length > 10 || this.$t('users.form.rules.email.min_length', {length:10}),
              v => v.length <= 255 || this.$t('users.form.rules.email.max_length', {length: 255})
            ]
          },

          birthday: {
            rules: [
              v => v.length > 0 || this.$t('users.form.rules.birthday.required'),
              v => v < Date.now || this.$t('users.form.rules.birthday.max_length'),
            ]
          },

          first_name: {
            rules: [
              v => v.length > 0 || this.$t('users.form.rules.first_name.required'),
              v => v.length >= 2 || this.$t('users.form.rules.first_name.min_length', {length:2}),
              v => v.length <= 60 || this.$t('users.form.rules.first_name.max_length', {length: 60})
            ]
          },

          last_name: {
            rules: [
              v => v.length > 0 || this.$t('users.form.rules.last_name.required'),
              v => v.length >= 2 || this.$t('users.form.rules.last_name.min_length', {length:2}),
              v => v.length <= 100 || this.$t('users.form.rules.last_name.max_length', {length: 100})
            ]
          },

          password: {
            rules: [
              v => v.length > 0 || this.$t('users.form.rules.password.required'),
              v => v.length >= 8 || this.$t('users.form.rules.password.min_length', {length:8}),
              v => v.length <= 60 || this.$t('users.form.rules.password.max_length', {length: 255})
            ]
          },

          password_confirmation: {
            rules: [
              v => v.length > 0 || this.$t('users.form.rules.password_confirmation.required'),
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

        "table": {
          "headers": [
            'Email',
            'ФИ',
            'Дата рождения',
            'Зарегистрирован',
            'Последние действия',
            ''
          ]
        },

        pagination: {
          last_page: 1,
          page: 1
        },

        calendar: false
      }
    },

    computed: {
      createFormRoute() {
        return '/admin/users/create';
      },

      editFormRoute() {
        return '/admin/users/update';
      }
    },

    methods: {
      async loadData() {
        const response = await axios.get('/admin/users');

        if (response.data.status === 'error') {
          this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
          return false;
        }

        this.users = response.data.users.data;
        this.pagination.last_page = response.data.users.last_page;
      },

      async remove(id) {
        const response = await axios.delete(`/admin/users/${id}`);

        switch (response.data.status) {
          case 'error':
            this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
            return false;

          case 'success':
            this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
            this.loadData();
            break;
        }
      },

      async save() {
        const response = await axios.post(`/admin/users`, this.user);

        switch (response.data.status) {
          case 'error':
            this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
            return false;

          case 'success':
            this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
            this.loadData();
            break;
        }
      },

      close() {
        this.modal = false;
        /* дописать очистку объекта */

      },

      edit(user) {
        this.user = {...user};
        this.modal = true;
      },

      onSearch() {
        if (this.search.keyword.length < 3) {
          return ;
        }

        this.searchData(this);
      },

      searchData: debounce((vm) => {
        axios.get('/admin/users', {
          params: {
            page: vm.pagination.page,
            keyword: vm.search.keyword
          }
        })
            .then(response => {
              vm.videos = response.data.videos.data;
              vm.pagination.last_page = response.data.videos.last_page;
            })
            .catch(error => {
              vm.$swal(vm.$t('swal,title.error'), error.data.msg, 'error');
            })
        ;
      }, 300),

      toRoute(destination, id = null) {
        switch (destination) {
          case 'create':
            this.$router.push({ path: this.createFormRoute});
            break;

          case 'update':
            this.$router.push({ path: `${this.editFormRoute}/${id}`});
            break;
        }
      }
    },

    watch: {
      'search.keyword': function (after, before) {
        if (after.length > 3) {
          this.onSearch();
        }
      }
    },

    created() {
      this.loadData();
      console.log(this.$refs.user_form);
    }
  }
</script>

<style scoped>

</style>
