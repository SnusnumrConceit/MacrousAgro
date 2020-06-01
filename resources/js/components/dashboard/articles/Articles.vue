<template>
    <v-card>
        <v-card-title>
            {{ $t('nav.links.resumes') }}
        </v-card-title>
        <v-card-text v-if="! loading" class="d-flex flex-wrap">
            <v-card
                    class="mx-auto my-2"
                    max-width="400"
                    @click="$router.push(`/articles/${article.id}`)"
                    v-for="article in articles" :key="article.id">
                    <v-img :src="article.src || 'https://cdn.vuetifyjs.com/images/cards/docks.jpg'"
                           class="white--text align-end"
                           height="200px">
                        <v-card-title>
                            {{ article.title }}
                        </v-card-title>
                    </v-img>
                <v-card-subtitle>
                    {{ article.display_publication_date }}
                </v-card-subtitle>
                <v-card-text class="text--primary" v-html="article.description.substring(0, 60)">
                </v-card-text>
                <v-card-actions>
                    <v-btn outlined
                           color="primary"
                           text
                           @click="$router.push(`/articles/${article.id}`)">
                        {{ $t('buttons.read') }}
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-card-text>
        <v-skeleton-loader type="card" v-else />
    </v-card>
</template>

<script>
  import scroll from '../../../mixins/infinite_scroll';

  export default {
    name: "articles",

    mixins: [scroll],

    data() {
      return {
        articles: [],

        pagination: {
          page: 1,
          last_page: 1
        },

        loading: false
      }
    },

    methods: {
      async getArticles() {
        try {
          const response = await axios.get(`${this.$attrs.apiRoute}/articles`, {
            params: {
              page: this.pagination.page
            }
          });

          this.articles = (this.pagination.page === 1)
              ? response.data.articles.data
              : this.articles.concat(response.data.articles.data);

          this.pagination.last_page = response.data.articles.last_page;
        } catch (e) {
          this.$swal(this.$t('swal.title.error'), e.message, 'error');
        }
      },

      async initData() {
        this.loading = true;

        await this.getArticles();

        this.loading = false;
      },

      /**
       * Обработчик события скролла
       */
      onScroll: function() {
        this.paginationScroll(this, document, 'getArticles');
      },
    },

    created() {
      this.initData();
    },
    
    mounted() {
      document.addEventListener('scroll', this.onScroll);
    }
  }
</script>