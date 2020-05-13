<template>
    <div>
        <v-card v-show="! loading">
            <v-parallax :src="article.src || 'https://cdn.vuetifyjs.com/images/cards/docks.jpg'">
                <v-card-title>
                    {{ article.title}}
                </v-card-title>
            </v-parallax>
            <v-card-subtitle class="d-flex justify-end" color="white">
                {{ article.display_publication_date }}
            </v-card-subtitle>
            <v-card-text class="text-justify body-1" v-html="article.description">
                <!--{{ article.description }}-->
            </v-card-text>
        </v-card>
        <v-skeleton-loader type="card" v-show="loading" />
    </div>
</template>

<script>
  export default {
    name: "Article",

    data() {
      return {
        article: {
          title: '',
          description: '',
          image: null,
          publication_date: '',
          is_published: true,
          src: ''
        },

        loading: false
      }
    },

    computed: {
      ID() {
        return this.$route.params.id;
      }
    },

    methods: {
      async getArticle() {
        try {
          const response = await axios.get(`${this.$attrs.apiRoute}/articles/${this.ID}`);

          this.article = response.data.article;
        } catch (e) {
          this.$swal(this.$t('swal.title.error'), e.message, 'error');
        }
      },

      /**
       * Переключатель загрузки
       *
       * @param show
       */
      toggleLoading(show) {
        this.loading = show;
      },

      /**
       * Инициализация компонента данными
       *
       * @return {Promise<void>}
       */
      async initArticle() {
        this.toggleLoading(true);

        await this.getArticle();

        this.toggleLoading(false);
      }
    },

    created() {
      this.initArticle();
    }
  }
</script>

<style scoped>

</style>