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
                          :footer-props="'На странице'">
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

                        <v-text-field v-model="search.keyword" append-icon="search" label="Поиск" single-line></v-text-field>

                        <v-dialog v-model="modal" max-width="500px">
                            <template v-slot:activator="{on}">
                                <v-btn outlined color="success" class="" dark v-on="on">
                                    <i class="pe-7s-plus"></i> {{ $t('users.btn.add')}}
                                </v-btn>
                            </template>

                            <v-card>
                                <v-card-title class="headline">
                                    Пользователь
                                </v-card-title>

                                <v-card-text>
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12" sm="6" md="4">
                                                <v-text-field v-model="user.email"></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="6" md="4">
                                                <v-text-field v-model="user.last_name"></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="6" md="4">
                                                <v-text-field v-model="user.first_name"></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="6" md="6">
                                                <v-text-field v-model="user.birthday"></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="6" md="6">
                                                <v-text-field v-model="user.password"></v-text-field>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-card-text>

                                <v-card-actions>
                                    <v-spacer></v-spacer>

                                        <v-btn color="success" @click="save()">Сохранить</v-btn>
                                        <v-btn color="blue darken-1" text @click="close()">Отмена</v-btn>

                                </v-card-actions>
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
          password: ''

        },

        modal: false,

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
        const response = await axios.post('/admin/users');

        if (response.data.status === 'error') {
          this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
          return false;
        }

        this.users = response.data.users.data;
        this.pagination.last_page = response.data.users.last_page;
      },

      async remove(id) {
        const response = await axios.post(`/admin/users/delete/${id}`);

        switch (response.data.status) {
          case 'error':
            this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
            return false;

          case 'success':
            this.$swal(this.$t('swal.title.success'), response.data.msg, 'success');
            this.loadData();
        }
      },

      save() {

      },

      close() {
        this.modal = false;
        /* дописать очистку объекта */

      },

      edit(user) {
        this.user = {...user};
        this.modal = true;
      },

      remove(user) {

      },

      async searchData() {
        this.switchPage(1);
        const response = await axios.get('/admin/users/search', {
          params: {
            page: this.pagination.page,
            keyword: this.search.keyword,
            order_by: this.search.order_by,
            filter: {...this.search.filter}
          }
        });

        if (response.data.status === 'error') {
          this.$swal(this.$t('swal.title.error', response.data.msg, 'error'));
          return false;
        }

        this.users = response.data.users.data;
        this.pagination.last_page = response.data.users.last_page;
      },

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

    created() {
      // this.loadData();
    }
  }
</script>

<style scoped>

</style>
