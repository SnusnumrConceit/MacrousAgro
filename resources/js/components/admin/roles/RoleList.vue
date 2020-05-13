<template>
    <div>
        <v-simple-table :height="750" v-show="! loading && roles.length" ref="table">
            <template v-slot:default>
                <thead>
                <th v-for="header in table.headers" :key="header" class="text-center">
                    {{ header }}
                </th>
                </thead>
                <tbody>
                <tr v-for="role in roles" :key="role.id">
                    <td>
                        {{ role.name }}
                    </td>
                    <td>
                        {{ role.description.substring(0, 120) }}
                    </td>
                    <td>
                        <v-tooltip top color="primary">
                            <template v-slot:activator="{ on }">
                                <v-icon color=""
                                        v-on="on"
                                        small
                                        @click="$router.push(`/admin/roles/${role.slug}`)">
                                    mdi-pencil
                                </v-icon>
                            </template>
                            <span>
                            {{ $t('tooltips.edit')}}
                        </span>
                        </v-tooltip>

                        <v-tooltip top color="error">
                            <template v-slot:activator="{ on }">
                                <v-icon color="red"
                                        v-on="on"
                                        small
                                        @click="remove(role.slug)">
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
            {{ $t('total', {total: total}) }}
        </span>

        <v-skeleton-loader v-show="loading" type="table-row-divider@6"/>

        <v-alert color="info" outlined v-if="! loading && ! roles.length">
            <span v-show="! searching">
                {{ $t('roles.no_roles') }}
            </span>
                <span v-show="searching">
                {{ $t('no_results') }}
            </span>
        </v-alert>
    </div>
</template>

<script>
  import {mapActions} from 'vuex';

  import scroll from '../../../mixins/infinite_scroll';
  import debounce from '../../../debounce';

  export default {
    name: "RoleList",

    mixins: [scroll],

    data() {
      return {
        table: {
          headers: [
            this.$t('roles.table.headers.name'),
            this.$t('roles.table.headers.description'),
            ''
          ]
        },

        roles: [],

        pagination: {
          page: 1,
          last_page: 1
        },

        total: 0,

        loading: false,
        searching: false
      }
    },

    methods: {
      /**
       * Показ / обнуление уведомлений
       */
      ...mapActions('notifications', [
        'showNotification'
      ]),

      /**
       * Получить список ролей
       *
       * Get listing of the roles
       *
       */
      async getRoles() {
        try {
          const response = await axios.get(`/roles`, {
            params: {
              page: this.pagination.page
            }
          });

          this.roles = (this.pagination.page === 1)
              ? response.data.roles.data
              : this.roles.concat(response.data.roles.data);

          this.pagination.last_page = response.data.roles.lastPage;
          this.total = response.data.roles.total;
        } catch (e) {
          this.showNotification({ type: 'error', message: e.response.data.message | e.message });
        }
      },

      /**
       * Обработчик события скролла
       */
      onScroll: function() {
        this.paginationScroll(this, document, 'getRoles');
      },

      /**
       * Обработчик события для debounce-поиска
       */
      onSearch(search) {
        this.searchData(this, search);
      },

      /**
       * Поиск ролей
       */
      searchData: debounce((vm, search) => {
        axios.get(`/roles`, {
          params: {
            page: vm.pagination.page,
            ...search
          }
        }).then(response => {
          vm.roles = (vm.pagination.page === 1) ? response.data.roles.data : vm.roles.concat(response.data.roles.data);
          vm.pagination.last_page = response.data.roles.last_page;
          vm.pagination.total = response.data.roles.total;
        }).catch(error => console.error(error));
      }, 300),

      /**
       * Удалить роль из спика
       *
       * Remove role from list
       *
       * @param slug
       * @returns {Promise<void>}
       */
      async remove(slug) {
        try {
          const response = await axios.delete(`/roles/${slug}`);

          this.showNotification({ type: 'success', message: response.data.message });
          this.roles = this.roles.filter(role => role.slug !== slug);
        } catch (e) {
          this.showNotification({ type: 'error', message: e.response.data.message || e.message });
        }
      },

      /**
       * Инициализация компонента данными
       *
       * Initialize component data
       *
       * @returns {Promise<void>}
       */
      async initData() {
        this.loading = true;

        await this.getRoles();

        this.loading = false;
      }
    },

    created() {
      this.initData();
    },

    mounted() {
      document.addEventListener('scroll', this.onScroll);

      this.$root.$on('role-created', this.initData);

      this.$root.$on('role-searching', this.onSearch);

      this.$root.$on('role-search-reset', this.onSearch);
    },

    destroyed() {
      document.removeEventListener('scroll', this.onScroll);
    }
  }
</script>