<template>
    <v-card>
        <role-toolbar></role-toolbar>

        <v-card-text>
            <role-list :roles="roles" :loading="loading" :total="total" @role-deleted="remove"></role-list>
        </v-card-text>
    </v-card>
</template>

<script>
  import {mapActions} from 'vuex';

  import scroll from '../../../mixins/infinite_scroll';
  import debounce from '../../../debounce';

  import RoleToolbar from './RoleToolbar';
  import RoleList from './RoleList';

  export default {
    name: "roles",

    components: {
      RoleToolbar,
      RoleList
    },

    mixins: [scroll],

    data() {
      return {
        roles: [],

        pagination: {
          page: 1,
          last_page: 1
        },

        total: 0,

        loading: false
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
          const response = await axios.get(`${this.$attrs.apiRoute}/roles`, {
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
        axios.get(`${vm.$attrs.apiRoute}/roles`, {
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
       * @param id
       * @returns {Promise<void>}
       */
      async remove({slug}) {
        try {
          const response = await axios.delete(`${this.$attrs.apiRoute}/roles/${slug}`);

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

<style scoped>

</style>
