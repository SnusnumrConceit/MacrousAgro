<template>
    <div>
        <v-simple-table :height="750" v-show="! loading && users.length">
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
                                        {{ $t('tooltips.edit') }}
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
                                        {{ $t('tooltips.delete') }}
                                    </span>
                        </v-tooltip>
                    </td>
                </tr>
                </tbody>
            </template>
        </v-simple-table>
        <span v-show="! loading" class="d-flex flex-row-reverse">
                    {{ $t('total', {total: pagination.total})}}
                </span>

        <v-skeleton-loader type="table-row-divider@6" v-show="loading" />

        <v-alert color="info" outlined v-if="! loading && ! users.length">
            <span v-show="! searching">
                {{ $t('users.no_users') }}
            </span>
            <span v-show="searching">
                {{ $t('no_results') }}
            </span>
        </v-alert>
    </div>
</template>

<script>
  import UserToolbar from './UserToolbar';

  import debounce from '../../../debounce';
  import scroll from '../../../mixins/infinite_scroll';

  import { mapActions } from 'vuex';

  export default {
    name: "UserList",

    mixins: [scroll],

    components: {
      UserToolbar
    },

    data() {
      return {
        users: [],

        table: {
          headers: [
            this.$t('users.table.headers.full_name'),
            this.$t('users.table.headers.email'),
            this.$t('users.table.headers.birthday'),
            this.$t('users.table.headers.registration_date'),
            this.$t('users.table.headers.updated_at'),
            ''
          ]
        },

        pagination: {
          last_page: 1,
          page: 1,
          total: ''
        },

        loading: false,
        searching: false,
      }
    },

    computed: {
      fullName() {
        return user => `${user.first_name} ${user.last_name}`;
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
       * Загрузка списка пользователей
       *
       * @returns {Promise<boolean>}
       */
      async getUsers() {
        try {
          const response = await axios.get(`/users`, {
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
          this.showNotification({ type:'error', message: e.response.data.message});
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
          const response = await axios.delete(`/users/${id}`);

          this.showNotification({ type: 'success', message: response.data.message});
          this.users = this.users.filter((user) => user.id !== id);
        } catch (e) {
          this.showNotification({ type: 'error', message: e.response.data.message});
        }
      },

      /**
       * Обработчик события debounce-поиска пользователей
       */
      onSearch({search, page}) {
        if (page) {
          this.pagination.page = page;
        }

        this.searching = true;

        this.searchData(this, search);
      },

      /**
       * Поиск пользователей
       */
      searchData: debounce((vm, search) => {
        vm.loading = true;
        axios.get(`/users`, {
          params: {
            page: vm.pagination.page,
            ...search
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
              vm.showNotification({ type: 'error', message: error.data.message });
            })
        ;
      }, 300),

      /**
       * Обработчик события скролла в таблице
       */
      onScroll: function () {
        this.paginationScroll(this, $('.v-data-table__wrapper')[0], 'getUsers');
      },

      async initData() {
        this.loading = true;

        await this.getUsers();

        this.loading = false;
      }
    },

    created() {
      this.initData();
    },

    mounted() {
      $('.v-data-table__wrapper')[0].addEventListener('scroll', this.onScroll);

      this.$root.$on('user-created', this.initData);

      this.$root.$on('user-searching', this.onSearch);
    },

    destroyed() {
      $('.v-data-table__wrapper')[0].removeEventListener('scroll', this.onScroll);
    }
  }
</script>