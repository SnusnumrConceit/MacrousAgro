<template>
    <v-card>
        <v-parallax src="http://www.unitek.ua/media/post/image/5/5/55766_dsc_7255-98353408.jpg" height="1000">
            <v-card v-if="products.length" style="background: none;" class="text--white">
                <v-card-title>
                    <h2 class="font-weight-black display-3" style="color: #2fff82; text-shadow: 5px 5px #558ABB">
                        {{ $t('landing.production')}}
                    </h2>
                </v-card-title>
                <v-card-text>
                    <v-slide-group>
                        <v-slide-item v-for="product in products" :key="product.id">
                            <v-card
                                    class="mx-3"
                                    max-width="400"
                                    style="cursor: pointer"
                                    @click="$router.push(`/products/${product.id}`)"
                            >
                                <v-img :src="product.src || 'https://cdn.vuetifyjs.com/images/cards/docks.jpg'"
                                       class="white--text align-end"
                                       height="200px"
                                >
                                    <v-card-title>
                                        {{ product.title }}
                                    </v-card-title>
                                </v-img>
                            </v-card>
                        </v-slide-item>
                    </v-slide-group>
                </v-card-text>
            </v-card>
        </v-parallax>

        <v-card v-if="articles.length">
            <v-card-title>
                <h2 class="font-weight-black display-3">
                    {{ $t('landing.articles') }}
                </h2>
            </v-card-title>
            <v-card-text>
                <v-slide-group >
                    <v-slide-item v-for="article in articles" :key="article.id">
                        <v-card
                                class="mx-3"
                                max-width="400"
                                style="cursor: pointer"
                                @click="$router.push(`/articles/${article.id}`)"
                        >
                            <v-img :src="article.src || 'https://cdn.vuetifyjs.com/images/cards/docks.jpg'"
                                   class="white--text align-end"
                                   height="200px"
                            >
                                <v-card-title>
                                    {{ article.title }}
                                </v-card-title>
                            </v-img>
                            <v-card-text class="text--primary">
                                {{ article.description.substring(0, 60) }}
                            </v-card-text>
                        </v-card>
                    </v-slide-item>
                </v-slide-group>
            </v-card-text>
        </v-card>

        <v-card v-if="photos.length">
            <v-card-title>
                <h2 class="font-weight-black display-3">
                    {{ $t('landing.photo_gallery')}}
                </h2>
            </v-card-title>
            <v-card-text>
                <v-slide-group >
                    <v-slide-item v-for="photo in photos" :key="photo.id">
                        <v-card
                                class="mx-3"
                                max-width="600"
                        >
                            <v-img :src="photo.path || 'https://cdn.vuetifyjs.com/images/cards/docks.jpg'"
                                   alt=""
                                   width="680px"
                                   height="480px"
                                   class="white--text align-end">
                                <v-card-title class="text-center">
                                    {{ photo.title }}
                                </v-card-title>
                            </v-img>
                        </v-card>
                    </v-slide-item>
                </v-slide-group>
            </v-card-text>
        </v-card>

        <v-card v-if="videos.length">
            <v-card-title>
                <h2 class="font-weight-black display-3">
                    {{ $t('landing.video_gallery')}}
                </h2>
            </v-card-title>
            <v-card-text>
                <v-slide-group >
                    <v-slide-item v-for="video in videos" :key="video.id">
                        <v-card
                                class="mx-3"
                                max-width="400"
                        >
                            <v-card-text>
                                <video :src="video.src" controls height="240" width="320"></video>
                            </v-card-text>
                            <v-card-title class="text-center">
                                {{ video.title }}
                            </v-card-title>
                        </v-card>
                    </v-slide-item>
                </v-slide-group>
            </v-card-text>
        </v-card>
    </v-card>
</template>

<script>
  export default {
    name: "Landing",

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