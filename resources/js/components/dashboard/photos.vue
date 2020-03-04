<template>
    <v-card>
        <v-card-title>
            Фотогалерея
        </v-card-title>
        <v-card-text>
            <v-row>
                <v-col cols="4" v-for="(photo, index) in photos" :key="photo.id">
                    <v-card>
                        <v-card-text>
                            <v-img :src="photo.path" alt=""></v-img>
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

  export default {
    name: "photos",

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
      async loadPhotos() {
        const response = await axios.get(`${this.$attrs.apiRoute}/photos`, {
          params: {
            page: this.page
          }
        });

        this.photos = response.data.photos.data;
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
              vm.$swal(vm.$t('swal.title.error'), error.data.msg, 'error');
            })
        ;
      }, 300),

      onScroll() {
        this.paginationScroll(this, document);
      },

      paginationScroll: debounce((vm, element) => {
        if (vm.loading) {
          return ;
        }

        let atTheBottom = false;
        let scrollTop = (element === document) ? element.documentElement.scrollTop : element.scrollTop;
        let viewportHeight = (element === document) ? window.innerHeight : element.offsetHeight;
        let totalHeight = (element === document) ? element.documentElement.offsetHeight : element.scrollHeight;

        atTheBottom = scrollTop + viewportHeight === totalHeight;

        if (atTheBottom && vm.pagination.page !== vm.pagination.last_page) {
          vm.pagination.page++;
          vm.is_search ? vm.searchData(vm) : vm.loadPhotos();
        }
      }, 300),
    },

    created() {
      this.loadPhotos();
    },

    mounted() {
      document.addEventListener('scroll', this.onScroll);
    }
  }
</script>

<style scoped>

</style>