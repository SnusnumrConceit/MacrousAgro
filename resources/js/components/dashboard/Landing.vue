<template>
    <v-card>
        <v-parallax src="http://www.unitek.ua/media/post/image/5/5/55766_dsc_7255-98353408.jpg" height="1000">
            <products :products="products"/>
        </v-parallax>

        <articles :articles="articles"/>

        <photos :photos="photos" />

        <videos :videos="videos" />
    </v-card>
</template>

<script>
  import Articles from './landing/articles/Articles';
  import Products from './landing/products/Products';
  import Photos   from './landing/photos/Photos';
  import Videos   from './landing/videos/Videos';

  export default {
    name: "Landing",

    components: {
      Articles,
      Products,
      Photos,
      Videos
    },

    data() {
      return {
        articles: [],
        products: [],
        photos: [],
        videos: [],

        pagination: {
          defaultPage: 1
        }
      }
    },

    methods: {
      async getArticles() {
        try {
          const response = await axios.get(`${this.$attrs.apiRoute}/articles`, {
            page: this.pagination.defaultPage
          });

          this.articles = response.data.articles.data;
        } catch (e) {
          console.error(e.response.data);
        }
      },

      async getProducts() {
        try {
          const response = await axios.get(`${this.$attrs.apiRoute}/products/random`, {
            page: this.pagination.defaultPage
          });

          this.products = response.data.products.data;
        } catch (e) {
          console.error(e.response.data);
        }
      },

      async getPhotos() {
        try {
          const response = await axios.get(`${this.$attrs.apiRoute}/photos/random`, {
            page: this.pagination.defaultPage
          });

          this.photos = response.data.photos.data;
        } catch (e) {
          console.error(e.response.data);
        }
      },

      async getVideos() {
        try {
          const response = await axios.get(`${this.$attrs.apiRoute}/videos/random`, {
            page: this.pagination.defaultPage
          });

          this.videos = response.data.videos.data;
        } catch (e) {
          console.error(e.response.data);
        }
      },

      async initData() {
        this.loading = true;

        await this.getProducts();

        await this.getArticles();

        await this.getPhotos();

        await this.getVideos();

        this.loading = false;
      }
    },

    created() {
      this.initData();
    }
  }
</script>

<style scoped>

</style>