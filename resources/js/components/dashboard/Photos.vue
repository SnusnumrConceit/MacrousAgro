<template>
    <v-card>
        <v-card-title>
            Фотогалерея
        </v-card-title>
        <v-card-text>
            <v-row>
                <v-col cols="4" v-for="(photo, index) in photos" :key="photo.id" height="100%" class="flex-card-full-size">
                    <v-card>
                        <v-card-text>
                            <v-img :src="photo.path || 'https://cdn.vuetifyjs.com/images/cards/docks.jpg'" alt=""></v-img>
                        </v-card-text>
                        <v-card-title class="text-center">
                            {{ photo.title }}
                        </v-card-title>
                    </v-card>
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>

<script>
  import debounce from '../../debounce';
  import scroll from "../../mixins/infinite_scroll";

  export default {
    name: "photos",

    mixins: [scroll],

    data() {
      return {
        photos: [],

        pagination: {
          last_page: 1,
          page: 1
        }
      }
    },


    methods: {
      async getPhotos() {
        const response = await axios.get(`${this.$attrs.apiRoute}/photos`, {
          params: {
            page: this.pagination.page
          }
        });

        this.photos = (this.pagination.page === 1)
            ? response.data.photos.data
            : this.photos.concat(response.data.photos.data);

        this.pagination.last_page = response.data.photos.last_page;
      },

      onSearch() {
        if (this.search.keyword.length < 3) {
          return ;
        }

        this.searchData(this);
      },

      searchData: debounce((vm) => {
        axios.get(`${this.$attrs.apiRoute}/photos/search`, {
          params: {
            page: vm.pagination.page,
          }
        })
            .then(response => {
              vm.photos = response.data.photos.data;
              vm.pagination.last_page = response.data.photos.last_page;
            })
            .catch(error => {
              vm.showNotification({ type: 'error', message: error.data.message});
            })
        ;
      }, 300),

      onScroll() {
        this.paginationScroll(this, document, 'getPhotos');
      }
    },

    created() {
      this.getPhotos();
    },

    mounted() {
      document.addEventListener('scroll', this.onScroll);
    }
  }
</script>

<style scoped>

</style>