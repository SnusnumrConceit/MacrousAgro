<template>
    <div>
        <v-simple-table :height="750" v-show="! loading && articles.length" ref="table">
            <template v-slot:default>
                <thead>
                <th v-for="header in table.headers" :key="header" class="text-left">
                    {{ header }}
                </th>
                </thead>
                <tbody>
                <tr v-for="article in articles" :key="article.id">
                    <td class="text-left">
                        {{ article.title }}
                    </td>
                    <td class="text-left">
                        {{ article.formatted_publication_date }}
                    </td>
                    <td class="text-left">
                        {{ article.formatted_updated_at }}
                    </td>
                    <td class="text-right">
                        <v-tooltip top color="primary">
                            <template v-slot:activator="{ on }">
                                <v-icon v-on="on"
                                        small
                                        @click="$router.push(`/admin/articles/${article.id}`)"
                                >
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
                                        @click="remove(article.id)"
                                >
                                    mdi-delete
                                </v-icon>
                            </template>
                            <span>
                                {{ $t('tooltips.delete')}}
                            </span>
                        </v-tooltip>
                    </td>
                </tr>
                </tbody>
            </template>

        </v-simple-table>

        <span v-show="! loading" class="d-flex flex-row-reverse">
            {{ $t('total', {total: pagination.total}) }}
        </span>

        <v-skeleton-loader type="table-row-divider@6" v-show="loading" />

        <v-alert color="info" outlined v-if="! loading && ! articles.length">
            <span v-show="! searching">
                {{ $t('articles.no_articles') }}
            </span>
            <span v-show="searching">
                {{ $t('no_results') }}
            </span>
        </v-alert>
    </div>
</template>

<script>
  import {mapActions} from 'vuex';

  import debounce from '../../../debounce';

  import scroll from '../../../mixins/infinite_scroll';

  export default {
    name: "ArticleList",

    mixins: [scroll],

    data() {
      return {
        articles: [],

        loading: false,
        searching: false,

        table: {
          headers: [
            this.$t('articles.table.headers.title'),
            this.$t('articles.table.headers.is_publicated'),
            this.$t('articles.table.headers.updated_at'),
            ''
          ]
        },

        pagination: {
          last_page: 1,
          page: 1,
          total: ''
        },
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
       * Инициализация данных для компонента
       *
       */
      async initData() {
        await this.getArticles();
      },

      /**
       * Загрузка списка статей
       *
       * @returns {Promise<boolean>}
       */
      async getArticles() {
        this.loading = true;

        try {
          const response = await axios.get(`/articles`, {
            params: {
              page: this.pagination.page
            }
          });

          this.articles = (this.pagination.page === 1)
              ? response.data.articles.data
              : this.articles.concat(response.data.articles.data);

          this.pagination.total = response.data.articles.total;
          this.pagination.last_page = response.data.articles.last_page;

          this.loading = false;
        } catch (e) {
          this.showNotification({ type: 'error', message: e.response.data.message});
        }
      },

      /**
       * Удаление статьи
       *
       * @param id
       * @returns {Promise<boolean>}
       */
      async remove(id) {
        try {
          const response = await axios.delete(`/articles/${id}`);

          this.showNotification({ type: 'success', message: response.data.message});
          this.articles = this.articles.filter((article) => article.id !== id);
        } catch (e) {
          this.showNotification({ type: 'error', message: e.response.data.message});
        }
      },

      /**
       * Обработчик события для debounce-поиска
       */
      onSearch({search, page}) {
        console.log(search, page);
        if (page) {
          this.pagination.page = page;
        }

        this.searching = true;

        this.searchData(this, search);
      },

      /**
       * Поиск статей
       */
      searchData: debounce((vm, search) => {
        axios.get(`/articles`, {
          params: {
            page: vm.pagination.page,
            ...search
          }
        }).then(response => {
          vm.articles = (vm.pagination.page === 1) ? response.data.articles.data : vm.articles.concat(response.data.articles.data);
          vm.pagination.last_page = response.data.articles.last_page;
          vm.pagination.total = response.data.articles.total;
        }).catch(error => console.error(error));
      }, 300),

      /**
       * Обработчик события для скролловой пагинации
       *
       */
      onScroll: function() {
        this.paginationScroll(this, $('.v-data-table__wrapper')[0], 'getArticles');
      },
    },

    created() {
      this.initData();
    },

    mounted() {
      $('.v-data-table__wrapper')[0].addEventListener('scroll', this.onScroll);

      this.$root.$on('article-created', this.initData);
      this.$root.$on('article-searching', this.onSearch);
    },

    destroyed() {
      $('.v-data-table__wrapper')[0].removeEventListener('scroll', this.onScroll);
    }
  }
</script>